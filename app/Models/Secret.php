<?php

namespace App\Models;

use App\Traits\TrDateFormatWithTZ;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Secret
 *
 * @property int      $id
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 * @property int|null $created_by_user_id
 * @property int|null $updated_by_user_id
 *
 * @property string $uuid
 *
 * @property string|null $secpass
 * @property string|null $sectext
 *
 * @property boolean $is_allow_show_created
 *
 * @property int $crr_show_count
 * @property int $max_show_count
 * @property boolean $is_hide_show_count
 *
 * @property Carbon $expired_at
 * @property boolean $is_hide_lifetime
 *
 *
 * @mixin \Eloquent
 */
class Secret extends Model
{

    public $table = 'secret';

    use TrDateFormatWithTZ;
    //public $timestamps = false;

    protected $casts = [
        //'crr_show_count' => 'bool',
    ];

    protected $dates = [
        //'created_at',
        //'updated_at',
        'expired_at',
    ];



}
