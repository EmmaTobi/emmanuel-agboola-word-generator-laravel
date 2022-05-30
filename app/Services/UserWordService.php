<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserWord;
use Illuminate\Support\Collection;

class UserWordService
{
    /**
     * Create Many User Words
     * @param array $data 
     * @return bool
     */
    public function createMany(array $data): bool
    {
        return UserWord::upsert($data, ['id']);
    }

    /**
     * Delete Many User Words
     * @param array $userId
     * @return bool
     */
    public function deleteManyByUserId(int $userId): bool
    {
        return UserWord::whereUserId($userId)->delete();
    }

    /**
     * Get Many User Words
     * @param array $userId
     * @return bool
     */
    public function getManyByUserId(int $userId): Collection
    {
        return UserWord::whereUserId($userId)->get();
    }
    
}
