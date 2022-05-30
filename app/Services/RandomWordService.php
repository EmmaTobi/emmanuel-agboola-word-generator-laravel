<?php

namespace App\Services;

use App\Models\Character;
use App\Models\User;

class RandomWordService
{
    protected ?array $alphabetIds = null;
    protected ?array $numberIds = null;
    protected ?array $alphaNumericIds = null;
    protected array $cachedResults = [];

    /**
     * Generate words consisting of alphabets
     * @param int $length The length of each word
     * @param int $amount The amount of words to be generated
     * @return array
     */
    public function alphabets(int $length, int $amount): array
    {
        return $this->getRandomCharactersByType(Character::ALPHABET, $length, $amount);
    }

    /**
     * Generate words consisting of numbers characters
     * @param int $length The length of each word
     * @param int $amount The amount of words to be generated
     * @return array
     */
    public function numbers(int $length, int $amount): array
    { 
        return $this->getRandomCharactersByType(Character::NUMBER, $length, $amount);
    }
    // /Users/mac/Documents/laravel_apps/emmanuel-agboola-word-generator-laravel/app/Http/Controllers/AuthController.php
    /**
     * Generate words consisting of alphanumeric characters
     * @param int $length The length of each word
     * @param int $amount The amount of words to be generated
     * @return array
     */
    public function alphaNumeric(int $length, int $amount): array
    {
        return $this->getRandomCharactersByType(Character::ALPHA_NUMERIC, $length, $amount);
    }

    /**
     * Generate random alphabet ids (alphabets ids in characters db)
     * @param int $length The length alphabet to generate
     * @return array
     */
    protected function getRandomAlphabetIdsByLength(int $length): array
    {
        if(!$this->alphabetIds){
            $this->alphabetIds = array_merge(
                range(Character::LOWERCASE_MIN, Character::LOWERCASE_MAX),
                range(Character::UPPERCASE_MIN, Character::UPPERCASE_MAX),
            );
        }

        $result = array_rand(array_flip($this->alphabetIds), $length);

        return $length == 1 ? [$result] : $result;
    }

    /**
     * Generate random number ids (numbers ids in characters db)
     * @param int $length The length numbers to generate
     * @return array
     */
    protected function getRandomNumberIdsByLength(int $length): array
    {
        if(!$this->numberIds){
            $this->numberIds = range(Character::NUMBER_MIN, Character::NUMBER_MAX);
        }
        
        if($length <= count($this->numberIds)){
            $result = array_rand(array_flip($this->numberIds), $length);
        }else{
            $result = array_rand(array_flip($this->numberIds), $count($this->numberIds));
            $overflowResult = array_rand(array_flip($this->numberIds), $length - count($this->numberIds));
            $result =  array_merge($result, $overflowResult);
        }

        return $length == 1 ? [$result] : $result;
    }

    /**
     * Generate random alphanumeric ids (numbers and alphabets ids in characters db)
     * @param int $length The length numbers to generate
     * @return array
     */
    protected function getRandomAlphaNumericIdsByLength(int $length): array
    {
        if(!$this->alphaNumericIds){
            $this->alphaNumericIds = array_merge(
                range(Character::LOWERCASE_MIN, Character::LOWERCASE_MAX),
                range(Character::UPPERCASE_MIN, Character::UPPERCASE_MAX),
                range(Character::NUMBER_MIN, Character::NUMBER_MAX),
            );
        }

        $result = array_rand(array_flip($this->alphaNumericIds), $length);

        return $length == 1 ? [$result] : $result;
    }

    /**
     * Generate random character by type and ids (numbers and alphabets ids in characters db)
     * @param int $type The type of character to generate
     * @param int $length The length of character to generate
     * @return array
     */
    protected function getRandomCharacterIdsByTypeAndLength(string $type, int $length): array
    {
        switch($type){
            case Character::ALPHABET:
                return $this->getRandomAlphabetIdsByLength($length);
            case Character::NUMBER:
                return $this->getRandomNumberIdsByLength($length);
            case Character::ALPHA_NUMERIC:
                return $this->getRandomAlphaNumericIdsByLength($length);
        }
        
        return [];
    }

    /**
     * Get Characters From Db by Id
     * @param array $ids character ids mapping to id in characters table
     * @return array
     */
    protected function getCharactersFromDbById(array $ids): array
    {
        return Character::whereIn('id', $ids)->pluck('symbol', 'id')->toArray();
    }

    /**
     * Get Random Characters 
     * @param string $type
     * @param int $length
     * @param int $amount
     * @return array
     */
    public function getRandomCharactersByType(string $type, int $length, int $amount): array
    {
        $characters = [];
        for($i = 0; $i < $amount; ++$i){
            // Get Random Characters from Db based on type and length of char constraint
            $randomCharacterIds = $this->getRandomCharacterIdsByTypeAndLength($type, $length);
            // I want to minimise the number of times i hit the database
            // Hence i only fetch new characters from db which have not been fetched already, improves performance
            // This is done by help of a cached results, which enable me store fetched characters and for easy lookup
            $newCharacterIds = array_diff($randomCharacterIds, array_keys($this->cachedResults));
            $this->cachedResults = $this->cachedResults + $this->getCharactersFromDbById($newCharacterIds);
            $results = collect($randomCharacterIds)->map(function($charId){
                return $this->cachedResults[$charId];
            })->toArray();
            //To Ensure randomness in all sense
            shuffle($results);

            $characters[] = implode($results);
        }
 
        return $characters;
    }
}
