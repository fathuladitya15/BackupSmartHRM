@extends('vBase')

@section('title') {{ 'Kelola Client' }} @endsection

@section('content')
@push('css')
    <style>
        .black {
            color: black;

        }
        h4 {
            color: white;
        }
        h5 {
            color: white;
        }
        .card-title {
            padding-right: 30px;
            border-left-width: 30px;
            padding-left: 30px;

        }
        .card-header {
            background-color:#47b4f5;
            margin-bottom: 20px;
        }

        .swal2-container {
            z-index: 999999;
        };
    </style>

@endpush
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Kelola Client /</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Client</h5>
            </div>
            <div class="card-title">

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Client
                  </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Nama Client</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Client</h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="Add">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Nama Perusahaan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="nama_client" type="text" id="nama_client">
                    </div>
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Provinsi</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="prov" type="text" id="prov">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Kota /Kabupaten</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="kota" type="text" id="kota">
                    </div>
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Kecamatan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="kec" type="text" id="kec">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Desa / Kelurahan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="kel" type="text" id="kel">
                    </div>
                    {{-- <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Kecamatan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="kec" type="text" id="kec">
                    </div> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>

@push('js')
    <script>
        var url_get_data = "{{ route('client-data') }}";
        var url_simpan_data = "{{ route('client-save') }}";
    </script>

    <script src="{{ asset('assets/js/superadmin/clients.js') }}"></script>
@endpush
@endsection
