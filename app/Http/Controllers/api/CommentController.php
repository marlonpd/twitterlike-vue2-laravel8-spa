<?php

namespace App\Http\Controllers\api;

use App\Repositories\CommentRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use JWTAuth;


class CommentController extends Controller
{
    // Route::get('tweet/{id}/comments', [CommentController::class, 'index'])->name('tweet.comments');
    // Route::post('comments/store', [CommentController::class, 'store'])->name('tweet.comments');
    // Route::put('comments/update', [CommentController::class, 'update'])->name('update.comment');
    // Route::delete('comments/delete', [CommentController::class, 'delete'])->name('delete.comment');
    
    private $commentRepository; 

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(int $index)
    {
        $comments = $this->commentRepository->findByTweetId($index);

        return response()->json([
            'comments' =>  new CommentResource($comments),
        ]);
    }

    public function store(StoreCommentRequest $request)
    {
        $posterId = JWTAuth::user()->id;

        $payload = [
            'poster_id' => $posterId,
            'tweet_id'  => $request->tweetId,
            'content' => $request->content,
        ];

        $comment = $this->commentRepository->create($payload);

        if ($comment) {
            return response()->json([
                'comment' =>  new CommentResource($comment),
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function update(UpdateCommentRequest $request)
    {
        $id = $request->id;
        $posterId = JWTAuth::user()->id;

        $payload = [
            'content' => $request->content,
        ];

        // \error_log(print_r($payload));
        // \error_log($id);

        $comment = $this->commentRepository->update($id, $payload);

        if ($comment) {
            return response()->json([
                'comment' => new CommentResource($this->commentRepository->findById($id)),
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function delete(DeleteCommentRequest $request)
    {
        $comment = $this->commentRepository->delete($request->id);
 
        if ($comment) {
            return response()->json([
                'comment' =>  $comment,
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}