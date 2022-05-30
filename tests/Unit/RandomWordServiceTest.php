<?php

namespace App\Services;

use App\Services\RandomWordService;
use App\Models\Character;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RandomWordServiceTest extends TestCase
{
    use DatabaseTransactions;

    public RandomWordService $randomWordService;


    protected $connectionsToTransact = ['sqlite'];

    protected function setup(): void
    {
        parent::setUp();
        $this->randomWordService = app()->make(RandomWordService::class);
    }

     /**
     * Test GetRandomCharactersByType
     *
     * @return void
     */
    public function testGetRandomCharactersByType()
    {
        $amount = 10;
        $length = 5;

        $results = $this->randomWordService->getRandomCharactersByType(Character::ALPHABET, $length, $amount);
        $this->assertEquals($amount, count($results));
        $this->assertEquals($length, strlen($results[$length]));

        $results = $this->randomWordService->getRandomCharactersByType(Character::NUMBER, $length, $amount);
        $this->assertEquals($amount, count($results));
        $this->assertEquals($length, strlen($results[$length]));

        $results = $this->randomWordService->getRandomCharactersByType(Character::ALPHA_NUMERIC, $length, $amount);
        $this->assertEquals($amount, count($results));
        $this->assertEquals($length, strlen($results[$length]));
    }

    /**
     * Test GetRandomNumbers
     *
     * @return void
     */
    public function testGetRandomNumbers()
    {
        $amount = 10;
        $length = 7;
        $results = $this->randomWordService->numbers($length, $amount);
        $this->assertTrue(is_numeric($results[$length]));
    }

    /**
     * Test GetRandomAlphabets
     *
     * @return void
     */
    public function testGetRandomAlphabets()
    {
        $amount = 10;
        $length = 2;
        $results = $this->randomWordService->alphabets($length, $amount);
        $this->assertTrue(is_string($results[$length]));
    }

    /**
     * Test GetRandomAlphaNumeric
     *
     * @return void
     */
    public function testGetRandomAlphaNumeric()
    {
        $amount = 10;
        $length = 2;
        $results = $this->randomWordService->alphaNumeric($length, $amount);
        $this->assertTrue(ctype_alnum($results[$length]));
    }
}
