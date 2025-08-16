<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = ["articles" => Article::all()];
        return view('admin.articles.main', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create', $data = []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slug' => 'required|string|unique:articles,slug',
            'content' => 'nullable|string',
            'page_status' => 'nullable|in:on',
        ]);

        $coverImagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'cover_' . time() . '.' . $file->getClientOriginalExtension();
            $coverImagePath = $file->storeAs('uploads/articles', $filename, 'public');
        }

        Article::create([
            "title" => $request->title,
            "content" => $request->content,
            "slug" => $request->slug,
            "image" => $coverImagePath,
        ]);

        return redirect()->route('articles.index')->with('success', 'article added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
        return view("admin.articles.show", ["article" => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $article = Article::find($id);
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->content = $request->content;

        if ($request->hasFile('update_image')) {
            $file = $request->file('update_image');
            $filename = 'cover_' . time() . '.' . $file->getClientOriginalExtension();
            $coverImagePath = $file->storeAs('uploads/articles', $filename, 'public');
            $article->image = $coverImagePath;
        }

        $article->status =  $request->has('status') ? true: false;
        $article->save();

        return redirect()->route('articles.index')->with('success', 'article update.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id)->delete();
        return redirect()->route('articles.index')->with('success', 'article delete.');

    }
}
