/*
 * homejs.js
 *
 * Contains all JavaScript functions for the STUDENT frontend pages
 * (index.php, profile.php, submit_complaint.php, etc.)
 */

/**
 * Adds a new complaint from the student form (submit_complaint.php).
 * Handles file upload via FormData.
 */
function addComplaint(formElement) {
    let formData = new FormData(formElement);

    // Basic validation
    if (formData.get("complaint_title").trim() === "" ||
        formData.get("dormitory_id").trim() === "" ||
        formData.get("room_number").trim() === "" ||
        formData.get("complaint_category").trim() === "") {
        errorMessage("Please fill out all required fields marked with *.");
        return;
    }

    // Optional: Add client-side file size validation
    var fileInput = document.getElementById('photo');
    if (fileInput && fileInput.files.length > 0) {
        var fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 5) { // 5MB limit
            errorMessage("File size exceeds the limit of 5MB.");
            return;
        }
    }

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=addComplaint",
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false, // Important for file uploads
        processData: false, // Important for file uploads
        success: function(response) {
            console.log("Add Complaint Response:", response);
            if (response && response.success) {
                successToastRedirect("complaints.php", "Complaint submitted successfully!"); // Redirect to 'my complaints' page
            } else {
                errorMessage(response.error || "Failed to submit complaint. Please try again.");
            }
        },
        error: function(error) {
            console.log(`Add Complaint Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while submitting the complaint.");
        },
    });
};

/**
 * Adds feedback from the "Contact Us / Give Feedback" form on index.php.
 */
function addFeedback(formElement) {
    var formData = new FormData(formElement);

    // Validation
    if (formData.get("name").trim() === "") {
        errorMessage("Please Enter Your Name.");
        return;
    }
    if (formData.get("email").trim() === "") {
        errorMessage("Please Enter Email Address.");
        return;
    }
    if (!emailvalidation(formData.get("email").trim(), true)) { // Show error
        return;
    }
    if (formData.get("subject").trim() === "") {
        errorMessage("Please Enter Subject.");
        return;
    }
    if (formData.get("message").trim() === "") {
        errorMessage("Please Enter Message.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=addFeedback", // Updated API endpoint
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log("Add Feedback Response:", response);
            if (response && response.success) {
                successToast("Feedback sent successfully!"); // Reloads index.php
            } else {
                errorMessage(response.error || "Failed to send feedback. Please try again.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Feedback Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while sending feedback.");
        },
    });
};

// --- Profile Update Functions (for Student Frontend) ---

/**
 * Changes a student's email (called from change_email.php).
 */
function changeStudentEmail(formElement) {
    var formData = new FormData(formElement);

    var currentEmail = formData.get("current_email").trim();
    var newEmail = formData.get("new_email").trim();
    var studentId = formData.get("student_id").trim();

    if (currentEmail === "" || newEmail === "") {
        errorMessage("Please fill out all required fields.");
        return;
    }

    if (!emailvalidation(newEmail, true)) { // Show error
        return;
    }

    // 1. Check if the current email is correct
    if (checkCurrentStudentEmail(currentEmail, studentId) <= 0) {
        errorMessage("Current Email is incorrect.");
        return;
    }

    // 2. Check if the new email already exists
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkEmailExistsAny",
        data: {
            email_to_check: newEmail
        }, // API checks if this email exists anywhere
        dataType: 'json',
        success: function(response) {
            console.log("Check Email Exists Response:", response);
            if (response.exists) {
                errorMessage("New Email Address is already in use by another account.");
            } else {
                // 3. If clear, update the email
                updateStudentEmail(studentId, newEmail);
            }
        },
        error: function(error) {
            console.log(`Check Email Exists Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred checking the email.");
        }
    });
}

/**
 * Helper function to update the student's email via API.
 */
