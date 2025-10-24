<!DOCTYPE html>
<html lang="en">

<?php include 'pages/header.php'; ?>


<body>
    <div id="auth" class="bg-img">
        <div class="card">
            <div class="d-flex align-items-center mb-4">
                <img src="assets/images/vexpress.png" alt="Logo" style="height: 40px;">
                <h2 class="mb-0">- Sign In</h2>
            </div>
            <form method="post">
                <div class="mb-3" style="margin-top: 8px">
                    <label for="email" class="form-label"><b>Email Address</b></label>
                    <input type="text" placeholder="Enter Email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><b>Password</b></label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="button" onclick="login(this.form)" class="btn btn-primary">Sign In</button>
                </div>
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="register.php" class="text-primary fw-bold">Sign Up</a></p>
                    <p>or go back to the <a href="../index.php" class="text-primary fw-bold">Home</a></p>
                </div>
            </form>
        </div>
    </div>
</body>

<style>
  body {
    background-color: #fff; /* fallback for non-supported browsers */
    background-image: url('assets/images/Background.jpg');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }
  
  .card {
    width: 100%;
    max-width: 800px; /* Adjust max-width as needed */
    height: auto; /* Let the height be determined by content */
    max-height: calc(100vh - 40px); /* Ensure it doesn't exceed the viewport height minus some padding */
    border-radius: 10px;
    background-color: #ffffff;
    margin-top: 40px;
    padding-top: 40px;
    padding-bottom: 20px;
    padding-left: 90px; /* Increase padding to make the card bigger */
    padding-right: 90px;
    box-shadow: 2px 5px 20px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Add scrollbar if content exceeds height */
  }

  .form-label {
    font-weight: bold;
    color: rgb(170, 166, 166);
  }

  .form-control {
    padding: 15px 20px;
    margin-top: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
  }

  .btn-primary {
    background-color: rgb(69, 69, 185);
    color: white;
    padding: 18px 20px;
    margin-top: 10px;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 10px;
    border: none;
  }

  .text-primary {
    color: rgb(69, 69, 185);
  }

  .fw-bold {
    font-weight: bold;
  }

  @media screen and (max-width: 768px) {
    .card {
      max-width: 90%; /* Adjust width for smaller screens */
      padding: 40px;
      margin-left: 15px;
    }
  }
</style>

</html>
