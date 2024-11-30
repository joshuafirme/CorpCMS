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

        return view('admin.settings.main', compact('data'));
    }


    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        if (Storage::exists($this->path)) {
            $data = Storage::get($this->path);
            $data = json_decode($data, true);
        }


        if ($request->logo) {
            $data['logo'] = Utils::uploadFile($request->logo, 'uploads/settings/');
        }

        $data['app_name'] = $request->app_name ? $request->app_name : '';

        $data['primary_color'] = $request->primary_color ? $request->primary_color : '';

        $data['secondary_color'] = $request->secondary_color ? $request->secondary_color : '';

        $data['meta_description'] = $request->meta_description ? $request->meta_description : '';
        
        $data['facebook'] = $request->facebook ? $request->facebook : '';

        $data['instagram'] = $request->instagram ? $request->instagram : '';

        $data['linkedin'] = $request->linkedin ? $request->linkedin : '';

        $data['twitter'] = $request->twitter ? $request->twitter : '';

        $data['tiktok'] = $request->tiktok ? $request->tiktok : '';
        return $data['logo'];
        Storage::put($this->path, json_encode($data));

        return redirect()->back()->with('success', 'Setting was successfully saved.');
    }
}
