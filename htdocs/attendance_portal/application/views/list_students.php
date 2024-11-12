<?php
$this->load->view('templates/header');

$previous_alpha_count = 4; 
$previous_beta_count = 4; 
$previous_gamma_count = 3; 

$alpha_percentage_change = (($alpha_count - $previous_alpha_count) / max($previous_alpha_count, 1)) * 100;
$beta_percentage_change = (($beta_count - $previous_beta_count) / max($previous_beta_count, 1)) * 100;
$gamma_percentage_change = (($gamma_count - $previous_gamma_count) / max($previous_gamma_count, 1)) * 100;

function getChangeIcon($percentage_change) {
    if ($percentage_change > 0) {
        return "<span style='color: green;'>&#9650;</span>"; 
    } elseif ($percentage_change < 0) {
        return "<span style='color: red;'>&#9660;</span>"; 
    } else {
        return "<span style='color: gray;'>-</span>"; 
    }
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" >
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<div class="container-fluid" style="font-family: 'Poppins', sans-serif; padding: 16px 32px;">
        
        <div class="today-date"> 
            <div style="color: #536387; font-size: 20px; font-weight: 550;">Attendance Dashboard</div>
            <div style="color: #29292A; border-radius: 6px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-color: #fff; padding: 6px 10px; font-size: 15px;">
                <i style="color: #536387;"></i>Today, <?php echo date('F j, Y'); ?>
            </div>

            <!-- far fa-calendar-alt <div style="color: #29292A; background-color: #fafafa; padding-right: 8px; padding: 8px; font-size: 15px;">📅<?php echo date('l, F j, Y'); ?></div> -->
        </div>
    
        
    <!-- Dashboard Card -->

    <div style="display: flex; gap: 16px; margin-bottom: 16px; width: 100%">
        
        <div class="dashcard-container" style="align-content: center; justify-content: space-between;">          
            <div style="display: grid; gap: 4px;">
                <div style="color: #848484; align-content: center">Total Students</div>
                <div style="font-size: 32px;"><?php echo $total_students; ?></div>
            </div>
            <div style="">
                <canvas id="studentDistributionChart" width="100" height="100"></canvas>
            </div>
        </div> 

        <div class="dashcard-container" style="align-items: center; justify-content: space-between;"> 
            <div style="display: grid; gap: 8px; ">
                <div style="color: #848484;">Alpha's</div>               
                <div style="font-size: 32px;"><?php echo $alpha_count; ?></div>

                <div style="color: #848484; font-size: 14px;">
                    <?php 
                        echo round($alpha_percentage_change, 1) . "% " . getChangeIcon($alpha_percentage_change); 
                    ?>
                </div>

            </div>   
            <div style="background-color: #4CAF50; align-content: center; text-align: center; border-radius: 60px; width: 60px; height: 60px; ">
                <div class="fas fa-users" style="color: #FFF; font-size: 20px;"></div>
            </div>                 
        </div>

        <div class="dashcard-container" style="align-items: center; justify-content: space-between;"> 
            <div style="display: grid; gap: 8px; ">
                <div style="color: #848484;">Beta's</div>               
                <div style="font-size: 32px;"><?php echo $beta_count; ?></div>

                <div style="color: #848484; font-size: 14px;">
                    <?php 
                        echo round($beta_percentage_change, 1) . "% " . getChangeIcon($beta_percentage_change); 
                    ?>
                </div>

            </div>   
            <div style="background-color: #FF9800; align-content: center; text-align: center; border-radius: 60px; width: 60px; height: 60px; ">
                <div class="fas fa-users" style="color: #FFF; font-size: 20px;"></div>
            </div>                 
        </div>

        <div class="dashcard-container" style="align-items: center; justify-content: space-between;"> 
            <div style="display: grid; gap: 8px; ">
                <div style="color: #848484;">Gamma's</div>               
                <div style="font-size: 32px;"><?php echo $gamma_count; ?></div>
                <div style="color: #848484; font-size: 14px;">
                    <?php 
                        echo round($gamma_percentage_change, 1) . "% " . getChangeIcon($gamma_percentage_change); 
                    ?>
                </div>
            </div>   
            <div style="background-color: #F44336; align-content: center; text-align: center; border-radius: 60px; width: 60px; height: 60px; ">
                <div class="fas fa-users" style="color: #FFF; font-size: 20px;"></div>
            </div>                 
        </div> 

    </div>
    
    <!-- row atas table -->
    <div class="d-flex justify-content-between align-items-center">
        <form method="GET" action="<?php echo base_url('students'); ?>" class="filter-container" style="height: 100%;">
            <div class="form-group" style="height: 100%">
                <!-- Select dropdown with search icon positioned over it -->
                <!-- <i class="fas fa-search search-icon"></i> -->
                <select name="classroom" id="classroomSelect" style="height: 100%;" class="custom-select" onchange="this.form.submit()">
                    <option value="">All Classroom</option>
                    <option value="Alpha" <?php echo $this->input->get('classroom') == 'Alpha' ? 'selected' : ''; ?>>Alpha</option>
                    <option value="Beta" <?php echo $this->input->get('classroom') == 'Beta' ? 'selected' : ''; ?>>Beta</option>
                    <option value="Gamma" <?php echo $this->input->get('classroom') == 'Gamma' ? 'selected' : ''; ?>>Gamma</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="student_id" style="padding: 6px 12px; border-radius: 4px;" placeholder="Student ID" value="<?php echo $this->input->get('student_id'); ?>" />
            </div>
   
            <div id="length-container" style="padding: 6px 8px;"></div>
            <!-- <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button> -->
        </form>

        <div class="form-group" style="background-color: #7B76DA; border-radius: 4px; color: #ffffff; text-align: center;">
        <a href="<?php echo base_url('students/add'); ?>" class="btn" style="color: #ffffff;">
                <div class="fas fa-user-plus" style="color: #ffffff; font-size: 13px; padding-right:8px;"></div> New Student
            </a>
            </div>
       
    </div>


    <!-- all students table -->
    <div class="no-padding">
        <table id="studentsTable" class="table table-bordered table-hover">
            <thead class="table" style="background-color: #F1F4FA;">
                <tr>
                    <th class="text-center" style="width: 16%; color: #29292A; font-size: 15px; font-weight: 550">Action</th>
                    <th style="width: 10%; color: #29292A; font-size: 15px; font-weight: 550">Student ID</th>
                    <th style="width: 10%; color: #29292A; font-size: 15px; font-weight: 550">Classroom</th>
                    <th style="width: 20%; color: #29292A; font-size: 15px; font-weight: 550">Name</th>
                    <th style="width: 10%; color: #29292A; font-size: 15px; font-weight: 550">Age</th>
                    <th style="width: 8%; color: #29292A; font-size: 15px; font-weight: 550">Gender</th>   
                    <th class="text-center" style="width: 8%; color: #29292A; font-size: 15px; font-weight: 550">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr style="background-color: white">
                        <td class="text-center">
                            <a href="<?php echo base_url('attendance/mark_attendance/' . $student->student_id); ?>" 
                               class="btn btn-success btn-sm" 
                               role="button">
                                Mark Attendance
                            </a>

                            <div class="dropdown d-inline">
                                <button class="btn btn-link btn-sm dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $student->student_id; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i> 
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton<?php echo $student->student_id; ?>">
                                    <a class="dropdown-item" href="<?php echo base_url('students/edit/' . $student->student_id); ?>">Edit</a>
                                    <a class="dropdown-item text-danger" href="<?php echo base_url('students/delete/' . $student->student_id); ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $student->student_id; ?></td>
                        <td><?php echo $student->classroom; ?></td>   
                        <td><?php echo $student->name; ?></td>
                        <td><?php echo $student->age; ?></td>
                        <td><?php echo $student->gender; ?></td>
                        <td class="text-center">
                            <?php
                                $attendanceRecord = $attendance[$student->student_id] ?? null;

                                if (!$attendanceRecord) {
                                    echo '<span class="text-danger font-italic">Action Required*</span>';
                                } else {
                                    $status = $attendanceRecord->status ?? 'Absent';
                                    $statusStyles = [
                                        'on-time' => ['bg' => 'bg-success', 'text' => 'text-white'],
                                        'late' => ['bg' => 'bg-warning', 'text' => 'text-dark'],
                                        'absent' => ['bg' => 'bg-danger', 'text' => 'text-white']
                                    ];
                                    $style = $statusStyles[strtolower($status)] ?? ['bg' => 'bg-secondary', 'text' => 'text-white'];
                                    echo '<span class="badge ' . $style['bg'] . ' ' . $style['text'] . '">' . ucfirst($status) . '</span>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- <script>
    $(document).ready(function() {
        // Toggle dropdowns on click
        $('.dropdown-toggle').on('click', function(event) {
            event.stopPropagation();
            $(this).next('.dropdown-menu').toggle();
        });

        // Close all dropdowns if clicked outside
        $(document).on('click', function() {
            $('.dropdown-menu').hide();
        });

        // Add border for selected classroom
        $('#classroomSelect').on('change', function() {
            if ($(this).val() !== "") {
                $(this).addClass('filtered');
            } else {
                $(this).removeClass('filtered');
            }
        });
    });
</script> -->

<!-- <script>
    $(document).ready(function() {
        $('#studentsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });
</script> -->

<script>
    const alphaCount = <?php echo $alpha_count; ?>;
    const betaCount = <?php echo $beta_count; ?>;
    const gammaCount = <?php echo $gamma_count; ?>;

    // chart from adminlte
    const ctx = document.getElementById('studentDistributionChart').getContext('2d');
    const studentDistributionChart = new Chart(ctx, {
        type: 'doughnut', 
        data: {
            labels: ['Alpha', 'Beta', 'Gamma'],
            datasets: [{
                label: '',
                data: [alphaCount, betaCount, gammaCount],
                backgroundColor: [
                    '#4CAF50', 
                    '#FF9800', 
                    '#F44336'  
                ],
                hoverBackgroundColor: [
                    '#66BB6A',
                    '#FFB74D',
                    '#E57373'
                ]
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false 
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
    $(document).ready(function() {
        $('#studentsTable').DataTable({
            "paging": true,     
            "pageLength": 10,     
            "ordering": true,     
            "searching": false,   
            "info": true,   
            "lengthChange": false,
            "language": {
                "paginate": {
                    "previous": "<", 
                    "next": ">"     
                }
            }
        });
        $('#studentsTable_length').detach().appendTo('#length-container');
    });
    
</script>

<style>
    form.filter-container{
        display: flex;
        align-items: center;
    }

    select#classroomSelect.custom-select{
        border-radius: 4px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        color: #333;
        line-height: 1.6;
    }   

    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 0px;
    }

    table.dataTable.no-footer {
        border-bottom: 0px;
    }

    .table-bordered thead td, .table-bordered thead th {
        border-bottom-width: 0px;
    }

    select[name="classroom"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 30px; 
    }

    button[type="submit"] i {
        font-size: 16px; 
        color: #333; 
    }

    button[type="submit"] {
        background: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    input[type="text"], input[type="number"], input[type="datetime-local"], select {
        width: 100%;
        padding: 13px;
        height: 100%;
        margin-top: 0px;
        margin-bottom: 0px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    #classroomSelect.filtered {
        border: 2px solid #007bff; 
    }

    .primary-action {
        padding: 6px 12px;
        background-color: #28a745;
        color: white;
        border-radius: 4px;
        text-decoration: none;
    }

    .no-padding {
        padding: 0;
        margin: 0;
    }

    .dashcard-container{
        width: 100%;
        display: flex;
        padding: 16px; 
        background: white; 
        /*text-align: center*/
        border-radius: 8px; 
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .today-date{
        justify-content: space-between;
        width: 100%; 
        display: flex;
        align-content; center;
        width: 100%; 
        margin-bottom: 16px;
        gap: 16px;
        /* padding: 16px;*/ 
        align-items: center;
    }

    .filter-container{
        display: flex;
        gap: 8px;
        position: relative; 
    }

  
    .custom-select {
        padding: 6.5px 12px;
        width: 228px;
    }
    

    /* Style for the search icon */
    .search-icon {
        position: absolute;
        top: 50%;
        left: 9px;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
    }
    
    .paginate_button{
        margin-top: 8px;
        border-radius: 4px;
    }
        
    .dataTables_paginate{
        font-size: 14px;
        margin-top: 0px;
    }
  
  
</style>
