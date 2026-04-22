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
.error-message {
        display: none;
        color: red;
        font-size: 0.75rem;
    }
.modal-dialog-scrollable .modal-content {
    overflow: visible;
}
</style>



<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
        <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

<div class="modal-body">
       <form class="g-3" id="clmModelForm" method="POST"  action="{{ route('activity_save')}}">

       @csrf
        @if($lead_id==0)
            <div class="row mb-3">
                <label for="lead_id" class="col-sm-4 col-form-label col-form-label-sm">School<span class="text-danger">*</span> :</label>
                <div class="col-sm-8">
                    <select class="form-control form-control-sm required" id="lead_id" name="lead_id" placeholder="Please select school">
                        <option value="">Please Select School</option>
                    @foreach($getSchools as $school)
                        <option value="{{$school->id}}">{{$school->school_name}}</option>
                    @endforeach
                    </select>
                    <div class="text-danger error-message" style="display: none;"></div>
                </div>
            </div>
        @else
            <input type="hidden" name="lead_id" value="<?= $lead_id ?>">  
        @endif
       <div class="row mb-3">
        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Subject<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <input type="text" class="form-control form-control-sm required" id="subject" name="subject" placeholder="Please enter subject">
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">POC Name<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <input type="text" class="form-control form-control-sm required" id="poc-name" name="poc_name" placeholder="Please enter poc name">
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>


    <div class="row mb-3">
        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">POC Designation<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <input type="text" class="form-control form-control-sm required" id="poc_designation" name="poc_designation" placeholder="Please enter poc designation">
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Solution Provided<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <input type="text" class="form-control form-control-sm required" id="solution_provided" name="solution_provided" placeholder="Please enter solution provided">
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Solution Provided Date :</label>
        <div class="col-sm-8">
            <input type="date" class="form-control form-control-sm" id="solution_provided_date" name="solution_provided_date">
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Follow-up status<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <select  class="form-select required" id="follow_up_status" name="follow_up_status" onchange="showhide(this.value)">
                <option value="">---Select Status---</option>
                <option value="1">YES</option>
                <option value="0">NO</option>
            </select>
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>
                                    <div class="row mb-3" style="display: none;" id="follow-up-date-field">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Follow-up Date<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm required" id="follow_up_date" name="follow_up_date">
                                            <div class="text-danger error-message" style="display: none;"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" style="display: none;" id="follow-up-reason-field">
                                        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Follow-up reason<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm required" id="follow_up_reason" name="follow_up_reason" placeholder="Please enter follow-up reason">
                                            <div class="text-danger error-message" style="display: none;"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Remark:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="remark" name="remark" placeholder="Please enter remark">
                                            <div class="text-danger error-message" style="display: none;"></div>
                                        </div>
                                    </div>       

                                    <input type="hidden" name="fortab" value="{{$tabPageValue}}">

    <div class="col-12 text-center">
        <button type="submit" id="clm_submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
      </div>


 <script>
     $(document).ready(function () {
        $("#clmModelForm").on("submit", function (e) {
            let isValid = true;
            $(".required, .follow-up-required:visible").each(function () {
                const value = $(this).val().trim();
                const errorMessage = "This field is required.";
                if (value === "") {
                    isValid = false;
                    $(this).siblings(".error-message").text(errorMessage).show();
                } else {
                    $(this).siblings(".error-message").hide();
                }
            });
            if (!isValid) {
                e.preventDefault();
            }
        });
        $(".required, .follow-up-required").on("input change", function () {
            if ($(this).val().trim() !== "") {
                $(this).siblings(".error-message").hide();
            }
        });
    });

    function showhide(statusType) {
        if (statusType ==1) {
            $("#follow-up-date-field, #follow-up-reason-field").show();
            $(".required").addClass("required");
        } else if (statusType == 0) {
            $("#follow-up-date-field, #follow-up-reason-field").hide();
            $(".required").removeClass("required");
            $(".required").siblings(".error-message").hide();
        }
    }
 </script>
 <script>
            $(document).ready(function() {
                $('#lead_id').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });
</script>