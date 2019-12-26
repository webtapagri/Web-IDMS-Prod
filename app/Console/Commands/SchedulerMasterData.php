<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SchedulerMasterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "SchedulerMasterData:sync";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sync data MASTER";

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
        
		\Log::info('============'.date('Ymdh').'  Running master data scheduler ');
		
		try {
			
			$rsp = app('App\Http\Controllers\MasterController')->sync_comp();
			\Log::info( $rsp );
			
			$rsp = app('App\Http\Controllers\MasterController')->sync_est();
			\Log::info( $rsp );
			
		}catch (\Throwable $e) {
			\Log::info(throwable_msg($e));
		}catch (\Exception $e) {
			\Log::info(exception_msg($e));
        }
		
		
		\Log::info('============'.date('Ymdh').' End scheduler master data  ');
        
    }
}