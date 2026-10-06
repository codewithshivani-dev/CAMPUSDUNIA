@php
    use App\Helpers\MenuHelper;
    
    // Get menu based on user role

@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-university"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            @if(auth()->user()->hasRole('superadmin'))
                Superadmin
            @elseif(auth()->user()->hasRole('admin'))
                Admin Panel
            @elseif(auth()->user()->hasRole('employee'))
                {{ auth()->user()->employee->department->name ?? 'Employee' }}
            @elseif(auth()->user()->hasRole('student'))
                Student Portal
            @endif
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dynamic Menu Items -->
    @foreach($menuItems as $item)
        @if($item->hasChildren())
            <!-- Dropdown Menu Item -->
            <li class="nav-item">
                @php
                    $isActive = false;
                    foreach ($item->children as $child) {
                        if (request()->is(ltrim($child->route, '/'))) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp
                <a class="nav-link navitem collapsed {{ $isActive ? 'active' : '' }}" href="#"
                   data-toggle="collapse" data-target="#collapse{{ $item->id }}"
                   aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                   aria-controls="collapse{{ $item->id }}">
                    <i class="{{ $item->icon }}"></i>
                    <span>{{ $item->name }}</span>
                </a>
                <div id="collapse{{ $item->id }}" class="collapse {{ $isActive ? 'show' : '' }}"
                     data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @foreach($item->children as $child)
                            <a class="collapse-item {{ request()->is(ltrim($child->route, '/')) ? 'active' : '' }}"
                               href="{{ url($child->route) }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
        @else
            <!-- Single Menu Item -->
            <li class="nav-item {{ request()->is(ltrim($item->route, '/')) ? 'active' : '' }}">
                <a class="nav-link navitem" href="{{ url($item->route) }}">
                    <i class="{{ $item->icon }}"></i>
                    <span>{{ $item->name }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>