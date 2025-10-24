"use strict";

addContactMessage = function addContactMessage(form) {
  var formData = new FormData(form);

  if (formData.get("name").trim() != "") {
    if (formData.get("email").trim() != "") {
      if (formData.get("subject").trim() != "") {
        if (formData.get("message").trim() != "") {
          $.ajax({
            method: "POST",
            url: "server/api.php?function_code=addcontact",
            data: formData,
            success: function success($data) {
              console.log($data);
              successToast();
            },
            cache: false,
            contentType: false,
            processData: false,
            error: function error(_error) {
              console.log("Error ".concat(_error));
            }
          });
        } else {
          errorMessage("Please Enter Message");
        }
      } else {
        errorMessage("Please Enter Phone Number");
      }
    } else {
      errorMessage("Please Enter Email Address");
    }
  } else {
    errorMessage("Please Enter Your Name");
  }
};

document.addEventListener("DOMContentLoaded", function () {
  // Function to handle location changes and weight input
  var calculation = function calculation() {
    var send_location = document.getElementById("send_location").value;
    var end_location = document.getElementById("end_location").value;
    var weight = document.getElementById("weight").value; // Validate that both locations are selected and weight is entered

    if (send_location === "Please Select" || end_location === "Please Select" || weight.trim() === "") {
      return; // Exit the function if validations fail
    }

    var data = {
      send_location: send_location,
      end_location: end_location
    }; // Make AJAX request only if all fields are valid

    $.ajax({
      method: "POST",
      url: "server/api.php?function_code=checkArea",
      data: data,
      success: function success(response) {
        console.log(response);

        if (response > 0) {
          var sum = parseInt(weight) * parseInt(response);
          document.getElementById("total").value = sum;
          document.getElementById("total_fee").value = sum;
        } else {
          errorMessage_R("This area is not in our service area");
        }
      },
      error: function error(_error2) {
        console.log("Error ".concat(_error2));
      }
    });
  }; // Add event listeners to trigger the calculation


  document.getElementById("send_location").addEventListener("change", calculation);
  document.getElementById("end_location").addEventListener("change", calculation);
  document.getElementById("weight").addEventListener("input", calculation);
});

var validateParcelDetails = function validateParcelDetails() {
  var send_location = document.getElementById("send_location").value;
  var end_location = document.getElementById("end_location").value;
  var weight = document.getElementById("weight").value;

  if (send_location === "Please Select" || end_location === "Please Select" || weight.trim() === "") {
    errorMessage("Please fill out all parcel details completely.");
    return false;
  }

  return true;
};

var validateSenderDetails = function validateSenderDetails() {
  var sender_name = document.getElementById("sender_name").value.trim();
  var sender_phone = document.getElementById("sender_phone").value.trim();
  var sender_address = document.getElementById("sender_address").value.trim();

  if (sender_name === "" || sender_phone === "" || sender_address === "") {
    errorMessage("Please fill out all sender details completely.");
    return false;
  }

  return true;
};

var validateReceiverDetails = function validateReceiverDetails() {
  var rec_name = document.getElementById("rec_name").value.trim();
  var rec_phone = document.getElementById("rec_phone").value.trim();
  var rec_address = document.getElementById("rec_address").value.trim();

  if (rec_name === "" || rec_phone === "" || rec_address === "") {
    errorMessage("Please fill out all receiver details completely.");
    return false;
  }

  return true;
};

var addRequest = function addRequest(form) {
  if (!validateParcelDetails() || !validateSenderDetails() || !validateReceiverDetails()) {
    return;
  }

  var formData = new FormData(form); // Validate price is calculated

  if (formData.get("total_fee").trim() === "") {
    errorMessage("Please enter locations and weight to get the price.");
    return;
  } // Make AJAX request if all validations pass


  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=addRequest",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function success(data) {
      console.log(data);
      successToastRedirect("tracking.php");
    },
    error: function error(_error3) {
      console.log("Error ".concat(_error3));
    }
  });
};
/* addRequest = (form) => {
  var formData = new FormData(form);

  if (formData.get("sender_phone").trim() != "") {
    if (formData.get("weight").trim() != "") {
      if (formData.get("total_fee").trim() != "") {
        if (formData.get("rec_phone").trim() != "") {
          if (formData.get("rec_address").trim() != "") {
            $.ajax({
              method: "POST",
              url: "server/api.php?function_code=addRequest",
              data: formData,
              success: function ($data) {
                console.log($data);
                successToastRedirect("tracking.php");
              },
              cache: false,
              contentType: false,
              processData: false,
              error: function (error) {
                console.log(`Error ${error}`);
              },
            });
          } else {
            errorMessage("Please Enter Receiver Address");
          }
        } else {
          errorMessage("Please Enter Receiver Phone Number");
        }
      } else {
        errorMessage("Please Enter Locations to get Price");
      }
    } else {
      errorMessage("Please Enter Parcel Weight");
    }
  } else {
    errorMessage("Please Enter Your Phone Number");
  }
};

calculation = (ele) => {
  var send_location = document.getElementById("send_location").value;
  var end_location = document.getElementById(ele.id).value;
  var weight = document.getElementById("weight").value;

  var data = {
    send_location: send_location,
    end_location: end_location,
  };

  if (weight.trim() != "") {
    $.ajax({
      method: "POST",
      url: "server/api.php?function_code=checkArea",
      data: data,
      success: function ($data) {
        console.log($data);

        if ($data > 0) {
          var sum = parseInt(weight) * parseInt($data);
          document.getElementById("total").value = sum;
          document.getElementById("total_fee").value = sum;
        } else {
          errorMessage_R("this area not in our service area");
        }
      },
      error: function (error) {
        console.log(`Error ${error}`);
      },
    });
  } else {
    errorMessage_R("Please enter Weight");
  }
}; */
//profile changers


