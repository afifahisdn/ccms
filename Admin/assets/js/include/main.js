/*
 * main.js
 *
 * Contains JavaScript functions primarily used by the ADMIN panel.
 */

/*.............................................................. Grid View (DataTables) ..............................................................*/
window.addEventListener("DOMContentLoaded", (event) => {
    // Simple-DataTables
    const datatablesSimple = document.getElementById("datatablesSimple");
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }
});

/*.............................................................. Settings Data..............................................................*/

// General function to update settings table text fields
settingsUpdate = (ele, field) => {
    var val = document.getElementById(ele.id).value;
    var data = {
        field: field,
        value: val,
    };

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=changesettings",
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log("Settings Update Response:", response);
            if (response && response.success) {
                successToast_RN("Setting updated!"); // Show success without reload
            } else {
                errorMessage(response.error || "Failed to update setting.");
            }
        },
        error: function(error) {
            console.log(`Settings Update Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while updating settings.");
        },
    });
};

/*.............................................................. Login..............................................................*/

login = (myForm) => {
    var formData = new FormData(myForm);

    if (formData.get("email").trim() === "" || formData.get("password").trim() === "") {
        errorMessage("Please enter both email and password.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=login",
        data: formData,
        dataType: 'text', // Expect 'admin' or 'student'
        success: function($data) {
            console.log("Login Response:", $data);
            if ($data.trim() == "admin") { // Staff or Admin
                window.location.href = "index.php"; // Redirect to admin dashboard
            } else if ($data.trim() == "student") { // Student
                window.location.href = "../index.php"; // Redirect to main site
            } else {
                errorMessage("Email or Password is incorrect.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Login Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred during login.");
        },
    });
};

/*.............................................................. General Data Update (Inline Edit) ..............................................................*/

/**
 * Shows a warning popup BEFORE updating a complaint status to 'Closed'.
 * Called by onchange in admin/complaint.php status dropdown.
 */
function confirmStatusChange(selectElement, complaintId, currentStatus) {
    var newStatus = selectElement.value;

    // Check if the status is actually changing
    if (newStatus === currentStatus) {
        return;
    }


    // 1. Handle "Resolved" Status - Ask for Notes
    if (newStatus === 'Resolved') {
        Swal.fire({
            title: 'Mark as Resolved?',
            text: 'Please enter the details of the resolution:',
            input: 'textarea',
            inputPlaceholder: 'e.g., Replaced the broken pipe, tested and verified...',
            showCancelButton: true,
            confirmButtonText: 'Resolve & Save',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            inputValidator: (value) => {
                if (!value) {
                    return 'You must write a resolution note!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const notes = result.value;
                
                // CORRECTED: Use 'complaintId' (the function parameter)
                var resolveData = {
                    complaint_id: complaintId, 
                    resolution_notes: notes
                };
                
                $.ajax({
                    method: "POST",
                    url: "../server/api.php?function_code=resolveComplaint",
                    data: resolveData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if(typeof successToast_RN === 'function') {
                                successToast_RN("Complaint Resolved Successfully!");
                            } else {
                                console.log("Complaint Resolved Successfully!"); 
                            }
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            errorMessage(response.error || "Failed to resolve complaint.");
                            selectElement.value = oldStatus; // Revert
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        errorMessage("An error occurred connecting to server.");
                        selectElement.value = oldStatus;
                    }
                });
            } else {
                // User clicked Cancel - Revert dropdown
                selectElement.value = oldStatus;
            }
        });
    // 2. Handle "Closed" Status (Admin Only) - Warning
    } else if (newStatus === "Closed") {
        Swal.fire({
            title: 'Are you sure?',
            text: "Setting this complaint to 'Closed' is final. The student cannot re-open it. Do you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, close it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with update
                updateData(selectElement, complaintId, 'complaint_status', 'complaint', 'complaint_id');
            } else {
                // User cancelled, reset the dropdown
                selectElement.value = currentStatus;
            }
        });
    } else {
        // For any other status change (Open -> In Progress), update immediately
        updateData(selectElement, complaintId, 'complaint_status', 'complaint', 'complaint_id');
    }
}

/**
 * Generic function for inline edits (like Urgency, or Category name).
 * Called by onchange events in tables.
 */
updateData = (ele, id, field, table, id_fild) => {
    var val = ele.value; // Get value directly from the element passed

    var data = {
        id_fild: id_fild,
        id: id,
        field: field,
        value: val,
        table: table,
    };

    // Add validation if needed
    if (field == "email") {
        if (typeof emailvalidation === 'function' && !emailvalidation(val, false)) { // Pass false to prevent reload
            return;
        }
    } else if (field == "phone") {
        if (typeof phonenumber === 'function' && !phonenumber(val, false)) { // Pass false to prevent reload
            return;
        }
    }

    callUpdate(data); // Call the AJAX function
};

/**
 * The actual AJAX call for inline updates.
 * NOW SENDS STAFF ID AND ROLE for auto-assignment.
 */
callUpdate = (data) => {
    // Get the staff ID and Role from the hidden fields on the page
    // (These fields will be added to admin/complaint.php)
    const staffId = document.getElementById('logged_in_staff_id') ? document.getElementById('logged_in_staff_id').value : '0';
    const userRole = document.getElementById('logged_in_user_role') ? document.getElementById('logged_in_user_role').value : 'staff';
    
    // Add them to the data object
    data.logged_in_staff_id = staffId;
    data.logged_in_user_role = userRole;

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=updateData",
        data: data, // `data` now includes the staff ID and role
        dataType: 'json',
        success: function(response) {
            console.log("Inline Update Response:", response);
            if (response && response.success) {
                successToast("Update successful!"); // Reload page
            } else {
                errorMessage(response.error || "Update failed. Please check the value.");
            }
        },
        error: function(error) {
            console.log(`Inline Update Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred during the update.");
        },
    });
};

