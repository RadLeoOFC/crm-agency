<a class="nav-link dropdown-toggle"
   data-toggle="dropdown"
   href="#"
   role="button"
   aria-haspopup="true"
   aria-expanded="false">
    <i class="fas fa-language"></i>
</a>

<div class="dropdown-menu dropdown-menu-right">
    @foreach($languages as $language)
        <a class="dropdown-item"
           href="{{ route('language.switch', $language->code) }}">
            {{ strtoupper($language->code) }}
        </a>
    @endforeach
</div>
