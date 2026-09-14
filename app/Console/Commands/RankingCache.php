<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:ranking-cache')]
#[Description('ランキングのキャッシュを作成')]
class RankingCache extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('キャッシュを作成しました');
    }
}
