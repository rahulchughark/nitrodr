
<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$user->name}}</h5>
        <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

<div class="modal-body">
       <form class="row g-3" id="clmModelForm" method="POST"  action="{{ route('transfer_user_data')}}">

       @csrf
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Transfer Data To:</label>
                                        <div class="col-sm-8">
                                        <select id="user_type" class="form-control form-select" name="user" required >
                                            <option value="">Select option</option>
                                            @foreach($faculty_list as $faculty)
                                                <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                                                   
                                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">          

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
      </div>