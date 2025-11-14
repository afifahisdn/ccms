/*
 * main.js
 *
 * Contains JavaScript functions primarily used by the ADMIN panel.
 */

/*.............................................................. Grid View (DataTables) ..............................................................*/
window.addEventListener("DOMContentLoaded", (event) => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki
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
        dataType: 'text', // Expect 'admin' or 'customer'
        success: function($data) {
            console.log("Login Response:", $data);
            if ($data.trim() == "admin") { // Staff or Admin
                window.location.href = "index.php"; // Redirect to admin dashboard
            } else if ($data.trim() == "customer") { // Student
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

/*.............................................................. Generic Data Update (Inline Edit) ..............................................................*/

/**
 * Shows a warning popup BEFORE updating a complaint status to 'Closed'.
 * Called by onchange in admin/complaint.php status dropdown.
 */
function confirmStatusChange(selectElement, complaintId, currentStatus) {
    var newStatus = selectElement.value;

    if (newStatus === "Closed") {
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
        // For any other status change (Open, In Progress, Resolved), update immediately
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
        if (!emailvalidation(val, false)) { // Pass false to prevent reload
            return;
        }
    } else if (field == "phone") {
        if (!phonenumber(val, false)) { // Pass false to prevent reload
            return;
        }
    }

    callUpdate(data); // Call the AJAX function
};

// The actual AJAX call for inline updates
callUpdate = (data) => {
    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=updateData",
        data: data,
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
    if (userEmail === "") {
        errorMessage("User email is missing. Cannot change password.");
        return;
    }

    // Check the current password
    if (checkStaffPasswordByEmail(currentPassword, userEmail) > 0) {
        var data = {
            id: userEmail,
            field: "password",
            value: newPassword,
            id_fild: "email",
            table: "staff", // Corrected table name
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
            try {
                result_count = parseInt(response.trim());
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

// This function replaces the old 'addRequestAdmin'
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
    if (formData.get("category_id").trim() === "") { // <-- Updated to category_id
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