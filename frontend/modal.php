
<!-- Login Modal -->
<div id="loginModal" class="modal-overlay">
  <div class="modal-container">
    <button class="close-btn" onclick="closeModal('loginModal')">
      &times;
    </button>
    <div class="popup-form-section">
      <h1>Welcome Back!</h1>
      <p class="slogan">Please enter your details to log in.</p>

      <!-- User Type Selector -->
      <div class="user-type-selector">
        <button type="button" class="user-type-btn active" onclick="switchUserType('customer')">
          Customer
        </button>
        <button type="button" class="user-type-btn" onclick="switchUserType('admin')">
          Admin
        </button>
      </div>

      <!-- Admin Indicator (hidden by default) -->
      <div id="adminIndicator" class="admin-indicator" style="display: none;">
        🔒 Admin Login Portal
      </div>

      <!-- Login Form -->
      <form id="loginForm" action="/GROUP-ONE/backend/auth/login.php" method="post">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectTo ?? 'home.php'); ?>">
        <input type="hidden" id="userType" name="user_type" value="customer">

        <div class="form-row">
          <label for="login-email">Email:</label>
          <input type="email" id="login-email" name="email" class="custom-input" required />
        </div>
        <div class="form-row">
          <label for="login-password">Password:</label>
          <input type="password" id="login-password" name="password" class="custom-input" required />
        </div>
        <a href="#" class="forgot-password">Forgot Password?</a>
        <button type="submit" class="custom-btn" id="loginSubmitBtn">Login as Customer</button>
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
<div id="registerModal" class="modal-overlay">
  <div class="modal-container">
    <button class="close-btn" onclick="closeModal('registerModal')">
      &times;
    </button>
    <div class="popup-form-section">
      <h1>Create Your Account</h1>
      <p class="slogan">Join our community and start trading.</p>

      <!-- User Type Selector for Registration -->
      <div class="user-type-selector">
        <button type="button" class="user-type-btn active" onclick="switchRegisterUserType('customer')">
          Customer
        </button>
        <button type="button" class="user-type-btn" onclick="switchRegisterUserType('admin')">
          Admin
        </button>
      </div>

      <!-- Admin Indicator for Registration -->
      <div id="registerAdminIndicator" class="admin-indicator" style="display: none;">
        🔒 Admin Registration Portal
      </div>

      <form id="registerForm" action="/GROUP-ONE/backend/auth/register.php" method="post">
        <input type="hidden" id="registerUserType" name="user_type" value="customer">

        <div class="form-row">
          <label for="register-username">Username:</label>
          <input type="text" id="register-username" name="username" class="custom-input" required />
        </div>

        <div class="form-row">
          <label for="register-email">Email:</label>
          <input type="email" id="register-email" name="email" class="custom-input" required />
        </div>

        <div class="form-row">
          <label for="register-password">Password:</label>
          <input type="password" name="password" id="register-password" class="custom-input" required />
        </div>

        <div class="form-row">
          <label for="register-password-confirm">Confirm Password:</label>
          <input type="password" name="password_confirm" id="register-password-confirm" class="custom-input" required />
        </div>

        <label class="custom-checkbox">
          <input type="checkbox" name="terms" required />
          I have read, understood and accept the terms and conditions.
        </label>

        <button type="submit" class="custom-btn" id="registerSubmitBtn">Register as Customer</button>
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

<script>
  // Modal management functions
  function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
  }

  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
  }

  function switchModal(fromModal, toModal) {
    closeModal(fromModal);
    openModal(toModal);
  }

  // User type switching for login
  function switchUserType(type) {
    const customerBtn = document.querySelector('#loginModal .user-type-btn:first-child');
    const adminBtn = document.querySelector('#loginModal .user-type-btn:last-child');
    const adminIndicator = document.getElementById('adminIndicator');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const userTypeInput = document.getElementById('userType');

    // Update button states
    if (type === 'customer') {
      customerBtn.classList.add('active');
      adminBtn.classList.remove('active');
      adminIndicator.style.display = 'none';
      submitBtn.textContent = 'Login as Customer';
      userTypeInput.value = 'customer';
    } else {
      adminBtn.classList.add('active');
      customerBtn.classList.remove('active');
      adminIndicator.style.display = 'block';
      submitBtn.textContent = 'Login as Admin';
      userTypeInput.value = 'admin';
    }
  }

  // User type switching for registration
  function switchRegisterUserType(type) {
    const customerBtn = document.querySelector('#registerModal .user-type-btn:first-child');
    const adminBtn = document.querySelector('#registerModal .user-type-btn:last-child');
    const adminIndicator = document.getElementById('registerAdminIndicator');
    const submitBtn = document.getElementById('registerSubmitBtn');
    const userTypeInput = document.getElementById('registerUserType');

    // Update button states
    if (type === 'customer') {
      customerBtn.classList.add('active');
      adminBtn.classList.remove('active');
      adminIndicator.style.display = 'none';
      submitBtn.textContent = 'Register as Customer';
      userTypeInput.value = 'customer';
    } else {
      adminBtn.classList.add('active');
      customerBtn.classList.remove('active');
      adminIndicator.style.display = 'block';
      submitBtn.textContent = 'Register as Admin';
      userTypeInput.value = 'admin';
    }
  }

  // Close modal when clicking outside
  window.onclick = function (event) {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  }

  // Handle form submission (you can customize this)
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    // You can add additional validation or processing here
    console.log('Login form submitted as:', document.getElementById('userType').value);
  });

  document.getElementById('registerForm').addEventListener('submit', function (e) {
    // You can add additional validation or processing here
    console.log('Register form submitted as:', document.getElementById('registerUserType').value);
  });
</script>