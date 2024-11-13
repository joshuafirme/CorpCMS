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

        if ($request->app_name) {
            $data['app_name'] = $request->app_name;
        }
        if ($request->primary_color) {
            $data['primary_color'] = $request->primary_color;
        }
        if ($request->secondary_color) {
            $data['secondary_color'] = $request->secondary_color;
        }
        if ($request->logo) {
            $data['logo'] = Utils::uploadFile($request->logo, 'uploads/settings/');
        }

        Storage::put($this->path, json_encode($data));

        return redirect()->back()->with('success', 'Setting was successfully saved.');
    }
}
