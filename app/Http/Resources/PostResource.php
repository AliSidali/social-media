<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\PostCommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{    /**

* Transform the resource into an array.
*
* @return array<string, mixed>
*/
    public function toArray(Request $request): array
    {

        $user = auth()->user();
        $comments = $this->post_comments;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->user),
            'attachments' => AttachmentResource::collection($this->attachments),
            'reactions' => $this->reactions,
            'has_current_user_reaction' => $this->reactions()->where('user_id', $user->id)->count() > 0,
            'post_comments_num' => $comments->count(),
            'comments' => self::convertIntoCommentTree($comments)[0],
            'group' => $this->group
        ];
    }

    private static function convertIntoCommentTree($comments, $parent_id = null)
    {
        $commentTree = [];
        $numOfreplies = 0;
        foreach ($comments as $comment) {
            if ($comment->parent_id === $parent_id) {
                $numOfreplies = $numOfreplies + $comment->numOfComments;
                $commentTree[] = new PostCommentResource($comment);
                $comment->childComments = self::convertIntoCommentTree($comments, $comment->id)[0];
                $comment->numOfComments = self::convertIntoCommentTree($comments, $comment->id)[1] + count($comment->childComments);
            }
        }
        return ([$commentTree, $numOfreplies]);
    }
}
