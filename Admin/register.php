<!DOCTYPE html>
<html lang="en">

<?php
/*
* admin/register.php
*
* Public-facing page for students to create a new account.
* This file is in the /admin/ folder but acts as a public page.
*/
include 'pages/header.php';
?>

<body>
    <div id="auth" class="bg-img">
        <div class="card">
            <div class="heading-wrapper">
                <!-- Changed logo and title -->
                <img src="assets/images/logo.png" alt="Logo" style="height: 40px;">
                <h2 class="mb-0">- Student Sign Up</h2>
            </div>
            
            <!-- This form ID is used by add.js -> addStudent -->
            <form id="basicform" method="post">
                <div class="mb-3" style="margin-top: 75px">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required>
                </div>

                <!-- Added Student ID Number -->
                <div class="mb-3">
                    <label for="student_id_number" class="form-label">Student ID Number</label>
                    <input type="text" class="form-control" name="student_id_number" id="student_id_number" placeholder="e.g., STU2024001" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp" placeholder="e.g., 0123456789" required>
                </div>

                <!-- Added Room Number -->
                <div class="mb-3">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="text" class="form-control" name="room_number" id="room_number" placeholder="e.g., A-101" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address (Permanent)</label>
                    <textarea class="form-control address-field" name="address" id="address" aria-describedby="addressHelp" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" name="gender" id="gender" aria-label="Default select example" required>
                        <option value="1" selected>Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="conf_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="conf_password" id="conf_password" required>
                </div>
                <div class="d-grid">
                    <!-- Changed JS function call to addStudent (from add.js) -->
                    <button class="btn btn-primary" onclick="addStudent(this.form)" type="button">Sign Up</button>
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
        background-color: #fff;
        /* fallback for non-supported browsers */
        /* Changed background image to a more appropriate placeholder */
        background-image: url('assets/images/college_background.jpg');
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
        width: 100%; /* Use 100% width with max-width */
        max-width: 800px;
        /* Adjust max-width to ensure card is not too large */
        height: auto;
        /* Let the height be determined by content */
        max-height: calc(100vh - 40px); /* Adjusted max-height */
        margin-top: 40px;
        margin-bottom: 40px; /* Added margin-bottom */
        padding: 40px 60px; /* Adjusted padding */
        border-radius: 10px;
        background-color: #ffffff;
        box-shadow: 2px 5px 20px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        /* Add scrollbar if content exceeds height */
    }

    .heading-wrapper {
        display: flex;
        align-items: center;
        justify-content: center; /* Center horizontally */
        margin-bottom: 2rem; /* Add space below header */
        margin-top: 1rem;
    }

    .heading-wrapper img {
        height: 40px;
        margin-right: 10px; /* Add space between logo and text */
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
        min-height: 80px; /* Slightly reduced height */
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
            max-width: 90%;
            /* Adjust width for smaller screens */
            padding: 25px 30px; /* Reduce padding on mobile */
            margin-left: auto; /* Center on mobile */
            margin-right: auto;
        }

        .heading-wrapper {
            flex-direction: column; /* Stack logo and text */
            margin-bottom: 1.5rem;
        }
         .heading-wrapper img {
             margin-right: 0;
             margin-bottom: 10px;
         }
    }
</style>

</html>
