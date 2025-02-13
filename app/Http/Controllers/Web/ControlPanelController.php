<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;

class ControlPanelController extends Controller
{
    public $pageSize = 2;
    public $pageSizeFull = 9;

    public function index()
    {
        // Index logic here
    }

    public function indexErrors()
    {
        $contents = File::get(storage_path("logs/laravel.log"));
        return View::make("ControlPanel.errors")->with("contents", $contents);
    }

    public function indexErrorsReset()
    {
        $filePath = storage_path("logs/laravel.log");
        $file = @fopen($filePath, "r+");

        if ($file !== false) {
            ftruncate($file, 0);
            fclose($file);
        }

        return Redirect::route("ControlPanelErrors");
    }
}
