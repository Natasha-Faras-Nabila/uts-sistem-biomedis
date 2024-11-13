<?php
session_start();
require_once'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard EMR</title>
    <style>

        
        .dashboard-icon {
            width: 64px;
            height: 64px;
            margin-bottom: 10px;
        }
        .menu-card {
            text-align: center;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            transition: transform 0.3s;
            background-color: white;
            box-shadow: #17a2b8(0,0,0,0.1);
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .welcome-banner {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        .logo-emr {
            width: 150px;
            height: 150px;
            margin: 20px 0;
        }
        .stats-icon {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .logout-btn {
            position: relative;
            top: 20px;
            right: 20px;
        }
        .container {
            position: relative;
            background-color: white; /* Warna background lebih samar */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #17a2b8; /* Warna button */
            border-color: #17a2b8;
        }
        .btn-primary:hover {
            background-color: #138496; /* Warna button saat dihover */
            border-color: #138496;
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- [Previous head content remains the same] -->
    <style>
        /* [Previous styles remain the same] */
        
        .welcome-banner {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .welcome-banner img {
            width: 100%; /* Makes image width 100% of container */
            height: 300px; /* Fixed height, you can adjust this value */
            object-fit: cover; /* Ensures image covers the area without distortion */
            border-radius: 8px; /* Optional: adds rounded corners */
            margin-bottom: 20px; /* Space between image and heading */
        }

        .container {
            position: relative;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 1200px; /* Optional: sets maximum width */
            width: 100%;
            margin: auto;
        }

        /* [Rest of your styles remain the same] */
    </style>
</head>
<body>
<div class="container">
    <div class="welcome-banner">
        <img src="medical-banner.jpeg" alt="Medical Banner" class="img-fluid">
        <h1>Selamat Datang di Sistem Rekam Medis Rumah Sakit Medika </h1>
        
    </div>


    <div class="row">
        <div class="col-md-4">
            <div class="menu-card">
                <!-- Icon Pasien -->
                <svg class="dashboard-icon" viewBox="0 0 64 64">
                    <circle cx="32" cy="20" r="12" fill="#17a2b8" />
                    <path d="M10 54 C10 42 20 34 32 34 C44 34 54 42 54 54" fill="#17a2b8" />
                </svg>
                <h4>Kelola Pasien</h4>
                <p>Manajemen data pasien</p>
                <a href="patients.php" class="btn btn-primary btn-block">Akses</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="menu-card">
                <!-- Icon Dokter -->
                <svg class="dashboard-icon" viewBox="0 0 64 64">
                    <circle cx="32" cy="20" r="12" fill="#17a2b8" />
                    <path d="M10 54 C10 42 20 34 32 34 C44 34 54 42 54 54" fill="#17a2b8" />
                    <rect x="26" y="10" width="12" height="4" fill="white" />
                    <rect x="30" y="6" width="4" height="12" fill="white" />
                </svg>
                <h4>Kelola Dokter</h4>
                <p>Manajemen data dokter</p>
                <a href="doctors.php" class="btn btn-primary btn-block">Akses</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="menu-card">
                <!-- Icon Rekam Medis -->
                <svg class="dashboard-icon" viewBox="0 0 64 64">
                    <rect x="12" y="8" width="40" height="48" fill="#17a2b8" />
                    <rect x="20" y="16" width="24" height="4" fill="white" />
                    <rect x="20" y="24" width="24" height="4" fill="white" />
                    <rect x="20" y="32" width="24" height="4" fill="white" />
                </svg>
                <h4>Rekam Medis</h4>
                <p>Manajemen rekam medis</p>
                <a href="records.php" class="btn btn-primary btn-block">Akses</a>
            </div>
        </div>
    </div>

  <!-- Quick Stats Section -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body d-flex align-items-center">
                <svg class="stats-icon" viewBox="0 0 40 40">
                    <circle cx="20" cy="15" r="8" fill="white" />
                    <path d="M8 38 C8 28 14 24 20 24 C26 24 32 28 32 38" fill="white" />
                </svg>
                <div>
                    <h5 class="card-title">Total Pasien</h5>
                    <p class="card-text"><?php 
                        $totalPatients = getTotalPatients($conn);
                        echo $totalPatients; ?> Pasien</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body d-flex align-items-center">
                <svg class="stats-icon" viewBox="0 0 40 40">
                    <circle cx="20" cy="15" r="8" fill="white" />
                    <path d="M8 38 C8 28 14 24 20 24 C26 24 32 28 32 38" fill="white" />
                    <rect x="17" y="10" width="6" height="2" fill="#4DB6AC" />
                    <rect x="19" y="8" width="2" height="6" fill="#4DB6AC" />
                </svg>
                <div>
                    <h5 class="card-title">Total Dokter</h5>
                    <p class="card-text"><?php 
                        $totalDoctors = getTotalDoctors($conn);
                        echo $totalDoctors; ?> Dokter</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body d-flex align-items-center">
                <svg class="stats-icon" viewBox="0 0 40 40">
                    <rect x="10" y="8" width="20" height="24" fill="white" />
                    <circle cx="20" cy="20" r="6" fill="#4DB6AC" />
                    <path d="M17 20 L20 23 L23 17" stroke="white" stroke-width="2" fill="none" />
                </svg>
                <div>
                    <h5 class="card-title">Total Kunjungan</h5>
                    <p class="card-text"><?php 
                        $todayVisits = getTodayVisits($conn);
                        echo $todayVisits; ?> Kunjungan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="logout-btn">
            <a href="logout.php" class="btn btn-danger">
                <svg style="width:20px; height:20px; margin-right:5px; vertical-align:text-bottom;" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z" />
                </svg>
                Logout
            </a>
        </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>