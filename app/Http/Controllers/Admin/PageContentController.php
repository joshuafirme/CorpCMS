<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class PageContentController extends Controller
{
    public function page($type)
    {
        $path = "page-content/$type.json";

        $data = null;

        if (Storage::exists($path)) {
            $data = Storage::get($path);
            $data = json_decode($data);
        }

        $page_title = Utils::decodeSlug($type, '-');

        return view('admin.page-content.main', compact('page_title', 'type', 'data'));
    }

    public function updateContent(Request $request, $type)
    {
        $data = $request->except(['_token']);

        $path = "page-content/$type.json";

        if (Storage::exists($path)) {
            $data = Storage::get($path);
            $data = json_decode($data, true);
        }

        if ($request->content) {
            $data['content'] = $request->content;
        }
        if ($type == 'about-us') {
            if ($request->cover_img) {
                $data['cover_img'] = Utils::uploadFile($request->cover_img, 'uploads/page-contents/');
            }
            if ($request->show_cover_img) {
                $data['show_cover_img'] = $request->show_cover_img;
            } else {
                $data['show_cover_img'] = null;
            }
        } else {
            if ($request->show_contact_form) {
                $data['show_contact_form'] = $request->show_contact_form;
            } else {
                $data['show_contact_form'] = null;
            }
            if ($request->show_content) {
                $data['show_content'] = $request->show_content;
            } else {
                $data['show_content'] = null;
            }
        }

        Storage::put($path, json_encode($data));

        return redirect()->back()->with('success', 'Content was successfully saved.');
    }
}
