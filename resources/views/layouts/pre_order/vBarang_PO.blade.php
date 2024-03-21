@php
    $no = 0;
@endphp
@foreach ($data_barang_po as $item)
<div class="row" id="r_{{ $no++ }}">
    <input type="hidden" name="id_barang[]"  value="{{ $item->id }}">
    <div class="col-lg-2">
        <input class="form-control"   name="nama_barang[]" id="nama_barang_0" type="text" value="{{ $item->nama_barang }}" placeholder="Nama Barang">
    </div>
    <div class="col-lg-2">
        <input class="form-control"   name="unit[]" id="unit_0" type="text" value="{{ $item->unit }}" placeholder="Unit">
    </div>
    <div class="col-lg-2">
        <input class="form-control"   name="satuan[]" id="satuan_0" type="text" value="{{ $item->satuan }}" placeholder="Satuan">
    </div>
    <div class="col-lg-2">
        <input class="form-control"   name="biaya[]" id="biaya_0" type="text" value="{{ $item->biaya }}" placeholder="Biaya/Unit">
    </div>
    <div class="col-lg-2">
        <input class="form-control"   name="jumlah[]" id="jumlah_0" type="text" value="{{ $item->jumlah }}" placeholder="Jumlah Unit">
    </div>
    <div class="col-lg-2">
        <input class="form-control"   name="keterangan[]" id="keterangan_0" type="text" value="{{ $item->keterangan }}" placeholder="Keterangan">
    </div>
</div>
<br>
@endforeach
