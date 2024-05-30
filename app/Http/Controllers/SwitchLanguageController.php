<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Session;

class SwitchLanguageController extends Controller
{
    use ApiTrait;

    public function switchLang($lang)
    {
        Session::put('locale', $lang);
        return $this->responseToJson('Language was switched', ApiCodes::SUCCESS);
    }

    public function getLanguage()
    {
        $dir    = resource_path('lang'); //this for server
        $files2 = array_diff(scandir($dir), array('..', '.','.DS_Store'));

        return $files2;
    }
}
