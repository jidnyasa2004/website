<?php
// Include database connection file
include 'connection.php';

// Initialize error messages
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $usrname = trim($_POST['usrname']);
  $passwordd = $_POST['passwordd'];
  $user_type = "Customer"; // Default user type

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } else {
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM credential WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $error_message = "Email is already registered.";
    }
    $stmt->close();
  }

  // Validate username (only letters and numbers, at least 3 characters)
  if (!preg_match("/^[a-zA-Z0-9]{3,}$/", $usrname)) {
    $error_message = "Username must be at least 3 characters long and contain only letters and numbers.";
  }

  // Validate password (minimum 8 characters, at least one uppercase, one lowercase, one digit, one special character)
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordd)) {
    $error_message = "Password must be at least 8 characters long and include one uppercase letter, one lowercase letter, one number, and one special character.";
  }

  // If no errors, insert into the database
  if (empty($error_message)) {
    // Hash password before storing (for better security)
    //$hashed_password = password_hash($passwordd, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO credential (email, username, password, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $usrname, $passwordd, $user_type);

    if ($stmt->execute()) {
      $success_message = "Registration successful! Redirecting to login page...";
      echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                  </script>";
    } else {
      $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Medical Store</title>

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #1E3A8A, #2563EB);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
    }

    .register-container {
      background: white;
      color: black;
      padding: 2rem;
      border-radius: 10px;
      width: 350px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .floating-placeholder {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .floating-placeholder input {
      border: 2px solid #1E3A8A;
      padding: 12px;
      border-radius: 6px;
      width: 100%;
      font-size: 16px;
      outline: none;
    }

    .floating-placeholder label {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      background: white;
      padding: 0 5px;
      font-size: 14px;
      color: #1E3A8A;
      transition: 0.3s;
    }

    .floating-placeholder input:focus+label,
    .floating-placeholder input:not(:placeholder-shown)+label {
      top: -8px;
      font-size: 12px;
      color: #2563EB;
    }

    .password-container {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #2563EB;
    }

    .register-btn {
      width: 100%;
      background: linear-gradient(to right, #2563EB, #1E3A8A);
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    .register-btn:hover {
      background: #1E3A8A;
    }

    .links {
      margin-top: 1rem;
    }

    .links a {
      color: #2563EB;
      text-decoration: none;
      font-weight: bold;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .message {
      margin-top: 1rem;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="register-container">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Register</h2>
    <form method="POST" action="" onsubmit="return validateForm()">

      <!-- Email Input -->
      <div class="floating-placeholder">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
      </div>

      <!-- Username Input -->
      <div class="floating-placeholder">
        <input type="text" name="usrname" id="usrname" placeholder=" " required>
        <label for="usrname">Username</label>
      </div>

      <!-- Password Input -->
      <div class="floating-placeholder password-container">
        <input type="password" name="passwordd" id="passwordd" placeholder=" " required>
        <label for="passwordd">Password</label>
        <i class="fa-solid fa-eye toggle-password" id="togglePassword" onclick="togglePassword()"></i>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="register-btn">Register</button>

      <!-- Links -->
      <div class="links">
        <a href="login.php">Already have an account? Sign In</a>
      </div>
    </form>
  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById('passwordd');
      const toggleIcon = document.getElementById('togglePassword');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    <?php if (!empty($success_message)): ?>
      Swal.fire("Success!", "<?= $success_message ?>", "success");
    <?php elseif (!empty($error_message)): ?>
      Swal.fire("Error!", "<?= $error_message ?>", "error");
    <?php endif; ?>
  </script>

</body>

</html>