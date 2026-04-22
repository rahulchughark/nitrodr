<style>
    .multiselect-native-select .btn-group {
        width: 100%;
    }
.multiselect {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: .8rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    background-color: var(--bs-body-bg);
    background-clip: padding-box;
    border: var(--bs-border-width) solid var(--bs-border-color);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: var(--bs-border-radius);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    text-align:left !important;
}
.modal-dialog-scrollable .modal-content {
    overflow: visible;
}
</style>
 

<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit </h5>
        <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

<div class="modal-body">
       <form class="g-3" id="clmModelForm" method="POST"  action="{{ route('update_clm')}}">
       @csrf
       <div id="contactContainer">
                                <div class="contact-group">
                                    <div class="card-header">
                                            <h5 class="modal-inr-hed">
                                                    School Details
                                            </h5>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">SPOC:</label>
                                        <div class="col-sm-8">
                                        <select class="form-control form-control-sm" name="spoc"  >
                                        <option value="" >---Select---</option>
                                            @foreach($spocs as $s)
                                            <option {{ (($lead_data->spoc == $s->id) ? 'selected' : '') }} value="{{$s->id}}" >{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>                                    
                                </div>
                                    <div class="contact-group">
                                       <div class="card-header">
                                            <h5 class="modal-inr-hed">
                                                    Decision Maker/Proprietor/Director/End User Details
                                            </h5>
                                        </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Full Name:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_name" value="{{isset($lead_data) ? $lead_data->eu_name : ''}}" >
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">Email:</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" name="eu_email" value="{{isset($lead_data) ? $lead_data->eu_email : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Mobile:</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="eu_mobile" value="{{isset($lead_data) ? $lead_data->eu_mobile : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Designation:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_designation" value="{{isset($lead_data) ? $lead_data->eu_designation : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Person 1st - Name:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_person_name1" value="{{isset($lead_data) ? $lead_data->eu_person_name1 : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Designation:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm" name="eu_designation1"  >
                                                       <option value="" >---Select---</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Management') ? 'selected' : '') }} value="Management" >Management</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Vice Chancellor') ? 'selected' : '') }} value="Vice Chancellor" >Vice Chancellor</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Registrar') ? 'selected' : '') }} value="Registrar" >Registrar</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Grade 4') ? 'selected' : '') }} value="Grade 4" >Grade 4</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Director') ? 'selected' : '') }} value="Director" >Director</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'DEAN') ? 'selected' : '') }} value="DEAN" >DEAN</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Principal') ? 'selected' : '') }} value="Principal" >Principal</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Professor') ? 'selected' : '') }} value="Professor" >Professor</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'HOD') ? 'selected' : '') }} value="HOD" >HOD</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Faculty') ? 'selected' : '') }} value="Faculty" >Faculty</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Others') ? 'selected' : '') }} value="Others" >Others</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Head - Centre of Excellence') ? 'selected' : '') }} value="Head - Centre of Excellence">Head - Centre of Excellence</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Vice principal') ? 'selected' : '') }} value="Vice principal" >Vice principal</option>
                                                       <option {{ (($lead_data->eu_designation1 == 'Founder') ? 'selected' : '') }} value="Founder" >Founder</option>
                                                       
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Contact Number:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" pattern="[0-9'-'\s]*" name="eu_mobile1" minlength="10" maxlength="10" value="{{isset($lead_data) ? $lead_data->eu_mobile1 : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Email ID:</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" name="eu_email1" value="{{isset($lead_data) ? $lead_data->eu_email1 : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Person 2nd - Name:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_person_name2" value="{{isset($lead_data) ? $lead_data->eu_person_name2 : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Contact Number:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_mobile2" pattern="[0-9'-'\s]*" minlength="10" maxlength="10" value="{{isset($lead_data) ? $lead_data->eu_mobile2 : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Email ID:</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" name="eu_email2" value="{{isset($lead_data) ? $lead_data->eu_email2 : ''}}" >
                                        </div>
                                    </div>
                                    </div>
                                    <div class="contact-group">
                                    <div class="card-header">
                                            <h5 class="modal-inr-hed">
                                            ICT360 ADMIN INFORMATION
                                            </h5>
                                        </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Full Name of Admin - ICT360:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="adm_name" value="{{isset($lead_data) ? $lead_data->adm_name : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Designation:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="adm_designation" value="{{isset($lead_data) ? $lead_data->adm_designation : ''}}" >
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">E-mail ID:</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" name="adm_email" value="{{isset($lead_data) ? $lead_data->adm_email : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Contact Number:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="adm_mobile" value="{{isset($lead_data) ? $lead_data->adm_mobile : ''}}"   pattern="[0-9'-'\s]*" minlength="10" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Alternative Contact Number:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="adm_alt_mobile" value="{{isset($lead_data) ? $lead_data->adm_alt_mobile : ''}}"   pattern="[0-9'-'\s]*" minlength="10" maxlength="10">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="contact-group">
                                    <div class="card-header">
                                            <h5 class="modal-inr-hed">
                                            Program Information
                                            </h5>
                                        </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Operational Boards in School:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" pattern="[a-zA-Z0-9'-'\s]*" name="school_board" value="{{isset($lead_data) ? $lead_data->school_board : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Start Date of ICT360 Program in school:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="program_start_date" value="{{isset($lead_data) ? $lead_data->program_start_date : ''}}" >
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">School Academic Year Start Date:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="academic_start_date" value="{{isset($lead_data) ? $lead_data->academic_start_date : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">School Academic Year End Date:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="academic_end_date" value="{{isset($lead_data) ? $lead_data->academic_end_date : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Grades Signed Up For ICT 360:</label>
                                        <div class="col-sm-8">
                                        <select name="grade_signed_up[]" id="grade_signed_up" class="form-control form-control-sm form-select"  multiple="multiple"  >
                                                         <option <?= in_array('1',$grade_signed_upArr) ? 'selected' : '' ?> value='1'>Grade 1</option>
                                                         <option <?= in_array('2',$grade_signed_upArr) ? 'selected' : '' ?> value='2'>Grade 2</option>
                                                         <option <?= in_array('3',$grade_signed_upArr) ? 'selected' : '' ?> value='3'>Grade 3</option>
                                                         <option <?= in_array('4',$grade_signed_upArr) ? 'selected' : '' ?> value='4'>Grade 4</option>
                                                         <option <?= in_array('5',$grade_signed_upArr) ? 'selected' : '' ?> value='5'>Grade 5</option>
                                                         <option <?= in_array('6',$grade_signed_upArr) ? 'selected' : '' ?> value='6'>Grade 6</option>
                                                         <option <?= in_array('7',$grade_signed_upArr) ? 'selected' : '' ?> value='7'>Grade 7</option>
                                                         <option <?= in_array('8',$grade_signed_upArr) ? 'selected' : '' ?> value='8'>Grade 8</option>
                                                         <option <?= in_array('9',$grade_signed_upArr) ? 'selected' : '' ?> value='9'>Grade 9</option>
                                                         <option <?= in_array('10',$grade_signed_upArr) ? 'selected' : '' ?> value='10'>Grade 10</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Student count for selected grades:</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="quantity" value="{{isset($lead_data) ? $lead_data->quantity : ''}}" >
                                        </div>
                                    </div>
<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Purchase Order No.:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="purchase_no" value="{{isset($lead_data) ? $lead_data->purchase_no : ''}}" >
                                        </div>
                                    </div>
<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Date of Application:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="application_date" value="{{isset($lead_data) ? $lead_data->application_date : ''}}" >
                                        </div>
                                    </div>
<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Purchase details:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="purchase_deails" value="{{isset($lead_data) ? $lead_data->purchase_deails : ''}}" >
                                        </div>
                                    </div>
<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Purchase/ Renewal for Number of years :</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="license_period" value="{{isset($lead_data) ? $lead_data->license_period : ''}}" >
                                        </div>
                                    </div>
 <div class="contact-group">
                                    <div class="card-header">
                                            <h5 class="modal-inr-hed">
                                            Lab Details 
                                            </h5>
                                        </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Does your School have any School App/ ERP System?:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm" name="is_app_erp"  >
                                                <option value="" >---Select---</option>
                                                <option {{ (($lead_data->is_app_erp == 'Yes') ? 'selected' : '') }} value="Yes" >Yes</option>
                                                <option {{ (($lead_data->is_app_erp == 'No') ? 'selected' : '') }} value="No" >No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">School IP Address:</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="ip_address" value="{{isset($lead_data) ? $lead_data->ip_address : ''}}" >
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">No. of Labs:</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="labs_count" value="{{isset($lead_data) ? $lead_data->labs_count : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Number of System (laptop/desktop) for ICT program.:</label>
                                        <div class="col-sm-8">
                                            <input type="number" min="0" class="form-control form-control-sm" name="system_count" value="{{isset($lead_data) ? $lead_data->system_count : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Operating systems used in ICT Labs:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="os" value="{{isset($lead_data) ? $lead_data->os : ''}}" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Student system ratio:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="student_system_ratio" value="{{isset($lead_data) ? $lead_data->student_system_ratio : ''}}" >
                                        </div>
                                    </div>
<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Lab teacher ratio:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="lab_teacher_ratio" value="{{isset($lead_data) ? $lead_data->lab_teacher_ratio : ''}}" >
                                        </div>
                                    </div>


            @if(isset($other_contacts) && count($other_contacts) > 0)
            @foreach($other_contacts as $index => $contact)
                <div class="contact-group">
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Full Name :</label>
                            <input type="text" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_name]" value="{{ $contact->eu_name }}" >
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Email :</label>
                            <input type="email" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_email]" value="{{ $contact->eu_email }}" >
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Mobile :</label>
                            <input type="number" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_mobile]" value="{{ $contact->eu_mobile }}" >
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Designation :</label>
                            <input type="text" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_designation]" value="{{ $contact->eu_designation }}" >
                    </div>
                </div>
                    <button type="button" class="btn btn-danger removeContact">Delete</button>
                </div>
                @endforeach
            @endif
                                    <input type="hidden" name="lead_id" id="lead_id" value="{{$lead_id}}">
                                    </div>
                                    </div>
                                    <div class="col-12 text-center">
                                    <button type="button" id="addContact" class="btn btn-secondary ">Add More</button>
                                        <button type="submit" id="clm_submit" class="btn btn-primary">Submit</button>
                                    </div>
    </form>
      </div>

      <script>
 $(document).ready(function () {
    var contactIndex = {{ isset($other_contacts) ? count($other_contacts) : 0 }};

    $('#addContact').click(function () {
        var newContact = `
            <div class="contact-group">
                <div class="row mb-2">
                <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Full Name:</label>
                    
                        <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_name]" value="" >
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Email:</label>
                   
                        <input type="email" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_email]" value="" >
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Mobile:</label>
                    
                        <input type="number" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_mobile]" value="" >
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Designation:</label>
                    
                        <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_designation]" value="" >
                    </div>
                </div>
                <button type="button" class="btn btn-danger removeContact">Delete</button>
            </div>
        `;
        $('#contactContainer').append(newContact);
        contactIndex++; // Increment the contact index
    });

    $(document).on('click', '.removeContact', function () {
        $(this).closest('.contact-group').remove();
    });
});

$(document).ready(function() {
        $('#grade_signed_up').multiselect();
       
    });
      </script>