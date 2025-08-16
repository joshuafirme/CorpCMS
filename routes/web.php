<?php

use App\Helper\Utils;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\PageContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('admin')->group(function () {
        // Route::get('dashboard', [DashboardController::class, 'dashboard']);

        Route::get('users/logout', [UserController::class, 'logout'])->name('users.logout');

        Route::resource('users', UserController::class);
        Route::post('users/update/{id}', [UserController::class, 'update']);
        Route::resource('user-roles', UserRoleController::class);
        Route::post('user-roles/update/{id}', [UserRoleController::class, 'update']);

        Route::resource('news', PostController::class);
        Route::post('news/update/{id}', [PostController::class, 'update']);

        Route::resource('sliders', SliderController::class);
        Route::post('sliders/update/{id}', [SliderController::class, 'update']);

        Route::resource('gallery', GalleryController::class);
        Route::post('gallery/update/{id}', [GalleryController::class, 'update']);

        Route::resource('settings', SettingsController::class);
        Route::post('settings/update/{id}', [SettingsController::class, 'update']);


        Route::get('page-content', [PageContentController::class, 'index']);
        Route::get('page-content/{page}', [PageContentController::class, 'page']);
        Route::post('page-content/{page}', [PageContentController::class, 'updateContent']);
        Route::post('pages/store', [PageContentController::class, 'store'])->name('admin.pages.store');
        Route::delete('pages/{page}', [PageContentController::class, 'destroy'])->name('admin.pages.destroy');


        Route::resource('messages', MessageController::class);

        Route::resource('orders', OrderController::class);
        Route::resource('products', ProductController::class);
        Route::resource('articles', ArticleController::class);

        Route::post('orders/approve/{id}', function ($id) {
            $order = \App\Models\Order::findOrFail($id);
            $order->status = 1;
            $order->save();

            return response()->json(['message' => 'Order approved']);
        });
    });
});

Route::post('orders/store-order', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'book' => 'required|string',
        'address' => 'nullable|string',
        'note' => 'nullable|string',
        'quantity' => 'required|integer|min:1',
        'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

        $filePath = $file->storeAs('public/payment_proofs', $filename);
        $validated['payment_proof_path'] = 'storage/payment_proofs/' . $filename;
    }

    $order = \App\Models\Order::create($validated);

    return response()->json(['success' => true, 'order' => $order]);
});



Route::get('/', [PageController::class, 'index']);
// Route::get('unseen-wins', [PageController::class, 'unseenWins']);
// Route::get('coloring-book', [PageController::class, 'coloringBook']);
// Route::get('contact-us', [PageController::class, 'contact']);
// Route::post('contact-us', [PageController::class, 'sendMessage']);
// Route::get('about-us', [PageController::class, 'about']);
// Route::get('shop', [PageController::class, 'shop']);


Route::get('shop/{slug}', [PageController::class, 'productShow'])->name('product.show');
Route::get('article/{slug}', function($slug){
    $article = Article::where("slug", $slug)->first();
    return view("app.article.show", ["article" => $article]);
});


Route::get('privacy-policy', [PageController::class, 'privacyPolicy']);
Route::get('terms-of-service', [PageController::class, 'termsOfService']);

Route::get('login', [UserController::class, 'login'])->name('login');

Route::post('login', [UserController::class, 'doLogin'])->name('user.doLogin');
Route::get('/{page}', [PageController::class, 'pageContent']);


Route::get("/test", function () {

    $pages = Utils::getNavPages();
    return $pages;

});
