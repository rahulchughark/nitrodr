<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblLeadContact extends Model
{
    protected $table = 'tbl_lead_contact';
    public $timestamps = false;
    protected $fillable = [
        'eu_name','lead_id','eu_email','eu_mobile','eu_designation','status'
            ];

}
