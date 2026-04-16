<?php
include("../includes/db.php");
include("../includes/layout.php");

$q = mysqli_query($conn,"SELECT drivers.*, vehicles.plate_number, vehicles.vehicle_model, vehicles.vehicle_type, vehicles.ownership , vehicles.registration_image FROM drivers LEFT JOIN vehicles ON drivers.vehicle_id = vehicles.id");
?>

<div class="">
    <div class="card shadow-lg p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-purple"><i class="fa fa-users"></i> <?php echo $t['drivers']; ?></h3>
            <button class="btn btn-dark" id="bulkEditBtn">
                <i class="fa fa-edit"></i> <?php echo $t['bulk_edit']; ?>
            </button>
            <button class="btn btn-success gradient-btn" data-bs-toggle="modal" data-bs-target="#addMultipleDriversModal">
                <i class="fa fa-plus"></i> <?php echo $t['add_multiple']; ?>
            </button>
        </div>

        <div class="table-responsive">
            <table id="driversTable" class="table table-striped table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th><?php echo $t['iqama']; ?></th>
                        <th><?php echo $t['name']; ?></th>
                        <th><?php echo $t['license']; ?></th>
                        <th><?php echo $t['type']; ?></th>
                        <th><?php echo $t['salary']; ?></th>
                        <th><?php echo $t['vehicle']; ?></th>
                        <th><?php echo $t['actions']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($q)){ ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="driverCheckbox" value="<?php echo $row['id']; ?>">
                        </td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['iqama_number']; ?></td>
                        <td><?php echo $row['driver_name']; ?></td>
                        <td><?php echo $row['license_number']; ?></td>
                        <td><?php echo $row['driver_type']; ?></td>
                        <td><?php echo $row['salary'] ? $row['salary'] : "-"; ?></td>
                        <td><?php echo $row['plate_number'] ? $row['plate_number'] : "-"; ?></td>
                        <td>
                            <button onclick="deleteItem('delete.php?id=<?php echo $row['id']; ?>')" class="btn btn-danger btn-sm mb-1 gradient-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-info btn-sm mb-1 gradient-info linkVehicleBtn"
                                data-id="<?php echo $row['id']; ?>"
                                data-vehicle="<?php echo $row['vehicle_id']; ?>"
                                data-bs-toggle="modal" data-bs-target="#linkVehicleModal">
                                <i class="fa fa-link"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="bulkEditModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form id="bulkEditForm">
      <div class="modal-content">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title"><?php echo $t['bulk_edit']; ?></h5>
          <button type="button" class="btn-close btn-close-white me-auto" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th><?php echo $t['iqama']; ?></th>
                  <th><?php echo $t['name']; ?></th>
                  <th><?php echo $t['license']; ?></th>
                  <th><?php echo $t['type']; ?></th>
                  <th><?php echo $t['salary']; ?></th>
                </tr>
              </thead>
              <tbody id="bulkEditContainer"></tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-dark"><?php echo $t['save_all']; ?></button>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- ربط المركبة -->
<div class="modal fade" id="linkVehicleModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form id="linkVehicleForm">
      <input type="hidden" name="driver_id" id="vehicleDriverId">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><?php echo $t['link_vehicle']; ?></h5>
          <button type="button" class="btn-close btn-close-white me-auto" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <select name="vehicle_id" id="vehicleSelect" class="form-select mb-3">
            <option value=""><?php echo $t['choose_vehicle']; ?></option>
            <?php
            $vehicles = mysqli_query($conn,"SELECT * FROM vehicles");
            while($v = mysqli_fetch_assoc($vehicles)){
                echo "<option value='{$v['id']}'
                    data-plate='{$v['plate_number']}'
                    data-model='{$v['vehicle_model']}'
                    data-type='{$v['vehicle_type']}'
                    data-owner='{$v['ownership']}'
                    data-image='{$v['registration_image']}'>
                    {$v['plate_number']}
                </option>";
            }
            ?>
          </select>
          <div id="vehicleDetails" style="display:none;">
            <div class="vehicle-card">
                <div class="vehicle-card-header">
                    <i class="fa fa-car"></i> <?php echo $t['vehicle_form']; ?>
                </div>

                <div class="vehicle-card-body">
                    <div class="row">
                        <div class="col-6">
                            <label><?php echo $t['plate_number']; ?></label>
                            <div class="value" id="v_plate"></div>
                        </div>
                        <div class="col-6">
                            <label><?php echo $t['vehicle_model']; ?></label>
                            <div class="value" id="v_model"></div>
                        </div>
                        <div class="col-6">
                            <label><?php echo $t['vehicle_type']; ?></label>
                            <div class="value" id="v_type"></div>
                        </div>
                        <div class="col-6">
                            <label><?php echo $t['ownership']; ?></label>
                            <div class="value" id="v_owner"></div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <img id="v_image" src="" class="vehicle-img">
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info gradient-info"><?php echo $t['link_vehicle']; ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- المودال نفسه -->
<div class="modal fade" id="addMultipleDriversModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form id="addMultipleDriversForm">
      <div class="modal-content">
        
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><?php echo $t['add_multiple']; ?></h5>
          <button type="button" class="btn-close btn-close-white me-auto" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="table-responsive">
            <table class="table table-bordered text-center align-middle" id="multiDriversTable">
              <thead class="table-success">
                <tr>
                  <th>#</th>
                  <th><?php echo $t['iqama']; ?></th>
                  <th><?php echo $t['name']; ?></th>
                  <th><?php echo $t['license']; ?></th>
                  <th><?php echo $t['type']; ?></th>
                  <th><?php echo $t['salary']; ?></th>
                  <th><?php echo $t['delete']; ?></th>
                </tr>
              </thead>

              <tbody id="multipleDriversContainer">
                <tr class="driver-row">
                  <td class="row-index">1</td>
                  <td><input type="text" name="iqama[]" class="form-control" required></td>
                  <td><input type="text" name="driver_name[]" class="form-control" required></td>
                  <td><input type="text" name="license_number[]" class="form-control" required></td>
                  
                  <td>
                    <select name="driver_type[]" class="form-select driver-type">
                      <option value="راتب">راتب</option>
                      <option value="طلب">بالطلب</option>
                    </select>
                  </td>

                  <td class="salary-field">
                    <input type="number" name="salary[]" class="form-control">
                  </td>

                  <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <button type="button" class="btn btn-success mt-2" id="addRowMultiple">
            <i class="fa fa-plus"></i> <?php echo $t['add_row']; ?>
          </button>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><?php echo $t['save_all']; ?></button>
        </div>

      </div>
    </form>
  </div>
</div>

<style>
/* Card */
.card {
    border-radius: 15px;
}

/* Gradient Buttons */
.gradient-btn {
    background: linear-gradient(45deg,#4e73df,#6f42c1);
    border: none;
    color: #fff;
    transition: 0.3s;
}
.gradient-btn:hover {
    background: linear-gradient(45deg,#6f42c1,#4e73df);
}

/* Gradient Action Buttons */
.gradient-warning { background: linear-gradient(45deg,#f6c23e,#dda20a); color: #fff; }
.gradient-warning:hover { background: linear-gradient(45deg,#dda20a,#f6c23e); }

.gradient-danger { background: linear-gradient(45deg,#e74a3b,#be2617); color: #fff; }
.gradient-danger:hover { background: linear-gradient(45deg,#be2617,#e74a3b); }

.gradient-info { background: linear-gradient(45deg,#36b9cc,#1cc0d1); color: #fff; }
.gradient-info:hover { background: linear-gradient(45deg,#1cc0d1,#36b9cc); }

/* Table */
#driversTable thead {
    background: linear-gradient(45deg,#6f42c1,#a374d1);
    color: #fff;
    font-weight: bold;
    text-align: center;
}
#driversTable thead th {
    border: none;
}
#driversTable tbody tr:hover {
    background-color: rgba(111,66,193,0.1);
}
.vehicle-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    background: #fff;
    border: 2px solid #e3e6f0;
}

.vehicle-card-header {
    background: linear-gradient(45deg,#36b9cc,#1cc0d1);
    color: #fff;
    padding: 10px;
    font-weight: bold;
    text-align: center;
    font-size: 18px;
}

.vehicle-card-body {
    padding: 15px;
}

.vehicle-card label {
    font-weight: bold;
    color: #4e73df;
    font-size: 13px;
}

.vehicle-card .value {
    background: #f8f9fc;
    padding: 8px;
    border-radius: 8px;
    margin-bottom: 10px;
    font-weight: bold;
}

.vehicle-img {
    max-width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #ddd;
}
.vehicle-card {
    background: linear-gradient(135deg,#f8f9fc,#e9ecf5);
}
.modal-header {
    direction: rtl;
}

.modal-title {
    font-weight: bold;
}

.modal-body label {
    font-weight: bold;
    color: #5a5c69;
}

.modal-body input,
.modal-body select,
.modal-body textarea {
    border-radius: 10px;
    border: 1px solid #d1d3e2;
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.modal-footer {
    border-top: 1px solid #eee;
}

/* حل مشكلة النص الأبيض */
.bg-purple label,
.bg-warning label,
.bg-info label,
.bg-success label {
    color: #333 !important;
}

/* المسافات */
.modal-body .form-label {
    margin-bottom: 5px;
}

/* تحسين الشكل */
.modal-body {
    background: #f8f9fc;
}

.table {
    text-align: <?php echo ($lang=='ar') ? 'right' : 'left'; ?>;
}

.modal-header,
.modal-body,
.modal-footer {
    direction: <?php echo ($lang=='ar') ? 'rtl' : 'ltr'; ?>;
}
</style>

<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
  // =========================
// Toast
// =========================
function showToast(msg, type='success'){
    Toastify({
        text: msg,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: type=='success'
            ? "linear-gradient(to right, #4CAF50, #45A049)"
            : "linear-gradient(to right, #FF5252, #FF1744)",
    }).showToast();
} 
$(document).ready(function() {

    // DataTable
    $('#driversTable').DataTable({
        "language": {"url": "<?php echo ($lang=='ar') ? '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json' : '//cdn.datatables.net/plug-ins/1.13.4/i18n/English.json'; ?>"},
        "pageLength": 10,
        "lengthMenu": [5,10,20,50],
        "ordering": true,
        "info": true,
        "responsive": true
    });

    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // =========================
    // اظهار/اخفاء الراتب (إضافة)
    // =========================
    function toggleAddSalary(){
        if($('#addDriverType').val() == 'راتب'){
            $('#addSalaryField').show();
        }else{
            $('#addSalaryField').hide();
            $('#addSalaryField input').val('');
        }
    }
    toggleAddSalary();
    $('#addDriverType').change(toggleAddSalary);

    // =========================
    // اظهار/اخفاء الراتب (تعديل)
    // =========================
    function toggleEditSalary(){
        if($('#editDriverType').val() == 'راتب'){
            $('#editSalaryField').show();
        }else{
            $('#editSalaryField').hide();
            $('#editSalary').val('');
        }
    }
    $('#editDriverType').change(toggleEditSalary);

    // =========================
    // فتح مودال التعديل وملئ البيانات
    // =========================
    $('.editDriverBtn').click(function(){
        $('#editDriverId').val($(this).data('id'));
        $('#editIqama').val($(this).data('iqama'));
        $('#editName').val($(this).data('name'));
        $('#editLicense').val($(this).data('license'));
        $('#editDriverType').val($(this).data('type'));
        $('#editSalary').val($(this).data('salary'));
        $('#editNotes').val($(this).data('notes'));

        toggleEditSalary();
    });

    // =========================
    // عرض بيانات المركبة
    // =========================
    $('#vehicleSelect').change(function(){

        var selected = $('#vehicleSelect option:selected');

        var plate = selected.data('plate');
        var model = selected.data('model');
        var type  = selected.data('type');
        var owner = selected.data('owner');
        var image = selected.data('image');

        if($(this).val()){
            $('#v_plate').text(plate);
            $('#v_model').text(model);
            $('#v_type').text(type);
            $('#v_owner').text(owner);
            $('#v_image').attr('src','../uploads/'+image);

            $('#vehicleDetails').fadeIn();
        } else {
            $('#vehicleDetails').hide();
        }
    });

    // =========================
    // فتح مودال ربط مركبة
    // =========================
    $('.linkVehicleBtn').click(function(){
        $('#vehicleDriverId').val($(this).data('id'));
        $('#vehicleSelect').val($(this).data('vehicle')).trigger('change');
    });

    // =========================
    // إضافة متعددة - إضافة صف
    // =========================
    // إعادة ترقيم الصفوف
    function reindexRows(){
        $('#multipleDriversContainer tr').each(function(i){
            $(this).find('.row-index').text(i+1);
        });
    }

    // إضافة صف جديد
    $('#addRowMultiple').click(function(){
        let row = $('.driver-row:first').clone();

        row.find('input').val('');
        row.find('select').val('راتب');

        $('#multipleDriversContainer').append(row);

        reindexRows();
    });

    // حذف صف
    $('#multipleDriversContainer').on('click','.removeRow',function(){
        if($('#multipleDriversContainer tr').length > 1){
            $(this).closest('tr').remove();
            reindexRows();
        }
    });

    // اظهار/اخفاء الراتب
    $('#multipleDriversContainer').on('change','.driver-type',function(){
        let row = $(this).closest('tr');

        if($(this).val() === 'راتب'){
            row.find('.salary-field').show();
        }else{
            row.find('.salary-field').hide();
            row.find('input[name="salary[]"]').val('');
        }
    });

    // اظهار/اخفاء الراتب في الإضافة المتعددة
    $('#multipleDriversContainer').on('change', '.driver-type', function(){
        let row = $(this).closest('.driver-row');
        if($(this).val() == 'salary'){
            row.find('.salary-field').show();
        }else{
            row.find('.salary-field').hide();
            row.find('.salary-field input').val('');
        }
    });

    // =========================
    // AJAX Forms
    // =========================
    $('#addDriverForm').submit(function(e){
        e.preventDefault();
        $.post('add.php', $(this).serialize(), function(res){
            $('#addDriverModal').modal('hide');
            showToast('<?php echo $t['toast_add_driver']; ?>');
            setTimeout(()=> location.reload(),500);
        });
    });

    $('#editDriverForm').submit(function(e){
        e.preventDefault();
        $.post('edit.php', $(this).serialize(), function(res){
            $('#editDriverModal').modal('hide');
            showToast('<?php echo $t['toast_update_driver']; ?>');
            setTimeout(()=> location.reload(),500);
        });
    });

    $('#linkVehicleForm').submit(function(e){
        e.preventDefault();
        $.post('link_vehicle.php', $(this).serialize(), function(res){
            $('#linkVehicleModal').modal('hide');
            showToast('<?php echo $t['toast_link_vehicle']; ?>');
            setTimeout(()=> location.reload(),500);
        });
    });

    $('#addMultipleDriversForm').submit(function(e){
        e.preventDefault();
        $.post('add_multiple.php', $(this).serialize(), function(res){
            $('#addMultipleDriversModal').modal('hide');
            showToast('<?php echo $t['toast_add_multiple']; ?>');
            setTimeout(()=> location.reload(),500);
        });
    });

});

// =========================
// حذف
// =========================
function deleteItem(url){
    Swal.fire({
        title: '<?php echo $t['confirm_delete']; ?>',
        text: "<?php echo $t['warning_delete']; ?>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<?php echo $t['yes_delete']; ?>',
        cancelButtonText: '<?php echo $t['cancel']; ?>'
    }).then((result) => {
        if(result.isConfirmed){
            window.location = url;
        }
    })
}



// select all
$('#selectAll').click(function(){
    $('.driverCheckbox').prop('checked', $(this).prop('checked'));
});

// فتح التعديل الجماعي
$('#bulkEditBtn').click(function(){

    let selected = [];

    $('.driverCheckbox:checked').each(function(){
        let row = $(this).closest('tr');

        selected.push({
            id: $(this).val(),
            iqama: row.find('td:eq(2)').text(),
            name: row.find('td:eq(3)').text(),
            license: row.find('td:eq(4)').text(),
            type: row.find('td:eq(5)').text() == 'راتب' ? 'salary' : 'request',
            salary: row.find('td:eq(6)').text()
        });
    });

    if(selected.length == 0){
        alert('<?php echo $t['select_one_driver']; ?>');
        return;
    }

    let html = '';

    selected.forEach(d => {
        html += `
        <tr>
            <td>
              ${d.id}
              <input type="hidden" name="id[]" value="${d.id}">
            </td>
            <td><input type="text" name="iqama[]" value="${d.iqama}" class="form-control"></td>
            <td><input type="text" name="name[]" value="${d.name}" class="form-control"></td>
            <td><input type="text" name="license[]" value="${d.license}" class="form-control"></td>

            <td>
              <select name="type[]" class="form-select bulk-type">
                <option value="راتب" ${d.type=='salary'?'selected':''}>راتب</option>
                <option value="طلب" ${d.type=='request'?'selected':''}>طلب</option>
              </select>
            </td>

            <td>
              <input type="number" name="salary[]" value="${d.salary}" class="form-control bulk-salary">
            </td>
        </tr>
        `;
    });

    $('#bulkEditContainer').html(html);

    $('#bulkEditModal').modal('show');
});

$('#bulkEditForm').submit(function(e){
    e.preventDefault();

    $.post('bulk_update.php', $(this).serialize(), function(){
        $('#bulkEditModal').modal('hide');
        showToast('<?php echo $t['toast_bulk_update']; ?>');
        setTimeout(()=> location.reload(),500);
    });
});

$('#linkVehicleForm').submit(function(e){
    e.preventDefault();

    $.post('update_link_vehicle.php', $(this).serialize(), function(res){

        if(res.trim() === 'success'){
            $('#linkVehicleModal').modal('hide');
            showToast('<?php echo $t['toast_link_vehicle']; ?>');
            setTimeout(()=> location.reload(),500);
        }else{
            showToast('<?php echo $t['error_msg']; ?>', 'error');
        }

    });
});
</script>