<?php
include("../includes/db.php");
include("../includes/layout.php");

$q = mysqli_query($conn,"SELECT expenses.*, drivers.driver_name 
FROM expenses
LEFT JOIN drivers ON expenses.driver_id = drivers.id
ORDER BY expenses.id DESC");


// جلب جميع السائقين
$drivers_options = "";
$drivers_q = mysqli_query($conn, "SELECT id, driver_name FROM drivers ORDER BY driver_name ASC");
while($driver = mysqli_fetch_assoc($drivers_q)){
    $drivers_options .= "<option value='{$driver['id']}'>{$driver['driver_name']}</option>";
}
?>

<div class="">
    <div class="card shadow-lg p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-purple"><i class="fa fa-gas-pump"></i> البنزين والصيانة</h3>
            <button class="btn btn-dark" id="bulkEditExpensesBtn">
                <i class="fa fa-edit"></i> تعديل جماعي
            </button>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMultipleExpensesModal">
                <i class="fa fa-plus"></i> إضافة متعدد
            </button>
        </div>

        <div class="table-responsive">
            <table id="expensesTable" class="table table-striped table-hover table-bordered text-center align-middle">
                <thead>
                    <tr data-image="<?php echo $row['invoice_image']; ?>">
                        <th><input type="checkbox" id="selectAllExpenses"></th>
                        <th>ID</th>
                        <th>السائق</th>
                        <th>نوع الخدمة</th>
                        <th>المبلغ</th>
                        <th>وصف العطل</th>
                        <th>الفاتورة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($q)){ ?>
                    <tr>
                        <td><input type="checkbox" class="expenseCheckbox" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['driver_name']; ?></td>
                        <td><?php echo $row['service_type']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['problem_description'] ? $row['problem_description'] : "-"; ?></td>
                        <td>
                            <?php if($row['invoice_image']) { ?>
                            <img src="../uploads/<?php echo $row['invoice_image']; ?>" width="80" class="rounded">
                            <?php } else { echo "-"; } ?>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <button onclick="deleteItem('delete.php?id=<?php echo $row['id']; ?>')" class="btn btn-danger btn-sm mb-1 gradient-danger" data-bs-toggle="tooltip" title="حذف العملية">
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

<!-- مودال تعديل جماعي -->
<div class="modal fade" id="bulkEditExpensesModal">
  <div class="modal-dialog modal-xl">
    <form id="bulkEditExpensesForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5>تعديل جماعي للعمليات</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>السائق</th>
                <th>نوع الخدمة</th>
                <th>المبلغ</th>
                <th>وصف العطل/أخرى</th>
                <th>الفاتورة</th>
                <th>التاريخ والوقت</th>
              </tr>
            </thead>
            <tbody id="bulkExpensesContainer"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-dark">حفظ</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="addMultipleExpensesModal">
  <div class="modal-dialog modal-xl">
    <form id="addMultipleExpensesForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5>إضافة عمليات متعددة</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table text-center">
            <thead class="table-success">
              <tr>
                <th>#</th>
                <th>السائق</th>
                <th>نوع الخدمة</th>
                <th>المبلغ</th>
                <th>وصف العطل/أخرى</th>
                <th>الفاتورة</th>
                <th>التاريخ والوقت</th>
                <th>حذف</th>
              </tr>
            </thead>
            <tbody id="multiExpensesContainer">
              <tr class="expense-row">
                <td class="row-index">1</td>
                <td>
                  <select name="driver_id[]" class="form-select">
                    <?php echo $drivers_options; ?>
                  </select>
                </td>
                <td>
                  <select name="service_type[]" class="form-select service-type">
                    <option value="fuel">بنزين</option>
                    <option value="internet">انترنت</option>
                    <option value="maintenance">عطل وصيانة</option>
                    <option value="other">أخرى</option>
                  </select>
                </td>
                <td><input type="number" name="amount[]" class="form-control" step="0.01"></td>
                <td>
                  <textarea name="problem_description[]" class="form-control problem-text" style="display:none;"></textarea>
                </td>
                <td>
                  <input type="file" name="invoice_image[]" class="form-control invoice-input mt-1">
                  <img src="" class="preview-img mt-2" style="width:70px;height:70px;display:none;border-radius:8px;">
                </td>
                <td>
                  <input type="datetime-local" name="created_at[]" class="form-control">
                </td>
                <td>
                  <button type="button" class="btn btn-danger removeRow">🗑</button>
                </td>
              </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-success" id="addExpenseRow">إضافة صف</button>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">حفظ</button>
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
#expensesTable thead {
    background: linear-gradient(45deg,#6f42c1,#a374d1);
    color: #fff;
    font-weight: bold;
    text-align: center;
}
#expensesTable thead th {
    border: none;
}
#expensesTable tbody tr:hover {
    background-color: rgba(111,66,193,0.1);
}

/* Rounded invoice images */
#expensesTable img {
    border-radius: 8px;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function deleteItem(url){
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لن تستطيع التراجع!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = url;
        }
    })
}

