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
        formData.get("category_id").trim() === "") { // <-- UPDATED to category_id
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
                successToastRedirect("complaints.php", "Complaint submitted successfully!");
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
 * Adds feedback from the "Contact Us" form on index.php.
 * (Assumes user is logged in).
 */
function addFeedback(formElement) {
    var formData = new FormData(formElement);

    // Validation (student_id is hidden, only check subject and message)
    if (formData.get("subject").trim() === "") {
        errorMessage("Please Enter Subject.");
        return;
    }
    if (formData.get("message").trim() === "") {
        errorMessage("Please Enter Message.");
        return;
    }
    if (formData.get("student_id").trim() === "") {
        errorMessage("Error: Student ID not found. Please log in again.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=addFeedback",
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
    var studentId = formData.get("student_id").trim(); // This is the VARCHAR PK

    if (currentEmail === "" || newEmail === "") {
        errorMessage("Please fill out all required fields.");
        return;
    }

    // Use validation function (which now checks for @college.edu)
    if (!emailvalidation(newEmail, true)) {
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
        },
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
    var studentId = formData.get("student_id").trim(); // VARCHAR PK

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
        student_id: student_id, // VARCHAR PK
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
        student_id: student_id, // VARCHAR PK
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
 * student_id_number is now READONLY and not sent.
 */
function updateStudentProfile(student_id) {
    // Get values from the form
    var name = document.getElementById('new_name').value;
    var phone = document.getElementById('new_phone').value;
    var address = document.getElementById('new_address').value;
    var gender = document.getElementById('new_gender').value;
    var room_number = document.getElementById('room_number').value;
    // student_id_number is no longer editable

    // Validation
    if (name.trim() === "" || phone.trim() === "" || address.trim() === "" || room_number.trim() === "") {
        errorMessage("Please fill out all fields: Name, Phone, Address, and Room Number.");
        return;
    }

    if (!phonenumber(phone, true)) { // Show error
        return;
    }

    var formData = {
        student_id: student_id, // This is the VARCHAR PK
        new_name: name,
        new_phone: phone,
        new_address: address,
        new_gender: gender,
        room_number: room_number
        // student_id_number is NOT sent
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
 * Shows confirmation and sets a complaint status to 'Withdrawn'.
 * Called from complaints.php.
 */
function withdrawComplaint(complaintId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to withdraw this complaint? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, withdraw it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Call helper function to set status
            updateStatusTo(complaintId, 'Withdrawn', 'Complaint withdrawn.');
        }
    });
}

/**
 * Student approves a "Resolved" complaint, setting status to 'Closed'.
 * Called from complaints.php.
 */
function approveComplaint(complaintId) {
    Swal.fire({
        title: 'Approve Resolution?',
        text: "This will close the complaint. This action cannot be undone.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745', // Green
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve & close!',
        cancelButtonText: 'Not now'
    }).then((result) => {
        if (result.isConfirmed) {
            updateStatusTo(complaintId, 'Closed', 'Complaint approved and closed.');
        }
    });
}

/**
 * Student re-opens a "Resolved" complaint, setting status back to 'Open'.
 * Called from complaints.php.
 */
function reopenComplaint(complaintId) {
    Swal.fire({
        title: 'Re-Open Complaint?',
        text: "This will set the status back to 'Open' for staff to review.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fd7e14', // Orange
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, re-open it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateStatusTo(complaintId, 'Open', 'Complaint has been re-opened.');
        }
    });
}

/**
 * Helper function to update a complaint's status via the API.
 * This is now the main function for student status changes.
 */
function updateStatusTo(complaintId, newStatus, successMessage) {
    var data = {
        complaint_id: complaintId,
        complaint_status: newStatus // e.g., 'Withdrawn', 'Closed', 'Open'
    };

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=updateComplaintStatus",
        data: data, // Sent as form data
        dataType: 'text', // Expects 'success' or 'error'
        success: function(response) {
            console.log("Update Status Response:", response);
            if (response.trim() === 'success') {
                successToast(successMessage); // Reloads page
            } else {
                errorMessage('Failed to update complaint status.');
            }
        },
        error: function(error) {
            console.log(`Update Status Error: ${JSON.stringify(error)}`);
            errorMessage('An error occurred while updating status.');
        }
    });
}