addContactMessage = (form) => {
  var formData = new FormData(form);

  if (formData.get("name").trim() != "") {
    if (formData.get("email").trim() != "") {
      if (emailvalidation(formData.get("email").trim())) {
        if (formData.get("subject").trim() != "") {
          if (formData.get("message").trim() != "") {
            $.ajax({
              method: "POST",
              url: "server/api.php?function_code=addcontact",
              data: formData,
              success: function ($data) {
                console.log($data);
                successToast();
              },
              cache: false,
              contentType: false,
              processData: false,
              error: function (error) {
                console.log(`Error ${error}`);
              },
            });
          } else {
            errorMessage("Please Enter Message");
          }
        } else {
          errorMessage("Please Enter Subject");
        }
      }
    } else {
      errorMessage("Please Enter Email Address");
    }
  } else {
    errorMessage("Please Enter Your Name");
  }
};

document.addEventListener("DOMContentLoaded", () => {
  // Function to handle location changes and weight input
  const calculation = () => {
    const send_location = document.getElementById("send_location").value;
    const end_location = document.getElementById("end_location").value;
    const weight = document.getElementById("weight").value;

    // Validate that both locations are selected and weight is entered
    if (send_location === "Please Select" || end_location === "Please Select" || weight.trim() === "") {
      return; // Exit the function if validations fail
    }

    const data = {
      send_location: send_location,
      end_location: end_location,
    };

    // Make AJAX request only if all fields are valid
    $.ajax({
      method: "POST",
      url: "server/api.php?function_code=checkArea",
      data: data,
      success: function (response) {
        console.log(response);

        if (response > 0) {
          const sum = parseInt(weight) * parseInt(response);
          document.getElementById("total").value = sum;
          document.getElementById("total_fee").value = sum;
        } else {
          errorMessage_R("This area is not in our service area");
        }
      },
      error: function (error) {
        console.log(`Error ${error}`);
      },
    });
  };

  // Add event listeners to trigger the calculation
  document.getElementById("send_location").addEventListener("change", calculation);
  document.getElementById("end_location").addEventListener("change", calculation);
  document.getElementById("weight").addEventListener("input", calculation);
});

const validateParcelDetails = () => {
  const send_location = document.getElementById("send_location").value;
  const end_location = document.getElementById("end_location").value;
  const weight = document.getElementById("weight").value;

  if (send_location === "Please Select" || end_location === "Please Select" || weight.trim() === "") {
    errorMessage("Please fill out all parcel details completely.");
    return false;
  }
  return true;
};

const validateSenderDetails = () => {
  const sender_name = document.getElementById("sender_name").value.trim();
  const sender_phone = document.getElementById("sender_phone").value.trim();
  const sender_address = document.getElementById("sender_address").value.trim();

  if (sender_name === "" || sender_phone === "" || sender_address === "") {
    errorMessage("Please fill out all sender details completely.");
    return false;
  }
  return true;
};

const validateReceiverDetails = () => {
  const rec_name = document.getElementById("rec_name").value.trim();
  const rec_phone = document.getElementById("rec_phone").value.trim();
  const rec_address = document.getElementById("rec_address").value.trim();

  if (rec_name === "" || rec_phone === "" || rec_address === "") {
    errorMessage("Please fill out all receiver details completely.");
    return false;
  }
  return true;
};

const addRequest = (form) => {
  if (!validateParcelDetails() || !validateSenderDetails() || !validateReceiverDetails()) {
    return;
  }

  const formData = new FormData(form);

  // Validate price is calculated
  if (formData.get("total_fee").trim() === "") {
    errorMessage("Please enter locations and weight to get the price.");
    return;
  }

  // Make AJAX request if all validations pass
  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=addRequest",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (data) {
      console.log(data);
      successToastRedirect("tracking.php");
    },
    error: function (error) {
      console.log(`Error ${error}`);
    },
  });
};

//profile changers

function changeEmail(form) {
  var formData = new FormData(form);

  var currentEmail = formData.get("current_email").trim();
  var newEmail = formData.get("new_email").trim();
  var customerId = formData.get("customer_id").trim();

  if (currentEmail === "" || newEmail === "") {
    errorMessage("Please fill out all required fields.");
    return;
  }

  if (checkEmail(currentEmail, customerId) <= 0) {
    errorMessage("Current Email is Wrong!");
    return;
  }

  if (!emailvalidation(newEmail)) {
    return;
  }

  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=checkEmailExist",
    data: { new_email: newEmail },
    success: function(response) {
      var result = JSON.parse(response);
      if (result.exists) {
        errorMessage("New Email Address already exists.");
      } else {
        updateEmail(customerId, newEmail);
      }
    },
    error: function(error) {
      console.log(`Error ${error}`);
    }
  });
}

function updateEmail(customerId, newEmail) {
  var data = {
    id: customerId,
    field: "email",
    value: newEmail,
    id_fild: "customer_id",
    table: "customer"
  };

  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=updateData",
    data: data,
    success: function(response) {
      console.log(response);
      successToastwithLogout();
    },
    error: function(error) {
      console.log(`Error ${error}`);
    }
  });
}

