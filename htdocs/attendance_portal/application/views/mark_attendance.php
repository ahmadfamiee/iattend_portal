<?php $this->load->view('templates/header'); ?>
<?php echo validation_errors(); ?>
<?php $current_time = date('g:i A'); ?>
<?php $attendance_date = date('l, j F', strtotime($attendance_date)); ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

<div style="padding: 16px 32px; font-family: 'Poppins', sans-serif;">

    <div class="container" style="display: flex; align-items: flex-start; gap: 16px;">
        <!-- left column (col-5) -->
        <div class="col-5" style="width: 25%; text-align: center; border-radius: 8px;">

        test

        <div class="hello" style="padding: 16px; margin-bottom: 16px; background: white; text-align: center; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);"> 
                <div style="color: #536387; font-size: 18px; font-weight: 550;">Welcome back, <?php echo $student_name; ?>!</div>
            </div>

            <div style="background: white; padding: 16px 0px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <p style="font-size: 46px; font-weight: 500; color: #29292A"><?php echo $current_time; ?></p>
                    <p style="font-size: 16px; padding-bottom: 24px; font-weight: 500; color: #848484"><?php echo $attendance_date; ?></p>


                    <!-- clock in/out button -->
                    <div style="display: flex; justify-content: center; padding-bottom: 24px">
                        <button id="attendanceButton"
                                data-state="<?php echo $attendance_state; ?>"
                                data-student-id="<?php echo htmlspecialchars($student_id); ?>"
                                data-clock-in-time="<?php echo $clock_in_time ? date('Y-m-d H:i:s', strtotime($clock_in_time)) : ''; ?>"
                                data-clock-out-time="<?php echo $clock_out_time ? date('Y-m-d H:i:s', strtotime($clock_out_time)) : ''; ?>"
                                style="width: 160px; height: 160px; border-radius: 50%; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); font-size: 14px;
                                    background-color: <?php echo $attendance_state === 'completed' ? '#6c757d' : ($attendance_state === 'clock_out' ? '#dc3545' : '#28a745'); ?>; 
                                    color: #fff; border: none; transition: background-color 0.3s;"
                                <?php if ($attendance_state === 'completed') echo 'disabled'; ?>
                                title="<?php echo $disable_reason; ?>">
                            <img src="<?php echo base_url('/assets/moos.png'); ?>" style="width: 100%; border-radius: 50%;">
                        </button>
                    </div>

                    <!-- display clock in/out times -->
                    <div style="display: flex; text-align: center; justify-content: space-around;">
                        <div style="font-family: 'Poppins', sans-serif;">
                            <i class="fa fa-clock" style="font-size: 30px; color: #28a745; margin-bottom: 8px;"></i>
                            <p style="margin: 0; font-size: 14px; color: #848484">Clock In</p>
                            <h4 id="clockInTime" style="font-size: 20px; font-weight: 700; color: #29292A;">
                                <?php echo $clock_in_time ? date('g:i A', strtotime($clock_in_time)) : '00:00'; ?>
                            </h4>
                        </div>
                        <div style="font-family: 'Poppins', sans-serif;">
                            <i class="fa fa-clock" style="font-size: 30px; color: #dc3545; margin-bottom: 8px;"></i>
                            <p style="margin: 0; font-size: 14px; color: #848484">Clock Out</p>
                            <h4 id="clockOutTime" style="font-size: 20px; font-weight: 700; color: #29292A;">
                                <?php echo $clock_out_time ? date('g:i A', strtotime($clock_out_time)) : '00:00'; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

        <!-- right column (col-7) -->
        <div class="col-7" style="width: 75%;">
            <div style="padding: 16px; background: white; border-radius: 8px; box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);">
                <div id="attendanceLabel" style="padding-bottom: 8px; font-weight: 550; font-size: 16px; color: #536387;">
                    <?php echo $disable_button ? 'COMPLETED' : 'CLOCK IN'; ?> / (<?php echo htmlspecialchars($student_id); ?>)
                </div>

                <div class="attendance-container" style="display: flex; align-items: center; gap: 16px;">
                    <div class="date-container" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); border-radius: 8px; text-align: center;">
                        <p style="margin: 0; font-size: 16px; color: #29292A;"><?php echo date('D'); ?></p>
                        <p style="margin: 0; font-size: 20px; color: #29292A; font-weight: bold;"><?php echo date('d'); ?></p>
                    </div>

                   
                    <div class="progress-container" style="width: 100%; background-color: #e0e0e0; border-radius: 10px; overflow: hidden; margin-bottom: 10px;">
                        <div id="progress-bar" class="progress-bar" style="height: 10px; width: 0; background-color: #4caf50; transition: width 1s linear; border-radius: 10px;"></div>
                    </div>

                    <div class="productivity-container" style="color: #29292A; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); border-radius: 8px; text-align: center;">
                        <p style="margin: 0; font-size: 16px;">Productivity</p>
                        <p= id="elapsed-time" style="color: #29292A;">00:00:00</p>
                    </div>

                </div>
            </div>

            <div class="attendance-history mt-4">
                <div class="text-secondary" style="color: #536387; font-size: 16px; font-weight: 550;">Attendance History</div>
                <div class="table-responsive" style="overflow: hidden; margin-top: 10px;">
                    <table id="attendanceTable" class="table table-bordered table-hover w-100">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="color: #29292A; font-size: 15px; font-weight: 550">Date</th>
                                <th class="text-center"><i class="fa fa-clock" style="font-size: 20px; color: #28a745;" title="Clock In"></i></th>
                                <th class="text-center"><i class="fa fa-clock" style="font-size: 20px; color: #dc3545;" title="Clock Out"></i></th>
                                <th class="text-center">Productivity</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($attendance_history)) : ?>
                                <?php foreach ($attendance_history as $record) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo date('d/m/Y', strtotime($record->attendance_date)); ?></td>
                                        <td class="text-center"><?php echo $record->clock_in ? date('g:i A', strtotime($record->clock_in)) : '-'; ?></td>
                                        <td class="text-center"><?php echo $record->clock_out ? date('g:i A', strtotime($record->clock_out)) : '-'; ?></td>
                                        <td class="text-center"><?php echo $record->total_hours ? $record->total_hours : '-'; ?> hrs</td>
                                        <td class="text-center">
                                            <span class="badge <?php 
                                                echo strtolower($record->status) === 'on-time' ? 'badge-success' : 
                                                    (strtolower($record->status) === 'late' ? 'badge-warning' : 
                                                    (strtolower($record->status) === 'absent' ? 'badge-danger' : 'badge-secondary'));
                                            ?>">
                                                <?php echo ucfirst($record->status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">No attendance history found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    let clockInTime, timerInterval;
    const targetHours = 8;
    const progressBar = $('#progress-bar');
    const elapsedTimeDisplay = $('#elapsed-time');
    const attendanceButton = $('#attendanceButton');
    const attendanceLabel = $('#attendanceLabel');

    const initialState = attendanceButton.data('state');
    const clockInTimestamp = attendanceButton.data('clock-in-time');
    const clockOutTimestamp = attendanceButton.data('clock-out-time');

    function updateTimer() {
        const currentTime = new Date();
        const elapsed = (currentTime - clockInTime) / 1000;
        const hours = String(Math.floor(elapsed / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0');
        const seconds = String(Math.floor(elapsed % 60)).padStart(2, '0');
        elapsedTimeDisplay.text(`${hours}:${minutes}:${seconds}`).css('color', '#536387');
        const progressPercentage = Math.min((elapsed / (targetHours * 3600)) * 100, 100);
        progressBar.css('width', `${progressPercentage}%`);
    }

    if (initialState === 'clock_out' && clockInTimestamp) {
        clockInTime = new Date(clockInTimestamp);
        const initialElapsed = (new Date() - clockInTime) / 1000;
        elapsedTimeDisplay.text(`${String(Math.floor(initialElapsed / 3600)).padStart(2, '0')}:${String(Math.floor((initialElapsed % 3600) / 60)).padStart(2, '0')}:${String(Math.floor(initialElapsed % 60)).padStart(2, '0')}`);
        const progressPercentage = Math.min((initialElapsed / (targetHours * 3600)) * 100, 100);
        progressBar.css('width', `${progressPercentage}%`);
        attendanceLabel.text('Onboarding');
        attendanceButton.css('background-color', '#dc3545');
        attendanceButton.data('state', 'clock_out');
        timerInterval = setInterval(updateTimer, 1000);
    } else if (initialState === 'completed') {
        const totalElapsed = (new Date(clockOutTimestamp) - new Date(clockInTimestamp)) / 1000;
        elapsedTimeDisplay.text(`${String(Math.floor(totalElapsed / 3600)).padStart(2, '0')}:${String(Math.floor((totalElapsed % 3600) / 60)).padStart(2, '0')}:${String(Math.floor(totalElapsed % 60)).padStart(2, '0')}`);
        progressBar.css('width', '100%');
        attendanceLabel.text('Completed');
        attendanceButton.prop('disabled', true);
    } else {
        attendanceLabel.text('Clock In');
        attendanceButton.css('background-color', '#28a745');
    }

    function checkForNewDay() {
        const todayDate = new Date().toDateString();
        const lastCheckedDate = localStorage.getItem('lastCheckedDate');
        if (lastCheckedDate !== todayDate) {
            attendanceButton.prop('disabled', false).css('background-color', '#28a745').data('state', 'clock_in');
            attendanceLabel.text('Clock In'); 
            progressBar.css('width', '0%');
            elapsedTimeDisplay.text('00:00:00');
            localStorage.setItem('lastCheckedDate', todayDate);
            clearInterval(timerInterval);
        }
    }
    setInterval(checkForNewDay, 1800000);
    checkForNewDay();

    attendanceButton.click(function() {
        const currentState = attendanceButton.data('state');
        const studentId = attendanceButton.data('student-id');
        
        if (currentState === 'clock_in' && !confirm("Are you sure to clock in now?")) {
            return;
        } else if (currentState === 'clock_out' && !confirm("Are you sure to clock out now?")) {
            return;
        }

        $.ajax({
            url: "<?php echo base_url('attendance/toggle_clock'); ?>",
            type: "POST",
            data: { 
                student_id: studentId, 
                state: currentState 
            },
            dataType: "json",
            success: function(response) {
                const date = new Date(response.time);
                const timeOnly = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                if (currentState === 'clock_in') {
                    clockInTime = date;
                    $('#clockInTime').text(timeOnly);
                    attendanceLabel.text('Onboarding');
                    attendanceButton.css('background-color', '#dc3545');
                    attendanceButton.data('state', 'clock_out');
                    timerInterval = setInterval(updateTimer, 1000);
                } else if (currentState === 'clock_out') {
                    clearInterval(timerInterval);
                    updateTimer();
                    $('#clockOutTime').text(timeOnly);
                    attendanceLabel.text('Completed');
                    attendanceButton.css('background-color', '#6c757d');
                    attendanceButton.prop('disabled', true);
                    alert("Your attendance has been submitted.");
                }
            },
            error: function() {
                alert("Error occurred while recording attendance. Please try again.");
            }
        });
    });

    $('#attendanceTable').DataTable({
        "paging": true,
        "pageLength": 5,
        "ordering": true,
        "order": [[0, "desc"]],
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });
});
</script>

<style>

    #attendanceButton {
        overflow: hidden; 
        transition: transform 0.2s ease;
    }

    #attendanceButton img {
        transition: transform 0.3s ease; 
    }

    #attendanceButton:hover img {
        transform: scale(1.2); 
    }

    /* #attendanceButton {
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        opacity: 1; 
    }

    #attendanceButton:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); 
        opacity: 0.9; 
    } */

    
     .productivity-container {
        background-color: #F4F7FE; 
        padding: 10px 20px; 
        border-radius: 8px; 
        font-family: 'Poppins', sans-serif; 
        font-size: 18px; 
    }

    .formbold-input-flex {
        display: flex;
        gap: 20px;
    }
    .formbold-form-label {
        color: #536387;
        font-size: 14px;
        margin-bottom: 5px;
        display: block;
    }
    .formbold-form-input {
        width: 100%;
        border-radius: 5px;
        border: 1px solid #dde3ec;
        background: #ffffff;
        font-weight: 500;
        font-size: 16px;
        color: #536387;
        outline: none;
    }
    .formbold-form-input:focus {
        border-color: #6a64f1;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
    }
    .formbold-btn {
        font-size: 16px;
        border-radius: 5px;
        padding: 12px 25px;
        border: none;
        font-weight: 500;
        background-color: #6a64f1;
        color: white;
        cursor: pointer;
        margin-top: 8px;
        width: 100%;
    }
    .formbold-btn:hover {
        background-color: #5a54d1;
    }
    .clock-button.gray {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    body {
        font-family: 'Poppins', sans-serif;
    }
    .attendance-container {
        display: flex;
        align-items: center;
    }
    .date-container {
        padding: 5px 12px;
        background-color: #F4F7FE;
        border-radius: 4px;
    }
    .date-container p {
        margin: 0;
        font-size: 14px;
    }
    .progress-container {
        width: 100%;
        background-color: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .progress-bar {
        height: 24px;
        width: 0;
        background-color: #4caf50;
        transition: width 1s linear;
        border-radius: 10px;
    }

    #elapsed-time {
        font-size: 20px;
        font-weight: bold;
        color: #29292A;
    }

    .attendance-history {
        margin-top: 16px;
        background-color: #ffffff;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    }
    .attendance-history table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
    }
    .attendance-history thead th {
        background-color: #F1F4FA;
        color: #495057;
        font-weight: bold;
        border-bottom: 2px solid #dee2e6;
        padding: 10px;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
    }
    .attendance-history tbody td {
        border-top: 1px solid #dee2e6;
        padding: 8px;
        background-color: #ffffff;
    }
    .attendance-history th, .attendance-history td {
        vertical-align: middle;
        text-align: center;
    }
    .attendance-history .badge {
        padding: 0.5em 1em;
        font-size: 0.9em;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 0px;
    }
    table.dataTable.no-footer {
        border-bottom: 0px;
    }
    .dataTables_info{
        font-size: 14px;
        font-weight: 500;
    }

    .status.on-time {
        color: green;
        font-weight: bold;
    }
    .status.late {
        color: red;
        font-weight: bold;
    }
    .status.absent {
        color: gray;
        font-weight: bold;
    }
    .paginate_button{
        margin-top: 8px;
        border-radius: 4px;
    }
        
    .dataTables_paginate{
        font-size: 14px;
        margin-top: 6px;
    }

</style>
