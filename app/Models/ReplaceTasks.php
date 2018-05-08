<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReplaceTasks
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $cabinet_id
 * @property int|null $door_id1
 * @property int|null $door_id2
 * @property int|null $state 0=初始化，10=未完成，20=已完成，30=异常
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property float|null $user_cost 应付
 * @property float|null $actual_cost 实付
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereActualCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereDoorId1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereDoorId2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUserCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUserId($value)
 * @mixin \Eloquent
 */
class ReplaceTasks extends Model
{

    //0=初始化，10=未完成，20=已完成，30=异常
    const TASK_STATE_INIT = 0;//初始化
    const TASK_STATE_TIMEOUT = 10;//超时未完成
    const TASK_STATE_COMPLETE = 20;//已完成
    const TASK_STATE_ABNORMAL = 30;//异常


    protected $table = 'replace_tasks';
    protected $primaryKey = 'id';
    protected $guarded = [];

}
