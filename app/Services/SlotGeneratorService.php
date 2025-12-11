<?php

namespace App\Services;

use App\Models\PriceList;
use App\Models\Slot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SlotGeneratorService
{
    public function generateForPriceList(PriceList $pricelist): int
    {
        $count = 0;

        // Даты valid_from → valid_to
        $startDate = Carbon::parse($pricelist->valid_from);
        $endDate   = Carbon::parse($pricelist->valid_to);

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {

            // 1. Проверяем Overrides
            $overrides = $pricelist->overrides()
                ->where('for_date', $date->format('Y-m-d'))
                ->where('is_active', 1)
                ->get();

            if ($overrides->count()) {
                foreach ($overrides as $override) {
                    $count += $this->generateSlotsForInterval(
                        $pricelist, 
                        $date, 
                        $override->starts_at, 
                        $override->ends_at, 
                        $override->slot_price, 
                        $override->capacity
                    );
                }
                continue;
            }

            // 2. Если нет override → берем RULES
            $weekday = $date->dayOfWeek; // 0=Sun

            $rules = $pricelist->rules()
                ->where('weekday', $weekday)
                ->where('is_active', 1)
                ->get();

            foreach ($rules as $rule) {
                $count += $this->generateSlotsForInterval(
                    $pricelist,
                    $date,
                    $rule->starts_at,
                    $rule->ends_at,
                    $rule->slot_price,
                    $rule->capacity
                );
            }
        }

        return $count;
    }

    private function generateSlotsForInterval(
        PriceList $pricelist,
        Carbon    $date,
        string    $startTime,
        string    $endTime,
        float     $slotPrice,
        int       $capacity
    ): int
    {
        $count = 0;

        // Combine date + time with timezone
        $timezone = $pricelist->timezone;

        $start = Carbon::parse($date->format('Y-m-d').' '.$startTime, $timezone);
        $end   = Carbon::parse($date->format('Y-m-d').' '.$endTime,   $timezone);

        $duration = $pricelist->default_slot_duration; // minutes

        while ($start->copy()->addMinutes($duration)->lte($end)) {

            $slotStart = $start->copy();
            $slotEnd   = $start->copy()->addMinutes($duration);

            // Проверяем дубль
            $exists = Slot::where('platform_id', $pricelist->platform_id)
                ->where('price_list_id', $pricelist->id)
                ->where('starts_at', $slotStart)
                ->where('ends_at', $slotEnd)
                ->exists();

            if (!$exists) {
                Slot::create([
                    'platform_id'  => $pricelist->platform_id,
                    'price_list_id'=> $pricelist->id,
                    'starts_at'    => $slotStart,
                    'ends_at'      => $slotEnd,
                    'price'        => $slotPrice,
                    'capacity'     => $capacity,
                    'used_capacity'=> 0,
                    'status'       => 'available',
                ]);

                $count++;
            }

            $start->addMinutes($duration);
        }

        return $count;
    }
}
