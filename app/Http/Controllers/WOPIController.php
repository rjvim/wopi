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
        $fileName = "sample.docx";

        $filePath = public_path('sample.docx');


        $FileInfoDto = array(
            'BaseFileName' => $fileName,
            'OwnerId' => 'admin',
            'ReadOnly' => true,
            'Size' => filesize($filePath),
            'Version' => rand()
        );

        $jsonString = json_encode($FileInfoDto);
        header('Content-Type: application/json');
        echo $jsonString;


        // $handle = fopen($filePath, "r");
        // $size = filesize($filePath);
        // $contents = fread($handle, $size);

        // return [
        //     "BaseFileName" => "sample.docx",
        //     "Size" => $size,
        //     "OwnerId" => 'admin',
        //     "ReadOnly" => true,
        //     "UserId" => 1,
        //     "Version" => rand(),
        //     "FileUrl" => url("sample.docx")
        // ];
    }

    function getFile($fileId)
    {
        $fileName = "sample.docx";

        $filePath = public_path('sample.docx');

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filePath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;

        $file = public_path('sample.docx');

        return new BinaryFileResponse($file);
    }
}
