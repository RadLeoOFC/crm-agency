<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle"
       data-toggle="dropdown"
       href="#"
       role="button"
       aria-haspopup="true"
       aria-expanded="false">
        <i class="fas fa-language"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-right">
        @foreach(config('app.available_locales') as $locale)
            <a href="{{ route('language.switch', $locale) }}"
               class="dropdown-item {{ app()->getLocale() === $locale ? 'active' : '' }}">
                {{ strtoupper($locale) }}
            </a>
        @endforeach
    </div>
</li>
