<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Str;

class PostController extends Controller
{
    public function index()
    {
        $data = Post::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.news.list', compact('data'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = $request->user()->id;

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/');
        }

        Post::create($data);

        return redirect()->back()->with('success', 'Category was added.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = $request->user()->id;

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/');
        }

        Post::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Category was updated.');
    }


    public function destroy($id)
    {

        $query = Post::where('id', $id);
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
