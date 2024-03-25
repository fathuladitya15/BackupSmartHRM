<?php

namespace App\Imports;

use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {


        $cek = User::where('id_karyawan',$row[0])->count();
        if($cek > 1) {
            return response()->json(['status' => TRUE,]);
        }
        return new User([
            'name' => $row[1],
            'username' => str_replace('-', '_', $row[0]),
            'email' => 'default'.str_replace('-', '_', $row[0]).rand(10,999). $row[9].'@gmail.com',
            'password' => Hash::make('password'),
            'id_karyawan' => $row[0],
            'roles' => 'karyawan',
            'id_client' => Auth::user()->id_client,
        ]);
    }
}
