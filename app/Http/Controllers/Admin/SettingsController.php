<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class SettingsController extends Controller
{
    public $path = "settings.json";
    public function index()
    {

        $data = null;

        if (Storage::exists($this->path)) {
            $data = Storage::get($this->path);
            $data = json_decode($data);
        }

        $info['app_name'] = isset($data->app_name) ? $data->app_name : '[app_name]';
        $info['app_version'] = isset($data->app_version) ? $data->app_version : '1.0';
        $info['logo'] = isset($data->logo) ? $data->logo : 'assets/img/favicon.png';
        $info['primary_color'] = isset($data->primary_color) ? $data->primary_color : '';
        $info['secondary_color'] = isset($data->secondary_color) ? $data->secondary_color : '';
        $info['meta_description'] = isset($data->meta_description) ? $data->meta_description : '';
        $info['facebook'] = isset($data->facebook) ? $data->facebook : '';
        $info['instagram'] = isset($data->instagram) ? $data->instagram : '';
        $info['linkedin'] = isset($data->linkedin) ? $data->linkedin : '';
        $info['twitter'] = isset($data->twitter) ? $data->twitter : '';
        $info['tiktok'] = isset($data->tiktok) ? $data->tiktok : '';

        $data = Utils::arrayToObject($info);

        return view('admin.settings.main', compact('data'));
    }


    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        if (Storage::exists($this->path)) {
            $data = Storage::get($this->path);
            $data = json_decode($data, true);
        }

        if ($request->app_version ) {
            $data['app_version'] = $request->app_version;
        }

        if ($request->logo) {
            $data['logo'] = Utils::uploadFile($request->logo, 'uploads/settings/');
        }

        $this->generateFavicon($data['logo']);

        Storage::put($this->path, json_encode($data));

        return redirect()->back()->with('success', 'Setting was successfully saved.');
    }

    function generateFavicon($source)
    {
        $destination = public_path('favicon.ico');

        $sizes = [
            [16, 16],
            [24, 24],
            [32, 32],
            [48, 48],
        ];

        $ico_lib = new \PHP_ICO($source, $sizes);
        $ico_lib->save_ico($destination);
    }
}
