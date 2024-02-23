@extends('vBase')
@section('title') {{ 'Management Shift' }} @endsection

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
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Absensi /</span>Kelola Shift</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Shift</h5>
            </div>
            <div class="card-title">

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#TambahShift" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Shift
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Type Shift</th>
                            <th>Jam Kerja</th>
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

<div class="modal fade" id="TambahShift" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle" style="color: black">Tambah Shift</h5>
          <button type="button" class="btn btn-default close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="formTambahShift">
            @csrf
            <input type="hidden" name="id" id="id" value="">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Nama Shift</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control "  placeholder="Shift" name="nama_shift" id="nama_shift" type="text" >
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Ke Berapa</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" placeholder="1" name="ke" type="text" id="ke">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Waktu Mulai</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" placeholder="1" name="waktu_mulai" type="time" id="waktu_mulai" >

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Waktu Selsai</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" placeholder="1" name="waktu_selesai" type="time" id="waktu_selesai" >
                    </div>
                </div>
                @if (in_array(Auth::user()->role,['superadmin']))
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="basic-default-name">Client</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="id_client" id="id_client" required class="form-control">

                            </select>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary simpan">
                    Simpan
                </button>
            </div>
        </form>
      </div>
    </div>
</div>

@endsection
  @push('js')
    <script>
        var url_get_data = "{{ route('shift-data') }}";
        var url_simpan_data = "{{ route('shift-save') }}";
        var url_get_datas = "{{ route('shift-get') }}";
        var url_delete_data = "{{ route('shift-delete') }}";
    </script>

    <script src="{{ asset('assets/js/shift.js') }}"></script>
@endpush
