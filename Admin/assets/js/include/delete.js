permanantdeleteData = (id, table, id_fild) => {
  Swal.fire({
    title: "Are you sure? This record will be deleted permenantly",
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
        url: "../server/api.php?function_code=permanantDeleteData",
        data: data,
        success: function ($data) {
          console.log($data);
          successToastDelete();
        },
        error: function (error) {
          console.log(`Error ${error}`);
        },
      });
      Swal.fire("Deleted!", "The data has been deleted.", "success");
    }
  });
};

deleteData = (id, table, id_fild) => {
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
      console.log("Confirmed");
      $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=permanantDeleteData",
        data: JSON.stringify({ id: id, table: table, id_fild: id_fild }),
        contentType: 'application/json',
        success: function (response) {
          console.log(response);
          iziToast.success({
            timeout: 1000,
            title: 'Success',
            message: 'Data has been deleted',
            onClosing: function(instance, toast, closedBy) {
              // Redirect after iziToast has closed
              setTimeout(function() {
                location.reload();
              }, 500);
            }
          });
        },
        error: function (error) {
          console.log(`Error ${error}`);
        },
      });
    } else {
      console.log("Cancelled");
    }
  });
};