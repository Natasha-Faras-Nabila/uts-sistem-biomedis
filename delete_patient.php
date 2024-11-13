<?php
session_start();
if (!isset($_SESSION['user'])) {
   header("Location: login.php");
   exit();
}

include 'db.php';

// Menangani penghapusan pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   if (isset($_POST['patient_id'])) {
       // Hapus dari database menggunakan prepared statement
       $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
       if ($stmt) {
           // Bind parameter dan eksekusi query
           if ($stmt->bind_param("i", $_POST['patient_id']) && !$stmt->execute()) {
               echo "Error: " . mysqli_error($conn);
           } else {
               echo "Pasien berhasil dihapus.";
               header("Location: patients.php"); // Redirect setelah hapus
               exit();
           }
       }
   }
}

// Mengambil data pasien untuk ditampilkan dalam dropdown
$result = mysqli_query($conn, "SELECT * FROM patients");
?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Delete Pasien</title>
</head>

<body>
<div class='container'>
<h2>Delete Pasien</h2>

<form method='POST'>
   <div class='form-group'>
       <label>Pilih Pasien:</label><br/>
       <select name='patient_id' required class='form-control'>
           <option value='' disabled selected>Pilih Pasien...</option>
           <?php while ($row = mysqli_fetch_assoc($result)): ?>
               <option value='<?php echo htmlspecialchars($row['patient_id']); ?>'><?php echo htmlspecialchars($row['name']); ?></option>
           <?php endwhile; ?>
       </select><br/>
   </div>

   <!-- Tombol delete -->
   <button type='submit' class='btn btn-danger'>Delete Pasien</button> 
</form>

<a href="patients.php" class="btn btn-secondary">Kembali ke Daftar Pasien</a>

</div></body></html>

<?php
$conn->close();
?>