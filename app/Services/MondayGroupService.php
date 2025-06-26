<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MondayGroupService
{
    protected string $token;

    public function __construct()
    {
        $this->token = env('MONDAY_API_KEY');
    }

    public function createGroup(string $boardId, string $groupName): string
    {
        $mutation = <<<GQL
            mutation {
                create_group (board_id: $boardId, group_name: "$groupName") {
                    id
                }
            }
        GQL;

        $response = Http::withToken($this->token)->post('https://api.monday.com/v2', [
            'query' => $mutation,
        ]);

        return $response->json('data.create_group.id');
    }

    public function createItems(string $boardId, string $groupId, array $dealData): void
    {
        // You can paste your bulk mutation logic here.
        Log::info("[Monday] Creating items for group $groupId in board $boardId");
    }
}