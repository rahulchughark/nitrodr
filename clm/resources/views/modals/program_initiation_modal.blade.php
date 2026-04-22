@if(empty($programInitiationDate))
<div class="modal fade" id="openProgram" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="openProgramLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('program.initiation.save') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead_id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title">Enter Program Initiation Date</h5>
                    <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                
                <div class="modal-body">
                    <input type="date" name="program_initiation_date" class="form-control" required>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Save Date</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
