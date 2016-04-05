<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Api\RecordedFutureApi;
use App\Services\Vendors\RecordedFuture\Api\Response;
use Illuminate\Support\Facades\Storage;

class InstanceApiResponseQueue
{
    /** @var RecordedFutureApi */
    protected $api;

    /** @var string */
    protected static $path = 'RecordedFuture-responses';

    /** @var string */
    protected $fileSystem = 'local';

    /**
     * InstanceApiResponseQueue constructor.
     *
     * @param RecordedFutureApi $api
     */
    public function __construct(RecordedFutureApi $api)
    {
        $this->api = $api;
    }

    /**
     * Get the path in relation to the storage folder (storage/app)
     *
     * @return string
     */
    public static function getFullPath()
    {
        return storage_path('app/'.self::$path);
    }

    public static function getRelativePath()
    {
        return self::$path;
    }

    /**
     * Processes a given company's instances for the last given number of hours.
     *
     * @param Company $company
     * @param int $hours
     */
    public function processHourly(Company $company, int $hours = 1)
    {
        $this->saveResponse($company, 'queryInstancesForEntityHourly', $hours);
    }

    /**
     * Processes a given company's instances for the last given number of days.
     *
     * @param Company $company
     * @param int $days
     */
    public function processDaily(Company $company, int $days = 1)
    {
        $this->saveResponse($company, 'queryInstancesForEntityDaily', $days);
    }

    /**
     * @param Company $company The company to feed to the API
     * @param string $function The function name to call on the API.
     * @param int $frequency The number of "things" to process. For example this could be the number of
     * days or hours to process for.
     */
    protected function saveResponse(Company $company, string $function, int $frequency)
    {
        $apiResponse = $this->api->{$function}($company->entity_id, $frequency);

        while ($apiResponse->countOfReferences()) {
            $this->writeToFileSystem($apiResponse, $company);

            $apiResponse = $this->api->{$function}(
                $company->entity_id,
                $frequency,
                ['page_start' => $apiResponse->getNextPageStart()]
            );
        }
    }

    /**
     * Writes the response into a file.
     *
     * @param Response $response
     * @param Company $company
     * @return string
     */
    protected function writeToFileSystem(Response $response, Company $company): string
    {
        $fileName = date('Y-m-d_H-i-s').'_'.$response->getNextPageStart().'-'.$company->entity_id.'.log';
        $path = $this->getRelativePath().'/'.$company->entity_id.'/'.$fileName;
        Storage::disk($this->fileSystem)->put($path, $response->asJson());
        return $path;
    }
}
