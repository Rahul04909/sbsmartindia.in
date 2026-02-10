$(document).ready(function() {
    
    // Open Modal logic will be handled by header click event
    
    // Close Modal
    $('.close-auth-modal, .auth-modal').click(function(e) {
        if(e.target === this) {
            $('#authModal').fadeOut();
        }
    });

    // Switch to Register
    $('#showRegister').click(function(e) {
        e.preventDefault();
        $('#loginFormContainer').hide();
        $('#registerFormContainer').fadeIn();
    });

    // Switch to Login
    $('#showLogin').click(function(e) {
        e.preventDefault();
        $('#registerFormContainer').hide();
        $('#loginFormContainer').fadeIn();
    });

    // Handle Login Submit
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    location.reload(); // Reload to update header
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Handle Register Submit
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'auth_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    // Switch to login
                    $('#registerFormContainer').hide();
                    $('#loginFormContainer').fadeIn();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
