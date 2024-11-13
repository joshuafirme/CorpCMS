<?php

namespace App\Http\Controllers;

use App\Models\ContactUsMessage;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Slider;

class PageController extends Controller
{
    public function index()
    {
        $sliders = Slider::get();

        $news = Post::get();

        return view('app.welcome', compact('sliders', 'news'));
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
}
