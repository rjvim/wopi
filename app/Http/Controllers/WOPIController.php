<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class WOPIController extends Controller
{
    function checkFileInfo($fileId)
    {
        $filePath = storage_path('app/public/test.doc');

        $handle = fopen($filePath, "r");
        $size = filesize($filePath);
        $contents = fread($handle, $size);

        return [
            "BaseFileName" => "test.doc",
            "Size" => $size,
            "OwnerId" => 1,
            "UserId" => 1,
            "Version" => rand(),
            "FileUrl" => url("test.doc")
        ];
    }

    function getFile($fileId)
    {
        $filePath = storage_path('app/public/test.doc');

        $handle = fopen($filePath, "r");
        $contents = fread($handle, filesize($filePath));
        header("Content-type: application/octet-stream");
        return $contents;
    }
}
