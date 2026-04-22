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
<input type="hidden" name="lead_id" value="{{$lead_id}}">
<input type="hidden" name="fortab" value="{{$tabPageValue}}">
       @csrf
 <input type="hidden" name="pagecheck" value="editactivity">
 <input type="hidden" name="id" value="{{$activityID}}">
       <div class="row mb-3">
        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Follow-up Solution<span class="text-danger">*</span> :</label>
        <div class="col-sm-8">
            <textarea type="text" cols="6" rows="6" class="form-control form-control-sm required" id="subject" name="follow_up_solution" placeholder="Please enter Follow-up Solution"></textarea>
            <div class="text-danger error-message" style="display: none;"></div>
        </div>
    </div>        

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
