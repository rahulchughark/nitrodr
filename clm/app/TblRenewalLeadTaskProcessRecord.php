<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblRenewalLeadTaskProcessRecord extends Model
{
    protected $table = 'tbl_renewal_lead_task_process_record';

    protected $fillable = ['program_initiation_date'];

    public $timestamps = false;
}
