/*
 * upload.js
 *
 * Contains JavaScript functions for file uploads.
 * - uploadSettingImage (for admin/settings.php)
 * - uploadComplaintImage (for uploading a photo to an *existing* complaint, if needed)
 */

/**
 * Uploads an image for the general website settings (header, about, etc.).
 * Assumes the form includes a hidden input 'field'.
 * Calls the 'SettingImage' API endpoint.
 */
function uploadSettingImage(formElement) { // Pass the form element
    var formData = new FormData(formElement);
    var fieldName = formData.get('field');

    if (!fieldName) {
        errorMessage("Setting field name is missing. Cannot upload image.");
        return;
    }
    if (!formData.has('file') || !formData.get('file').name) {
        errorMessage("Please select an image file to upload.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=SettingImage",
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log("Setting Image Upload Response:", response);
            if (response && response.success) {
                // loading() shows a processing message and then reloads the page
                loading("Updating Setting Image...", 1500);
            } else {
                errorMessage(response.error || "Setting image upload failed.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Setting Image Upload Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred during setting image upload.");
        },
    });
};

/**
 * Uploads an image associated with a complaint.
 * Assumes the form includes 'complaint_id'.
 * Calls the 'uploadComplaintPhoto' API endpoint.
 *
 * NOTE: This function is for uploading a photo *after* a complaint is made.
 * The main 'addComplaint' function (in add.js/homejs.js) handles the initial upload.
 */
function uploadComplaintImage(formElement) {
    var formData = new FormData(formElement);
    var complaintId = formData.get('complaint_id');

    if (!complaintId) {
        errorMessage("Complaint ID is missing. Cannot upload photo.");
        return;
    }
    if (!formData.has('file') || !formData.get('file').name) {
        errorMessage("Please select an image file to upload.");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=uploadComplaintPhoto", // You may need to create this endpoint
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log("Complaint Photo Upload Response:", response);
            if (response && response.success) {
                successToast("Complaint photo uploaded!"); // Reloads page
            } else {
                errorMessage(response.error || "Complaint photo upload failed.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.error(`AJAX Error (Complaint Photo): ${JSON.stringify(error)}`);
            errorMessage("An error occurred during photo upload.");
        },
    });
};