<?php

use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[IdeaController::class,'index'])->name('idea.index');
Route::get('/ideas/{idea:slug}',[IdeaController::class,'show'])->name('idea.show');
Route::view('/idea','show');

Route::get('/post/{post}', function (Post $post) {
    return view('post.show', [
        'post' => $post,
    ]);
})->name('post.show');

Route::post('/post/{post}/comment', function (Request $request, Post $post) {
    return back()->with('success_message', 'Comment was posted!');
})->name('comment.store');

Route::get('/post/{post}/edit', function (Post $post) {
    return view('post.edit', [
        'post' => $post,
    ]);
})->name('post.edit');

Route::patch('/post/{post}', function (Request $request, Post $post) {
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'photo' => 'nullable|sometimes|image|max:5000',
    ]);

    $post->update([
        'title' => $request->title,
        'content' => $request->content,
        'photo' => $request->photo ? $request->file('photo')->store('photos', 'public') : $post->photo,
    ]);

    return back()->with('success_message', 'Post was updated successfully!');
})->name('post.update');

require __DIR__.'/auth.php';
