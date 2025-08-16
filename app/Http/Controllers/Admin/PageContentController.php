<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Helper\Utils;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PageContentController extends Controller
{
    public function index()
    {
        $data = '';
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
        $data = [
            'data' => Page::orderBy("id", "desc")->get(),
        ];

        return view('admin.pages.main', $data);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'required|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['order'] = Page::orderBy("order", "desc")->first()->order ?? 1;

        $page = new Page;
        $page->is_published = $request->is_published;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->slug = $request->slug;
        $page->show_cover_img = 1;
        $page->page_status = 1;
        if ($request->cover_img) {
            $page->cover_img = Utils::uploadFile($request->cover_img, 'uploads/page-contents/');
        }
        $page->show_contact_form = 0;
        $page->show_content = 1;
        $page->order = $page->order ? $page->order : Page::count('id') + 1;
        $page->save();

        return redirect()->back()->with('success', 'Page was successfully created.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $filename = Str::slug($page->slug) . '.json';
        $path = "page-content/{$filename}";

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully.']);
    }


    public function page($type)
    {
        $path = "page-content/$type.json";
        // $data = null;

        // if (Storage::exists($path)) {
        //     $data = Storage::get($path);
        //     $data = json_decode($data);
        // }

        $page_title = Utils::decodeSlug($type, '-');
        $page = Page::where("slug", $type)->first();


        // if(!$page->is_published){
        //     abort(404);
        // }

        return view('admin.page-content.main', compact('page_title', 'type', 'page'));
    }

    public function updateContent(Request $request, $type)
    {
        $data = $request->except(['_token']);

        $path = "page-content/$type.json";


        // if (Storage::exists($path)) {
        //     $data = Storage::get($path);
        //     $data = json_decode($data, true);
        // }

        $data = Page::where("slug", $request->slug)->first();

        $page_status = isset($request->page_status) == "on" ? true : false;
        $page = Page::where("slug", $request->slug)->first();
        $page->is_published = $page_status;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->order = $page->order ? $page->order : Page::count('id') + 1;
        if ($request->cover_img) {
            $page->cover_img = Utils::uploadFile($request->cover_img, 'uploads/page-contents/');
        }

        if ($request->content) {
            $data['content'] = $request->content;
        }

        $data['page_status'] = $page_status;
        if ($request->show_cover_img) {
            $data['show_cover_img'] = $request->show_cover_img;
        } else {
            $data['show_cover_img'] = null;
        }

        if ($request->cover_img) {
            $data['cover_img'] = Utils::uploadFile($request->cover_img, 'uploads/page-contents/');
        }

        if ($type == 'home') {
            if ($request->cover_img) {
                $data['cover_img'] = Utils::uploadFile($request->cover_img, 'uploads/page-contents/');
            }
            if ($request->show_cover_img) {
                $data['show_cover_img'] = $request->show_cover_img;
            } else {
                $data['show_cover_img'] = null;
            }
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

        $page->save();

        // Storage::put($path, json_encode($data));

        return redirect()->back()->with('success', 'Content was successfully saved.');
    }
}
