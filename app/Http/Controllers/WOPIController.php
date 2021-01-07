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
            "FileUrl" => url("sample.docx")
        ];
    }

    function getFile($fileId)
    {
        $filePath = public_path('sample.docx');

        $file = public_path('sample.docx');

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }

    }
}
