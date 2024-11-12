<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Portal</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/main.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif; margin: 0">
    <!-- Header with Flexbox Layout -->
    <header style="display: flex; background-color: #7B76DA; align-items: center; justify-content: space-between; color: white; padding: 10px 32px;">
        <h2 style="margin: 0; font-size: 24px; font-weight: 500;">iAttend</h2>

        <!-- Navigation Menu -->
        <nav style="display: flex; gap: 20px;">
            <a href="<?php echo base_url('students'); ?>" style="color: white; text-decoration: none;">Home</a>
            <a href="<?php echo base_url('attendance/report_attendance'); ?>" style="color: white; text-decoration: none;">Attendance Report</a>
        </nav> 
        
        
    </header>



    