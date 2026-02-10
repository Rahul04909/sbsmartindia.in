$(document).ready(function () {

    // Open Modal logic - Delegated event for robustness
    $(document).on('click', '#account-link, a[title="Account"], #login-btn-trigger', function (e) {
        e.preventDefault();

        // Check login status via global var set by session
        if (typeof isLoggedIn !== 'undefined' && isLoggedIn) {
            if (confirm('Logout from ' + (typeof userName !== 'undefined' ? userName : "Account") + '?')) {
                window.location.href = 'logout.php';
            }
        } else {
            $('#authModal').css('display', 'flex').hide().fadeIn();
        }
    });

    // Close Modal
    $('.close-auth-modal, .auth-modal').click(function (e) {
        if (e.target === this) {
            $('#authModal').fadeOut();
        }
    });

    // Switch to Register (Step 1)
    $('#showRegister').click(function (e) {
        e.preventDefault();
        $('#loginFormContainer').hide();
        $('#registerFormContainer').fadeIn();
        $('#otpFormContainer').hide();
        $('#registrationSuccessContainer').hide();
    });

    // Switch to Login
    $('#showLogin, #goToLogin').click(function (e) {
        e.preventDefault();
        $('#registerFormContainer').hide();
        $('#otpFormContainer').hide();
        $('#registrationSuccessContainer').hide();
        $('#loginFormContainer').fadeIn();
    });

    // Back to Register
    $('#backToRegister').click(function (e) {
        e.preventDefault();
        $('#otpFormContainer').hide();
        $('#registerFormContainer').fadeIn();
    });

    // Step 1: Handle Send OTP
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Sending OTP...');

        // Store form data to pass to Step 2
        var formData = $(this).serializeArray();
        var email = $('#regEmail').val();

        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Populate Hidden Fields in OTP Form
                    $('#otpEmailDisplay').text(email);

                    formData.forEach(function (field) {
                        if (field.name === 'name') $('#otpName').val(field.value);
                        if (field.name === 'email') $('#otpEmail').val(field.value);
                        if (field.name === 'phone') $('#otpPhone').val(field.value);
                        if (field.name === 'password') $('#otpPassword').val(field.value);
                    });

                    // Show OTP Form
                    $('#registerFormContainer').hide();
                    $('#otpFormContainer').fadeIn();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            },
            complete: function () {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Step 2: Handle Verify & Register
    $('#otpForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Verifying...');

        var formData = $(this).serialize();

        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Show Success
                    $('#otpFormContainer').hide();
                    $('#registrationSuccessContainer').fadeIn();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            },
            complete: function () {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Handle Login Submit
    $('#loginForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
