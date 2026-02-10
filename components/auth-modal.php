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
                        <input type="hidden" name="action" value="register">
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
                                <input type="email" name="email" placeholder="name@example.com" required>
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
                        <button type="submit" class="btn-auth-submit">Register</button>
                    </form>
                    
                    <div class="auth-switch">
                        Already have an account? <a href="#" id="showLogin">Login Here</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="assets/css/auth-modal.css">
