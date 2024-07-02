<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $data = $request->validated();

            $post = Post::create($data);

            $attachments = $data['attachments'] ?? [];
            foreach ($attachments as $attachment) {
                $path = $attachment->store('attachments/post-' . $post->id, 'public');
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'created_by' => $user->id
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {

            Storage::deleteDirectory('public/attachments/post-' . $post->id);
            DB::rollBack();
        }


        return back();



    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $user = auth()->user();

        DB::beginTransaction();

        try {

            $attachments_path = [];
            $post->update($data);
            $deleted_attachment_ids = $data['deleted_file_ids'] ?? [];
            $deleted_attachments = PostAttachment::query()
                ->whereIn('id', $deleted_attachment_ids)
                ->where('post_id', $post->id)
                ->get();
            foreach ($deleted_attachments as $attachment) {
                $attachment->delete();

            }
            foreach ($data['attachments'] as $attachment) {
                $attachments_path[] = $path = $attachment->store('attachments/post-' . $post->id, 'public');
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'created_by' => $user->id
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($attachments_path as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        return back();
    }

    public function destroy(Post $post)
    {

        $post = Post::where('id', $post->id)->where('user_id', auth()->user()->id)->first();
        if (!$post) {
            return response("You don't have a permission to delete this post", 403);
        }

        $post->delete();
        return back();
    }

    public function downloadAttachment(PostAttachment $attachment)
    {

        return Storage::disk('public')->download($attachment->path);
    }
}
