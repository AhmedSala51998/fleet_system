<?php
include("../includes/db.php");
include("../includes/layout.php");

$q = mysqli_query($conn,"SELECT * FROM vehicles");
?>

<div class="">
    <div class="card shadow-lg p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-purple"><i class="fa fa-car"></i> <?php echo $t['vehicles']; ?></h3>
            <button class="btn btn-dark" id="bulkEditVehiclesBtn">
                <i class="fa fa-edit"></i> <?php echo $t['bulk_edit']; ?>
            </button>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMultipleVehiclesModal">
                <i class="fa fa-plus"></i> <?php echo $t['add_vehicles']; ?>
            </button>
        </div>

        <div class="table-responsive">
            <table id="vehiclesTable" class="table table-striped table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllVehicles"></th>
                        <th>ID</th>
                        <th><?php echo $t['type']; ?></th>
                        <th><?php echo $t['model']; ?></th>
                        <th><?php echo $t['ownership']; ?></th>
                        <th><?php echo $t['plate']; ?></th>
                        <th><?php echo $t['image']; ?></th>
                        <th><?php echo $t['actions']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($q)){ ?>
                    <tr data-image="<?php echo $row['registration_image']; ?>">
                        <td>
                            <input type="checkbox" class="vehicleCheckbox" value="<?php echo $row['id']; ?>">
                        </td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['vehicle_type']; ?></td>
                        <td><?php echo $row['vehicle_model']; ?></td>
                        <td><?php echo $row['ownership']; ?></td>
                        <td><?php echo $row['plate_number']; ?></td>
                        <td>
                            <?php if($row['registration_image']) { ?>
                            <img src="../uploads/<?php echo $row['registration_image']; ?>" width="80" class="rounded">
                            <?php } else { echo "-"; } ?>
                        </td>
                        <td>
                            <button onclick="deleteItem('delete.php?id=<?php echo $row['id']; ?>')" class="btn btn-danger btn-sm mb-1 gradient-danger" data-bs-toggle="tooltip" title="<?php echo $t['confirm_delete']; ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkEditVehiclesModal">
  <div class="modal-dialog modal-xl">
    <form id="bulkEditVehiclesForm" enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header bg-dark text-white">
          <h5><?php echo $t['bulk_edit']; ?></h5>
          <!-- زر الإغلاق -->
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <table class="table table-bordered text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th><?php echo $t['type']; ?></th>
                <th><?php echo $t['model']; ?></th>
                <th><?php echo $t['ownership']; ?></th>
                <th><?php echo $t['plate']; ?></th>
                <th><?php echo $t['image']; ?></th>
              </tr>
            </thead>
            <tbody id="bulkVehiclesContainer"></tbody>
          </table>
        </div>

        <div class="modal-footer">
          <button class="btn btn-dark"><?php echo $t['save']; ?></button>
        </div>

      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="addMultipleVehiclesModal">
  <div class="modal-dialog modal-xl">
    <form id="addMultipleVehiclesForm" enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header bg-success text-white">
          <h5><?php echo $t['add_multiple']; ?></h5>
        </div>

        <div class="modal-body">

          <table class="table text-center">
            <thead class="table-success">
              <tr>
                <th>#</th>
                <th><?php echo $t['type']; ?></th>
                <th><?php echo $t['model']; ?></th>
                <th><?php echo $t['ownership']; ?></th>
                <th><?php echo $t['plate']; ?></th>
                <th><?php echo $t['image']; ?></th>
                <th><?php echo $t['actions']; ?></th>
              </tr>
            </thead>

            <tbody id="multiVehiclesContainer">
              <tr class="vehicle-row">
                <td class="row-index">1</td>

                <td><input type="text" name="type[]" class="form-control"></td>
                <td><input type="text" name="model[]" class="form-control"></td>

                <td>
                  <select name="ownership[]" class="form-select">
                    <option value="company"><?php echo $t['company']; ?></option>
                    <option value="rented"><?php echo $t['rented']; ?></option>
                  </select>
                </td>

                <td><input type="text" name="plate[]" class="form-control"></td>
                <td>
                    <input type="file" name="image[]" class="form-control image-input">

                    <img src="" class="preview-img mt-2" 
                        style="width:70px; height:70px; object-fit:cover; display:none; border-radius:8px;">
                </td>

                <td>
                  <button type="button" class="btn btn-danger removeRow">🗑</button>
                </td>
              </tr>
            </tbody>
          </table>

          <button type="button" class="btn btn-success" id="addVehicleRow">
            <?php echo $t['add_row']; ?>
          </button>

        </div>

        <div class="modal-footer">
          <button class="btn btn-success"><?php echo $t['save']; ?></button>
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

/* Table */
#vehiclesTable thead {
    background: linear-gradient(45deg,#6f42c1,#a374d1);
    color: #fff;
    font-weight: bold;
    text-align: center;
}
#vehiclesTable thead th {
    border: none;
}
#vehiclesTable tbody tr:hover {
    background-color: rgba(111,66,193,0.1);
}

