/*
 * validation.js
 *
 * Contains client-side validation functions.
 */

/**
 * Validates a Malaysian phone number format (basic check).
 * Shows an error message if invalid.
 * @param {string} inputtxt - The phone number string to validate.
 * @param {boolean} [showError=true] - Whether to show the error message.
 * @returns {boolean} - True if valid, false otherwise.
 */
function phonenumber(inputtxt, showError = true) {
    // Allows 01[0-9] followed by 7 or 8 digits (e.g., 0123456789 or 0111234567)
    // Allows optional hyphen.
    var phoneno = /^(01[0-9])-?[0-9]{7,8}$/;
    if (inputtxt && inputtxt.match(phoneno)) {
        return true;
    } else {
        if (showError) {
            // Use the non-reloading error message
            errorMessage("Phone Number is not valid. Please use a format like 012-3456789.");
        }
        return false;
    }
}

/**
 * Validates a standard email format.
 * Shows an error message if invalid.
 * @param {string} inputtxt - The email string to validate.
 * @param {boolean} [showError=true] - Whether to show the error message.
 * @returns {boolean} - True if valid, false otherwise.
 */
function emailvalidation(inputtxt, showError = true) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (inputtxt && inputtxt.match(mailformat)) {
        return true;
    } else {
        if (showError) {
            // Use the non-reloading error message
            errorMessage("Email format is not valid. Please enter a valid email address.");
        }
        return false;
    }
}

/**
 * Validates a Student ID Number format.
 * Example: STU2024001 (STU + 7 digits). Adjust regex as needed.
 * @param {string} studentId - The student ID string to validate.
 * @param {boolean} [showError=true] - Whether to show the error message.
 * @returns {boolean} - True if valid, false otherwise.
 */
function validateStudentIDNumber(studentId, showError = true) {
    // This regex requires 'STU' (case-insensitive) followed by exactly 7 digits.
    var format = /^STU[0-9]{7}$/i;
    if (studentId && studentId.match(format)) {
        return true;
    } else {
        if (showError) {
            // Use the non-reloading error message
            errorMessage("Invalid Student ID format. Expected format like 'STU1234567'.");
        }
        return false;
    }
}