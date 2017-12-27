<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feedbacks
 *
 * @property int $id
 * @property int $user_id
 * @property int $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedbacks whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedbacks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedbacks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedbacks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedbacks whereUserId($value)
 * @mixin \Eloquent
 */
class Feedbacks extends Model
{
    //
    protected $guarded = [];
}
