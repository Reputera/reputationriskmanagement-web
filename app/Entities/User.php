<?php

namespace App\Entities;

use App\Entities\Exceptions\InvalidRoleAssignment;
use App\Entities\Traits\Toggleable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entities\User
 *
 * @property integer $id
 * @property string $name
 * @property string $status
 * @property string $role
 * @property string $email
 * @property string $phone_number
 * @property string $phone_number_extension
 * @property integer $company_id
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Entities\Company $company
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User wherePhoneNumberExtension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use SoftDeletes, Toggleable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'role', 'company_id', 'phone_number', 'phone_number_extension'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @throws InvalidRoleAssignment
     * @return User
     */
    public static function create(array $attributes = [])
    {
        self::arrayHasAssignableRole($attributes);
        return parent::create($attributes);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @return User
     */
    public function firstOrCreate(array $attributes)
    {
        if (! is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        self::arrayHasAssignableRole($attributes);
        $instance = $this->model->newInstance($attributes);

        $instance->save();

        return $instance;
    }

    /**
     * Checks if a given array has a valid role in it.
     *
     * @param array $attributes
     * @throws InvalidRoleAssignment
     */
    protected static function arrayHasAssignableRole(array $attributes)
    {
        $role = array_get($attributes, 'role');
        $allRoles = Role::values();
        if (!$role || !in_array($role, $allRoles)) {
            throw new InvalidRoleAssignment('On of the following roles ('.
                implode(', ', $allRoles).') must be assigned to create a user.');
        }
    }

    /**
     * Checks the user to see if they the role that was given.
     *
     * @param $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return strtolower($this->role) == strtolower($role);
    }

    /**
     * Checks the user to see if they are an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return ($this->role && $this->role == Role::ADMIN);
    }

    public function restore()
    {
        $this->update(['status' => Status::ENABLED]);
        return parent::restore();
    }

    public function delete()
    {
        $this->update(['status' => Status::DISABLED]);
        return parent::delete();
    }
}
