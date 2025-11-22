<?php
namespace App\Services;
use App\Models\{Platform, PriceList, PriceListRule, PriceOverride, PromoCode, Booking};
use Carbon\Carbon;

class PricingService
{
    public function quote(Platform $platform, Carbon $startAt, Carbon $endAt, ?string $promoCode, ?int $clientId): array
    {
        $pl = PriceList::where('platform_id', $platform->id)
            ->where('is_active', true)
            ->where(function($q) use ($startAt) { $q->whereNull('valid_from')->orWhere('valid_from', '<=', $startAt->toDateString()); })
            ->where(function($q) use ($startAt) { $q->whereNull('valid_to')->orWhere('valid_to', '>=', $startAt->toDateString()); })
            ->firstOrFail();

        $override = PriceOverride::where('price_list_id', $pl->id)
            ->whereDate('for_date', $startAt->toDateString())
            ->where('starts_at', '<=', $startAt->format('H:i:s'))
            ->where('ends_at', '>=', $endAt->format('H:i:s'))
            ->where('is_active', true)->first();

        if ($override) {
            $listPrice = (float)$override->slot_price;
            $capacity  = (int)($override->capacity ?? 0);
            $bandStart = $override->starts_at;
            $bandEnd   = $override->ends_at;
        } else {
            $weekday = (int)$startAt->isoWeekday();
            $rule = PriceListRule::where('price_list_id', $pl->id)
                ->where(function($q) use ($weekday) { $q->whereNull('weekday')->orWhere('weekday', $weekday); })
                ->where('starts_at', '<=', $startAt->format('H:i:s'))
                ->where('ends_at', '>=', $endAt->format('H:i:s'))
                ->where('is_active', true)
                ->orderByRaw('weekday is null')
                ->firstOrFail();
            $listPrice = (float)$rule->slot_price;
            $capacity  = (int)$rule->capacity;
            $bandStart = $rule->starts_at;
            $bandEnd   = $rule->ends_at;
        }

        $bandStartDT = Carbon::parse($startAt->toDateString().' '.$bandStart, $pl->timezone);
        $bandEndDT   = Carbon::parse($startAt->toDateString().' '.$bandEnd, $pl->timezone);

        $used = Booking::query()
            ->whereIn('status', ['pending','confirmed'])
            ->whereHas('slot', function($q) use ($platform, $bandStartDT, $bandEndDT) {
                $q->where('platform_id', $platform->id)
                  ->whereBetween('starts_at', [$bandStartDT, $bandEndDT]);
            })->count();

        if ($capacity > 0 && $used >= $capacity) {
            throw new \RuntimeException('Capacity exceeded for selected time window');
        }

        $discount = 0.0; $appliedPromoId = null;
        if ($promoCode) {
            $promo = $this->validatePromo($promoCode, $platform->id, $pl->id, $listPrice, $clientId);
            if ($promo) {
                $discount = $promo->discount_type === 'percent'
                    ? round($listPrice * ((float)$promo->discount_value / 100), 2)
                    : min((float)$promo->discount_value, $listPrice);
                $appliedPromoId = $promo->id;
            }
        }

        $final = max(0, $listPrice - $discount);
        return [
            'price_list_id'=>$pl->id,
            'band'=>['starts_at'=>$bandStart,'ends_at'=>$bandEnd],
            'list_price'=>$listPrice,
            'discount'=>$discount,
            'final_price'=>$final,
            'currency'=>$pl->currency,
            'promo_code_id'=>$appliedPromoId,
        ];
    }

    protected function validatePromo(string $code, int $platformId, int $priceListId, float $amount, ?int $clientId): ?PromoCode
    {
        $now = now();
        $promo = PromoCode::where('code', $code)->where('is_active', true)
            ->where(function($q) use ($now){ $q->whereNull('starts_at')->orWhere('starts_at','<=',$now); })
            ->where(function($q) use ($now){ $q->whereNull('ends_at')->orWhere('ends_at','>=',$now); })
            ->first();
        if (!$promo) return null;

        if ($promo->applies_to === 'platform' && (int)$promo->platform_id !== $platformId) return null;
        if ($promo->applies_to === 'price_list' && (int)$promo->price_list_id !== $priceListId) return null;
        if ($promo->min_order_amount && $amount < (float)$promo->min_order_amount) return null;

        if (!is_null($promo->max_uses)) {
            $total = $promo->redemptions()->count();
            if ($total >= (int)$promo->max_uses) return null;
        }
        if ($clientId && !is_null($promo->max_uses_per_client)) {
            $byClient = $promo->redemptions()->where('client_id', $clientId)->count();
            if ($byClient >= (int)$promo->max_uses_per_client) return null;
        }
        return $promo;
    }
}
