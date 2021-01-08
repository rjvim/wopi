<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


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
            "FileUrl" => url("sample.docx")
        ];
    }

    function getFile($fileId)
    {
        $file = public_path('sample.docx');

        return new BinaryFileResponse($file);
    }
}
