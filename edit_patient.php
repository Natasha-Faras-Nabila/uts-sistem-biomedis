<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Menangani pembaruan pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact_info = $_POST['contact_info'];

    // Update ke database
    $stmt = $conn->prepare("UPDATE patients SET name = ?, date_of_birth = ?, gender = ?, contact_info = ? WHERE patient_id = ?");
    $stmt->bind_param("ssssi", $name, $dob, $gender, $contact_info, $patient_id);

    if ($stmt->execute()) {
        echo "Pasien berhasil diperbarui.";
        header("Location: patients.php"); // Redirect setelah update
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Mengambil data pasien untuk ditampilkan
$patient_id = $_GET['id']; // Ambil ID pasien dari URL
$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="style.css"> <!-- Link ke CSS -->
<title>Edit Pasien</title>
</head>

<body>
<div class='container'>
<h2>Edit Pasien</h2>

<form method='POST'>
    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient['patient_id']); ?>">

    <div class='form-group'>
        <label>Nama:</label>
        <input type='text' name='name' value="<?php echo htmlspecialchars($patient['name']); ?>" class='form-control' required>
    </div>

    <div class='form-group'>
        <label>Tanggal Lahir:</label>
        <input type='date' name='date_of_birth' value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>" class='form-control' required>
    </div>

    <div class='form-group'>
        <label>Jenis Kelamin:</label><br/>
        <select name='gender' required class='form-control'>
            <option value='Male' <?php if($patient['gender'] == 'Male') echo 'selected'; ?>>Laki-laki</option>
            <option value='Female' <?php if($patient['gender'] == 'Female') echo 'selected'; ?>>Perempuan</option>
        </select><br/>
    </div>

    <div class='form-group'>
        <label>Kontak Info:</label><br/>
        <input type='text' name='contact_info' value="<?php echo htmlspecialchars($patient['contact_info']); ?>" class='form-control' required/>
    </div>

    <button type='submit' class='btn btn-primary'>Update Pasien</button>
</form>

<a href="patients.php" class="btn btn-secondary">Kembali ke Daftar Pasien</a>

</div></body></html>

<?php
$conn->close();
?>