/* Rounded registration images */
#vehiclesTable img {
    border-radius: 8px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
$(document).ready(function() {
    $('#vehiclesTable').DataTable({
        "language": {"url": "<?php echo ($lang=='ar') ? '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json' : '//cdn.datatables.net/plug-ins/1.13.4/i18n/English.json'; ?>"},
        "pageLength": 10,
        "lengthMenu": [5,10,20,50],
        "ordering": true,
        "info": true,
        "responsive": true
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
});

function deleteItem(url){
    Swal.fire({
        title: '<?php echo $t['confirm_delete']; ?>',
        text: '<?php echo $t['delete_warning']; ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<?php echo $t['yes_delete']; ?>',
        cancelButtonText: '<?php echo $t['cancel']; ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = url;
        }
    })
}


// select all
$('#selectAllVehicles').click(function(){
    $('.vehicleCheckbox').prop('checked', $(this).prop('checked'));
});

// فتح المودال
$('#bulkEditVehiclesBtn').click(function(){

    let selected = [];

    $('.vehicleCheckbox:checked').each(function(){
        let row = $(this).closest('tr');

        selected.push({
            id: $(this).val(),
            type: row.find('td:eq(2)').text(),
            model: row.find('td:eq(3)').text(),
            ownership: row.find('td:eq(4)').text(),
            plate: row.find('td:eq(5)').text(),
            image: row.data('image') // ✅ هنا المهم
        });
    });

    if(selected.length == 0){
        alert('<?php echo $t['select_one_vehicle_alert']; ?>');
        return;
    }

    let html = '';

    selected.forEach(v => {

        let imageHtml = '';

        if(v.image){
            imageHtml = `<img src="../uploads/${v.image}" 
                style="width:70px;height:70px;border-radius:8px;">`;
        }

        html += `
        <tr>
            <td>
                ${v.id}
                <input type="hidden" name="id[]" value="${v.id}">
            </td>

            <td><input type="text" name="type[]" value="${v.type}" class="form-control"></td>
            <td><input type="text" name="model[]" value="${v.model}" class="form-control"></td>

            <td>
                <select name="ownership[]" class="form-select">
                    <option value="company" ${v.ownership=='company'?'selected':''}><?php echo $t['company']; ?></option>
                    <option value="rented" ${v.ownership=='rented'?'selected':''}><?php echo $t['rented']; ?></option>
                </select>
            </td>

            <td><input type="text" name="plate[]" value="${v.plate}" class="form-control"></td>

            <td>
                ${imageHtml}

                <input type="file" name="image[]" class="form-control image-input mt-2">

                <img src="" class="preview-img mt-2"
                style="width:70px;height:70px;display:none;border-radius:8px;">
            </td>
        </tr>
        `;
    });

    $('#bulkVehiclesContainer').html(html);
    $('#bulkEditVehiclesModal').modal('show');
});
$('#bulkEditVehiclesForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: 'bulk_update_vehicles.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(){
            Swal.fire('<?php echo $t['update_success']; ?>', '', 'success');
            setTimeout(()=> location.reload(),500);
        }
    });
});

function reindexVehicleRows(){
    $('#multiVehiclesContainer tr').each(function(i){
        $(this).find('.row-index').text(i+1);
    });
}

$('#addVehicleRow').click(function(){
    let row = $('.vehicle-row:first').clone();

    row.find('input').val('');
    row.find('.preview-img').attr('src','').hide(); // ✅ تصفير الصورة

    $('#multiVehiclesContainer').append(row);
    reindexVehicleRows();
});

$('#multiVehiclesContainer').on('click','.removeRow',function(){
    if($('#multiVehiclesContainer tr').length > 1){
        $(this).closest('tr').remove();
        reindexVehicleRows();
    }
});
$('#addMultipleVehiclesForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: 'add_multiple_vehicles.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(){
            Swal.fire('<?php echo $t['add_success']; ?>', '', 'success');
            setTimeout(()=> location.reload(),500);
        }
    });
});
// =========================
// preview image
// =========================
$('#multiVehiclesContainer').on('change', '.image-input', function(e){

    let file = e.target.files[0];
    let row = $(this).closest('td');
    let preview = row.find('.preview-img');

    if(file){
        let reader = new FileReader();

        reader.onload = function(e){
            preview.attr('src', e.target.result);
            preview.show();
        }

        reader.readAsDataURL(file);
    } else {
        preview.hide();
    }
});
$('#bulkVehiclesContainer').on('change', '.image-input', function(e){

    let file = e.target.files[0];
    let row = $(this).closest('td');
    let preview = row.find('.preview-img');

    if(file){
        let reader = new FileReader();

        reader.onload = function(e){
            preview.attr('src', e.target.result);
            preview.show();
        }

        reader.readAsDataURL(file);
    } else {
        preview.hide();
    }
});
</script>