changePassword = (form) => {
  var formData = new FormData(form);

  if (formData.get("current_password").trim() != "") {
    if (formData.get("new_password").trim() != "") {
      if (formData.get("confirm_new_password").trim() != "") {
        if (
          formData.get("new_password") === formData.get("confirm_new_password")
        ) {
          if (
            checkPassword(
              formData.get("current_password"),
              formData.get("customer_id")
            ) > 0
          ) {
            var data = {
              id: formData.get("customer_id"),
              field: "password",
              value: formData.get("new_password"),
              id_fild: "customer_id",
              table: "customer",
            };

            $.ajax({
              method: "POST",
              url: "server/api.php?function_code=updateData",
              data: data,
              success: function ($data) {
                console.log($data);
                successToastwithLogout();
              },
              error: function (error) {
                console.log(`Error ${error}`);
              },
            });
          } else {
            errorMessage("Current Password is Wrong");
          }
        } else {
          errorMessage("Password is Not Match!");
        }
      } else {
        errorMessage("Please Enter Phone Number");
      }
    } else {
      errorMessage("Please Enter New Password");
    }
  } else {
    errorMessage("Please Enter Current Password");
  }
};

checkPassword = (password, customer_id) => {
  const data = {
    password: password,
    customer_id: customer_id,
  };
  var values;
  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=checkPassword",
    data: data,
    async: false,
    success: function (data) {
      values = data;
      console.log(data);
    },

    error: function (error) {
      console.log(`Error ${error}`);
    },
  });
  return values;
};

function checkEmail(email, customer_id) {
  const data = {
    email: email,
    customer_id: customer_id,
  };
  var values;

  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=checkEmail",
    data: data,
    async: false,
    success: function (data) {
      console.log(data);
      values = data;
    },
    error: function (error) {
      console.log(`Error ${error}`);
    },
  });

  return values;
}

updateDataFromHome = (ele, id, field, table, id_fild) => {
  var itemid = ele.id;
  var val = document.getElementById(ele.id).value;

  var data = {
    id_fild: id_fild,
    id: id,
    field: field,
    value: val,
    table: table,
  };

  if (field == "email") {
    if (emailvalidation(val)) {
      callUpdateRequestFromHome(data);
    }
  } else if (field == "phone") {
    if (phonenumber(val)) {
      callUpdateRequestFromHome(data);
    }
  } else {
    callUpdateRequestFromHome(data);
  }
};

deleteDataFromHome = (id, table, id_fild) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      var data = {
        id: id,
        table: table,
        id_fild: id_fild,
      };

      console.log(data);

      $.ajax({
        method: "POST",
        url: "server/api.php?function_code=deleteData",
        data: data,
        success: function ($data) {
          console.log($data);
          successToastwithLogout();
        },
        error: function (error) {
          console.log(`Error ${error}`);
        },
      });
      Swal.fire("Deleted!", "Your file has been deleted.", "success");
    }
  });
};

function confirmCancellation(trackingId) {
  Swal.fire({
      title: 'Are you sure?',
      text: "Do you really want to cancel this order?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, cancel it!',
      cancelButtonText: 'No, keep it'
  }).then((result) => {
      if (result.isConfirmed) {
          // Proceed with updating the tracking status
          updateTrackingStatus(trackingId, 5);
      } else {
          // Reset the select to its previous value if canceled
          var previousStatus = 1; // Previous status was 1 (Order Pending)
          $("#tracking_status_" + trackingId).val(previousStatus);
      }
  });
}

function updateTrackingStatus(trackingId, status) {
  var data = {
      tracking_id: trackingId,
      tracking_status: status
  };

  $.ajax({
      method: "POST",
      url: "server/api.php?function_code=updateTrackingStatus",
      data: data,
      success: function(response) {
          console.log(response);
          if (response === 'success') {
              iziToast.success({
                  timeout: 1000,
                  title: 'Success',
                  message: 'Order status updated successfully!',
                  position: 'center',
                  onClosing: function(instance, toast, closedBy) {
                    // Redirect after iziToast has closed
                    setTimeout(function() {
                        window.location.href = 'profile.php';
                    }, 500);
                  }
              });
          } else {
              iziToast.error({
                  title: 'Error',
                  message: 'Failed to update order status.',
                  position: 'center',
              });
          }
      },
      error: function(error) {
          console.log(`Error ${error}`);
          iziToast.error({
              title: 'Error',
              message: 'An error occurred while updating the order status.',
              position: 'center',
          });
      }
  });
}

function profileUpdate(customer_id) {
  var name = document.getElementById('new_name').value;
  var phone = document.getElementById('new_phone').value;
  var address = document.getElementById('new_address').value;
  var gender = document.getElementById('new_gender').value;

  var formData = {
      customer_id: customer_id,
      new_name: name,
      new_phone: phone,
      new_address: address,
      new_gender: gender
  };

  $.ajax({
      type: 'POST',
      url: 'server/api.php?function_code=updateProfile',
      data: JSON.stringify(formData),
      contentType: 'application/json',
      success: function(data) {
          if (data === 'success') {
              iziToast.success({
                  timeout: 1000,
                  title: 'Success',
                  message: 'Profile updated successfully!',
                  position: 'center',
                  onClosing: function(instance, toast, closedBy) {
                    // Redirect after iziToast has closed
                    setTimeout(function() {
                        window.location.href = 'profile.php';
                    }, 500);
                  }
              });
          } else {
              iziToast.error({
                  title: 'Error',
                  message: 'Failed to update profile!',
                  position: 'center',
              });
          }
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
          iziToast.error({
              title: 'Error',
              message: 'Error updating profile!',
              position: 'center',
          });
      }
  });
}