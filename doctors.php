<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Menangani penambahan dokter baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $contact_info = $_POST['contact_info'];

   // Menyimpan data dokter ke database 
   // Menggunakan prepared statements untuk keamanan 
   // Menghindari SQL Injection 
   // Menggunakan bind_param() untuk mengikat parameter 
   // Menjalankan query 
   // Memeriksa apakah eksekusi berhasil 
   // Jika berhasil, tampilkan pesan sukses 
   // Jika gagal, tampilkan pesan error 

   // Menyimpan data dokter ke database 
   // Menggunakan prepared statements untuk keamanan 
   // Menghindari SQL Injection 
   // Menggunakan bind_param() untuk mengikat parameter 
   // Menjalankan query 
   // Memeriksa apakah eksekusi berhasil 
   // Jika berhasil, tampilkan pesan sukses 
   // Jika gagal, tampilkan pesan error 

$stmt = $conn->prepare("INSERT INTO doctors (name, specialization, contact_info) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $specialization, $contact_info);

if ($stmt->execute()) {
        echo "Dokter berhasil ditambahkan.";
} else {
        echo "Error: " . $stmt->error;
}
}

// Mengambil data dokter untuk ditampilkan
$result = $conn->query("SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Kelola Dokter</title></head>

<body>
<div class='container'>
<h2>Kelola Dokter</h2>

<form method='POST'>
<div class='form-group'>
<label>Nama:</label><br/>
<input type='text' name='name' required class='form-control'/>
</div>

<div class='form-group'>
<label>Spesialisasi:</label><br/>
<input type='text' name='specialization' required class='form-control'/>
</div>

<div class='form-group'>
<label>Kontak Info:</label><br/>
<input type='text' name='contact_info' required class='form-control'/>
</div>

<button type='submit' class='btn btn-primary'>Tambah Dokter</button>

</form>

<h3>Daftar Dokter</h3>

<table class='table'>
<thead><tr><th>ID Dokter</th><th>Nama Dokter</th><th>Spesialisasi</th><th>Kontak Info</th></tr></thead><tbody>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($row['doctor_id']); ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['specialization']); ?></td>
<td><?php echo htmlspecialchars($row['contact_info']); ?></td>
<td>
    <a href="edit_doctor.php?id=<?php echo htmlspecialchars($row['doctor_id']); ?>" class="btn btn-warning">Edit</a>
    <form action="delete_doctor.php" method="POST" style="display:inline;">
        <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($row['doctor_id']); ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dokter ini?');">Delete</button>
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
