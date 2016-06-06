<?php

namespace App\Console\Commands\SystemCleanup;

use App\Entities\CompanyVectorColor;
use Illuminate\Console\Command;

class SyncCompanyVectorColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system-cleanup:sync-company-vector-colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures that every company has all colors associated, and updates where necessary.';

    public function handle()
    {
        CompanyVectorColor::syncAllCompanyColors();
    }
}
