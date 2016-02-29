<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\StockHistory
 *
 * @property integer $id
 * @property integer $company_id
 * @property \Carbon\Carbon $date
 * @property float $open
 * @property float $high
 * @property float $low
 * @property float $close
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Company $Company
 * @method static \App\Entities\StockHistory|null find($id)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereOpen($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereHigh($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereLow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereClosed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\StockHistory whereUpdatedAt($value)
 */
class StockHistory extends Model
{
    protected $dates = ['created_at', 'updated_at', 'date'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
