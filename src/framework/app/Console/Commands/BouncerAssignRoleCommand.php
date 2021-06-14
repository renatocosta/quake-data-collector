<?php

namespace App\Console\Commands;

use App\Entities\User;
use Illuminate\Console\Command;
use Modules\Account\Extensions\PermissionsData;
use Silber\Bouncer\Database\Role;

class BouncerAssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bouncerRole:assign {userId} {scopeId} {roleName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = (int)$this->argument('userId');
        $scopeId = (int)$this->argument('scopeId');
        $roleName = $this->argument('roleName');

        /** @var User $user */
        $user   = User::find($userId);
        if (!$user) {
         $this->error("User with ID '{$userId}' not found");
         return;
        }

        if(!Role::query()->where('name', $roleName)->exists()) {
            $this->error("Role with name '{$roleName}' not found");
            return;
        }

        $user->assign($roleName, $scopeId);
    }
}
