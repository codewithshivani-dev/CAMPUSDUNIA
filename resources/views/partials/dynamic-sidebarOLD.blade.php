@php
    use App\Helpers\MenuHelper;
    
    // Get menu based on user role
    $menuItems = $menuItems ?? [];
    $serviceInstitutedetails = $serviceInstitutedetails ?? null;
    
    // Detect institute type
    $instituteType = $serviceInstitutedetails->type ?? 'Institute';

    // Label replacement map
    $labelMap = [
        'School' => [
            'Institute' => 'School',
            'Institutes' => 'Schools',
            'Course' => 'Class',
            'Courses' => 'Classes',
            'View Institute' => 'View School',
        ],
        'Institute' => [
            'Institute' => 'Institute',
            'Institutes' => 'Institutes',
            'Course' => 'Course',
            'Courses' => 'Courses',
            'View Institute' => 'View Institute',
        ],
    ];

    $activeMap = $labelMap[$instituteType] ?? $labelMap['Institute'];

    // Replace helper
    function replaceLabel($text, $map) {
        return str_replace(array_keys($map), array_values($map), $text);
    }
@endphp

<!-- Sidebar -->
<nav class="bg-gradient-primary" id="accordionSidebar">
    <!-- Sidebar Brand -->
    @if(auth()->user()->hasRole('superadmin'))
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15 me-2">
                <i class="fas fa-university"></i>
            </div>
            <div class="sidebar-brand-text">
                Superadmin
            </div>
        </a>
    @elseif(auth()->user()->hasRole('admin') || auth()->user()->hasRole('employee') || auth()->user()->hasRole('student'))
        <a class="sidebar-brand" href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('employee') ? route('employee.dashboard') : route('student.dashboard')) }}">
            <div class="sidebar-brand-img">
                @if($serviceInstitutedetails && isset($serviceInstitutedetails->documents[0]['logo_path']))
                    <img src="{{ asset('/image/'.$serviceInstitutedetails->documents[0]['logo_path']) }}" alt="Logo">
                @else
                    <img src="https://test.cerebroxtek.com/image/logo.png" alt="Campusdunia-Logo">
                @endif
            </div>
        </a>
    @endif

    <!-- Divider -->
    <hr class="dropdown-divider my-0 opacity-25">

    <!-- Dynamic Menu Items -->
    <div class="sidebar-nav" id="sidebarAccordion">
        @foreach($menuItems as $item)
            @if($item->hasChildren())
                <!-- Dropdown Menu Item -->
                @php
                    $isActive = false;
                    foreach ($item->children as $child) {
                        if (request()->is(ltrim($child->route, '/'))) {
                            $isActive = true;
                            break;
                        }
                    }
                    $collapseId = 'collapse' . $item->id;
                @endphp

                <div class="nav-item">
                    <a class="nav-link {{ $isActive ? '' : 'collapsed' }} sidebar-nav-link" 
                       data-bs-toggle="collapse" 
                       href="#{{ $collapseId }}"
                       role="button"
                       aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                       aria-controls="{{ $collapseId }}">
                        <i class="{{ $item->icon }}"></i>
                        <span>{{ replaceLabel($item->name, $activeMap) }}</span>
                    </a>

                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                        <div class="collapse-menu">
                            @foreach($item->children as $child)
                                <a class="collapse-item {{ request()->is(ltrim($child->route, '/')) ? 'active' : '' }}"
                                   href="{{ url($child->route) }}">
                                    {{ replaceLabel($child->name, $activeMap) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            @else
                <!-- Single Menu Item -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->is(ltrim($item->route, '/')) ? 'active' : '' }}" 
                       href="{{ url($item->route) }}">
                        <i class="{{ $item->icon }}"></i>
                        <span>{{ replaceLabel($item->name, $activeMap) }}</span>
                    </a>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Divider -->
    <hr class="d-none dropdown-divider my-2 opacity-25 mx-3">

    <!-- Sidebar Toggler (Desktop only) -->
    <div class="text-center d-none">
        <button class="sidebar-toggler" id="sidebarToggle">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>
</nav>