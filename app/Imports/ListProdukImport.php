<?php

namespace App\Imports;

use Auth;
use App\Models\ListProduk;
use Maatwebsite\Excel\Concerns\ToModel;

class ListProdukImport implements ToModel
{
    private $tipe_produk;

    public function __construct($tipe_produk) {
        $this->tipe_produk = $tipe_produk;
    }
    public function model(array $row)
    {
        $tipe_produk = $this->tipe_produk;
        return new ListProduk([
            'no_produk' => $row[0],
            'nama_produk' => $row[1],
            'tipe_produk' => $tipe_produk,
            'satuan_produk' => 'pcs',
            'harga_produk' => '0',
            'id_client' => Auth::user()->id_client,
        ]);
    }
}
