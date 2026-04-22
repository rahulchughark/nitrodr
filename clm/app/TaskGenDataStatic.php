<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskGenDataStatic extends Model
{
    public function __construct() {
        $this->status = 'Not Started';
        $this->id = 0;
    }
}
