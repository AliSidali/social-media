<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(User $user)
    {
        $user = new UserResource($user);
        return Inertia::render(
            'Profile/Index',
            [
                "user" => $user,
                'status' => session('status'),
                'success' => session('success'),
            ]
        );
    }
    // public function edit(Request $request): Response
    // {
    //     return Inertia::render('Profile/Edit', [
    //         'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
    //         'status' => session('status'),
    //     ]);
    // }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.index', $request->user())->with('success', 'Your profile details were updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateImages(Request $request)
    {

        $user = auth()->user();
        $images = $request->validate([
            'cover' => ['nullable', 'image'],
            'avatar' => ['nullable', 'image']
        ]);

        $cover = $images['cover'] ?? null;
        $avatar = $images['avatar'] ?? null;

        $success = '';

        if ($cover) {
            if (auth()->user()->cover_path) {
                Storage::disk('public')->delete(auth()->user()->cover_path);
            }
            $cover_path = $cover->store('user-' . $user->id, 'public');  //store the image in storage folder
            $user->update(['cover_path' => $cover_path]);
            $success = 'Your cover image has been updated.';
        }
        if ($avatar) {
            if (auth()->user()->avatar_path) {
                Storage::disk('public')->delete(auth()->user()->avatar_path);
            }
            $avatar_path = $avatar->store('user-' . $user->id, 'public');
            $user->update(['avatar_path' => $avatar_path]);
            $success = 'Your avatar image has been updated.';
        }

        return back()->with('success', $success);

    }


}
