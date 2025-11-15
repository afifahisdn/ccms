/*
 * add.js
 *
 * Contains all JavaScript functions for ADDING new data via AJAX.
 * - addCategory 
 * - addDepartment
 * - addDormitory
 * - addStudent (for public registration)
 * - addStudentAdmin (for admin panel)
 * - addStaff (for admin panel)
 */

/**
 * Adds a new complaint category.
 * Called from admin/categories.php modal.
 */
function addCategory(formElement) {
    let fd = new FormData(formElement);

    // Validate form fields
    const categoryName = fd.get("category_name") ? fd.get("category_name").trim() : "";

    if (!categoryName) {
        errorMessage("Please Enter Category Name");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addCategory",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Category Response:", response);
            if (response && response.exists) {
                errorMessage(response.message || "This Category Name Already Exists.");
            } else if (response && response.success) {
                successToast("Category added successfully!"); // Re-loads page
            } else {
                errorMessage(response.error || "Failed to add category.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Category Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while adding the category.");
        },
    });
};

/**
 * Adds a new department.
 * Called by admin/department.php modal.
 */
function addDepartment(formElement) {
    let fd = new FormData(formElement);

    const departmentName = fd.get("department_name") ? fd.get("department_name").trim() : "";
    const departmentType = fd.get("department_type") ? fd.get("department_type").trim() : "";

    if (!departmentName) {
        errorMessage("Please Enter Department Name");
        return;
    }
    if (!departmentType) {
        errorMessage("Please Select Department Type");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addDepartment",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Department Response:", response);
            if (response && response.exists) {
                errorMessage(response.message || "This Department Name Already Exists.");
            } else if (response && response.success) {
                successToast("Department added successfully!");
            } else {
                errorMessage(response.error || "Failed to add department.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Department Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while adding the department.");
        },
    });
};

/**
 * Adds a new dormitory.
 * Called by admin/dormitory.php modal.
 */
function addDormitory(formElement) {
    let fd = new FormData(formElement);

    const dormitoryName = fd.get("dormitory_name") ? fd.get("dormitory_name").trim() : "";
    const dormitoryCode = fd.get("dormitory_code") ? fd.get("dormitory_code").trim() : "";

    if (!dormitoryName) {
        errorMessage("Please Enter Dormitory Name");
        return;
    }
    if (!dormitoryCode) {
        errorMessage("Please Enter Dormitory Code");
        return;
    }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addDormitory",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Dormitory Response:", response);
            if (response && response.exists) {
                errorMessage(response.message || "This Dormitory Name or Code Already Exists.");
            } else if (response && response.success) {
                successToast("Dormitory added successfully!");
            } else {
                errorMessage(response.error || "Failed to add dormitory.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Dormitory Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while adding the dormitory.");
        },
    });
};

/**
 * Adds a new student from the public registration page (admin/register.php).
 */
function addStudent(formElement) {
    let fd = new FormData(formElement);

    const name = fd.get("name") ? fd.get("name").trim() : "";
    const email = fd.get("email") ? fd.get("email").trim() : "";
    const phone = fd.get("phone") ? fd.get("phone").trim() : "";
    const gender = fd.get("gender") ? fd.get("gender").trim() : "";
    const password = fd.get("password") ? fd.get("password").trim() : "";
    const conf_password = fd.get("conf_password") ? fd.get("conf_password").trim() : "";
    const student_id_number = fd.get("student_id_number") ? fd.get("student_id_number").trim() : "";
    const room_number = fd.get("room_number") ? fd.get("room_number").trim() : "";

    // Validation checks
    if (!name) { errorMessage("Please Enter Full Name."); return; }
    if (!email) { errorMessage("Please Enter Email."); return; }
    if (!student_id_number) { errorMessage("Please Enter Student ID Number."); return; }
    if (!phone) { errorMessage("Please Enter Phone Number."); return; }
    if (!room_number) { errorMessage("Please Enter Room Number."); return; }
    if (!gender) { errorMessage("Please Select Gender."); return; }
    if (!password) { errorMessage("Please Enter Password."); return; }
    if (password !== conf_password) { errorMessage("Passwords do not match."); return; }
    
    // Call validation functions (they will show their own errors)
    if (typeof validateStudentIDNumber === 'function' && !validateStudentIDNumber(student_id_number)) { return; }
    if (typeof emailvalidation === 'function' && !emailvalidation(email)) { return; }
    if (typeof phonenumber === 'function' && !phonenumber(phone)) { return; }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addStudent",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Student Response:", response);
            // This is where the "already exists" error is handled
            if (response && response.exists) {
                errorMessage(response.message || "Email or Student ID already exists.");
            } else if (response && response.success) {
                successToastRedirect("login.php", "Registration successful! Please log in.");
            } else {
                errorMessage(response.error || "An unknown error occurred during registration.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Student Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred during registration.");
        },
    });
};