$(document).ready(function(){

    // ===== DataTable =====
    $('#expensesTable').DataTable({
        language: {"url":"//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json"},
        pageLength:10,lengthMenu:[5,10,20,50],ordering:true,info:true,responsive:true
    });

    // ===== Tooltip =====
    var tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipList.map(el => new bootstrap.Tooltip(el));

    // ===== حذف عملية =====
    window.deleteItem = function(url){
        Swal.fire({
            title:'هل أنت متأكد؟',text:"لن تستطيع التراجع!",icon:'warning',
            showCancelButton:true,confirmButtonText:'نعم احذف',cancelButtonText:'إلغاء'
        }).then(result=>{ if(result.isConfirmed){ window.location=url; } });
    }

    // ===== Toggle textarea حسب النوع =====
    function toggleProblemField(row){
        let type = row.find('.service-type').val();
        let textarea = row.find('.problem-text');
        if(type==='maintenance' || type==='other'){
            textarea.show();
        } else {
            textarea.hide().val('');
        }
    }

    // ===== إضافة صف جديد =====
    $('#addExpenseRow').click(function(){
        let row = $('.expense-row:first').clone(true); // clone مع events
        row.find('input, textarea').val('');
        row.find('.preview-img').attr('src','').hide();
        row.find('select.service-type').val('fuel');
        toggleProblemField(row);
        $('#multiExpensesContainer').append(row);
        reindexExpenseRows();
    });

    // ===== حذف صف =====
    $('#multiExpensesContainer').on('click','.removeRow',function(){
        if($('#multiExpensesContainer tr').length>1){
            $(this).closest('tr').remove();
            reindexExpenseRows();
        }
    });

    // ===== إعادة ترقيم الصفوف =====
    function reindexExpenseRows(){
        $('#multiExpensesContainer tr').each(function(i){
            $(this).find('.row-index').text(i+1);
        });
    }

    // ===== عند تغيير النوع في الإضافة =====
    $('#multiExpensesContainer').on('change','.service-type',function(){
        toggleProblemField($(this).closest('tr'));
    });

    // ===== Preview الصورة =====
    $('#multiExpensesContainer').on('change','.invoice-input',function(e){
        let file = e.target.files[0];
        let row = $(this).closest('tr'); // بدل td
        let preview = row.find('.preview-img');
        if(file){
            let reader = new FileReader();
            reader.onload = e => preview.attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        } else preview.hide();
    });

    // select all
    $('#selectAllExpenses').click(function(){
        $('.expenseCheckbox').prop('checked', $(this).prop('checked'));
    });

    // ===== تعديل جماعي =====
    $('#bulkEditExpensesBtn').click(function(){
        let selected = [];

        $('.expenseCheckbox:checked').each(function(){
            let row = $(this).closest('tr');
            let image = row.find('img').attr('src') || '';
            selected.push({
                id: $(this).val(),
                driver: row.find('td:eq(2)').text(),
                type: row.find('td:eq(3)').text(),
                amount: row.find('td:eq(4)').text(),
                problem: row.find('td:eq(5)').text()=='-'?'':row.find('td:eq(5)').text(),
                image: image,
                dateandtime: row.find('td:eq(7)').text(),
            });
        });

        if(selected.length==0){ alert('اختار عملية واحدة على الأقل'); return; }

        let html='';
        selected.forEach(v=>{
            let imageHtml = v.image ? `<img src="${v.image}" style="width:70px;height:70px;border-radius:8px;">` : '';
            html+=`
                <tr>
                    <td>${v.id}<input type="hidden" name="id[]" value="${v.id}"></td>
                    <td>
                        <select name="driver_id[]" class="form-select">
                            <?php echo $drivers_options; ?>
                        </select>
                    </td>
                    <td>
                        <select name="service_type[]" class="form-select service-type">
                            <option value="fuel" ${v.type=='fuel'?'selected':''}>بنزين</option>
                            <option value="internet" ${v.type=='internet'?'selected':''}>انترنت</option>
                            <option value="maintenance" ${v.type=='maintenance'?'selected':''}>عطل وصيانة</option>
                            <option value="other" ${v.type=='other'?'selected':''}>أخرى</option>
                        </select>
                    </td>
                    <td><input type="number" name="amount[]" value="${v.amount}" class="form-control" step="0.01"></td>
                    <td>
                        <textarea name="problem_description[]" class="form-control problem-text" style="${v.type=='maintenance'||v.type=='other'?'':'display:none;'}">${v.problem}</textarea>
                    </td>
                    <td>
                        ${imageHtml}
                        <input type="file" name="invoice_image[]" class="form-control invoice-input mt-2">
                        <img src="" class="preview-img mt-2" style="width:70px;height:70px;display:none;border-radius:8px;">
                    </td>
                    <td>
                      <input type="datetime-local" name="created_at[]" class="form-control" value="${v.dateandtime}">
                    </td>
                </tr>
            `;
        });

        $('#bulkExpensesContainer').html(html);
        $('#bulkEditExpensesModal').modal('show');
    });

    $('#bulkExpensesContainer').on('change','.service-type',function(){
        toggleProblemField($(this).closest('tr'));
    });
    $('#bulkExpensesContainer').on('change','.invoice-input',function(e){
        let file = e.target.files[0];
        let row = $(this).closest('tr');
        let preview = row.find('.preview-img');
        if(file){
            let reader = new FileReader();
            reader.onload = e => preview.attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        } else preview.hide();
    });

});

$('#addMultipleExpensesForm').submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: 'add_multiple_expenses.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(){
            Swal.fire('تمت الإضافة', '', 'success');
            setTimeout(()=> location.reload(),500);
        }
    });
});
$('#bulkEditExpensesForm').submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: 'bulk_update_expenses.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(){
            Swal.fire('تم التحديث', '', 'success');
            setTimeout(()=> location.reload(),500);
        }
    });
});
</script>