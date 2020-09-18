<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $created_by_user_id
 * @property int $updated_by_user_id
 * @property bool $is_admin
 * @property string|null $key
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $auth_name
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic
 * @property string|null $password_hash
 * @property string|null $password_updated_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $lastime_logged_in_at
 * @property string|null $remember_token
 * @property string|null $blocked_at
 * @property bool $is_super
 * @property string|null $comment
 *
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 *
 *
 * @mixin \Eloquent
 */
class User extends \Illuminate\Foundation\Auth\User
{

    public $table = 'user';
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
