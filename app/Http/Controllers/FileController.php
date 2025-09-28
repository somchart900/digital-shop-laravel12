<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
       public function download($filename)
    {
        $path = storage_path('app/private/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

       
       // return response()->file($path);  // เปิดใน browser ถ้าเป็น pdf/jpg,
        return response()->download($path); // ให้ดาวน์โหลด
    }
}
