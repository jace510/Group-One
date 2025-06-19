<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal</title>
</head>
<body>
    <!-- Login Modal -->
  <div id="loginModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
      <button class="close-btn" onclick="closeModal('loginModal')">
        &times;
      </button>
      <div class="popup-form-section">
        <h1>Welcome Back!</h1>
        <p class="slogan">Please enter your details to log in.</p>
        
        <!-- Corrected: Using a relative path for the form action -->
        <form action="../backend/auth/login.php" method="post">
          <div class="form-row">
            <label for="login-email">Email:</label>
            <input type="email" id="login-email" name="email" class="custom-input" required />
          </div>
          <div class="form-row">
            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="password" class="custom-input" required />
          </div>
          <a href="#" class="forgot-password">Forgot Password?</a>
          <button type="submit" class="custom-btn">Login</button>
        </form>

        <div class="form-footer">
          <p>
            Don't have an account?
            <a onclick="switchModal('loginModal', 'registerModal')">Register here</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div id="registerModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
      <button class="close-btn" onclick="closeModal('registerModal')">
        &times;
      </button>
      <div class="popup-form-section">
        <h1>Create Your Account</h1>
        <p class="slogan">Join our community and start trading.</p>

        <!-- Corrected: Using a relative path for the form action -->
        <form action="../backend/auth/register.php" method="post">
          
          <div class="form-row">
            <label for="register-username">Username:</label>
            <input type="text" id="register-username" name="username" class="custom-input" required />

          <div class="form-row">
            <label for="register-email">Email:</label>
            <input type="email" id="register-email" name="email" class="custom-input" required />
          </div>

          <div class="input-group">
            <div class="form-row">
              <label for="register-password">Password:</label>
              <input type="password" name="password" id="register-password" class="custom-input" required />
            </div>
            <div class="form-row">
              <label for="register-password-confirm">Confirm Password:</label>
              <input type="password" name="password_confirm" id="register-password-confirm" class="custom-input"
                required />
            </div>
          </div>

          <label class="custom-checkbox">
            <input type="checkbox" name="terms" required />
            I have read, understood and accept the terms and conditions.
          </label>

          <button type="submit" class="custom-btn">Register</button>
        </form>

        <div class="form-footer">
          <p>
            Already have an account?
            <a onclick="switchModal('registerModal', 'loginModal')">Login here</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <script src="main.js"></script>

  <script>
  window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
      modal.classList.remove('active');
      modal.style.display = 'none';
    });
    document.body.style.overflow = 'auto';
  });
  function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'flex';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }
}
</script>
    
</body>
</html>