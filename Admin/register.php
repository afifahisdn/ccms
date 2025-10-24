<!DOCTYPE html>
<html lang="en">

<?php include 'pages/header.php'; ?>

<body>
    <div id="auth" class="bg-img">
        <div class="card">
            <div class="heading-wrapper">
                <img src="assets/images/vexpress.png" alt="Logo" style="height: 40px;" style="width: 200px; height: 55px";>
                <h2 class="mb-0">- Sign Up</h2>
            </div>
            <form>
                <div class="mb-3" style="margin-top: 75px">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control address-field" name="address" id="address" aria-describedby="addressHelp"></textarea>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" name="gender" id="gender" aria-label="Default select example">
                        <option value="1" selected>Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-3">
                    <label for="conf_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="conf_password" id="conf_password">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" onclick="addCustomer(this.form)" type="button">Sign Up</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="login.php" class="text-primary fw-bold">Sign In</a></p>
            </div>
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
    width: 150%; /* Set width to 150% */
    max-width: 800px; /* Adjust max-width to ensure card is not too large */
    transform: translateX(-15%); /* Translate card to center */
    height: auto; /* Let the height be determined by content */
    max-height: calc(100vh - 80px); /* Ensure it doesn't exceed the viewport height minus some padding */
    margin-top: 40px;
    padding-top: 40px;
    padding-bottom: 20px;
    padding-left: 90px; /* Increase padding to make the card bigger */
    padding-right: 90px;
    border-radius: 10px;
    background-color: #ffffff;
    padding: 40px; /* Increase padding to make the card bigger */
    box-shadow: 2px 5px 20px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Add scrollbar if content exceeds height */
  }

    .heading-wrapper {
        display: flex;
        align-items: center;
        position: absolute; /* Position relative to the card */
        top: 0; /* Align to the top */
        left: 50%; /* Center horizontally */
        margin-top: 35px;
        transform: translateX(-50%); /* Adjust for half of the heading's width */
    }

    .heading-wrapper img {
        height: 40px;
    }

    .heading-wrapper h2 {
        font-weight: bold;
        margin: 0;
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

  .address-field {
    min-height: 100px; /* Increase height for address field */
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
      transform: translateX(0); /* Reset translation for small screens */
      padding: 40px;
      margin-left: 17px;
    }
    .heading-wrapper {
        transform: translateX(0);
        left: 17%; /* Center horizontally */
    }

  }
</style>

</html>
