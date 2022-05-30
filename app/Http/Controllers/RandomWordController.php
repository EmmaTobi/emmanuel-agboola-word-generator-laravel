<?php

namespace App\Http\Controllers;

use App\Services\UserWordService;
use App\Services\RandomWordService;
use Illuminate\Http\Request;

class RandomWordController extends Controller
{
    private UserWordService $userWordService;
    private RandomWordService $randomWordService;

    public function __construct()
    {
        $this->userWordService = app()->make(UserWordService::class);
        $this->randomWordService = app()->make(RandomWordService::class);
    }

    /**
     * Handle User Create Random Words Request
     * @param Request $request
     */
    public function generateFreshWords(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'length' => 'required',
            'quantity' => 'required',
        ]);

        $results = $this->randomWordService->getRandomCharactersByType(
            $data['type'],
            $data['length'],
            $data['quantity'],
        );

        $userId = auth()->id();
        $results = collect($results)->map(function($word) use ($userId) {
            return [
                'user_id' => $userId,
                'word' => $word,
            ];
        })->toArray();

        $this->userWordService->deleteManyByUserId($userId);

        $this->userWordService->createMany($results);

        return view('words', compact('results'));
    }

    /**
     * Display Create Random Words Request Form
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $results = $this->userWordService->getManyByUserId(auth()->id());

        return view('words', compact('results'));
    }
}
