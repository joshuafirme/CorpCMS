<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $data = Gallery::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.gallery.list', compact('data'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
       // $data['user_id'] = $request->user()->id;

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/gallery/');
        }

        Gallery::create($data);

        return redirect()->back()->with('success', 'Image was added.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/gallery/');
        }

        Gallery::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Image was updated.');
    }


    public function destroy($id)
    {

        $query = Gallery::where('id', $id);
        if ($query->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Data was deleted.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Deletion failed'
        ]);
    }
}
