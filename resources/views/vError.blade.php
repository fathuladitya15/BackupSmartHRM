@extends('vBase')
@section('title'){{ 'Halaman Belum tersedia' }}@endsection
@push('css')
    <style>
        .black {
            color: black;

        }
        p {
            color: white;
        }
        h2 {
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
    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
          <h2 class="mb-2 mx-2">Page Not Found :(</h2>
          <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
          <div class="mt-3">
            <img
              src="../assets/img/illustrations/page-misc-error-light.png"
              alt="page-misc-error-light"
              width="500"
              class="img-fluid"
              data-app-dark-img="illustrations/page-misc-error-dark.png"
              data-app-light-img="illustrations/page-misc-error-light.png"
            />
          </div>
        </div>
      </div>
      <!-- /Error -->
@endsection
