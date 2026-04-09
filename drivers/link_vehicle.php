<?php
include("../includes/db.php");

$driver_id = $_GET['id'];

if(isset($_POST['save'])){
    $vehicle_id = $_POST['vehicle_id'];

    mysqli_query($conn,"UPDATE drivers SET vehicle_id='$vehicle_id' WHERE id='$driver_id'");
    header("Location: index.php");
}

$vehicles = mysqli_query($conn,"SELECT * FROM vehicles");
?>

<form method="POST">
    <label>اختر المركبة</label>
    <select name="vehicle_id">
        <?php while($v = mysqli_fetch_assoc($vehicles)){ ?>
        <option value="<?php echo $v['id']; ?>">
            <?php echo $v['vehicle_type']." - ".$v['plate_number']; ?>
        </option>
        <?php } ?>
    </select>

    <button name="save">ربط</button>
</form>