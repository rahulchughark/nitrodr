@extends('layouts.layout')
@section('content')

<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="cs-overlay"></div>
    <div class="search-overlay"></div>

    @include('layouts.nav')

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="middle-content container-xxl p-0">

                <!-- Center Align -->
                <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-header text-center" style="background: #f8f9fa;">
                                <h4 class="mb-0">Change Password</h4>
                            </div>
                            <div class="card-body p-4">

                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form id="changePasswordForm" method="POST" action="{{ route('password.updated') }}">
    @csrf

    <div class="form-group mb-3">
    <label for="current_password">Current Password <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Please enter current password">
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#current_password">
            👁️
        </button>
    </div>
    <small id="currentPassError" class="text-danger"></small>
</div>

<div class="form-group mb-3">
    <label for="new_password">New Password <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="password" id="new_password" name="new_password" class="form-control" required placeholder="Please enter new password">
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password">
            👁️
        </button>
    </div>
    <small id="newPassError" class="text-danger"></small>
</div>

<div class="form-group mb-3">
    <label for="new_password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required placeholder="Please enter confirm password">
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password_confirmation">
            👁️
        </button>
    </div>
    <small id="confirmPassError" class="text-danger"></small>
</div>


    <button type="submit" class="btn w-100" style="background-color:#5bbfc6; color:#fff;">
        Update Password
    </button>
</form>


                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                <p class="text-muted">COPYRIGHT © – 2024 ICT360 CLM, All Rights Reserved.</p>
            </div>
        </div>
        <!--  END FOOTER  -->

    </div>
    <!--  END CONTENT AREA  -->
</div>

@endsection




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // ✅ Current password live check
    $("#current_password").on("blur", function () {
        let currentPassword = $(this).val();
        if (currentPassword.length > 0) {
            $.ajax({
                url: "{{ route('check.current.password') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    current_password: currentPassword
                },
                success: function (response) {
                    if (!response.valid) {
                        $("#currentPassError").text("❌ Current password is incorrect");
                    } else {
                        $("#currentPassError").text("");
                    }
                }
            });
        }
    });

    // ✅ Form Submit Validation
    $("#changePasswordForm").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let btn = form.find("button[type=submit]");
        let newPass = $("#new_password").val();
        let confirmPass = $("#new_password_confirmation").val();
        let valid = true;

        // Reset errors
        $("#newPassError, #confirmPassError").text("");

        // Password Strength Regex
        let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/;

        // Check new password strength
        if (!passwordRegex.test(newPass)) {
            $("#newPassError").text("Password must contain 8-15 chars, at least 1 uppercase, 1 lowercase, 1 number, 1 special char.");
            valid = false;
        }

        // Check confirm password
        if (newPass !== confirmPass) {
            $("#confirmPassError").text("Confirm password does not match.");
            valid = false;
        }

        // Agar validation fail hai
        if (!valid || $("#currentPassError").text() !== "") {
            return;
        }

        // ✅ Button ko "Updating..." banado
        btn.prop("disabled", true).text("Updating...");

        // ✅ Ab password update ke liye AJAX submit
        $.ajax({
            url: form.attr("action"),
            method: "POST",
            data: form.serialize(),
            success: function () {
                // Success message dikhaye
                $(".alert").remove();
                form.prepend('<div class="alert alert-success">✅ Password changed successfully! Logging out...</div>');

                // 2 sec baad logout
                setTimeout(function () {
                    $.ajax({
                        url: "{{ route('logout') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            window.location.href = "login";
                        }
                    });
                }, 1000);
            },
            error: function () {
                form.prepend('<div class="alert alert-danger">Something went wrong. Try again.</div>');
                btn.prop("disabled", false).text("Update Password");
            }
        });

    });

});
</script>


<script>
$(document).on("click", ".toggle-password", function () {
    let input = $($(this).data("target"));
    let type = input.attr("type") === "password" ? "text" : "password";
    input.attr("type", type);

    // Icon change kare (👁️ se 🙈)
    $(this).text(type === "password" ? "👁️" : "🙈");
});
</script>

