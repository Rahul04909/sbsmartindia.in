$(document).ready(function () {

    // Open Modal logic - Delegated event for robustness
    $(document).on('click', '#account-link, a[title="Account"]', function (e) {
        e.preventDefault();
        console.log('Account icon clicked'); // Debugging

        // Check login status via AJAX or a global variable set by PHP
        // For now, we rely on the server-side check or a cookie. 
        // But since PHP session variables aren't directly available in JS without echo,
        // we'll check a data attribute or global JS var.

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

    // Switch to Register
    $('#showRegister').click(function (e) {
        e.preventDefault();
        $('#loginFormContainer').hide();
        $('#registerFormContainer').fadeIn();
    });

    // Switch to Login
    $('#showLogin').click(function (e) {
        e.preventDefault();
        $('#registerFormContainer').hide();
        $('#loginFormContainer').fadeIn();
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
                    alert(response.message);
                    location.reload(); // Reload to update header
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Handle Register Submit
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    // Switch to login
                    $('#registerFormContainer').hide();
                    $('#loginFormContainer').fadeIn();
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
