<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Instance
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $vector_id
 * @property string $entity_id
 * @property string $type
 * @property string $start
 * @property string $language
 * @property string $source
 * @property string $title
 * @property string $fragment
 * @property string $fragment_hash
 * @property string $link
 * @property float $sentiment
 * @property float $positive_sentiment
 * @property float $negative_sentiment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entities\Company $company
 * @property-read \App\Entities\Vector $vector
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Country[] $countries
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereVectorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereFragment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereFragmentHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereSentiment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance wherePositiveSentiment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereNegativeSentiment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Instance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Instance extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vector()
    {
        return $this->belongsTo(Vector::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'instance_country');
    }

}