<?php

namespace App\Console\Commands;

use App\User;
use Auth;
use Illuminate\Console\Command;

class SessionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively logout all users daily.';

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
        User::where('is_login',1)->update(['is_login'=>0]);
        $this->info('Successfully sent daily quote to everyone.');
    }
}