/**
 * Adds a new student from the admin panel (admin/add_student.php).
 */
function addStudentAdmin(formElement) {
    let fd = new FormData(formElement);

    const name = fd.get("name") ? fd.get("name").trim() : "";
    const email = fd.get("email") ? fd.get("email").trim() : "";
    const phone = fd.get("phone") ? fd.get("phone").trim() : "";
    const gender = fd.get("gender") ? fd.get("gender").trim() : "";
    const password = fd.get("password") ? fd.get("password").trim() : "";
    const conf_password = fd.get("conf_password") ? fd.get("conf_password").trim() : "";
    const student_id_number = fd.get("student_id_number") ? fd.get("student_id_number").trim() : "";
    const room_number = fd.get("room_number") ? fd.get("room_number").trim() : "";

    // Validation checks
    if (!name) { errorMessage("Please Enter Full Name."); return; }
    if (!email) { errorMessage("Please Enter Email."); return; }
    if (!student_id_number) { errorMessage("Please Enter Student ID Number."); return; }
    if (!phone) { errorMessage("Please Enter Phone Number."); return; }
    if (!room_number) { errorMessage("Please Enter Room Number."); return; }
    if (!gender) { errorMessage("Please Select Gender."); return; }
    if (!password) { errorMessage("Please Enter Password."); return; }
    if (password !== conf_password) { errorMessage("Passwords do not match."); return; }

    // Call validation functions (they will show their own errors)
    if (typeof validateStudentIDNumber === 'function' && !validateStudentIDNumber(student_id_number)) { return; }
    if (typeof emailvalidation === 'function' && !emailvalidation(email)) { return; }
    if (typeof phonenumber === 'function' && !phonenumber(phone)) { return; }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addStudent",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Student Admin Response:", response);
            if (response && response.exists) {
                errorMessage(response.message || "Email or Student ID already exists.");
            } else if (response && response.success) {
                successToastRedirect("student.php", "Student added successfully!");
            } else {
                errorMessage(response.error || "An unknown error occurred adding student.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Student Admin Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred adding student.");
        },
    });
};

/**
 * Adds a new staff member from the admin panel (admin/staff.php modal).
 */
function addStaff(formElement) {
    let fd = new FormData(formElement);

    const name = fd.get("name") ? fd.get("name").trim() : "";
    const email = fd.get("email") ? fd.get("email").trim() : "";
    const phone = fd.get("phone") ? fd.get("phone").trim() : "";
    const nric = fd.get("nric") ? fd.get("nric").trim() : "";
    const gender = fd.get("gender") ? fd.get("gender").trim() : "";
    const password = fd.get("password") ? fd.get("password").trim() : "";
    const conf_password = fd.get("conf_password") ? fd.get("conf_password").trim() : "";
    const department_id = fd.get("department_id") ? fd.get("department_id").trim() : "";
    const staff_role = fd.get("staff_role") ? fd.get("staff_role").trim() : "";

    // Validation checks
    if (!name) { errorMessage("Please Enter Full Name."); return; }
    if (!email) { errorMessage("Please Enter Email."); return; }
    if (!phone) { errorMessage("Please Enter Phone Number."); return; }
    if (!nric) { errorMessage("Please Enter NRIC."); return; }
    if (!department_id) { errorMessage("Please Select Department."); return; }
    if (!staff_role) { errorMessage("Please Select Staff Role."); return; }
    if (!password) { errorMessage("Please Enter Password."); return; }
    if (password !== conf_password) { errorMessage("Passwords do not match."); return; }
    if (!gender) { errorMessage("Please Select Gender."); return; }

    // Call validation functions (they will show their own errors)
    // Note: Staff emails do not need @college.edu validation
    if (typeof phonenumber === 'function' && !phonenumber(phone)) { return; }

    $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=addStaff",
        data: fd,
        dataType: 'json',
        success: function(response) {
            console.log("Add Staff Response:", response);
            if (response && response.exists) {
                errorMessage(response.message || "This Email Address is Already Registered!");
            } else if (response && response.success) {
                successToast("Staff member added successfully!");
            } else {
                errorMessage(response.error || "Failed to add staff member.");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function(error) {
            console.log(`Add Staff Error: ${JSON.stringify(error)}`);
            errorMessage("An error occurred while adding staff.");
        },
    });
};