@php
    use App\Helpers\MenuHelper;
    
    $menuItems = $menuItems ?? [];
    $serviceInstitutedetails = $serviceInstitutedetails ?? null;
    
    $instituteType = $serviceInstitutedetails->type ?? 'Institute';

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

    function replaceLabel($text, $map) {
        return str_replace(array_keys($map), array_values($map), $text);
    }
@endphp

<nav class="bg-gradient-primary" id="accordionSidebar">

    {{-- Sidebar Brand --}}
    @if(auth()->user()->hasRole('superadmin'))
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15 me-2">
                <i class="fas fa-university"></i>
            </div>
            <div class="sidebar-brand-text">
                Superadmin
            </div>
        </a>
    @else
        <a class="sidebar-brand" href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('employee') ? route('employee.dashboard') : route('student.dashboard')) }}">
            <div class="sidebar-brand-img">
                @if($serviceInstitutedetails && isset($serviceInstitutedetails->documents[0]['logo_path']))
                    <img src="{{ asset('/image/'.$serviceInstitutedetails->documents[0]['logo_path']) }}" alt="Logo">
                @else
                    <img src="https://test.cerebroxtek.com/image/logo.png" alt="Logo">
                @endif
            </div>
        </a>
    @endif

    <hr class="dropdown-divider my-0 opacity-25">

    <div class="sidebar-nav" id="sidebarAccordion">
        @foreach($menuItems as $item)

            @if($item->hasChildren())

                @php
                    $isActive = false;

                    foreach ($item->children as $child) {
                        $route = trim($child->route, '/');

                        if (request()->is($route) || request()->is($route . '/*')) {
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
                                @php
                                    $route = trim($child->route, '/');
                                    $isChildActive = request()->is($route) || request()->is($route . '/*');
                                @endphp

                                <a class="collapse-item {{ $isChildActive ? 'active' : '' }}"
                                   href="{{ url($child->route) }}">
                                    {{ replaceLabel($child->name, $activeMap) }}
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>

            @else

                @php
                    $route = trim($item->route, '/');
                    $isActive = request()->is($route) || request()->is($route . '/*');
                @endphp

                <div class="nav-item">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" 
                       href="{{ url($item->route) }}">

                        <i class="{{ $item->icon }}"></i>
                        <span>{{ replaceLabel($item->name, $activeMap) }}</span>
                    </a>
                </div>

            @endif

        @endforeach
    </div>

    <hr class="d-none dropdown-divider my-2 opacity-25 mx-3">

    <div class="text-center d-none">
        <button class="sidebar-toggler" id="sidebarToggle">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>

</nav>