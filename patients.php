<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Menangani penambahan pasien baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact_info = $_POST['contact_info'];

    $stmt = $conn->prepare("INSERT INTO patients (name, date_of_birth, gender, contact_info) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $dob, $gender, $contact_info);
    
    if ($stmt->execute()) {
        echo "Pasien berhasil ditambahkan.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Mengambil data pasien untuk ditampilkan
$result = $conn->query("SELECT * FROM patients");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
    <title>Kelola Pasien</title>
</head>
<body>
<div class="container">
<h2>Kelola Pasien</h2>

<form method="POST">
  <div class="form-group">
      <label>Nama:</label>
      <input type="text" class="form-control" name="name" required>
  </div>
  <div class="form-group">
      <label>Tanggal Lahir:</label>
      <input type="date" class="form-control" name="dob" required>
  </div>
  <div class="form-group">
      <label>Jenis Kelamin:</label><br/>
      <select name="gender" required class='form-control'>
          <option value="">Pilih...</option>
          <option value='Male'>Laki-laki</option>
          <option value='Female'>Perempuan</option>
      </select><br/>
  </div>

  <div class="form-group">
      <label>Kontak Info:</label><br/>
      <input type='text' name='contact_info' required class='form-control'/>
  </div>

  <button type='submit' class='btn btn-primary'>Tambah Pasien</button>

</form>

<h3>Daftar Pasien</h3>

<table class='table'>
<thead><tr><th>ID Pasien</th><th>Nama</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Kontak Info</th></tr></thead><tbody>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($row['patient_id']); ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
<td><?php echo htmlspecialchars($row['gender']); ?></td>
<td><?php echo htmlspecialchars($row['contact_info']); ?></td>
<td>
    <a href="edit_patient.php?id=<?php echo htmlspecialchars($row['patient_id']); ?>" class="btn btn-warning">Edit</a>
    <form action="delete_patient.php" method="POST" style="display:inline;">
        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($row['patient_id']); ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?');">Delete</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
  
</tbody></table>

<a href='index.php' class='btn btn-secondary'>Kembali ke Dashboard</a>

</div></body></html>

<?php 
// Tutup koneksi database 
$conn->close(); 
?> 
