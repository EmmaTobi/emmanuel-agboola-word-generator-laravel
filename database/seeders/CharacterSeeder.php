<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Character;

class CharacterSeeder extends Seeder
{
    /**
     * Seed characters table in database.
     *
     * @return void
     */
    public function run()
    {
        Character::insert(
            $this->getCharactersPayload(), 
            ['id', 'symbol'], 
            ['id']
        );
    }

    /**
     * Get Characters Payload
     *
     * @return array
     */
    public function getCharactersPayload(): array 
    {
       return array_merge( $this->getNumbers(), $this->getAlphabets());
    }

    /**
     * Get Number Characters Payload
     *
     * @return array
     */
    public function getNumbers(): array
    {
        return collect(range(0, 9))->map(function(int $number, int $index){
            return [
                'id' => $index,
                'symbol' => $number,
            ];
        })->toArray();
    }

    /**
     * Get Alphabets Characters Payload
     *
     * @return array
     */
    public function getAlphabets(): array
    {
        $counter = 9;
        $lowerCase = collect(range('a', 'z'))->map(function(string $char, int $index) use (&$counter){
            return [
                'id' => ++$counter,
                'symbol' => $char,
            ];
        })->toArray();

        $upperCase = collect(range('A', 'Z'))->map(function(string $char,  int $index) use (&$counter){
            return [
                'id' => ++$counter,
                'symbol' => $char,
            ];
        })->toArray();

        return array_merge($lowerCase, $upperCase);
    }
}