/**
 * Updates staff profile (Admin or Staff self-edit).
 * Collects all fields and sends them to the API.
 */
function updateStaffProfile(formElement) {
    var formData = new FormData(formElement);
    
    // Add role if disabled (FormData doesn't capture disabled fields)
    var roleSelect = formElement.querySelector('select[name="staff_role"]');
    if (roleSelect && roleSelect.disabled) {
        formData.append("staff_role", roleSelect.value);
    }
    
    // Add department if disabled
    var deptSelect = formElement.querySelector('select[name="department_id"]');
    if (deptSelect && deptSelect.disabled) {
        formData.append("department_id", deptSelect.value);
    }

    // Basic required checks
    if (!formData.get("name")?.trim()) return errorMessage("Name is required.");
    if (!formData.get("email")?.trim()) return errorMessage("Email is required.");
    if (!formData.get("phone")?.trim()) return errorMessage("Phone Number is required.");
    if (!formData.get("nric")?.trim()) return errorMessage("NRIC is required.");
    if (!formData.get("department_id")?.trim()) return errorMessage("Department is required.");

    // Format validation
    if (!emailvalidation(formData.get("email"))) return;
    if (!phonenumber(formData.get("phone"))) return;

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=updateStaffProfile", // Ensure this case exists in api.php
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Update Staff Response:", response);
            if (response.success) {
                successToast("Profile updated successfully!");
            } else {
                errorMessage(response.error || "Failed to update profile.");
            }
        },
        error: function(xhr, status, error) {
             console.error(xhr.responseText);
             errorMessage("An error occurred while updating.");
        }
    });
}

/*.............................................................. Admin/Staff Password Change ..............................................................*/

changePasswordAdmin = (form) => {
    var formData = new FormData(form);

    var currentPassword = formData.get("current_password").trim();
    var newPassword = formData.get("new_password").trim();
    var confirmPassword = formData.get("confirm_new_password").trim();
    var userEmail = formData.get("email").trim();

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

    // Check the current password
    if (checkStaffPasswordByEmail(currentPassword, userEmail) > 0) {
        var data = {
            id: userEmail,
            field: "password",
            value: newPassword,
            id_fild: "email",
            table: "staff",
        };

        $.ajax({
            method: "POST",
            url: "../server/api.php?function_code=updateData",
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log("Admin Change Password Response:", response);
                if (response && response.success) {
                    successToastwithLogoutInAdmin("Password changed successfully! Please log in again.");
                } else {
                    errorMessage(response.error || "Failed to change password. Please try again.");
                }
            },
            error: function(error) {
                console.log(`Admin Change Password Error: ${JSON.stringify(error)}`);
                errorMessage("An error occurred while changing the password.");
            },
        });
    } else {
        errorMessage("Current Password is incorrect.");
    }
};

// Check staff's current password using email
checkStaffPasswordByEmail = (password, email) => {
    const data = {
        password: password,
        email: email,
    };
    var result_count = 0;
    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=checkStaffPasswordByEmail", // Correct API endpoint
        data: data,
        async: false, // Wait for the result
        dataType: 'text',
        success: function(response) {
            console.log("Check Staff Password Response:", response);
            let cleanResponse = response.toString().trim();
            try {
                result_count = parseInt(response.trim());
                console.log("Parsed Result Count:", result_count);
            } catch (e) {
                console.error("Error parsing checkStaffPasswordByEmail response:", response);
            }
        },
        error: function(error) {
            console.log(`Check Staff Password Error: ${JSON.stringify(error)}`);
        },
    });
    return result_count;
};

/*.............................................................. Add Complaint (Admin Form) ..............................................................*/

addComplaintAdmin = (formElement) => {
    var formData = new FormData(formElement);

    // --- Validation specific to the admin complaint form ---
    if (formData.get("student_id").trim() === "") {
        errorMessage("Please Select a Student.");
        return;
    }
    if (formData.get("dormitory_id").trim() === "") {
        errorMessage("Please Select a Dormitory.");
        return;
    }
    if (formData.get("room_number").trim() === "") {
        errorMessage("Please Enter Room Number.");
        return;
    }
    if (formData.get("complaint_title").trim() === "") {
        errorMessage("Please Enter Complaint Title.");
        return;
    }
    if (formData.get("category_id").trim() === "") { 
        errorMessage("Please Select a Category.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addComplaintAdmin",
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log("Admin Add Complaint Response:", response);
            if (response && response.success) {
                successToastRedirect("complaint.php", "Complaint added successfully!");
            } else {
                errorMessage(response.error || "Failed to add complaint. Please check details.");
            }
        },
        error: function(error) {
            console.log("Admin Add Complaint Error:", JSON.stringify(error));
            errorMessage("Failed to add complaint. Please try again.");
        },
        cache: false,
        contentType: false, // For file upload
        processData: false, // For file upload
    });
};