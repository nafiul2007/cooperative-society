<div class="menu-wrapper">
    <style>
        .nav .nav-item {
            border: 1px solid #dee2e6; /* Light gray border */
            margin-bottom: 0; /* Removed spacing between items */
            background-color: #f8f9fa; /* Light gray background */
            transition: background-color 0.2s ease;
        }

        .nav .nav-item + .nav-item {
            border-top: none; /* Avoid double borders between items */
        }

        .nav .nav-item:hover {
            background-color: #dadada; /* Slightly darker on hover */
        }

        .nav-link {
            padding: 0.5rem 1rem;
            color: #212529;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
        }

        .nav-link.active {
            background-color: rgb(58, 58, 58);
            color: white !important;
        }

        .collapse .nav-item {
            background-color: #ffffff;
        }

        .collapse .nav-link {
            padding-left: 1.5rem;
        }
        
        .nav {
            margin-bottom: 0.25rem;
        }
    </style>

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            @if (Auth::user()->hasRole('admin')) 
                Admin User
            @else
                Member User
            @endif
        </span>
    </div>
    <div class="card-body">
        <ul class="nav flex-column">
            {{-- Society Info Collapsible Menu --}}
            @if (Auth::user()->hasRole('admin') || $hasSocietyInfo)
            <li class="nav-item">
                @php
                    $isSocietyOpen = request()->routeIs('society-info.*');
                @endphp
                <a class="nav-link d-flex justify-content-between align-items-center {{ $isSocietyOpen ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#societyMenu" role="button"
                    aria-expanded="{{ $isSocietyOpen ? 'true' : 'false' }}" aria-controls="societyMenu">
                    <span>Society Info</span>
                    <i class="bi {{ $isSocietyOpen ? 'bi-chevron-up' : 'bi-chevron-down' }}"></i>
                </a>
                <div class="collapse {{ $isSocietyOpen ? 'show' : '' }}" id="societyMenu">
                    <ul class="nav flex-column ms-3 mt-1">
                        @if (Auth::user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('society-info.edit') ? 'active' : '' }}"
                                    href="{{ route('society-info.edit') }}">
                                    @if ($hasSocietyInfo) Edit Info @else Add Info @endif
                                </a>
                            </li>
                        @endif
                        @if ($hasSocietyInfo)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('society-info.show') ? 'active' : '' }}"
                                    href="{{ route('society-info.show') }}">
                                    View Info
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- Other Menu Items --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                    href="{{ route('profile.edit') }}">
                    Profile
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}"
                    href="{{ route('members.index') }}">
                    Members
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('contribution.*') ? 'active' : '' }}"
                    href="{{ route('contributions.index') }}">
                    Contributions
                </a>
            </li>

            {{-- Logout (optional) --}}
            {{-- 
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Log Out</button>
                </form>
            </li>
            --}}
        </ul>
    </div>
</div>

{{-- Optional: Toggle Icon Script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggler = document.querySelector('[href="#societyMenu"]');
        const icon = toggler?.querySelector('i');

        toggler?.addEventListener('click', () => {
            icon?.classList.toggle('bi-chevron-down');
            icon?.classList.toggle('bi-chevron-up');
        });
    });
</script>
