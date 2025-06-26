<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MondayBoardService
{
    protected string $token;

    public function __construct()
    {
        $this->token = env('MONDAY_API_KEY');
    }

    public function getOrCreateBoard(array $dealData): string
    {
        $boardName = $dealData['boat_name'] . ' ' . $dealData['start_date']->year;

        return Cache::remember("monday_board_{$boardName}", 3600, function () use ($boardName) {
            $query = <<<GQL
                query {
                    boards (limit: 100) {
                        id name
                    }
                }
            GQL;

            $response = Http::withToken($this->token)->post('https://api.monday.com/v2', [
                'query' => $query,
            ]);

            $boards = $response->json('data.boards') ?? [];

            $board = collect($boards)->firstWhere('name', $boardName);

            if ($board) {
                return $board['id'];
            }

            $mutation = <<<GQL
                mutation {
                    create_board (board_name: "$boardName", board_kind: public, workspace_id: 11202839) {
                        id
                    }
                }
            GQL;

            $create = Http::withToken($this->token)->post('https://api.monday.com/v2', [
                'query' => $mutation
            ]);

            return $create->json('data.create_board.id');
        });
    }
}