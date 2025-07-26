
    <div class="">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Menu</span>
        </div>
        <div class="card-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        Profile
                    </a>
                </li>

                @if(Auth::user()->hasRole('admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                        Manage Members
                    </a>
                </li>
                @endif
{{-- 
                <li class="nav-item mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">Log Out</button>
                    </form>
                </li> --}}
            </ul>
        </div>
    </div>
