/*
 * homejs.js
 *
 * Contains all JavaScript functions for the STUDENT frontend pages
 */

/**
 * Adds a new complaint from the student form (submit_complaint.php).
 */
function addComplaint(formElement) {
    let formData = new FormData(formElement);

    if (formData.get("complaint_title").trim() === "" ||
        formData.get("dormitory_id").trim() === "" ||
        formData.get("room_number").trim() === "" ||
        formData.get("category_id").trim() === "") {
        errorMessage("Please fill out all required fields marked with *.");
        return;
    }

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
        contentType: false,
        processData: false,
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
 */
function addFeedback(formElement) {
    var formData = new FormData(formElement);

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
    var studentId = formData.get("student_id").trim();

    if (currentEmail === "" || newEmail === "") {
        errorMessage("Please fill out all required fields.");
        return;
    }
    if (!emailvalidation(newEmail, true)) {
        return;
    }
    if (checkCurrentStudentEmail(currentEmail, studentId) <= 0) {
        errorMessage("Current Email is incorrect.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkEmailExistsAny",
        data: { email_to_check: newEmail },
        dataType: 'json',
        success: function(response) {
            if (response.exists) {
                errorMessage("New Email Address is already in use by another account.");
            } else {
                updateStudentEmail(studentId, newEmail);
            }
        },
        error: function(error) {
            errorMessage("An error occurred checking the email.");
        }
    });
}

function updateStudentEmail(studentId, newEmail) {
    var data = { id: studentId, field: "email", value: newEmail, id_fild: "student_id", table: "student" };
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=updateData",
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response && response.success) {
                successToastwithLogout("Email updated! Please log in again.");
            } else {
                errorMessage(response.error || "Failed to update email.");
            }
        },
        error: function(error) {
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

    if (checkStudentPassword(currentPassword, studentId) > 0) {
        var data = { id: studentId, field: "password", value: newPassword, id_fild: "student_id", table: "student" };
        $.ajax({
            method: "POST",
            url: "server/api.php?function_code=updateData",
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    successToastwithLogout("Password changed successfully! Please log in again.");
                } else {
                    errorMessage(response.error || "Failed to change password.");
                }
            },
            error: function(error) {
                errorMessage("An error occurred while changing the password.");
            },
        });
    } else {
        errorMessage("Current Password is incorrect.");
    }
};

function checkStudentPassword(password, student_id) {
    const data = { password: password, student_id: student_id };
    var result_count = 0;
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkStudentPassword",
        data: data,
        async: false,
        dataType: 'text',
        success: function(response) {
            try { result_count = parseInt(response.trim()); } catch (e) {}
        },
        error: function(error) {}
    });
    return result_count;
};

function checkCurrentStudentEmail(email, student_id) {
    const data = { email: email, student_id: student_id };
    var result_count = 0;
    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=checkCurrentStudentEmail",
        data: data,
        async: false,
        dataType: 'text',
        success: function(response) {
            try { result_count = parseInt(response.trim()); } catch (e) {}
        },
        error: function(error) {}
    });
    return result_count;
};

/**
 * Updates student profile info from profile.php.
 */
function updateStudentProfile(student_id) {
    var name = document.getElementById('new_name').value;
    var phone = document.getElementById('new_phone').value;
    var gender = document.getElementById('new_gender').value;
    var room_number = document.getElementById('room_number').value;

    if (name.trim() === "" || phone.trim() === "" || room_number.trim() === "") {
        errorMessage("Please fill out all fields: Name, Phone, and Room Number.");
        return;
    }
    if (typeof validateStudentIDNumber === 'function' && !validateStudentIDNumber(student_id, false)) { /* Only validate format if function exists */ }
    if (!phonenumber(phone, true)) { return; }

    var formData = {
        student_id: student_id,
        new_name: name,
        new_phone: phone,
        new_gender: gender,
        room_number: room_number
    };

    $.ajax({
        type: 'POST',
        url: 'server/api.php?function_code=updateStudentProfile',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response.status === 'success') {
                iziToast.success({
                    timeout: 1500,
                    title: 'Success',
                    message: 'Profile updated successfully!',
                    position: 'center',
                    onClosing: function() { window.location.reload(); }
                });
            } else {
                errorMessage(response.message || 'Failed to update profile.');
            }
        },
        error: function(xhr, status, error) {
            errorMessage('Error updating profile!');
        }
    });
}

// --- Complaint Action Functions ---

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
 * Helper function to update a complaint's status via the API.
 */
function updateStatusTo(complaintId, newStatus, successMessage) {
    var data = {
        complaint_id: complaintId,
        complaint_status: newStatus
    };

    $.ajax({
        method: "POST",
        url: "server/api.php?function_code=updateComplaintStatus",
        data: data,
        dataType: 'text',
        success: function(response) {
            if (response.trim() === 'success') {
                successToast(successMessage); // Reloads page
            } else {
                errorMessage('Failed to update complaint status.');
            }
        },
        error: function(error) {
            errorMessage('An error occurred while updating status.');
        }
    });
}