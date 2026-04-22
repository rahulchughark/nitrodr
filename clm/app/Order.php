<?php
namespace App;
 
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
 
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
   // protected $table = 'orders';
   public $timestamps = false;

   protected $fillable = ['spoc','eu_name','eu_email','eu_mobile','eu_designation','eu_person_name1','eu_mobile1','eu_designation1','eu_email1','eu_person_name2','eu_mobile2','eu_email2','adm_name','adm_designation','adm_email','adm_mobile','adm_alt_mobile','school_board','program_start_date','academic_start_date','academic_end_date','grade_signed_up','quantity','purchase_no','application_date','purchase_deails','license_period','is_app_erp','ip_address','labs_count','system_count','os','student_system_ratio','lab_teacher_ratio','program_initiation_date','onboard_mail_sent'];

   protected static function boot()
    {
        parent::boot();

        static::updating(function ($order) {
            $original = $order->getOriginal();
            $changes = $order->getDirty();
            $userId = Auth::id();
          
          // dd($changes,$original);
          $fields_to_monitor = [
            'spoc' => 'SPOC',
            'eu_name' => 'EU Name',
            'eu_mobile' => 'EU Mobile',
            'eu_email' => 'EU Email',
            'eu_designation' => 'EU Designation',
            'eu_person_name1' => 'EU Person Name 1',
            'eu_designation1' => 'EU Designation 1',
            'eu_mobile1' => 'EU Mobile 1',
            'eu_email1' => 'EU Email 1',
            'eu_person_name2' => 'EU Person Name 2',
            'eu_mobile2' => 'EU Mobile 2',
            'eu_email2' => 'EU Email 2',
            'adm_name' => 'Admin Name',
            'adm_designation' => 'Admin Designation',
            'adm_email' => 'Admin Email',
            'adm_mobile' => 'Admin Mobile',
            'adm_alt_mobile' => 'Admin Alt Mobile',
            'school_board' => 'School Board',
            'program_start_date' => 'Program Start Date',
            'academic_start_date' => 'Academic Start Date',
            'academic_end_date' => 'Academic End Date',
            'grade_signed_up' => 'Grade Signed Up',
            'quantity' => 'Student Count',
            'purchase_no' => 'Purchase No',
            'application_date' => 'Application Date',
            'purchase_deails' => 'Purchase Details',
            'license_period' => 'License Period',
            'is_app_erp' => 'Is App ERP',
            'ip_address' => 'IP Address',
            'labs_count' => 'Labs Count',
            'system_count' => 'System Count',
            'os' => 'OS',
            'student_system_ratio' => 'Student System Ratio',
            'lab_teacher_ratio' => 'Lab Teacher Ratio',
            'program_initiation_date' => 'Program Initiation Date',
            'onboard_mail_sent' => 'onboard_mail_sent'
        ];
            foreach ($changes as $field => $newValue) {
              if($field != 'onboard_mail_sent'){
                $oldValue = $original[$field] ?? 'N/A';
                if($field == 'spoc')
                {
                  $oldValue = DB::table('users')->where('id', $original['spoc'])->value('name');
                  $newValue = DB::table('users')->where('id', $changes['spoc'])->value('name');
                }
                if ($field == 'grade_signed_up') {
                  $grade = $changes[$field];
                  $newValue = $grade;
                } 
                $oldValue = $oldValue == null || $oldValue == '' ? 'N/A' : $oldValue ;
                
                // dd($field);
              foreach($fields_to_monitor as $fieldM => $type){
                if($field == $fieldM){
                  $typeM = $type;
                }
              }
              $current_date = date('Y-m-d H:i:s');
                  LeadModifyLog::create([
                      'lead_id' => $order->id,
                      'type' => $typeM,
                      'previous_name' => $oldValue,
                      'modify_name' => $newValue??"N/A",
                      'created_by' => 0,
                      'created_date' => now(),
                      'created_by_clm' => $userId,
                  ]);
              }
            }
        });
    }
}
?>