<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


//print_r($response->getContent());  
class TweetTest extends TestCase
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

    public function test_success_storing_tweet()
    {
        $token = $this->get_login_token();  

        $tenantData = [
            "content" => "This sis a sample tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/tweet/store', $tenantData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );
    }

    public function test_success_updating_tenant()
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

        $tweetData = [
            "id"    => $tweet['id'],
            "content" => "This sis a sample updated tweet content"
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/tweet/update', $tweetData, ['Accept' => 'application/json']);
        
        
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );
    }

    public function test_success_deleting_tweet()
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

        $tweetData = [
            "id"    => $tweet['id'],
        ];

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', '/api/tweet/delete', $tweetData, ['Accept' => 'application/json']);
       // print_r($response->getContent());  
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweet',
            ]
        );
    }


    public function test_success_searching_tweets()
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

        $searchKey = 'tweet';

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', "/api/tweets/search/$searchKey");
       //print_r($response->getContent());  
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweets',
              'count'
            ]
        );
    }

    public function test_success_getting_tweets()
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

        $searchKey = 'tweet';

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', "/api/tweets/1");
      // print_r($response->getContent());  
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
              'tweets',
              'count'
            ]
        );
    }

}