function updateStudentEmail(studentId, newEmail) {
    var data = {
        id: studentId,
        field: "email",
        value: newEmail,
        id_fild: "student_id",
        table: "student"
    };

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=updateData",
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log("Update Email Response:", response);
            if (response && response.success) {
                successToastwithLogout("Email updated! Please log in again."); // Logs out
            } else {
                errorMessage(response.error || "Failed to update email.");
            }
        },
        error: function(error) {
            console.log(`Update Email Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while updating the email.");
        }
    });
}

/**
 * Changes a student's password (called from change_password.php).
 */
function changeStudentPassword(formElement) {
    var formData = new FormData(formElement);

    var currentPassword = formData.get("current_password").trim();
    var newPassword = formData.get("new_password").trim();
    var confirmPassword = formData.get("confirm_new_password").trim();
    var studentId = formData.get("student_id").trim();

    if (currentPassword === "" || newPassword === "" || confirmPassword === "") {
        errorMessage("Please fill in all password fields.");
        return;
    }
    if (newPassword.length < 6) {
        errorMessage("Password must be at least 6 characters long.");
        return;
    }
    if (newPassword !== confirmPassword) {
        errorMessage("New passwords do not match.");
        return;
    }

    // Check if the current password is correct
    if (checkStudentPassword(currentPassword, studentId) > 0) {
        var data = {
            id: studentId,
            field: "password",
            value: newPassword,
            id_fild: "student_id",
            table: "student"
        };

        $.ajax({
            method: "POST",
            url: "server/api.php?function_code=updateData",
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log("Change Password Response:", response);
                if (response && response.success) {
                    successToastwithLogout("Password changed successfully! Please log in again.");
                } else {
                    errorMessage(response.error || "Failed to change password.");
                }
            },
            error: function(error) {
                console.log(`Change Password Error: ${JSON.stringify(error)}`);
                errorMessage("An error occurred while changing the password.");
            },
        });
    } else {
        errorMessage("Current Password is incorrect.");
    }
};

/**
 * Synchronously checks student's current password.
 * @returns {number} 1 if correct, 0 if incorrect.
 */
function checkStudentPassword(password, student_id) {
    const data = {
        password: password,
        student_id: student_id,
    };
    var result_count = 0;
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkStudentPassword",
        data: data,
        async: false, // Wait for the response
        dataType: 'text',
        success: function(response) {
            console.log("Check Password Response:", response);
            try {
                result_count = parseInt(response.trim());
            } catch (e) {
                console.error("Error parsing checkStudentPassword response:", response);
            }
        },
        error: function(error) {
            console.log(`Check Password Error: ${JSON.stringify(error)}`);
        },
    });
    return result_count;
};

/**
 * Synchronously checks if the email belongs to the current student.
 * @returns {number} 1 if it matches, 0 if not.
 */
function checkCurrentStudentEmail(email, student_id) {
    const data = {
        email: email,
        student_id: student_id,
    };
    var result_count = 0;
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkCurrentStudentEmail",
        data: data,
        async: false, // Wait for response
        dataType: 'text',
        success: function(response) {
            console.log("Check Email Response:", response);
            try {
                result_count = parseInt(response.trim());
            } catch (e) {
                console.error("Error parsing checkCurrentStudentEmail response:", response);
            }
        },
        error: function(error) {
            console.log(`Check Email Error: ${JSON.stringify(error)}`);
        },
    });
    return result_count;
};

/**
 * Updates student profile info from the "Save Changes" button on profile.php.
 */
function updateStudentProfile(student_id) {
    // Get values from the form
    var name = document.getElementById('new_name').value;
    var phone = document.getElementById('new_phone').value;
    var address = document.getElementById('new_address').value;
    var gender = document.getElementById('new_gender').value;
    var student_id_number = document.getElementById('student_id_number').value;
    var room_number = document.getElementById('room_number').value;

    // Validation
    if (name.trim() === "" || phone.trim() === "" || address.trim() === "" || student_id_number.trim() === "" || room_number.trim() === "") {
        errorMessage("Please fill out all fields: Name, Phone, Address, Student ID, and Room Number.");
        return;
    }
    
    // --- New Validation Call ---
    if (!validateStudentIDNumber(student_id_number, true)) { // Show error
        return; 
    }
    // --- End New Validation ---

    if (!phonenumber(phone, true)) { // Show error
        return;
    }

    var formData = {
        student_id: student_id,
        new_name: name,
        new_phone: phone,
        new_address: address,
        new_gender: gender,
        student_id_number: student_id_number,
        room_number: room_number
    };

    $.ajax({
        type: 'POST',
        url: 'server/api.php?function_code=updateStudentProfile',
        data: JSON.stringify(formData), // Send as JSON
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            console.log("Update Profile Response:", response);
            if (response && response.status === 'success') {
                iziToast.success({
                    timeout: 1500,
                    title: 'Success',
                    message: 'Profile updated successfully!',
                    position: 'center',
                    onClosing: function(instance, toast, closedBy) {
                        window.location.reload(); // Reload profile.php
                    }
                });
            } else {
                errorMessage(response.message || 'Failed to update profile.');
            }
        },
        error: function(xhr, status, error) {
            console.error("Update Profile Error:", xhr.responseText);
            errorMessage('Error updating profile!');
        }
    });
}

/**
 * Initiates the withdrawal (soft delete) of a complaint.
 * Called from complaints.php.
 */
function withdrawComplaint(complaintId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to withdraw this complaint?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, withdraw it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use the soft-delete function from delete.js
            deleteData(complaintId, 'complaint', 'complaint_id');
            // deleteData() will show a success toast and reload the page
        }
    });
}

/**
 * Deletes a student's own account (soft delete).
 * Called from profile.php.
 */
function deleteDataFromHome(id, table, id_fild) {
    // Ensure it's the student table being targeted
    if (table !== 'student' || id_fild !== 'student_id') {
        errorMessage("Invalid delete request.");
        return;
    }

    Swal.fire({
        title: "Are you sure you want to delete your account?",
        text: "This action cannot be undone! You will be logged out.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete my account!",
    }).then((result) => {
        if (result.isConfirmed) {
            var data = {
                id: id,
                table: 'student',
                id_fild: 'student_id',
            };

            $.ajax({
                method: "POST",
                url: "server/api.php?function_code=deleteData", // Soft delete
                data: data, // PHP function expects $_POST
                dataType: 'json',
                success: function(response) {
                    console.log("Delete Account Response:", response);
                    if (response && response.success) {
                        // Use successToastwithLogout to log out after deletion
                        successToastwithLogout("Your account has been deleted.");
                    } else {
                        errorMessage(response.error || "Failed to delete account. Please contact support.");
                    }
                },
                error: function(error) {
                    console.log(`Delete Account Error: ${JSON.stringify(error)}`);
                    errorMessage("An error occurred while deleting the account.");
                },
            });
        }
    });
};