<?php $this->load->view('templates/header'); ?>
<?php echo validation_errors(); ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">

<div style="padding: 16px 32px;">
    <div class="container" style="display: flex; align-items: flex-start; gap: 24px;">

        <!-- Left Column (col-5) for Attendance Marking -->
        <div style="width: 25%; display: grid; gap: 16px;">
            <div class="edit-container">
                <h3 style="color: #536387;">Welcome back, <?php echo $student->name; ?>! 👋</h3>
            </div>

            <!-- Attendance Card -->
            <!-- <div class="col-5" style="padding: 24px 16px; background: white; text-align: center; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <div class="formbold-form-title">
                    <h2 id="clockStatus" style="padding-bottom: 16px; font-size: 28px; color: #536387;">CLOCK IN</h2>
                </div>

                <div style="display: flex; justify-content: center; padding-bottom: 24px">
                    <button id="clockButton" onclick="handleClock(event)" style="width: 160px; height: 160px; border-radius: 50%; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); font-size: 14px; background-color: #28a745; color: #fff; border: none; transition: opacity 0.5s ease;">
                        <img src="<?php echo base_url('/assets/moos.png'); ?>" style="width: 100%; border-radius: 50%;">
                    </button>
                </div>

                <form id="attendanceForm" method="post" action="<?php echo base_url('attendance/mark_attendance'); ?>">
                    <input type="hidden" name="student_id" value="<?php echo $student->student_id; ?>">
                    <input type="hidden" name="clock_in" id="clockInInput">
                    <input type="hidden" name="clock_out" id="clockOutInput">
                </form>

                <div style="display: flex; text-align: center; justify-content: space-around;">
                    <div style="font-family: 'Poppins', sans-serif;">
                        <i class="fa fa-clock" style="font-size: 30px; color: #28a745; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 15px; color: #848484">Clock In</p>
                        <h4 id="clockInTime" style="font-size: 22px; font-weight: 700; color: #536387;">00:00</h4>
                    </div>

                    <div style="font-family: 'Poppins', sans-serif;">
                        <i class="fa fa-clock" style="font-size: 30px; color: #dc3545; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 15px; color: #848484">Clock Out</p>
                        <h4 id="clockOutTime" style="font-size: 22px; font-weight: 700; color: #536387;">00:00</h4>           
                    </div>
                </div>
            </div> -->

            <div class="edit-container" style="align-content: center; justify-content: space-between;">          
                <div style="display: grid;">
                    <div style="color: #848484; font-size: 14px; align-content: center">Total Attendance</div>
                    <div style="font-size: 26px;"><?php echo $total_attendance_count; ?></div>
                </div>
                <div style="background-color: #5a54d1; align-content: center; text-align: center; border-radius: 50px; width: 50px; height: 50px; ">
                    <div class="fa fa-line-chart" style="color: #FFF; font-size: 18px;"></div>
                </div>
            </div> 
            <div class="edit-container" style="align-content: center; justify-content: space-between;">          
                <div style="display: grid;">
                    <div style="color: #848484; font-size: 14px; align-content: center">Average Clock In</div>
                    <div style="font-size: 26px;"><?php echo $average_clock_in_time ? $average_clock_in_time : 'N/A'; ?></div>
                </div>
                <div style="background-color: #5a54d1; align-content: center; text-align: center; border-radius: 50px; width: 50px; height: 50px; ">
                    <div class="fa-regular fa-clock" style="color: #FFF; font-size: 18px;"></div>
                </div>
            </div> 
     