changeEmail = function changeEmail(form) {
  var formData = new FormData(form);

  if (formData.get("current_email").trim() != "") {
    if (formData.get("new_email").trim() != "") {
      if (checkEmail(formData.get("current_email"), formData.get("customer_id")) > 0) {
        var data = {
          id: formData.get("customer_id"),
          field: "email",
          value: formData.get("new_email"),
          id_fild: "customer_id",
          table: "customer"
        };
        $.ajax({
          method: "POST",
          url: "server/api.php?function_code=updateData",
          data: data,
          success: function success($data) {
            console.log($data);
            successToastwithLogout();
          },
          error: function error(_error4) {
            console.log("Error ".concat(_error4));
          }
        });
      } else {
        errorMessage("Current Emaiil is Wrong!");
      }
    } else {
      errorMessage("Please Enter Email Address");
    }
  } else {
    errorMessage("Please Enter Your Name");
  }
};

changePassword = function changePassword(form) {
  var formData = new FormData(form);

  if (formData.get("current_password").trim() != "") {
    if (formData.get("new_password").trim() != "") {
      if (formData.get("confirm_new_password").trim() != "") {
        if (formData.get("new_password") === formData.get("confirm_new_password")) {
          if (checkPassword(formData.get("current_password"), formData.get("customer_id")) > 0) {
            var data = {
              id: formData.get("customer_id"),
              field: "password",
              value: formData.get("new_password"),
              id_fild: "customer_id",
              table: "customer"
            };
            $.ajax({
              method: "POST",
              url: "server/api.php?function_code=updateData",
              data: data,
              success: function success($data) {
                console.log($data);
                successToastwithLogout();
              },
              error: function error(_error5) {
                console.log("Error ".concat(_error5));
              }
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

checkPassword = function checkPassword(password, customer_id) {
  var data = {
    password: password,
    customer_id: customer_id
  };
  var values;
  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=checkPassword",
    data: data,
    async: false,
    success: function success(data) {
      values = data;
      console.log(data);
    },
    error: function error(_error6) {
      console.log("Error ".concat(_error6));
    }
  });
  return values;
};

function checkEmail(email, customer_id) {
  var data = {
    email: email,
    customer_id: customer_id
  };
  var values;
  $.ajax({
    method: "POST",
    url: "server/api.php?function_code=checkEmail",
    data: data,
    async: false,
    success: function success(data) {
      console.log(data);
      values = data;
    },
    error: function error(_error7) {
      console.log("Error ".concat(_error7));
    }
  });
  return values;
}

updateDataFromHome = function updateDataFromHome(ele, id, field, table, id_fild) {
  var itemid = ele.id;
  var val = document.getElementById(ele.id).value;
  var data = {
    id_fild: id_fild,
    id: id,
    field: field,
    value: val,
    table: table
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

deleteDataFromHome = function deleteDataFromHome(id, table, id_fild) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then(function (result) {
    if (result.isConfirmed) {
      var data = {
        id: id,
        table: table,
        id_fild: id_fild
      };
      console.log(data);
      $.ajax({
        method: "POST",
        url: "server/api.php?function_code=deleteData",
        data: data,
        success: function success($data) {
          console.log($data);
          successToastwithLogout();
        },
        error: function error(_error8) {
          console.log("Error ".concat(_error8));
        }
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
  }).then(function (result) {
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
    success: function success(response) {
      console.log(response);

      if (response === 'success') {
        iziToast.success({
          timeout: 1000,
          title: 'Success',
          message: 'Order status updated successfully!'
        });
      } else {
        iziToast.error({
          title: 'Error',
          message: 'Failed to update order status.'
        });
      }
    },
    error: function error(_error9) {
      console.log("Error ".concat(_error9));
      iziToast.error({
        title: 'Error',
        message: 'An error occurred while updating the order status.'
      });
    }
  });
}
//# sourceMappingURL=homejs.dev.js.map
