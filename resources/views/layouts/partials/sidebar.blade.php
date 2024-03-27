        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="app-brand demo">
              <a href="index.html" class="app-brand-link">
                <span class="app-brand-logo demo">
                  <svg
                    width="25"
                    viewBox="0 0 25 42"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                  >
                    <defs>
                      <path
                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                        id="path-1"
                      ></path>
                      <path
                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                        id="path-3"
                      ></path>
                      <path
                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                        id="path-4"
                      ></path>
                      <path
                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                        id="path-5"
                      ></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                          <g id="Mask" transform="translate(0.000000, 8.000000)">
                            <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                            </mask>
                            <use fill="#696cff" xlink:href="#path-1"></use>
                            <g id="Path-3" mask="url(#mask-2)">
                              <use fill="#696cff" xlink:href="#path-3"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                            </g>
                            <g id="Path-4" mask="url(#mask-2)">
                              <use fill="#696cff" xlink:href="#path-4"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                            </g>
                          </g>
                          <g
                            id="Triangle"
                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                          >
                            <use fill="#696cff" xlink:href="#path-5"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                          </g>
                        </g>
                      </g>
                    </g>
                  </svg>
                </span>
                <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>
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
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Manajemen</span>
                    </li>
                    @php
                        $data = App\Models\Clients::where("nama_client",'!=','PT Proven Force Indonesia')->get();
                    @endphp
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
                    <li class="menu-item {{  menuOpen(['direktur-pre-order-index']) }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle" style="color: white">
                            <i class="menu-icon tf-icons bx bx-cart"></i>
                            <div data-i18n="Authentications">Permintaan Pembelian</div>
                        </a>

                        <ul class="menu-sub">
                            @foreach ($data as $item)
                                <li class="menu-item  {{ ($item->nama_client == Request::segment(4)) && ( Request::segment(2) == "pre-order") ? 'active' : ""  }}">
                                    <a href="{{ route('direktur-pre-order-index',['nama_client' => $item->nama_client,'id' => HashVariable($item->id)]) }}" class="menu-link " >
                                        <div data-i18n="Basic">PT {{ $item->nama_client }} </div>
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
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
