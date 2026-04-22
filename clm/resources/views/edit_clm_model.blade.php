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
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Full Name<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_name" value="{{isset($lead_data) ? $lead_data->eu_name : ''}}" required>
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">Email<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" name="eu_email" value="{{isset($lead_data) ? $lead_data->eu_email : ''}}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Landline Number :</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="eu_landline" value="{{isset($lead_data) ? $lead_data->eu_landline : ''}}">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Mobile<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" name="eu_mobile" value="{{isset($lead_data) ? $lead_data->eu_mobile : ''}}" required>
                                        </div>
                                    </div>
    								   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Designation<span class="text-danger">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="eu_designation" value="{{isset($lead_data) ? $lead_data->eu_designation : ''}}" required>
                                        </div>
                                    </div>
            @if(isset($other_contacts) && count($other_contacts) > 0)
            @foreach($other_contacts as $index => $contact)
                <div class="contact-group">
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Full Name :</label>
                            <input type="text" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_name]" value="{{ $contact->eu_name }}" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Email :</label>
                            <input type="email" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_email]" value="{{ $contact->eu_email }}" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Mobile :</label>
                            <input type="number" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_mobile]" value="{{ $contact->eu_mobile }}" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label col-form-label-sm">Designation :</label>
                            <input type="text" class="form-control form-control-sm" name="contacts[{{ $index }}][eu_designation]" value="{{ $contact->eu_designation }}" required>
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
                    <label class=" col-form-label col-form-label-sm">Full Name<span class="text-danger">*</span> :</label>
                    
                        <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_name]" value="" required>
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Email<span class="text-danger">*</span> :</label>
                   
                        <input type="email" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_email]" value="" required>
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Mobile<span class="text-danger">*</span> :</label>
                    
                        <input type="number" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_mobile]" value="" required>
                    </div>
                    <div class="col-sm-3">
                    <label class=" col-form-label col-form-label-sm">Designation<span class="text-danger">*</span> :</label>
                    
                        <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][eu_designation]" value="" required>
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
      </script>