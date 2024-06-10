@php
    use App\Role;
    use App\UserMenu;

    $user_menu = UserMenu::with('role')
        ->whereHas('role', function ($query) {
            return $query->whereIn('role', json_decode(auth()->user()->roles));
        })
        ->orderBy('id')
        ->get();
    // $user_menu = Role::with('menu')
    //     ->where('role', auth()->user()->role)
    //     ->get();
    // dd($user_menu->pluck('title'));
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="">
        <img src="{{ asset('img/favicon.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">SMS - MI Jambearum</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach ($user_menu as $menu)
                    @if ($menu->route == null)
                        <li class="nav-item has-treeview 
                            {{ $menu->sub_menu->contains(function ($sub_menu) {
                                return Route::is($sub_menu->route);
                            })
                                ? 'menu-open'
                                : '' }}"
                            id="{{ 'li' . $menu->title }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon {{ $menu->icon }}"></i>
                                <p>
                                    {{ $menu->title }}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ml-4">
                                @foreach ($menu->sub_menu->filter(function ($subMenu) use ($user_menu) {
        return $user_menu->pluck('id')->contains($subMenu->id);
    }) as $sub_menu)
                                    <li class="nav-item">
                                        <a href="{{ route($sub_menu->route, $sub_menu->route_param ? $sub_menu->route_param : '') }}"
                                            class="nav-link {{ Route::is($sub_menu->route) && Request::route('role') == $sub_menu->route_param ? 'active' : '' }}">
                                            <i class="{{ $sub_menu->icon }} nav-icon"></i>
                                            <p>{{ $sub_menu->title }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @elseif($menu->menu_id == null && $menu->route)
                        <li class="nav-item">
                            <a href="{{ route($menu->route) }}"
                                class="nav-link {{ Route::is($menu->route) && Request::route('role') == $menu->route_param ? 'active' : '' }}">
                                <i class="{{ $menu->icon }} nav-icon"></i>
                                <p>{{ $menu->title }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
                <li class="nav-item has-treeview"
                    id="Aturan Jam Siswa">
                    <a href="{{ route('aturan-jam-siswa.index') }}" class="nav-link {{ Route::is('aturan-jam-siswa.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Aturan Jam Absensi
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="Absensi Kehadiran Siswa">
                    <a href="{{ route('absensi-kehadiran.index') }}" class="nav-link {{ Route::is('absensi-kehadiran.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Absensi Kehadiran Siswa
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="Absensi Kehadiran Guru">
                    <a href="{{ route('absensi-kehadiran.guru') }}" class="nav-link {{ Route::is('absensi-kehadiran.guru') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Absensi Kehadiran Guru
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="Tabungan Siswa">
                    <a href="{{ route('kelas.tabungan.index') }}" class="nav-link {{ Route::is('kelas.tabungan.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Tabungan Siswa
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="Infaq">
                    <a href="{{ route('kelas.infaq.index') }}" class="nav-link {{ Route::is('kelas.infaq.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Infaq
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview" id="Pembelian LKS">
                    <a href="{{ route('kelas.lks.index') }}" class="nav-link {{ Route::is('kelas.lks.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Pembelian LKS
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
