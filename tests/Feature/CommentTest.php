<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function get_login_token()
    {
        $faker = \Faker\Factory::create();

        $email = preg_replace('/@example\..*/', '@domain.com', $faker->unique()->safeEmail);
        $password = "password";

        $userData = [
            "name" => "John Doe",
            "email" => $email,
            "password" => $password,
            "password2" => $password,
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "user",
            ]);

        $userData = [
            "email" => $email,
            "password" =>  $password,
        ];

        $response = $this->json('POST', 'api/authenticate', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "user",
                "token"
            ]);
        
        $token = $response->json()['token'];
        
        return $token;
    }


    public function test_success_posting_comment()
    {
        $token = $this->get_login_token();  

        $tweetData = [
            "content" => "This sis a sample tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/tweet/store', $tweetData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );

        $tweet = $response->json()['tweet'];


       // print_r($response->getContent()); 

        $commentData = [
            'posterId' => $tweet['posterId'],
            'tweetId'  =>$tweet['id'],
            'content' => 'This is a comment of a tweet',
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/comment/store', $commentData, ['Accept' => 'application/json']);
       // print_r($response->getContent()); 
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

    }


    public function test_success_updating_comment()
    {
        $token = $this->get_login_token();  

        $tweetData = [
            "content" => "This sis a sample tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/tweet/store', $tweetData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );

        $tweet = $response->json()['tweet'];

        $commentData = [
            'posterId' => $tweet['posterId'],
            'tweetId'  =>$tweet['id'],
            'content' => 'This is a comment of a tweet',
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/comment/store', $commentData, ['Accept' => 'application/json']);
       // print_r($response->getContent()); 
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

        $comment = $response->json()['comment'];

        $updatedCommentData = [
            'id'  => $comment['id'],
            'content' => 'This is an updated comment of a tweet',
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/comment/update', $updatedCommentData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

    }


    public function test_success_deleting_comment()
    {
        $token = $this->get_login_token();  

        $tweetData = [
            "content" => "This sis a sample tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/tweet/store', $tweetData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );

        $tweet = $response->json()['tweet'];

        $commentData = [
            'posterId' => $tweet['posterId'],
            'tweetId'  =>$tweet['id'],
            'content' => 'This is a comment of a tweet',
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/comment/store', $commentData, ['Accept' => 'application/json']);
       // print_r($response->getContent()); 
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

        $comment = $response->json()['comment'];

        $updatedCommentData = [
            'id'  => $comment['id'],
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', '/api/comment/delete', $updatedCommentData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

    }


    public function test_success_getting_comment()
    {
        $token = $this->get_login_token();  

        $tweetData = [
            "content" => "This sis a sample tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/tweet/store', $tweetData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );

        $tweet = $response->json()['tweet'];
        $tweetId = $tweet['id'];

        $commentData = [
            'posterId' => $tweet['posterId'],
            'tweetId'  => $tweetId,
            'content' => 'This is a comment of a tweet',
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/comment/store', $commentData, ['Accept' => 'application/json']);
       // print_r($response->getContent()); 
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comment',
            ]
        );

        $comment = $response->json()['comment'];
        $commentId = $comment['id'];

        $updatedCommentData = [
            'id'  => $commentId,
        ];


        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', "/api/tweet/$tweetId/comments");
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'comments',
            ]
        );

    }
}