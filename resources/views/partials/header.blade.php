<header class="page-hero">
    @if (auth()->check() && auth()->user()->hasRole('Admin'|| 'Rgs'|| 'Dg'|| 'Dfc' || 'Cc'))
        <div class="hero-content container">
            <h1>{{ $title ?? 'CBC' }}</h1>
        </div>  
    @else
      <h1>Bienvenue — Portail CBC</h1>
    @endif
    </div>
</header>
