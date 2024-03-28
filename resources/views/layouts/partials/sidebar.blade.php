        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="app-brand demo" style="padding-left:32px !important">
              <a href="{{ route('home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/layouts/pfiBlueWhite.png') }}" alt="" width="90">
                <span class="app-brand-text demo menu-text fw-bolder ms-2"></span>
              </a>

              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item {{ menuActive(['home','profile']) }}">
                    <a href="{{ route("home") }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                @if (in_array(Auth::user()->roles,['superadmin','admin','korlap','hrd','spv-internal','kr-pusat','kr-project','karyawan','manajer']))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Manajemen</span>
                    </li>
                    @if (in_array(Auth::user()->roles,['superadmin','admin','korlap','hrd','spv-internal']))
                        {{-- KARYAWAN --}}
                        <li class="menu-item {{  menuOpen(['karyawan-add','karyawan','karyawan-edit','jabatan','divisi']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                                <div data-i18n="">Karyawan</div>
                            </a>
                            <ul class="menu-sub">
                                @if (Auth::user()->roles != 'spv-internal')
                                    <li class="menu-item {{  menuActive('karyawan-add') }}">
                                        <a href="{{ route('karyawan-add') }}" class="menu-link" >
                                        <div data-i18n="Basic">Tambah Karyawan</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{  menuActive(['karyawan','karyawan-edit']) }}">
                                        <a href="{{ route('karyawan') }}" class="menu-link" >
                                        <div data-i18n="Basic">Kelola Karyawan</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{  menuActive(['divisi']) }}">
                                        <a href="{{ route('divisi') }}" class="menu-link" >
                                        <div data-i18n="Basic">Kelola Divisi</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{  menuActive(['jabatan']) }}">
                                        <a href="{{ route('jabatan') }}" class="menu-link" >
                                        <div data-i18n="Basic">Kelola Jabatan</div>
                                        </a>
                                    </li>
                                @else
                                <li class="menu-item {{  menuActive(['karyawan','karyawan-edit']) }}">
                                    <a href="{{ route('karyawan') }}" class="menu-link" >
                                    <div data-i18n="Basic">Kelola Karyawan</div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- CLIENT --}}
                    @if (in_array(Auth::user()->roles,['superadmin']))
                        <li class="menu-item {{  menuOpen(['client']) }}">
                            <a href="{{ route('client') }}" class="menu-link">
                                <i class='menu-icon tf-icons bx bxs-building-house'></i>
                                <div data-i18n="Analytics">Kelola Client</div>
                            </a>
                        </li>
                    @endif

                    {{-- ABSENSI --}}
                    <li class="menu-item {{  menuOpen(['shift','absensi-data','absensi-search-one','absensi']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-calendar"></i>
                            <div data-i18n="">Absensi</div>
                        </a>
                        <ul class="menu-sub">
                            @if (in_array(Auth::user()->roles,['admin','korlap','superadmin','spv-internal','hrd']))
                                @if (in_array(Auth::user()->roles,['admin','korlap']))
                                    @if(Auth::user()->id_client != 2)
                                    <li class="menu-item ">
                                        <a href="#" class="menu-link" >
                                        <div data-i18n="Basic">Log Absensi PKWT</div>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="menu-item  {{ Request::segment(2) == 'khl' ? 'active' : '' }} ">
                                        <a href="{{ route('absensi-data',['karyawan' => 'khl']) }}" class="menu-link" >
                                        <div data-i18n="Basic">Log Absensi KHL</div>
                                        </a>
                                    </li>

                                @endif
                                @if (in_array(Auth::user()->roles,['hrd','spv-internal']))
                                    <li class="menu-item {{ Request::segment(2) == 'pusat' ? 'active' : '' }}">
                                        <a href="{{ route('absensi-data',['karyawan' => 'pusat']) }}" class="menu-link" >
                                        <div data-i18n="Basic">Log Absensi PUSAT</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ Request::segment(2) == 'project' ? 'active' : '' }}">
                                        <a href="{{ route('absensi-data',['karyawan' => 'project']) }}" class="menu-link" >
                                        <div data-i18n="Basic">Log Absensi PROJECT</div>
                                        </a>
                                    </li>
                                @endif
                                <li class="menu-item {{  menuActive('shift') }}">
                                    <a href="{{ route('shift') }}" class="menu-link" >
                                    <div data-i18n="Basic">Kelola Shift</div>
                                    </a>
                                </li>

                            @endif
                            @if (in_array(Auth::user()->roles,['kr-pusat','karyawan','kr-project']))
                                <li class="menu-item {{ menuActive('absensi') }}">
                                    <a href="{{ route('absensi') }}" class="menu-link" >
                                    <div data-i18n="Basic">Absensi Anda</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if(in_array(Auth::user()->roles,['superadmin']))
                        <li class="menu-item {{  menuOpen(['role']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bxs-lock"></i>
                                <div data-i18n="">Perizinan</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{  menuActive('role') }}">
                                    <a href="{{ route('roles') }}" class="menu-link" >
                                    <div data-i18n="Basic">Role</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    {{-- LEMBUR --}}
                    @if(Auth::user()->roles != 'superadmin')
                        @php
                            $id_jabatan = App\Models\Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first()->jabatan;
                            $jabatan = App\Models\Jabatan::find($id_jabatan)->nama_jabatan;

                        @endphp
                    @else
                        @php
                            $id_jabatan = "";
                            $jabatan = "Superadmin";
                        @endphp
                    @endif


                    @if ($jabatan != 'Manager' && $jabatan != 'Head')
                        <li class="menu-item {{ menuOpen(['lembur','lembur-self']) }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-time"></i>
                            <div data-i18n="Authentications">Lembur </div>
                            </a>
                            @if (in_array(Auth::user()->roles,['admin']))

                                <ul class="menu-sub">
                                    <li class="menu-item {{ menuActive('lembur') }}">
                                        <a href="{{ route('lembur') }}" class="menu-link" >
                                        <div data-i18n="Basic">Data Lembur Karyawan</div>
                                        </a>
                                    </li>

                                </ul>
                            @elseif (in_array(Auth::user()->roles,['kr-pusat']))

                                <ul class="menu-sub">
                                    <li class="menu-item {{ menuActive('lembur-self') }}">
                                        <a href="{{ route('lembur-self') }}" class="menu-link" >
                                        <div data-i18n="Basic">Data Lembur Anda </div>
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <ul class="menu-sub">
                                    <li class="menu-item {{ menuActive('lembur') }}">
                                        <a href="{{ route('lembur') }}" class="menu-link" >
                                        <div data-i18n="Basic">Data Lembur </div>
                                        </a>
                                    </li>

                                </ul>
                            @endif

                        </li>

                    @endif


                    {{-- PRODDUK --}}
                    @if(in_array(Auth::user()->roles,['admin','korlap','spv-internal']))
                        @if (in_array(Auth::user()->id_client,[2,8]))
                            <li class="menu-item {{  menuOpen(['list-produk','laporan-produksi','laporan-produksi-detail','laporan-produksi-yp']) }}">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-cube"></i>
                                <div data-i18n="Authentications">Produk</div>
                                </a>
                                <ul class="menu-sub">
                                    @if (in_array(Auth::user()->roles ,['admin','korlap']))
                                        <li class="menu-item {{ menuActive("list-produk") }}">
                                            <a href="{{ route("list-produk") }}" class="menu-link" >
                                            <div data-i18n="Basic">List Produk</div>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ menuActive("produk-satuan") }}">
                                            <a href="#" class="menu-link" >
                                            <div data-i18n="Basic">List Satuan Produk</div>
                                            </a>
                                        </li>
                                        @if (Auth::user()->id_client == 2)
                                            <li class="menu-item  {{ menuActive(['laporan-produksi-detail','laporan-produksi']) }}">
                                                <a href="{{ route('laporan-produksi') }}" class="menu-link" >
                                                <div data-i18n="Basic">Buat Laporan Produksi</div>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->id_client == 8)

                                            <li class="menu-item  {{ Request::segment(2) == 'Y3' ? 'active' : ''  }}">
                                                <a href="{{ route('laporan-produksi-yp',['kategori' => "Y3"]) }}" class="menu-link" >
                                                <div data-i18n="Basic">Rekap FG Y3</div>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::segment(2) == 'Y4' ? 'active' : ''  }}">
                                                <a href="{{ route('laporan-produksi-yp',['kategori' => 'Y4']) }}" class="menu-link" >
                                                <div data-i18n="Basic">Rekap FG Y4</div>
                                                </a>
                                            </li>
                                        @endif

                                    @elseif (in_array(Auth::user()->roles, ['super-admin']))
                                        <li class="menu-item {{ menuActive(["laporan-produksi"]) }}">
                                            <a href="{{ route('laporan-produksi') }}" class="menu-link" >
                                            <div data-i18n="Basic">Laporan Produksi</div>
                                            </a>
                                        </li>
                                    @elseif (in_array(Auth::user()->roles, ['spv-internal']))
                                            @if (Auth::user()->id_client == 8)

                                                <li class="menu-item  {{ Request::segment(2) == 'Y3' ? 'active' : ''  }}">
                                                    <a href="{{ route('laporan-produksi-yp',['kategori' => "Y3"]) }}" class="menu-link" >
                                                    <div data-i18n="Basic">Rekap FG Y3</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Request::segment(2) == 'Y4' ? 'active' : ''  }}">
                                                    <a href="{{ route('laporan-produksi-yp',['kategori' => 'Y4']) }}" class="menu-link" >
                                                    <div data-i18n="Basic">Rekap FG Y4</div>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="menu-item {{ menuActive(["laporan-produksi"]) }}">
                                                    <a href="{{ route('laporan-produksi') }}" class="menu-link" >
                                                    <div data-i18n="Basic">Laporan Produksi</div>
                                                    </a>
                                                </li>
                                            @endif
                                    @endif
                                </ul>
                            </li>
                        @endif
                         {{-- SURAT PERINGATAN --}}
                        <li class="menu-item {{  menuOpen(['peringatan','index-rf']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Authentications">Surat</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{  menuActive('peringatan') }}">
                                    <a href="{{ route('peringatan') }}" class="menu-link" >
                                    <div data-i18n="Basic">Surat Peringatan</div>
                                    </a>
                                </li>
                                <li class="menu-item {{  menuActive('index-rf') }}">
                                    <a href="{{ route('index-rf') }}" class="menu-link" >
                                    <div data-i18n="Basic">Surat Referensi Kerja</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    {{-- SURAT --}}
                    @if(in_array(Auth::user()->roles,['karyawan']))
                            <li class="menu-item {{  menuOpen(['peringatan','index-rf']) }} ">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-file"></i>
                                <div data-i18n="Authentications">Surat</div>
                                </a>
                                <ul class="menu-sub">
                                    <li class="menu-item {{  menuActive('peringatan') }}">
                                        <a href="{{ route('peringatan') }}" class="menu-link" >
                                        <div data-i18n="Basic">Surat Peringatan</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{  menuActive('index-rf') }}">
                                        <a href="{{ route('index-rf') }}" class="menu-link" >
                                        <div data-i18n="Basic">Surat Referensi Kerja</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                    @endif


                    {{-- IZIN --}}
                    <li class="menu-item {{  menuOpen(['izin']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                        <div data-i18n="Authentications">Izin</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{  menuActive('izin') }}">
                                <a href="{{ route('izin') }}" class="menu-link" >
                                <div data-i18n="Basic">Pengajuan Izin</div>
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- CUTI --}}
                    @if(in_array(Auth::user()->roles,['kr-project','kr-pusat','hrd','direktur','manajer']))
                        <li class="menu-item {{  menuOpen(['cuti','cuti-kategori']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                            <div data-i18n="Authentications">Cuti</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{  menuActive('cuti') }}">
                                    <a href="{{ route('cuti') }}" class="menu-link" >
                                    <div data-i18n="Basic">Pengajuan Cuti</div>
                                    </a>
                                </li>
                                @if(Auth::user()->roles == 'hrd')
                                    <li class="menu-item {{  menuActive('cuti-kategori') }}">
                                        <a href="{{ route('cuti-kategori') }}" class="menu-link" >
                                        <div data-i18n="Basic">Kategori Cuti</div>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    @if (in_array(Auth::user()->roles,['spv-internal','admin','korlap']))
                        <li class="menu-item {{  menuOpen(['pre-order']) }}">
                            <a href="{{ route('pre-order') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cart"></i>
                            <div>Permintaan Pembelian</div>
                            </a>
                        </li>
                    @endif

                @endif


                {{-- ROUTING DIREKTUR --}}
                @if (Auth::user()->roles == 'direktur')
                @php
                    $divisi = App\Models\Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
                    $nama_divisi = App\Models\Divisi::find($divisi->divisi)->nama_divisi;
                @endphp
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Manajemen</span>
                    </li>
                    @php
                        $data = App\Models\Clients::where("nama_client",'!=','PT Proven Force Indonesia')->get();
                    @endphp
                    @if ($nama_divisi == 'MPO')
                        <li class="menu-item {{  menuOpen(['direktur-rf-index']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-file"></i>
                                <div data-i18n="Authentications">Referensi Kerja </div>
                            </a>

                            <ul class="menu-sub">
                                @foreach ($data as $item)
                                    <li class="menu-item  {{ $item->nama_client == Request::segment(2)  ? 'active' : ""  }}">
                                        <a href="{{ route('direktur-rf-index',['nama_client' => $item->nama_client, 'id' => HashVariable($item->id)]) }}" class="menu-link " >
                                            <div data-i18n="Basic">{{ $item->nama_client }} </div>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>
                        <li class="menu-item {{  menuOpen(['pre-order-direktur']) }} ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-cart"></i>
                                <div data-i18n="Authentications">Permintaan Pembelian</div>
                            </a>

                            <ul class="menu-sub">
                                @foreach ($data as $item)
                                    <li class="menu-item  {{ $item->nama_client == Request::segment(3)  ? 'active' : ""  }}">
                                        <a href="{{ route('pre-order-direktur',['nama_client' => $item->nama_client,'id' => HashVariable($item->id)]) }}" class="menu-link " >
                                            <div data-i18n="Basic">{{ $item->nama_client }} </div>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>

                    @endif
                    {{-- SURAT --}}
                    {{-- <li class="menu-item {{  menuOpen(['index-rf']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Authentications">Surat</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{  menuActive('index-rf') }}">
                                <a href="{{ route('index-rf') }}" class="menu-link" >
                                <div data-i18n="Basic">Surat Referensi Kerja</div>
                                </a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- LEMBUR --}}
                    @php
                        $data  = App\Models\Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
                        $divisi = App\Models\Divisi::find($data->divisi);
                        $nama_divisi = $divisi->nama_divisi;
                    @endphp
                    @if (Auth::user()->roles != 'head')
                        <li class="menu-item {{ menuOpen('lembur') }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-time"></i>
                            <div data-i18n="Authentications">Lembur</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ menuActive('lembur') }}">
                                    <a href="{{ route('lembur') }}" class="menu-link" >
                                    <div data-i18n="Basic">Data Lembur</div>
                                    </a>
                                </li>

                            </ul>
                        </li>

                    @endif
                    <li class="menu-item {{  menuOpen(['izin']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                        <div data-i18n="Authentications">Izin</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{  menuActive('izin') }}">
                                <a href="{{ route('izin') }}" class="menu-link" >
                                <div data-i18n="Basic">Pengajuan Izin</div>
                                </a>
                            </li>

                        </ul>
                    </li>
                    {{-- CUTI --}}
                    <li class="menu-item {{  menuOpen(['cuti','cuti-kategori']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                        <div data-i18n="Authentications">Cuti</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{  menuActive('cuti') }}">
                                <a href="{{ route('cuti') }}" class="menu-link" >
                                <div data-i18n="Basic">Pengajuan Cuti</div>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- PENGUMUMAN --}}

                @if(Auth::user()->roles == 'spv-internal')
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Informasi</span>
                </li>
                <li class="menu-item {{ menuActive('pengumuman') }}">
                    <a href="{{ route("pengumuman") }}" class="menu-link">
                        <i class='menu-icon tf-icons bx bx-user-voice'></i>
                    <div data-i18n="Analytics">Pengumuman</div>
                    </a>
                </li>

                @endif
            </ul>
          </aside>
          <!-- / Menu -->
