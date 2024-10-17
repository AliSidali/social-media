<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\Attachment;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Enums\ReactionEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use Illuminate\Validation\Rules\File;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\PostCommentResource;

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
                Attachment::create([
                    'attachable_id' => $post->id,
                    'attachable_type' => Post::class,
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
        $user = auth()->user();

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $attachments_path = [];
            $post->update($data);
            $deleted_attachment_ids = $data['deleted_file_ids'] ?? [];
            $deleted_attachments = $post->attachments()
                ->whereIn('id', $deleted_attachment_ids)
                ->where('attachable_id', $post->id)
                ->get();
            foreach ($deleted_attachments as $attachment) {
                $attachment->delete();

            }
            foreach ($data['attachments'] as $attachment) {
                $attachments_path[] = $path = $attachment->store('attachments/post-' . $post->id, 'public');
                Attachment::create([
                    'attachable_id' => $post->id,
                    'attachable_type' => Post::class,
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

        $post = $post->where('user_id', auth()->user()->id)->first();
        if (!$post) {
            return response("You don't have a permission to delete this post", 403);
        }

        $post->delete();
        return back();
    }

    public function downloadAttachment(Attachment $attachment)
    {

        return Storage::disk('public')->download($attachment->path);
    }

    public function savePostReaction(Request $request, Post $post)
    {
        $user = auth()->user();
        $data = $request->validate([
            'reaction' => ['string', Rule::enum(ReactionEnum::class)]
        ]);


        $reaction = $post->reactions()->where('user_id', $user->id)->first();
        if ($reaction) {
            if ($reaction->type == $data['reaction']) {
                $reaction->delete();
                $has_reaction = false;

            } else {
                $reaction->update(['type' => $data['reaction']]);
                $has_reaction = true;
            }
        } else {
            Reaction::create([
                'user_id' => $user->id,
                'reactionable_id' => $post->id,
                'reactionable_type' => Post::class,
                'type' => $data['reaction']
            ]);
            $has_reaction = true;

        }


        return response([
            'reactions' => $post->reactions,
            'has_reaction' => $has_reaction
        ]);
    }

    //comment methods
    public function createComment(Request $request, Post $post)
    {
        DB::beginTransaction();
        $user = auth()->user();


        try {

            $data = $request->validate([
                "text" => ['required', 'string'],
                "parent_id" => ['integer', 'exists:post_comments,id'],
                "attachment" => [
                    'file',
                    "max:1048576",
                    File::types(['jpg', 'jpeg', 'png', 'gif', 'webp']),

                ]
            ]);


            $comment = PostComment::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'text' => $data['text'], // USE nl2br function here for the new line if you don't use ckeditor ex nl2br($data['text'])
                'parent_id' => $data['parent_id'] ?? null
            ]);


            $attachment = $request->attachment;
            if ($attachment) {
                $path = $attachment->store('attachments/comment-' . $comment->id, 'public');
                Attachment::create([
                    'attachable_id' => $comment->id,
                    'attachable_type' => PostComment::class,
                    'name' => $attachment->getClientOriginalName(),
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'path' => $path,
                    'created_by' => $user->id
                ]);
            }

            DB::commit();
            return response([
                'comment' => new PostCommentResource($comment),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($path) {
                Storage::disk('public')->delete($path);
            }
            return response([], 500);
        }
    }

    public function destroyComment(Request $request, PostComment $comment)
    {
        $user = auth()->user();
        $post_id = $comment->post_id;

        if ($user->id !== $comment->user_id) {
            return response("You don't have a permission to delete this comment", 403);
        }


        $this->deleteAttachment($comment);

        $comment->delete();

        $post = Post::find($post_id);

        return response([
            'post' => new PostResource($post)
        ], 200);
    }



    public function updateComment(UpdateCommentRequest $request, PostComment $comment)
    {
        $user = auth()->user();
        DB::beginTransaction();
        try {

            $data = $request->validated();

            $comment->update($data);

            //dd($data['attachment']);
            $deletedAttachmentId = $request->deletedAttachmentId;
            if ($deletedAttachmentId) {
                $attachment = Attachment::where('id', $deletedAttachmentId)->first();
                $attachment->delete();
            }

            $attachment = $request->attachment;
            if ($attachment) {
                $path = $attachment->store('attachments/comment-' . $comment->id, 'public');
                Attachment::create([
                    'attachable_id' => $comment->id,
                    'attachable_type' => PostComment::class,
                    'name' => $attachment->getClientOriginalName(),
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'path' => $path,
                    'created_by' => $user->id
                ]);
            }


            DB::commit();
            $comment = new PostCommentResource(PostComment::find($comment->id));
            return response(['comment' => $comment], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            response([], 422);
        }
    }

    public function saveCommentReaction(Request $request, PostComment $comment)
    {
        $user = auth()->user();
        $data = $request->validate([
            'reaction' => ['required', 'string', Rule::enum(ReactionEnum::class)]
        ]);

        $reaction = $comment->reactions()->where('user_id', $user->id)->first();
        if ($reaction) {
            $reaction->delete();
            $hasUserReaction = false;
        } else {
            Reaction::create([
                'user_id' => $user->id,
                'type' => $data['reaction'],
                'reactionable_id' => $comment->id,
                'reactionable_type' => PostComment::class
            ]);
            $hasUserReaction = true;
        }

        return response([
            'hasUserReaction' => $hasUserReaction,
            'commentReactionNum' => $comment->reactions()->count()
        ], 200);
    }



    //no route methods
    private function deleteAttachment($comment)
    {
        foreach ($comment->attachments as $attachment) {
            $attachmentFolder = dirname($attachment->path);
            Storage::disk('public')->deleteDirectory($attachmentFolder);
        }
        foreach ($comment->replies as $reply) {
            $this->deleteAttachment($reply);
        }
    }
}
