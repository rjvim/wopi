<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    function host()
    {
        $favIconUrl = "https://c5-word-view-15.cdn.office.net/wv/resources/1033/FavIcon_Word.ico";

        $wopiSrc = urlencode(url('/wopi/files/123'));

        $officeActionUrl = "https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?wopisrc=".$wopiSrc;
        $accessTokenValue = "abcdabcdabcd";
        $accessTokenTtl = 0;

        return view('host',compact('favIconUrl','officeActionUrl','accessTokenValue','accessTokenTtl'));
    }

}
