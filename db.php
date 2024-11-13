<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "coba_emr"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mengecek apakah tabel ada
function tableExists($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result->num_rows > 0;
}

// Fungsi untuk mendapatkan total pasien
function getTotalPatients($conn) {
    if (!tableExists($conn, 'patients')) {
        return 0;
    }
    $query = "SELECT COUNT(*) as total FROM patients";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return $data['total'];
    }
    return 0;
}

// Fungsi untuk mendapatkan total dokter
function getTotalDoctors($conn) {
    if (!tableExists($conn, 'doctors')) {
        return 0;
    }
    $query = "SELECT COUNT(*) as total FROM doctors";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return $data['total'];
    }
    return 0;
}

// Fungsi untuk mendapatkan total kunjungan hari ini

function getTodayVisits($conn) {
    if (!tableExists($conn, 'medical_records')) {
        return 0;
    }
    
    $query = "SELECT COUNT(*) as total FROM medical_records";
    try {
        $result = mysqli_query($conn, $query);
        if ($result) {
            $data = mysqli_fetch_assoc($result);
            return $data['total'];
        }
    } catch (Exception $e) {
        return 0;
    }
    return 0;
}
?>