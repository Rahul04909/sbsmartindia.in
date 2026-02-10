<!-- Auth Modal -->
<div id="authModal" class="auth-modal">
    <div class="auth-modal-content">
        <button class="close-auth-modal">&times;</button>
        
        <div class="auth-split-layout">
            <!-- Left Side: Image -->
            <div class="auth-image-side">
                <div class="auth-overlay">
                    <h2>SB Smart</h2>
                    <p>Industrial & Electrical Solutions</p>
                </div>
            </div>

            <!-- Right Side: Forms -->
            <div class="auth-form-side">
                
                <!-- Login Form -->
                <div id="loginFormContainer">
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="auth-subtitle">Login to access your account</p>
                    
                    <form id="loginForm" class="auth-form">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" name="email" placeholder="name@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" placeholder="Enter password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth-submit">Login</button>
                    </form>
                    
                    <div class="auth-switch">
                        Don't have an account? <a href="#" id="showRegister">Register Now</a>
                    </div>
                </div>

                <!-- Register Form -->
                <div id="registerFormContainer" style="display: none;">
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Join us for exclusive B2B pricing</p>
                    
                    <form id="registerForm" class="auth-form">
                        <input type="hidden" name="action" value="send_otp">
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" name="name" placeholder="Your Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" name="email" id="regEmail" placeholder="name@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-phone"></i>
                                <input type="tel" name="phone" placeholder="Your Phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" placeholder="Create password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth-submit">Send OTP</button>
                    </form>
                    
                    <div class="auth-switch">
                        Already have an account? <a href="#" id="showLogin">Login Here</a>
                    </div>
                </div>

                <!-- OTP Form -->
                <div id="otpFormContainer" style="display: none;">
                    <h2 class="auth-title">Verify Email</h2>
                    <p class="auth-subtitle">Enter the OTP sent to <span id="otpEmailDisplay" style="font-weight: 600;"></span></p>
                    
                    <form id="otpForm" class="auth-form">
                        <input type="hidden" name="action" value="verify_register">
                        <!-- Hidden fields to store registration data -->
                        <input type="hidden" name="name" id="otpName">
                        <input type="hidden" name="email" id="otpEmail">
                        <input type="hidden" name="phone" id="otpPhone">
                        <input type="hidden" name="password" id="otpPassword">

                        <div class="form-group">
                            <label>One Time Password (OTP)</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-key"></i>
                                <input type="text" name="otp" placeholder="Enter 6-digit OTP" required maxlength="6" pattern="\d{6}">
                            </div>
                        </div>
                        <button type="submit" class="btn-auth-submit">Verify & Register</button>
                    </form>
                    
                    <div class="auth-switch">
                        <a href="#" id="backToRegister">Back to Registration</a>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="registrationSuccessContainer" style="display: none; text-align: center; padding-top: 40px;">
                    <div style="font-size: 50px; color: #28a745; margin-bottom: 20px;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h2 class="auth-title">Welcome Aboard!</h2>
                    <p class="auth-subtitle">Your account has been successfully created.</p>
                    <button class="btn-auth-submit" id="goToLogin">Login Now</button>
                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="assets/css/auth-modal.css">
