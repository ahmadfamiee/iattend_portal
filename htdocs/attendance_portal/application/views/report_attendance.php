<?php $this->load->view('templates/header'); ?>
<?php echo validation_errors(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
<div class="container-fluid" style="padding: 16px 32px; overflow: hidden;">
    <div style="color: #536387; font-size: 20px; font-weight: 550; padding-bottom: 16px;">Attendance Report</div>


    <div style="display: flex; gap: 8px; margin-bottom: 16px; align-items: center;">
        <label style="font-size: 14px;" for="classroomFilter">Filter by Classroom: </label>
        <select id="classroomFilter" class="form-control" style="width: 100%;">
            <option value="">All Classrooms</option>
            <option value="Alpha">Alpha</option>
            <option value="Beta">Beta</option>
            <option value="Gamma">Gamma</option>
        </select>
        <div style="display: flex; align-items: center;">
            <label style="font-size: 14px; padding-right: 8px;" for="fromDate">From: </label>
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div style="display: flex; align-items: center;">
            <label style="font-size: 14px; padding-right: 8px;" for="toDate">To: </label>
            <input type="date" id="toDate" class="form-control">
        </div>
    </div>

    <!-- <input type="date" id="dateFilter" placeholder="Select a date"> -->


    <div class="table-responsive">
        <table id="attendanceTable" class="table table-bordered table-hover">
            <thead class="table" style="background-color: #FAFBFC;">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Classroom</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Status</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attendance_reports)) : ?>
                    <?php foreach ($attendance_reports as $report) : ?>
                        <tr>
                            <td><?php echo $report->student_id; ?></td>
                            <td><?php echo $report->name; ?></td>
                            <td><?php echo $report->classroom; ?></td>
                            <td><?php echo $report->attendance_date; ?></td>
                            <td><?php echo $report->clock_in; ?></td>
                            <td><?php echo $report->clock_out; ?></td>
                            <td><?php echo ucfirst($report->status); ?></td>
                            <td><?php echo $report->total_hours; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="8">No attendance records available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    var table = $('#attendanceTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true 
    });

    $('#classroomFilter').on('change', function() {
        applyFilters(); 
    });

    // Listen for changes in the date range filters
    $('#fromDate, #toDate').on('change', function() {
        applyFilters(); 
    });

    function applyFilters() {
        var classroom = $('#classroomFilter').val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        // Apply classroom filter if selected
        table.column(2).search(classroom ? '^' + classroom + '$' : '', true, false);

        // Filter by date range on the "Date" column (column index 3)
        if (fromDate || toDate) {
            // Custom filter function for date range
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var date = data[3]; // Date column
                if (fromDate && date < fromDate) {
                    return false;
                }
                if (toDate && date > toDate) {
                    return false;
                }
                return true;
            });
        } else {
            // Remove custom filter if no date range is selected
            $.fn.dataTable.ext.search.pop();
        }

        // Redraw the table to apply both filters
        table.draw();
    }
});
</script>


<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f6f9;
}

.table-responsive {
    overflow-x: hidden; 
    width: 100%; 
}

label {
    margin-bottom: 0rem; 
}


.dt-buttons .dt-button {
    background-color: #7B76DA !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 500;
    margin-right: 4px;
}

.dt-buttons .dt-button:hover {
    background-color: #6a64d4 !important; 
    color: white !important;
}


.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #aaa;
    border-radius: 4px;
    padding: 6px 8px;
    background-color: white !important;
    margin-left: 6px;
}

.dataTables_info{
    font-size: 14px;
    font-weight: 500;
}

.dataTables_paginate{
    font-size: 14px;
    margin-top: 6px;
}

.dataTables_filter{
    font-size: 13px;
}


</style>

