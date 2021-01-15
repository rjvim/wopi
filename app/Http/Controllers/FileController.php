<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    function host()
    {
        $favIconUrl = "https://c5-word-view-15.cdn.office.net/wv/resources/1033/FavIcon_Word.ico";

        $wopiSrc = urlencode(url('/wopi/files/'.rand()));

        $accessTokenValue = rand();

        $officeActionUrl = "https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?WOPISrc=".$wopiSrc;

        $accessTokenTtl = 0;

        return view('host',compact('favIconUrl','officeActionUrl','accessTokenValue','accessTokenTtl'));
    }

    function test()
    {
        $favIconUrl = "https://c5-word-view-15.cdn.office.net/wv/resources/1033/FavIcon_Word.ico";

        $wopiSrc = urlencode(url('/wopi/files/sample.docx'));
        $accessTokenValue = rand();

        $officeActionUrl = "https://FFC-onenote.officeapps.live.com/hosting/WopiTestFrame.aspx?WOPISrc=".$wopiSrc;
        $accessTokenTtl = 0;

        return view('test',compact('favIconUrl','officeActionUrl','accessTokenValue','accessTokenTtl'));
    }

}