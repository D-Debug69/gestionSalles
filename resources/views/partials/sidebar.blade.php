<aside class="sidebar d-flex flex-column" id="mainSidebar" aria-hidden="false">
  <div class="p-3 border-bottom">
    <img src="{{ asset('images/cbc.jpeg') }}" class="logo rounded-circle" alt="Logo">
    <br>
    @if(auth()->check())
      
        <h6>{{ auth()->user()->name }}- Gestion Globale</h6>
      
    @else
      <h6>Utilisateur</h6>
    @endif
  </div>

  <ul class="nav flex-column p-3 flex-grow-1">
    @auth
      
        <li class="nav-item"><a class="nav-link active" href="/adminView">Tableau de bord</a></li>
        @can('view salle')
        <li class="nav-item"><a class="nav-link" href="/allSallesView">Salles</a></li>
        @endcan
        @can('view reservation')
        <li class="nav-item"><a class="nav-link" href="/allReservationsView">Réservations</a></li>
        @endcan
        @can('view user')
        <li class="nav-item"><a class="nav-link" href="/allUsersView">Utilisateurs</a></li>
        @endcan
        @can('view entreprise')
        <li class="nav-item"><a class="nav-link" href="/allEntreprisesView">Entreprises</a></li>
        @endcan
        @can('view association')
        <li class="nav-item"><a class="nav-link" href="/allAssociationsView">Associations</a></li>
        @endcan
      
      @if (auth()->user()->role === 'Admin')
        <li class="nav-item"><a class="nav-link active" href="{{ route('roles.index') }}">Gérer les rôles</a></li>
      
      @endif
    @endauth
  </ul>

  <div class="p-3 border-top mt-auto">
    @auth
      @if(in_array(strtolower(auth()->user()->role), ['admin', 'rgs', 'dg', 'dfc', 'cc']))
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger w-100">Déconnexion</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-danger w-100">Se connecter</a>
      @endif
    @endauth
    @guest
      <a href="{{ route('login') }}" class="btn btn-danger w-100">Se connecter</a>
    @endguest
  </div>

</aside>
