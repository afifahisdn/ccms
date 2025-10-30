/*
 * delete.js
 *
 * Contains JavaScript functions for soft deleting (deleteData)
 * and permanently deleting (permanantDeleteData).
 */

/**
 * Initiates a soft delete action (sets is_deleted = 1).
 * Calls the 'deleteData' API endpoint.
 * This is the standard delete function.
 */
function deleteData(id, table, id_fild) {
    Swal.fire({
        title: "Are you sure?",
        text: "This record will be marked as deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Soft delete confirmed for:", {
                id,
                table,
                id_fild
            });
            var formData = {
                id: id,
                table: table,
                id_fild: id_fild,
            };

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=deleteData", // Correct: calls soft delete
                data: formData, // PHP function expects $_POST
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    console.log("Soft delete response:", response);
                    if (response && response.success) {
                        successToastDelete(); // Shows success and reloads page
                    } else {
                        errorMessage("Failed to delete the record: " + (response.error || 'Unknown error'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`AJAX Error (Soft Delete): ${textStatus} - ${errorThrown}`);
                    errorMessage("An error occurred while deleting the record.");
                },
            });
        } else {
            console.log("Soft delete cancelled.");
        }
    });
};

/**
 * Initiates a PERMANENT delete action (DELETE FROM table).
 * Calls the 'permanantDeleteData' API endpoint.
 * USE WITH EXTREME CAUTION.
 */
function permanantDeleteData(id, table, id_fild) {
    Swal.fire({
        title: "Confirm Permanent Deletion!",
        text: "This record will be ERASED FOREVER! This action cannot be undone.",
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, PERMANENTLY delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Permanent delete confirmed for:", {
                id,
                table,
                id_fild
            });
            var jsonData = JSON.stringify({
                id: id,
                table: table,
                id_fild: id_fild,
            });

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=permanantDeleteData", // Correct: calls hard delete
                data: jsonData, // PHP function expects JSON
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    console.log("Permanent delete response:", response);
                    if (response && response.success) {
                        successToastDelete(); // Shows success and reloads page
                    } else {
                        errorMessage("Permanent deletion failed: " + (response.error || 'Unknown error'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`AJAX Error (Permanent Delete): ${textStatus} - ${errorThrown}`);
                    errorMessage("An error occurred during permanent deletion.");
                },
            });
        } else {
            console.log("Permanent delete cancelled.");
        }
    });
};