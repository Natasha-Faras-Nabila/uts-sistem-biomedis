<?php
session_start();
if (!isset($_SESSION['user'])) {
   header("Location: login.php");
   exit();
}

include 'db.php';

// Menangani pembaruan rekam medis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $record_id = $_POST['record_id'];
   $patient_id = $_POST['patient_id'];
   $doctor_id = $_POST['doctor_id'];
   $visit_date = $_POST['visit_date'];
   $diagnosis = $_POST['diagnosis'];
   $treatment = $_POST['treatment'];

   // Update ke database
   $stmt = $conn->prepare("UPDATE medical_records SET patient_id = ?, doctor_id = ?, visit_date = ?, diagnosis = ?, treatment = ? WHERE record_id = ?");
   $stmt->bind_param("iisssi", $patient_id, $doctor_id, $visit_date, $diagnosis, $treatment, $record_id);

   if ($stmt->execute()) {
       echo "Rekam medis berhasil diperbarui.";
       header("Location: records.php"); // Redirect setelah update
       exit();
   } else {
       echo "Error: " . $stmt->error;
   }
}

// Mengambil data rekam medis untuk ditampilkan
$record_id = $_GET['id']; // Ambil ID rekam medis dari URL
$stmt = $conn->prepare("SELECT * FROM medical_records WHERE record_id = ?");
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result_record = $stmt->get_result()->fetch_assoc();

// Ambil semua pasien dan dokter untuk dropdown
$patients_result = mysqli_query($conn, "SELECT * FROM patients");
$doctors_result = mysqli_query($conn, "SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Edit Rekam Medis</title>
</head>

<body>
<div class="container">
<h2>Edit Rekam Medis</h2>

<form method="POST">
   <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($record_id); ?>">

   <div class="form-group">
       <label>Pilih Pasien:</label><br/>
       <select name="patient_id" required class="form-control">
           <?php while ($row_patient=mysqli_fetch_assoc($patients_result)): ?>
               <option value="<?php echo htmlspecialchars($row_patient["patient_id"]); ?>" <?php if($row_patient["patient_id"] == $result_record["patient_id"]) echo 'selected'; ?>>
                   <?php echo htmlspecialchars($row_patient["name"]); ?>
               </option>
           <?php endwhile; ?>
       </select><br/>
   </div>

   <div class="form-group">
       <label>Pilih Dokter:</label><br/>
       <select name="doctor_id" required class="form-control">
           <?php while ($row_doctor=mysqli_fetch_assoc($doctors_result)): ?>
               <option value="<?php echo htmlspecialchars($row_doctor["doctor_id"]); ?>" <?php if($row_doctor["doctor_id"] == $result_record["doctor_id"]) echo 'selected'; ?>>
                   <?php echo htmlspecialchars($row_doctor["name"]); ?>
               </option>
           <?php endwhile; ?>
       </select><br/>
   </div>

   <div class="form-group">
       <label>Tanggal Kunjungan:</label><br/>
       <input type="date" name="visit_date" value="<?php echo htmlspecialchars($result_record["visit_date"]); ?>" required class="form-control"/>
   </div>

   <div class="form-group">
       <label>Diagnosis:</label><br/>
       <textarea name="diagnosis" required class="form-control"><?php echo htmlspecialchars($result_record["diagnosis"]); ?></textarea>
   </div>

   <div class="form-group">
       <label>Treatment:</label><br/>
       <textarea name="treatment" required class="form-control"><?php echo htmlspecialchars($result_record["treatment"]); ?></textarea>
   </div>

   <!-- Tombol update -->
   <button type="submit" class="btn btn-primary">Update Rekam Medis</button>
</form>

<a href="records.php" class="btn btn-secondary">Kembali ke Daftar Rekam Medis</a>

</div></body></html>

<?php
$conn->close();
?>