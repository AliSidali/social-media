<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::get('/u/{user:username}', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/g/{group:slug}', [GroupController::class, 'profile'])->name('group.profile');



Route::middleware('auth')->group(function () {
    Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])->name('profile.updateImages');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Post routes
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update'); //UPDATE POST WITH ADDING OR DELETING ITS ATTACHMENTS
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])->name('attachment.download');
    Route::post('/post/{post}/reaction', [PostController::class, 'savePostReaction'])->name('post.reaction');
    Route::post('/post/{post}/comment', [PostController::class, 'createComment'])->name('post.comment');
    Route::post('/comment/{comment}', [PostController::class, 'updateComment'])->name('comment.update');
    Route::delete('/comment/{comment}', [PostController::class, 'destroyComment'])->name('comment.destroy');
    Route::post('/comment/{comment}/reaction', [PostController::class, 'saveCommentReaction'])->name('comment.reaction');
    Route::post('/group', [GroupController::class, 'store'])->name('group.store');

    //Group Routes
    Route::post('/group/{group:slug}/update-images', [GroupController::class, 'updateImage'])->name('group.updateImages');
    Route::post('/group/{group:slug}/invite-user', [GroupController::class, 'inviteUser'])->name('group.inviteUser');
    Route::get('/group/{group:slug}/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])->name('group.approveInvitation');

});

require __DIR__ . '/auth.php';
