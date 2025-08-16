<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ContactUsMessage;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $sliders = Slider::get();
        $news = Post::get();

        $data = readPageContent('home');
        // dd($data);
        // $data = Page::where("slug","home")->first();

        $articles = Article::where("status", 1)->get();
        return view('app.welcome', compact('sliders', 'news', 'data', 'articles'));


    }

    public function pageContent($page)
    {
        $view = 'app.' . Str::slug($page);

        if (Str::slug($page) == "shop") {
            $view .= ".index";


            $page = Page::where("slug", Str::slug($page))->first();
            $data = [
                "products" => Product::where("status", 1)->get(),
                "page" => $page
            ];


            if (!$page->is_published) {
                abort(404);
            }

            return view($view, $data);

        } else {
            $data = Page::where("slug", Str::slug($page))->first();

            if ($data->is_published == 0) {
                abort(404);
            }


            return view('app.page', compact("data"));
        }

    }

    public function news()
    {
        $news = Post::get();

        return view('app.news', compact('news'));
    }

    public function newsInfo($slug)
    {
        $news = Post::where('slug', $slug)->first();

        return view('app.news-info', compact('news'));
    }

    public function gallery()
    {
        $gallery = Gallery::where('status', 1)->get();

        return view('app.gallery', compact('gallery'));
    }

    public function contact()
    {
        $data = readPageContent('contact-us');
        return view('app.contact', compact('data'));
    }

    public function sendMessage(Request $request)
    {
        $recaptcha_token = request()->token;

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptcha_token
        ];

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => "POST",
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $json_response = file_get_contents($url, false, $context);

        $result = json_decode($json_response, true);

        if ($result['success'] == false) {
            return redirect()->back()->with('danger', 'Recaptcha token is invalid, please reload this page and try again.');
        }

        ContactUsMessage::create($request->except(['_token']));

        return redirect()->back()->with('success', 'Your message was successfully sent!');
    }

    public function about()
    {
        $data = readPageContent('about-us');
        return view('app.about', compact('data'));
    }

    public function shop()
    {
        $data = [
            "products" => Product::where("status", 1)->get()
        ];

        return view('app.shop.index', $data);
    }

    public function productShow($slug)
    {

        $product = Product::where("product_slug", $slug)->where("status", 1)->first();
        return view('app.shop.show', [
            "product" => $product
        ]);
    }

    public function privacyPolicy()
    {
        $data = readPageContent('privacy-policy');
        return view('app.privacy-policy', compact('data'));
    }

    public function termsOfService()
    {
        $data = readPageContent('terms-of-service');
        return view('app.terms-of-service', compact('data'));
    }

    public function coloringBook()
    {
        $data = [
            "news" => []
        ];
        return view('app.coloring-book', $data);
    }

    public function unseenWins()
    {
        $sliders = Slider::get();
        $news = Post::get();

        return view('app.unseen-wins', compact('sliders', 'news'));
    }
}