<!--  
            <div class="edit-container">
                <p class="log-container">
                    Total Attendance
                </p>
                <p class="log-container">
                    4
                </p>
            </div> -->

            <!-- <div class="edit-container" style="align-content: center; justify-content: space-between;">          
                <div style="display: grid; gap: 4px;">
                    <div style="color: #848484; font-size: 14px; align-content: center">Average Clock In</div>
                    <div style="font-size: 26px;"><?php echo $average_clock_in_time ? $average_clock_in_time : 'N/A'; ?></div>
                </div>
                <div style="background-color: #5a54d1; align-content: center; text-align: center; border-radius: 50px; width: 50px; height: 50px; ">
                    <div class="fa fa-clock" style="color: #FFF; font-size: 18px;"></div>
                </div>
                </div>
            </div>  -->

        </div>
        

        <!-- Right Column (col-7) for Edit Student Form -->
        <div class="col-7" style="padding: 16px; width: 85%; background: white; border-radius: 8px; box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);">
            <h3 style="padding-bottom: 8px; color: #536387;">Profile / (<?php echo $student->student_id; ?>)</h3>
            <form method="post" action="<?php echo base_url('students/edit/'.$student->student_id); ?>">
                
                <div class="formbold-input-flex">
                    <div style="width: 100%;">
                        <label for="classroom" class="formbold-form-label">Classroom</label>
                        <select name="classroom" id="classroom" required class="formbold-form-input">
                            <option value="">Select Classroom</option>
                            <option value="Alpha" <?php echo ($student->classroom == 'Alpha') ? 'selected' : ''; ?>>Alpha</option>
                            <option value="Beta" <?php echo ($student->classroom == 'Beta') ? 'selected' : ''; ?>>Beta</option>
                            <option value="Gamma" <?php echo ($student->classroom == 'Gamma') ? 'selected' : ''; ?>>Gamma</option>
                        </select>
                    </div>

                    <div style="width: 100%;">
                        <label for="name" class="formbold-form-label">Name</label>
                        <input type="text" name="name" value="<?php echo $student->name; ?>" required class="formbold-form-input" />
                    </div>
                </div>

                <div class="formbold-input-flex">
                <div style="width: 100%;">
                        <label for="age" class="formbold-form-label">Age</label>
                        <input type="number" name="age" value="<?php echo $student->age; ?>" required class="formbold-form-input" />
                    </div>
                    <div style="width: 100%;">
                        <label for="gender" class="formbold-form-label">Gender</label>
                        <select name="gender" required class="formbold-form-input">
                            <option value="M" <?php echo ($student->gender == 'M') ? 'selected' : ''; ?>>Male</option>
                            <option value="F" <?php echo ($student->gender == 'F') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <!-- <div style="width: 100%;">
                        <label for="age" class="formbold-form-label">Age</label>
                        <input type="number" name="age" value="<?php echo $student->age; ?>" required class="formbold-form-input" />
                    </div> -->
                </div>
                
                <div style="display: flex; gap: 16px;">
                <a href="<?php echo base_url('students'); ?>" class="formbold-btn" style="background-color: #F4F6F5; color: #000000; text-align: center; text-decoration: none;">Cancel</a>
                    <button type="submit" class="formbold-btn">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>

<input type="hidden" name="student_id" id="student_id" value="<?php echo $student->student_id; ?>">


<script>
    
    const BASE_URL = "<?php echo base_url(); ?>";
    let clockedIn = false;
    
    document.addEventListener('DOMContentLoaded', () => {
    const studentId = document.getElementById('student_id').value;

    // AJAX request to check if attendance has already been marked today
    fetch(`${BASE_URL}attendance/mark_attendance`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_id: studentId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Attendance data:", data);
        } else {
            console.log(data.message);
        }
    })
    .catch(error => console.error("Error checking attendance:", error));
});


    function handleClock(event) {
    event.preventDefault();

    const now = new Date();
    const clockButton = document.getElementById('clockButton');
    const clockStatus = document.getElementById('clockStatus');

    if (!clockedIn) {
        if (!confirm("Are you sure you want to clock in?")) return;

        // Set clock-in time
        document.getElementById('clockInInput').value = now.toISOString();
        document.getElementById('clockInTime').textContent = now.toLocaleTimeString();
        clockStatus.textContent = "CLOCK OUT";
        clockButton.style.backgroundColor = "#dc3545";
        clockedIn = true;
    } else {
        // Set clock-out time
        document.getElementById('clockOutInput').value = now.toISOString();
        document.getElementById('clockOutTime').textContent = now.toLocaleTimeString();

        // Collect form data to send via AJAX
        const studentId = document.querySelector('[name="student_id"]').value;
        const clockIn = document.getElementById('clockInInput').value;
        const clockOut = document.getElementById('clockOutInput').value;

        // AJAX request to submit attendance data
        fetch(`${BASE_URL}attendance/mark_attendance`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ student_id: studentId, clock_in: clockIn, clock_out: clockOut })
        })
        .then(response => response.json())
        .then(data => {
            // Handle success or error
            if (data.success) {
                clockButton.disabled = true;
                clockButton.style.backgroundColor = "#6c757d";
                clockButton.style.cursor = "not-allowed";
                clockStatus.textContent = "CLOCKED TODAY";
            } else {
                alert("There was an error processing your attendance. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again later.");
        });
    }
}

</script>

<style>

    .edit-container{
        align-items: center;
        display: flex;
        padding: 16px; 
        background: white; 
        border-radius: 8px; 
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);"
    }

    .log-container{
        color: #29292A; 
        font-size: 15px;
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
        font-family: 'Poppins', sans-serif;
        width: 100%;
        border-radius: 5px;
        border: 1px solid #dde3ec;
        padding: 8px 8px;
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
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        border-radius: 5px;
        padding: 9px 8px;
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

    #clockButton:active {
        opacity: 0.7; 
    }

    .clock-button.gray {
        background-color: #6c757d;
        cursor: not-allowed;
    }

</style>
