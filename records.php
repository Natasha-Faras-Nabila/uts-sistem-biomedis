<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Pastikan ini ada untuk mengakses $conn

// Menangani penambahan rekam medis baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form input rekam medis pasien 
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $visit_date = $_POST['visit_date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];

    // Menyimpan rekam medis ke database menggunakan prepared statements
    $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, doctor_id, visit_date, diagnosis, treatment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $patient_id, $doctor_id, $visit_date, $diagnosis, $treatment);

    // Memeriksa apakah eksekusi berhasil
    if ($stmt->execute()) {
        echo "Rekam medis berhasil ditambahkan.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Mengambil data rekam medis untuk ditampilkan
$result = $conn->query("SELECT medical_records.*, patients.name AS patient_name, doctors.name AS doctor_name FROM medical_records JOIN patients ON medical_records.patient_id = patients.patient_id JOIN doctors ON medical_records.doctor_id = doctors.doctor_id");

?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Kelola Rekam Medis</title>
</head>

<body>
<div class='container'>
<h2>Kelola Rekam Medis Pasien</h2>

<form method='POST'>
    <div class='form-group'>
        <label>Pilih Pasien:</label><br/>
        <select name='patient_id' required class='form-control'>
            <option value='' disabled selected>Pilih Pasien...</option>

            <?php  
            // Mengambil semua pasien dari database untuk dropdown
            $patients_result = $conn->query("SELECT * FROM patients");

            while ($patient_row = $patients_result->fetch_assoc()) { ?>
                <option value='<?php echo $patient_row['patient_id']; ?>'><?php echo $patient_row['name']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class='form-group'>
        <label>Pilih Dokter:</label><br/>
        <select name='doctor_id' required class='form-control'>
            <option value='' disabled selected>Pilih Dokter...</option>

            <?php  
            // Mengambil semua dokter dari database untuk dropdown
            $doctors_result = $conn->query("SELECT * FROM doctors");

            while ($doctor_row = $doctors_result->fetch_assoc()) { ?>
                <option value='<?php echo $doctor_row['doctor_id']; ?>'><?php echo $doctor_row['name']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class='form-group'>
        <label>Tanggal Kunjungan:</label><br/>
        <input type='date' name='visit_date' required class='form-control'/>
    </div>

    <div class='form-group'>
        <label>Diagnosis:</label><br/>
        <textarea name='diagnosis' required class='form-control'></textarea>
    </div>

    <div class='form-group'>
        <label>Treatment:</label><br/>
        <textarea name='treatment' required class='form-control'></textarea>
    </div>

    <button type='submit' class='btn btn-primary'>Tambah Rekam Medis Pasien</button>
</form>

<h3>Daftar Rekam Medis Pasien</h3>

<table class="table">
<thead>
<tr>
<th>ID Rekam Medis</th>
<th>Nama Pasien</th>
<th>Nama Dokter</th>
<th>Tanggal Kunjungan</th>
<th>Diagnosis</th>
<th>Treatment</th>
</tr>
</thead>
<tbody>

<?php while ($record_row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $record_row['record_id']; ?></td>
<td><?php echo $record_row['patient_name']; ?></td>
<td><?php echo $record_row['doctor_name']; ?></td>
<td><?php echo $record_row['visit_date']; ?></td>
<td><?php echo $record_row['diagnosis']; ?></td>
<td><?php echo $record_row['treatment']; ?></td>
<td>
<a href="edit_record.php?id=<?php echo htmlspecialchars($record_row["record_id"]); ?>" class="btn btn-warning">Edit</a>
<form action="delete_record.php" method="POST" style="display:inline;">
<input type="hidden" name="record_id" value="<?php echo htmlspecialchars($record_row["record_id"]); ?>">
<button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">Delete</button>
</form></td>
</tr>
<?php endwhile; ?>






</tbody></table>

<a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>

</div></body></html>

<?php
// Tutup koneksi database 
$conn->close(); 
?>