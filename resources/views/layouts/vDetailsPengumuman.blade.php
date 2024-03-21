@extends('vBase')
@section('title') {{  'Pengumuman '  .$detail->judul }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span>Pengumuman</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Detail Pengumuman</h5>
            </div>
            <div class="card-body">
                <h3>{{ Str::title($detail->judul) }}</h3>
                <hr>
                <p>
                    {!! $detail->keterangan  !!}
                </p>
                <hr>
                <h5 style="color: black">Lampiran</h5>
                <hr>
                <br>
                <table class="table table-bordered" border="1" >
                    <thead>
                        <tr>
                            <th>Filename</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $item)
                            <tr>
                                <td>{{ $item->filename }}</td>
                                <td>
                                    {{-- <a href="{{ route("pengumuman-download",['filename'=>  hashVariable($item->filename),'ext' => $item->extension ]) }}" class="btn btn-sm btn-primary "> <i class='bx bx-cloud-download'></i> Download File </a> --}}
                                    <a href="#" class="btn btn-sm btn-primary "> <i class='bx bx-cloud-download'></i> Download File </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>

</script>

@endpush
