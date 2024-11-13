<?php
session_start();
if (!isset($_SESSION['user'])) {
   header("Location: login.php");
   exit();
}

include 'db.php';

// Menangani penghapusan rekam medis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   if (isset($_POST['record_id'])) {
       $stmt = mysqli_prepare($conn, "DELETE FROM medical_records WHERE record_id=?");
       
       if ($stmt) {
           mysqli_stmt_bind_param($stmt, "i", $_POST["record_id"]);
           
           if (mysqli_stmt_execute($stmt)) {
               header('Location: records.php'); 
               exit(); 
           } else { 
               die('Error: ' . mysqli_error($conn));
           }
           
           mysqli_stmt_close($stmt);
      }
  }
}

// Mengambil data rekam medis untuk ditampilkan dalam dropdown 
$result = mysqli_query($conn, "SELECT medical_records.*, patients.name AS patient_name, doctors.name AS doctor_name FROM medical_records JOIN patients ON medical_records.patient_id = patients.patient_id JOIN doctors ON medical_records.doctor_id = doctors.doctor_id");

?>

<!DOCTYPE html> 
<html lang="id"> 
<head> 
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
<link rel="stylesheet" href="style.css"> 
<title>Delete Rekam Medis</title> 
</head> 
<body> 
<div class="container"> 
    <h2>Delete Rekam Medis</h2> 

    <form method="post"> 
        <div class="form-group"> 
            <label>Pilih Rekam Medis:</label><br/> 
            <select name="record_id" required class="form-control"> 
                <option value="" disabled selected>Pilih Rekam Medis</option>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['record_id']; ?>">
                        <?php echo $row['patient_name'] . " - " . $row['doctor_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div> 
        <button type="submit" class="btn btn-danger">Hapus Rekam Medis</button>
    </form>
</div> 

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
