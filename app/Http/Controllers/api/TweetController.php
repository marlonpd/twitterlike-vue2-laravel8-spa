<?php

namespace App\Http\Controllers\api;

use App\Repositories\TweetRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Http\Requests\DeleteTweetRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TweetResource;
use Illuminate\Http\Request;
use JWTAuth;


class TweetController extends Controller
{   
    private $tweetRepository;

    public function __construct(TweetRepositoryInterface $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }


    public function index(?int $pageIndex)
    {
        $posterId = JWTAuth::user()->id;
        $tweets = $this->tweetRepository->findByPosterIdLimitedList($posterId, $pageIndex);

        return response()->json([
            'tweets' =>  TweetResource::collection($tweets),
            'count' => count($tweets)
        ]);
    }

    public function show()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLimitedList($pageIndex)
    {
        $posterId = JWTAuth::user()->id;
        $tweetCount = $this->tweetRepository->countByPosterId($posterId);

        return response()->json([
            'tweets' =>  TweetResource::collection($this->tweetRepository->findByPosterIdLimitedList($posterId, $pageIndex)),
            'count' => $tweetCount
        ]);
    }

    public function store(StoreTweetRequest $request)
    {
        $posterId = JWTAuth::user()->id;

        $payload = [
            'poster_id' => $posterId,
            'content' => $request->content,
        ];

        $tweet = $this->tweetRepository->create($payload);

        if ($tweet) {
            return response()->json([
                'tweet' =>  new TweetResource($tweet),
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $posterId = JWTAuth::user()->id;

        $payload = [
            'poster_id' => $posterId,
            'content' => $request->content,
        ];

        $tweet = $this->tweetRepository->update($id, $payload);

        if ($tweet) {
            return response()->json([
                'tweet' => new TweetResource($this->tweetRepository->findById($id)),
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(string $searchKey)
    {
        $posterId = JWTAuth::user()->id;
        $tweets = $this->tweetRepository->search($searchKey);

        return response()->json([
            'tweets' =>  TweetResource::collection($tweets),
            'count' => count($tweets)
        ]);
    }

    public function destroy(DeleteTweetRequest $request)
    {
        $tweet = $this->tweetRepository->delete($request->id);
 
        if ($tweet) {
            return response()->json([
                'tweet' =>  $tweet,
            ]);
        } else{
            return response()->json([
                'error' => 'Encountered an error.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}