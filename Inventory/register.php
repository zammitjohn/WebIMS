<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Register item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active">Register item</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card">
    
          <div class="card-body">

            <div class="form-group">
              <label for="input1">SKU</label>
              <select id="SKU" class="form-control">
                <option value="">None</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="input2">Serial Number</label>
              <input type="text" maxlength="255" class="form-control" id="serialNumber" placeholder="Enter serial number">
            </div>
            
            <div class="form-group">
              <label for="input3">Date Purchased</label>
              <input type="date" class="form-control" id="datePurchased">
            </div>     
            
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <input type="Button" class="btn btn-primary button_action_create" onClick="Register()" value="Submit"></input>
          </div>
   
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
';
$title = "Register item";
$ROOT = '../';
include('../master.php');
?>

<script>

// populate inventoryId dropdown
$(document).ready(function() {
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/read",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].SKU + " (" + data[element].category_name + ") " + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);

      // populate fields on 'add to'
      var urlParams = new URLSearchParams(window.location.search);
      var id = urlParams.get('id'); // inventoryId
      if (id != null) {
        $('#SKU').val(id);
        $('#SKU').attr('disabled', 'disabled'); // disable field
      }

    }
  });
});


function Register() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/registry/create',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      serialNumber: $("#serialNumber").val(),
      datePurchased: $("#datePurchased").val()
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '../inventory/view?id=' + $("#SKU").val();
      }
    }
  });
}
</script>