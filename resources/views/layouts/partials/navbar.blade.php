<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
              <i class="bx bx-buildings fs-4 lh-0"></i>
                <h4 style="margin-bottom: 2px; margin-left: 20px; color:black">
                    @php
                        if(Auth::user()->id_client != null) {
                            $nama_client = App\Models\Clients::find(Auth::user()->id_client)->nama_client;
                        }else {
                            $nama_client = "";
                        }
                    @endphp
                    @if (Auth::user()->role == 'superadmin')
                        Superadmin
                    @else
                    {{ $nama_client }}</h4>
                    @endif
            </div>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <li class="nav-item lh-1 me-3">
                <i class='bx bxs-bell'></i>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                <a class="dropdown-item" href="#">
                    <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                        @if (Auth::user()->roles != 'superadmin')
                            @php
                                $role       = App\Models\Role::where('slug_role',Auth::user()->roles)->first();
                                $rolename   = $role->name_role;
                                $table_kr   = App\Models\Karyawan::where('id_karyawan','like','%'.Auth::user()->id_karyawan.'')->first();
                                $divisi     = App\Models\Divisi::find($table_kr->divisi)->nama_divisi;
                                $jabatan    = App\Models\Jabatan::find($table_kr->jabatan)->nama_jabatan;
                                $client     = App\Models\Clients::find(Auth::user()->id_client)->nama_client;
                            @endphp
                        @endif
                        <small class="text-muted">{{ Auth::user()->id_client != null ? $client  : ''}}</small><br>
                        <small class="text-muted">{{ Auth::user()->roles == 'superadmin' ? 'Superadmin' : $rolename }}</small><br>
                        <small class="text-muted">{{ Auth::user()->roles != 'superadmin' ? $divisi  : ''}}</small>
                    </div>
                    </div>
                </a>
                </li>
                <li>
                <div class="dropdown-divider"></div>
                </li>
                <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-power-off me-2"></i>
                    <span class="align-middle">Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                </li>
            </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
  </nav>
