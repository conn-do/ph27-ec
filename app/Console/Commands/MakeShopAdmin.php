<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeShopAdmin extends Command
{
    protected $signature = 'shop:make-admin {email : Email of an existing account}';

    protected $description = 'Grant shop administration access to an existing account';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('アカウントが見つかりません。先に会員登録してください。');

            return self::FAILURE;
        }
        $user->forceFill(['is_admin' => true])->save();
        $this->info('管理者権限を付与しました。/admin からログインできます。');

        return self::SUCCESS;
    }
}
