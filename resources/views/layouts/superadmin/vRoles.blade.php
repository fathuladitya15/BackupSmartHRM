@extends('vBase')

@section('title') {{  'Manajemen Perizinan' }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Perizinan /</span>Role</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Role</h5>
            </div>
            <div class="card-title">

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Role
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Nama Role</th>
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
          <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Role</h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="Add">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class=" col-form-label" for="basic-default-name">Nama Role</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="name_role" type="text" id="name_role">
                        <input class="form-control"  name="id" type="hidden" id="id">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class=" col-form-label" for="basic-default-name">Slug</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="slug_role" type="text" id="slug_role">
                    </div>
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
        var url_get_data = "{{ route('roles-data') }}";
        var url_simpan_data = "{{ route('roles-save') }}";
        var url_delete_data = "{{ route('roles-delete') }}";
        var url_get_datas = "{{ route('roles-get') }}";

    </script>

    <script src="{{ asset('assets/js/superadmin/role.js') }}"></script>
@endpush
@endsection
