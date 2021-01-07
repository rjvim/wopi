<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class WOPIController extends Controller
{
    function lock($fileId)
    {
        return [
            "status" => "ok"
        ];
    }

    function checkFileInfo($fileId)
    {
        $filePath = public_path('sample.docx');

        $handle = fopen($filePath, "r");
        $size = filesize($filePath);
        $contents = fread($handle, $size);

        return [
            "BaseFileName" => "sample.docx",
            "Size" => $size,
            "OwnerId" => 1,
            "UserId" => 1,
            "Version" => rand(),
            // "FileUrl" => url("sample.docx")
        ];
    }

    function getFile($fileId)
    {
        $filePath = public_path('sample.docx');
        header("Content-Type: application/octet-stream");
        // $contents = file_get_contents($filePath);

        $handle = fopen($filePath, "rb");
        $contents = fread($handle, filesize($filePath));

        // echo $contents;
        return $contents;
    }
}
