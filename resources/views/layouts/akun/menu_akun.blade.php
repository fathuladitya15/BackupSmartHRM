<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
      <a class="nav-link {{  menuActiveDetailProfile('karyawan-edit') }}" href="{{ route('karyawan-edit',['hash' => HashVariable($data_users->id_karyawan)]) }}"><i class="bx bx-user me-1"></i> Akun Karyawan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{  menuActiveDetailProfile('karyawan-pribadi') }}" href="{{ route('karyawan-pribadi',['hash' => HashVariable($data_users->id_karyawan)]) }}"  >
        <i class="bx bx-bell me-1"></i> Data Pribadi</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{  menuActiveDetailProfile('karyawan-client') }}" href="{{ route('karyawan-client',['hash' => HashVariable($data_users->id_karyawan)]) }}" >
        <i class="bx bx-link-alt me-1"></i> Detail Karyawan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{  menuActiveDetailProfile('karyawan-dokumen') }}" href="{{ route('karyawan-dokumen',['hash' => HashVariable($data_users->id_karyawan)]) }}" >
          <i class="bx bx-link-alt me-1"></i> Dokumen</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{  menuActiveDetailProfile('karyawan-bank') }}" href="{{ route('karyawan-bank',['hash' => HashVariable($data_users->id_karyawan)]) }}" >
          <i class="bx bx-link-alt me-1"></i> Bank</a>
    </li>
</ul>
