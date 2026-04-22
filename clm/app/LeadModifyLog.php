<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadModifyLog extends Model
{
    protected $table = 'lead_modify_log';
    public $timestamps = false;
    protected $fillable = ['lead_id','type','previous_name','modify_name','created_by','created_by_clm','created_date'];
}