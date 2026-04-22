<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAnswer extends Model
{
    protected $table = 'task_answers';
    public $timestamps = false;
    protected $fillable = [
'task_id','lead_id','question_id','answer','gen_task_id'
    ];
}
