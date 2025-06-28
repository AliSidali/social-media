<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\GlobalSearchController;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified', LocaleMiddleware::class])->name('home');
Route::get('/u/{user:username}', [ProfileController::class, 'index'])->middleware(LocaleMiddleware::class)->name('profile.index');
Route::post('/lang/{lang}', [LanguageController::class, 'index'])->name('language.index');

Route::middleware(['auth', LocaleMiddleware::class])->group(function () {
    Route::post('/user/follow/{user}', [UserController::class, 'followUser'])->name('user.follow');


    Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])->name('profile.updateImages');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/search/{keyword}', [GlobalSearchController::class, 'globalSearch'])->name('globalSearch');

    //Post routes
    Route::get('/post/{post}', [PostController::class, 'view'])->name('post.view');
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
    Route::post('/post/ai-post', [PostController::class, 'aiPostContent'])->name('post.aiContent');
    Route::post('/post/urlPreview', [PostController::class, 'fetchUrlPreview'])->name('post.urlPreview');
    Route::post('/post/{post}/pin', [PostController::class, 'pinUnpin'])->name('post.pinUnpin');

    //Group Routes
    Route::get('/g/{group:slug}', [GroupController::class, 'profile'])->middleware(LocaleMiddleware::class)->name('group.profile');
    Route::post('/group/{group:slug}/update-images', [GroupController::class, 'updateImage'])->name('group.updateImages');
    Route::post('/group/{group:slug}/invite-user', [GroupController::class, 'inviteUser'])->name('group.inviteUser');
    Route::get('/group/{group:slug}/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])->name('group.approveInvitation');
    Route::post('/group/{group}/join', [GroupController::class, 'join'])->name('group.join');
    Route::post('/group/{group}/approve-request', [GroupController::class, 'approveRequest'])->name('group.approveRequest');
    Route::patch('/group/{group}/update-role', [GroupController::class, 'updateRole'])->name('group.updateRole');
    Route::put('/group/{group:slug}/update', [GroupController::class, 'update'])->name('group.update');
    //delete group's user
    Route::delete('/group-user/{group:slug}', [GroupController::class, 'destroyUserGroup'])->name('groupUser.destroy');
    //notification route
    Route::get('/notification/read', [GroupController::class, 'readNotifications'])->name('notification.read');
});

require __DIR__ . '/auth.php';
