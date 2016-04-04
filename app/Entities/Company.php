<?php

namespace App\Entities;

use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entities\Company
 *
 * @property integer $id
 * @property string $name
 * @property string $stock_symbol
 * @property string $entity_id
 * @property integer $industry_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Entities\Industry $industry
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Company[] $competitors
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereStockSymbol($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Company whereIndustryId($value)
 * @method static \App\Entities\Company find($id)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'stock_name'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function competitors()
    {
        return $this->belongsToMany(Company::class, 'company_competitor', 'company_id', 'competitor_company_id');
    }

    /**
     * Queues the responses from the Recorded Future API for a given number of hours.
     *
     * @param int $hours
     */
    public function queueInstancesHourly(int $hours = 1)
    {
        app(InstanceApiResponseQueue::class)->processHourly($this, $hours);
    }

    /**
     * Queues the responses from the Recorded Future API for a given number of days.
     *
     * @param int $days
     */
    public function queueInstancesDaily(int $days = 1)
    {
        app(InstanceApiResponseQueue::class)->processDaily($this, $days);
    }
}
