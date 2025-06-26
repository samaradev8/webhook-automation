<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DealParserService;
use App\Services\MondayBoardService;
use App\Services\MondayGroupService;

class ProcessFreshsalesDealJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(
        DealParserService $parser,
        MondayBoardService $boardService,
        MondayGroupService $groupService
    ): void {

        $dealData = $parser->parse($this->payload['deal_name']);

        $dealData['deal_closed_date'] = $this->payload['deal_closed_date'];

        // $boardId = $boardService->getOrCreateBoard($dealData);

        // $groupId = $groupService->createGroup($boardId, $this->payload['deal_name']);

        // $groupService->createItems($boardId, $groupId, $dealData);
    }
}
