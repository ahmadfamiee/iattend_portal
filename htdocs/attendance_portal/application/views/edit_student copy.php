<?php echo validation_errors(); ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">

<div style="padding: 16px 32px;">
    <div class="container" style="display: flex; align-items: flex-start; gap: 24px;">

        <!-- left (col-5) for illustration -->
        <div style="width: 30%;">
            <div class="hello" style="padding: 16px; margin-bottom: 16px; background: white; text-align: center; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h4 style="color: #536387;">Welcome back, <?php echo $student->name; ?>! 👋</h4>
            </div>

            <div class="col-5" style="align-items: center; padding: 16px; background: white; width: 30%; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; ">
                <h2 style="color: #536387; margin-bottom: 16px;">New joiner? Simplify your attendance<br>Register now!</h2>
                    <img src="<?php echo base_url('/assets/new_student.png'); ?>" style="width: 80%; max-width: 220px; border-radius: 8px;">
            </div>

          
        </div>

        <!-- right (col-7) for add form -->
        <div class="col-7" style="padding: 16px; width: 70%; background: white; border-radius: 8px; box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #536387; padding-bottom:8px">Add New Student</h2>
            <form method="post" action="<?php echo base_url('students/add'); ?>">

            <div style="width: 100%;">
                    <label for="classroom" class="formbold-form-label">Classroom</label>
                    <select name="classroom" id="classroom" required class="formbold-form-input" onchange="updateStudentIdPlaceholder()">
                        <option value="">Select Classroom</option>
                        <option value="Alpha" <?php echo (isset($student->classroom) && $student->classroom == 'Alpha') ? 'selected' : ''; ?>>Alpha</option>
                        <option value="Beta" <?php echo (isset($student->classroom) && $student->classroom == 'Beta') ? 'selected' : ''; ?>>Beta</option>
                        <option value="Gamma" <?php echo (isset($student->classroom) && $student->classroom == 'Gamma') ? 'selected' : ''; ?>>Gamma</option>
                    </select>
                </div>

                <div class="formbold-input-flex">
                    <div style="width: 100%;">
                    <label for="student_id" class="formbold-form-label">Student ID</label>
                    <input type="text" name="student_id" id="student_id" required class="formbold-form-input" placeholder="e.g., A123 if in Alpha">
                    </div>
                    <div style="width: 100%;">
                        <label for="name" class="formbold-form-label">Name</label>
                        <input type="text" name="name" id="name" required class="formbold-form-input" />
                    </div>
                </div>

                <div class="formbold-input-flex">
                    <div style="width: 100%;">
                        <label for="age" class="formbold-form-label">Age</label>
                        <input type="number" name="age" id="age" required class="formbold-form-input" />
                    </div>
                    <div style="width: 100%;">
                        <label for="gender" class="formbold-form-label">Gender</label>
                        <select name="gender" id="gender" required class="formbold-form-input">
                            <option value="">Select Gender</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>            

                <button type="submit" class="formbold-btn">Add</button>
            </form>
        </div>

    </div>
</div>

<input type="hidden" name="student_id" id="student_id" value="<?php echo $student->student_id; ?>">


<script>
    
    const BASE_URL = "<?php echo base_url(); ?>";
    let clockedIn = false;
    
    document.addEventListener('DOMContentLoaded', () => {
    // Get student_id from the hidden input field
    const studentId = document.getElementById('student_id').value;

    // AJAX request to check if attendance has already been marked today
    fetch(`${BASE_URL}attendance/mark_attendance`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_id: studentId })
    })
    .then(response => response.json())
    .then(data => {
        // Handle attendance status here
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
        // notification.style.display = "block";

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
                // Disable the button and show notification
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
    /* General Form Styles */
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

    #clockButton:active {
    opacity: 0.7; /* Adjust the opacity to your preference */
}

.clock-button.gray {
    background-color: #6c757d; /* Gray color */
    cursor: not-allowed;
}

</style>
