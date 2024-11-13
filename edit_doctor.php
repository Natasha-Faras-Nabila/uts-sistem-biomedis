<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Menangani pembaruan dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $contact_info = $_POST['contact_info'];

    // Update ke database
    $stmt = $conn->prepare("UPDATE doctors SET name = ?, specialization = ?, contact_info = ? WHERE doctor_id = ?");
    $stmt->bind_param("sssi", $name, $specialization, $contact_info, $doctor_id);

    if ($stmt->execute()) {
        echo "Dokter berhasil diperbarui.";
        header("Location: doctors.php"); // Redirect setelah update
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Mengambil data dokter untuk ditampilkan
$doctor_id = $_GET['id']; // Ambil ID dokter dari URL
$stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Edit Dokter</title>
</head>

<body>
<div class='container'>
<h2>Edit Dokter</h2>

<form method='POST'>
    <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor['doctor_id']); ?>">

    <div class='form-group'>
        <label>Nama:</label>
        <input type='text' name='name' value="<?php echo htmlspecialchars($doctor['name']); ?>" class='form-control' required>
    </div>

    <div class='form-group'>
        <label>Spesialisasi:</label>
        <input type='text' name='specialization' value="<?php echo htmlspecialchars($doctor['specialization']); ?>" class='form-control' required>
    </div>

    <div class='form-group'>
        <label>Kontak Info:</label><br/>
        <input type='text' name='contact_info' value="<?php echo htmlspecialchars($doctor['contact_info']); ?>" class='form-control' required/>
    </div>

    <button type='submit' class='btn btn-primary'>Update Dokter</button>
</form>

<a href="doctors.php" class="btn btn-secondary">Kembali ke Daftar Dokter</a>

</div></body></html>

<?php
$conn->close();
?>