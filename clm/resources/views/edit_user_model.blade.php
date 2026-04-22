<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$user->name}}</h5>
        <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

<div class="modal-body">
       <form class="row g-3 my-3" id="clmModelForm" method="POST"  action="{{ route('update_user')}}">

       @csrf
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Name :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Name" value="{{$user->name}}" name="name">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">E-Mail Address :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Email" value="{{$user->email}}" disabled>
                                        </div>
                                    </div>
									
									<div class="row mb-3">
                                        <label for="password" class="col-sm-4 col-form-label col-form-label-sm">{{ __('Password') }}</label>

                                        <div class="col-md-8">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div id="password-error" class="text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password-confirm" class="col-sm-4 col-form-label col-form-label-sm">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-8">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                            <div id="confirm-password-error" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Contact :</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control form-control-sm" placeholder="Contact" value="{{$user->mobile}}" name="mobile">
                                        </div>
                                    </div>

									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">User Type :</label>
                                        <div class="col-sm-8">
                                        <select id="user_type" class="form-control form-select" name="user_type" required >
                                            <option value="">Select option</option>
                                            <option {{$user->user_type == 'ADMIN' ? 'selected' : ''}} value="ADMIN">ADMIN</option>
                                            <option {{$user->user_type == 'FACULTY' ? 'selected' : ''}} value="FACULTY">FACULTY</option>
                                            <option {{$user->user_type == 'HELPDESK' ? 'selected' : ''}} value="HELPDESK">HELPDESK</option>
                                            <option {{$user->user_type == 'SALES' ? 'selected' : ''}} value="SALES">SALES</option>
                                        </select>
                                        </div>
                                    </div>
                                                                   
                                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">          

    <div class="col-12 text-center">
        <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
    </div>
    </form>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        document.getElementById('password').addEventListener('keyup', function () {
            var password = document.getElementById('password').value;
            var submitButton = document.getElementById('submit-button');
            var confirmPassword = document.getElementById('password-confirm').value;
            if(password != ''){
                if(password != confirmPassword){
                    submitButton.disabled = true;
                }else{
                    submitButton.disabled = false;
                }
            }else{
                if(confirmPassword != ''){
                    submitButton.disabled = true;
                }else{
                    submitButton.disabled = false;
                }
            }
        });
document.getElementById('password-confirm').addEventListener('keyup', function () {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('password-confirm').value;
        var passwordError = document.getElementById('password-error');
        var confirmPasswordError = document.getElementById('confirm-password-error');
        var submitButton = document.getElementById('submit-button');
        // Clear the previous error message
        passwordError.innerText = '';
        confirmPasswordError.innerText = '';

        // Check if passwords match
        if (password !== confirmPassword) {
            confirmPasswordError.innerText = 'Password do not match.';
            submitButton.disabled = true;
        } else {
            confirmPasswordError.innerText = '';
            submitButton.disabled = false;
        }
    });
</script>
