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
        $filePath = public_path('test.doc');

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
        $filePath = public_path('test.doc');
        header("Content-Type: application/octet-stream");
        // $contents = file_get_contents($filePath);

        $handle = fopen($filePath, "r");
        $contents = fread($handle, filesize($filePath));

        return response()
            ->download($filePath, "test.doc",[
                'Content-Type' => 'application/octet-stream'
            ]);

        // echo $contents;
        return $contents;
    }
}
