@extends('vBase')
@section('title') {{ 'Lembur - Supervisor' }}@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">

<style>
    .black {
        color: black;

    }
    h4 {
        color: white;
    }
    h3 {
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

</style>

@endpush

@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>


<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Lembur</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>ID Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Tanggal</th>
                                <th>Tugas Dikerjakan</th>
                                <th>Mulai Jam</th>
                                <th>Sampai Jam</th>
                                <th>Jumlah Jam</th>
                                <th>Status</th>
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

<!-- MODAL VIEW FILE -->
<div class="modal fade" id="modalView" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Detail File </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form >
                    <div class="col-lg-12" style="text-align: center;">
                        <img src="" alt="" id="image_file_lembur" style="width: 50%">
                    </div>
                    <div class="col-lg-12">
                        <iframe src="" width="100%" height="500px" id="pdf_file_lembur" frameborder="0"></iframe>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL VIEW FILE -->


@endsection

@push('js')

<script>
    var url_data    = "{{ route('lembur-spv-internal',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_view    = "{{ route('lembur-get-files') }}";
    var asset       = "{{ URL::to('') }}";
</script>
<script src="{{ asset('assets/js/supervisor/lembur.js') }}"></script>

@endpush
