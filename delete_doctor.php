<?php
session_start();
if (!isset($_SESSION['user'])) {
   header("Location: login.php");
   exit();
}

include 'db.php';

// Menangani penghapusan dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   if (isset($_POST['doctor_id'])) {
       // Hapus dari database menggunakan prepared statement
       $stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id = ?");
       if ($stmt) {
           // Bind parameter dan eksekusi query
           if ($stmt->bind_param("i", $_POST['doctor_id']) && !$stmt->execute()) {
               echo "Error: " . mysqli_error($conn);
           } else {
               echo "Dokter berhasil dihapus.";
               header("Location: doctors.php"); // Redirect setelah hapus
               exit();
           }
       }
   }
}

// Mengambil data dokter untuk ditampilkan dalam dropdown
$result = mysqli_query($conn, "SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Delete Dokter</title>
</head>

<body>
<div class='container'>
<h2>Delete Dokter</h2>

<form method='POST'>
   <div class='form-group'>
       <label>Pilih Dokter:</label><br/>
       <select name='doctor_id' required class='form-control'>
           <option value='' disabled selected>Pilih Dokter...</option>
           <?php while ($row = mysqli_fetch_assoc($result)): ?>
               <option value='<?php echo htmlspecialchars($row['doctor_id']); ?>'><?php echo htmlspecialchars($row['name']); ?></option>
           <?php endwhile; ?>
       </select><br/>
   </div>

   <!-- Tombol delete -->
   <button type='submit' class='btn btn-danger'>Delete Dokter</button> 
</form>

<a href="doctors.php" class="btn btn-secondary">Kembali ke Daftar Dokter</a>

</div></body></html>

<?php
$conn->close();
?>