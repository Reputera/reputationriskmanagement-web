<?php

namespace App\Console\Commands\SystemCleanup;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Instance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system-cleanup:instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backs up all instance records older than a year old.';

    public function handle()
    {
        $dateToday = Carbon::now();
        $dateLastYear = clone $dateToday;
        $dateLastYear->subYear(1);

        $remoteStoragePath = $this->getRemotePathFromDate($dateToday);
        $localStoragePath = $this->getLocalPath();

        $this->backupRecords($dateToday, $dateLastYear, $localStoragePath, $remoteStoragePath);
        $this->deleteRecords($dateLastYear);
    }

    /**
     * Gets the path for the remote location.
     *
     * @param Carbon $dateToday
     * @return string
     */
    protected function getRemotePathFromDate(Carbon $dateToday)
    {
        $remoteStoragePath = $dateToday->year.'/'.$dateToday->format('m (F)').'/'.$dateToday->format('d (D)');

        if (!\Storage::disk('s3')->exists($remoteStoragePath)) {
            \Storage::disk('s3')->makeDirectory($remoteStoragePath);
        }

        return $remoteStoragePath;
    }

    /**
     * Gets the path to store the backup files locally.
     *
     * @return string
     */
    protected function getLocalPath()
    {
        $localStoragePath = storage_path('temp_instance_backups');
        if (!\File::isDirectory($localStoragePath)) {
            \File::makeDirectory($localStoragePath);
        }

        return $localStoragePath;
    }

    /**
     * Makes a backup of the selected tables to they can be deleted.
     *
     * @param Carbon $dateToday
     * @param Carbon $dateLastYear
     * @param $localStoragePath
     * @param $remoteStoragePath
     */
    protected function backupRecords(
        Carbon $dateToday,
        Carbon $dateLastYear,
        $localStoragePath,
        $remoteStoragePath
    ) {
        $username = \DB::connection()->getConfig('username');
        $password = \DB::connection()->getConfig('password');

        $instanceCountriesFileName = 'instance_country-backup-'.$dateToday->toDateString().'.sql';
        exec('mysqldump --lock-all-tables --user='.$username.' --password='.$password.' rrm instance_country'.
            ' --where="instance_id in (select id from instances where start < \''.$dateLastYear->toDateString().'\')"  >'.
            $localStoragePath.'/'.$instanceCountriesFileName);

        \Storage::disk('s3')->put(
            $remoteStoragePath.'/'.$instanceCountriesFileName,
            \File::get($localStoragePath.'/'.$instanceCountriesFileName)
        );

        $instanceFileName = 'instances-backup-'.$dateToday->toDateString().'.sql';
        exec('mysqldump --user='.$username.' --password='.$password.' rrm instances'.
            ' --where="start < \''.$dateLastYear->toDateString().'\'"  >'.
            $localStoragePath.'/'.$instanceFileName);

        \Storage::disk('s3')->put(
            $remoteStoragePath.'/'.$instanceFileName,
            \File::get($localStoragePath.'/'.$instanceFileName)
        );

        \File::deleteDirectory($localStoragePath);
    }

    protected function deleteRecords($dateLastYear)
    {
        $instanceIdsToDelete = $query = \DB::table('instances')
            ->where('start', '<', $dateLastYear->toDateString())
            ->select(['id'])
            ->pluck('id');

        \DB::table('instance_country')
            ->whereIn('instance_id', $instanceIdsToDelete)
            ->delete();

        \DB::table('instances')
            ->whereIn('id', $instanceIdsToDelete)
            ->delete();
    }
}
