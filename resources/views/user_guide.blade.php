@extends('adminlte::page')

@section('title', __('docs.title'))

@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-header"><strong>{{ __('docs.nav') }}</strong></div>
      <div class="list-group list-group-flush">
        <a href="#overview" class="list-group-item list-group-item-action">{{ __('docs.sections.overview') }}</a>
        <a href="#setup" class="list-group-item list-group-item-action">{{ __('docs.sections.setup_flow') }}</a>
        <a href="#modules" class="list-group-item list-group-item-action">{{ __('docs.sections.modules') }}</a>
        <a href="#roles" class="list-group-item list-group-item-action">{{ __('docs.sections.role_matrix') }}</a>
        <a href="#faq" class="list-group-item list-group-item-action">{{ __('docs.sections.faq') }}</a>
        <a href="#glossary" class="list-group-item list-group-item-action">{{ __('docs.sections.glossary') }}</a>
        <a href="#support" class="list-group-item list-group-item-action">{{ __('docs.sections.support') }}</a>
      </div>
    </div>
  </div>

  <div class="col-md-9">

    <div id="overview" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.overview') }}</h5></div>
      <div class="card-body">
        <p>@lang('docs.overview.p1')</p>
        <p>@lang('docs.overview.p2')</p>
      </div>
    </div>

    <div id="setup" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.setup_flow') }}</h5></div>
      <div class="card-body">
        <ol class="mb-0">
          <li>@lang('docs.setup.1')</li>
          <li>@lang('docs.setup.2')</li>
          <li>@lang('docs.setup.3')</li>
          <li>@lang('docs.setup.4')</li>
          <li>@lang('docs.setup.5')</li>
          <li>@lang('docs.setup.6')</li>
          <li>@lang('docs.setup.7')</li>
        </ol>
      </div>
    </div>

    <div id="modules" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.modules') }}</h5></div>
      <div class="card-body">
        {{-- Platforms --}}
        <h6 class="mt-3">1 {{ __('docs.modules.platforms.name') }}</h6>
        <p>@lang('docs.modules.platforms.desc')</p>
        <ul>
          <li>@lang('docs.modules.platforms.points.0')</li>
          <li>@lang('docs.modules.platforms.points.1')</li>
          <li>@lang('docs.modules.platforms.points.2')</li>
          <li>@lang('docs.modules.platforms.points.3')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.platforms.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.platforms.related')</p>

        {{-- Pricelists --}}
        <h6 class="mt-4">2 {{ __('docs.modules.pricelists.name') }}</h6>
        <p>@lang('docs.modules.pricelists.desc')</p>
        <ul>
          <li>@lang('docs.modules.pricelists.points.0')</li>
          <li>@lang('docs.modules.pricelists.points.1')</li>
          <li>@lang('docs.modules.pricelists.points.2')</li>
          <li>@lang('docs.modules.pricelists.points.3')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.pricelists.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.pricelists.related')</p>

        {{-- Price Rules --}}
        <h6 class="mt-4">3 {{ __('docs.modules.pricerules.name') }}</h6>
        <p>@lang('docs.modules.pricerules.desc')</p>
        <ul>
          <li>@lang('docs.modules.pricerules.points.0')</li>
          <li>@lang('docs.modules.pricerules.points.1')</li>
          <li>@lang('docs.modules.pricerules.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.pricerules.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.pricerules.related')</p>

        {{-- Price Overrides --}}
        <h6 class="mt-4">4 {{ __('docs.modules.priceoverrides.name') }}</h6>
        <p>@lang('docs.modules.priceoverrides.desc')</p>
        <ul>
          <li>@lang('docs.modules.priceoverrides.points.0')</li>
          <li>@lang('docs.modules.priceoverrides.points.1')</li>
          <li>@lang('docs.modules.priceoverrides.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.priceoverrides.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.priceoverrides.related')</p>

        {{-- Slots --}}
        <h6 class="mt-4">5 {{ __('docs.modules.slots.name') }}</h6>
        <p>@lang('docs.modules.slots.desc')</p>
        <ul>
          <li>@lang('docs.modules.slots.points.0')</li>
          <li>@lang('docs.modules.slots.points.1')</li>
          <li>@lang('docs.modules.slots.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.slots.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.slots.related')</p>

        {{-- Bookings --}}
        <h6 class="mt-4">6 {{ __('docs.modules.bookings.name') }}</h6>
        <p>@lang('docs.modules.bookings.desc')</p>
        <ul>
          <li>@lang('docs.modules.bookings.points.0')</li>
          <li>@lang('docs.modules.bookings.points.1')</li>
          <li>@lang('docs.modules.bookings.points.2')</li>
          <li>@lang('docs.modules.bookings.points.3')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.bookings.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.bookings.related')</p>

        {{-- Clients --}}
        <h6 class="mt-4">7 {{ __('docs.modules.clients.name') }}</h6>
        <p>@lang('docs.modules.clients.desc')</p>
        <ul>
          <li>@lang('docs.modules.clients.points.0')</li>
          <li>@lang('docs.modules.clients.points.1')</li>
          <li>@lang('docs.modules.clients.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.clients.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.clients.related')</p>

        {{-- Promo Codes --}}
        <h6 class="mt-4">8 {{ __('docs.modules.promocodes.name') }}</h6>
        <p>@lang('docs.modules.promocodes.desc')</p>
        <ul>
          <li>@lang('docs.modules.promocodes.points.0')</li>
          <li>@lang('docs.modules.promocodes.points.1')</li>
          <li>@lang('docs.modules.promocodes.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.promocodes.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.promocodes.related')</p>

        {{-- Users --}}
        <h6 class="mt-4">9 {{ __('docs.modules.users.name') }}</h6>
        <p>@lang('docs.modules.users.desc')</p>
        <ul>
          <li>@lang('docs.modules.users.points.0')</li>
          <li>@lang('docs.modules.users.points.1')</li>
          <li>@lang('docs.modules.users.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.users.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.users.related')</p>

        {{-- Roles --}}
        <h6 class="mt-4">10 {{ __('docs.modules.roles.name') }}</h6>
        <p>@lang('docs.modules.roles.desc')</p>
        <ul>
          <li>@lang('docs.modules.roles.points.0')</li>
          <li>@lang('docs.modules.roles.points.1')</li>
          <li>@lang('docs.modules.roles.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.roles.who')</p>
        <p class="mb-3"><strong>@lang('docs.related')</strong> @lang('docs.modules.roles.related')</p>

        {{-- Languages --}}
        <h6 class="mt-4">11 {{ __('docs.modules.languages.name') }}</h6>
        <p>@lang('docs.modules.languages.desc')</p>
        <ul>
          <li>@lang('docs.modules.languages.points.0')</li>
          <li>@lang('docs.modules.languages.points.1')</li>
          <li>@lang('docs.modules.languages.points.2')</li>
        </ul>
        <p class="mb-1"><strong>@lang('docs.who_uses')</strong> @lang('docs.modules.languages.who')</p>
        <p class="mb-0"><strong>@lang('docs.related')</strong> @lang('docs.modules.languages.related')</p>

      </div>
    </div>

    <div id="roles" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.role_matrix') }}</h5></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>{{ __('docs.role_matrix.module') }}</th>
              <th>{{ __('docs.role_matrix.admin') }}</th>
              <th>{{ __('docs.role_matrix.manager') }}</th>
              <th>{{ __('docs.role_matrix.accountant') }}</th>
              <th>{{ __('docs.role_matrix.partner') }}</th>
              <th>{{ __('docs.role_matrix.client') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>{{ __('docs.modules.platforms.name') }}</td><td>CRUD</td><td>R</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.pricelists.name') }}</td><td>CRUD</td><td>CRU</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.pricerules.name') }}</td><td>CRUD</td><td>CRU</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.priceoverrides.name') }}</td><td>CRUD</td><td>CRU</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.slots.name') }}</td><td>CRUD</td><td>CRU</td><td>—</td><td>—</td><td>R</td></tr>
            <tr><td>{{ __('docs.modules.bookings.name') }}</td><td>CRUD</td><td>CRUD</td><td>R</td><td>R</td><td>CR (self)</td></tr>
            <tr><td>{{ __('docs.modules.clients.name') }}</td><td>CRUD</td><td>CRU</td><td>R</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.promocodes.name') }}</td><td>CRUD</td><td>CRU</td><td>R</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.users.name') }}</td><td>CRUD</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.roles.name') }}</td><td>CRUD</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>{{ __('docs.modules.languages.name') }}</td><td>CRUD</td><td>R</td><td>R</td><td>R</td><td>R</td></tr>
          </tbody>
        </table>
        <p class="text-muted small mb-0">@lang('docs.role_matrix.legend')</p>
      </div>
    </div>

    <div id="faq" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.faq') }}</h5></div>
      <div class="card-body">
        <p><strong>@lang('docs.faq.q1')</strong><br>@lang('docs.faq.a1')</p>
        <p><strong>@lang('docs.faq.q2')</strong><br>@lang('docs.faq.a2')</p>
        <p><strong>@lang('docs.faq.q3')</strong><br>@lang('docs.faq.a3')</p>
      </div>
    </div>

    <div id="glossary" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.glossary') }}</h5></div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">{{ __('docs.glossary.platform') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.platform_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.pricelist') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.pricelist_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.rule') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.rule_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.override') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.override_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.slot') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.slot_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.booking') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.booking_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.promocode') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.promocode_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.capacity') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.capacity_def')</dd>
          <dt class="col-sm-3">{{ __('docs.glossary.stackable') }}</dt>
          <dd class="col-sm-9">@lang('docs.glossary.stackable_def')</dd>
        </dl>
      </div>
    </div>

    <div id="support" class="card mb-4">
      <div class="card-header"><h5 class="mb-0">{{ __('docs.sections.support') }}</h5></div>
      <div class="card-body">
        <p>@lang('docs.support.p1')</p>
        <p class="mb-0">@lang('docs.support.p2')</p>
      </div>
    </div>

  </div>
</div>
@stop
