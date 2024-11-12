<?php echo validation_errors(); ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">

<div style="padding: 16px 32px;">
    <div class="container" style="display: flex; align-items: flex-start; gap: 24px;">

        <!-- left (col-5) for illustration -->
        <div style="width: 30%;">
            <div class="col-5" style="align-items: center; padding: 16px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; ">
                <h2 style="color: #536387; margin-bottom: 16px;">Simplify attendance, <br>Register now!</h2>
                    <img src="<?php echo base_url('/assets/new_student.png'); ?>" style="width: 80%; max-width: 220px; border-radius: 8px;">
                        <div style="font-size: 12px; color: #536387; margin-top: 10px;">
                            <a href="<?php echo base_url('students'); ?>" style="font-weight: 500; text-align: center; text-decoration: none;">Existed student? Click here</a>
                        </div>
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
<style>
    /* General Form Styles (same as edit_student.php) */
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
        padding: 8px 8px;
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
</style>

<script>
    // JavaScript to dynamically update the Student ID placeholder based on selected classroom
    function updateStudentIdPlaceholder() {
        const classroom = document.getElementById("classroom").value;
        const studentIdInput = document.getElementById("student_id");

        if (classroom === "Alpha") {
            studentIdInput.placeholder = "e.g., A123";
        } else if (classroom === "Beta") {
            studentIdInput.placeholder = "e.g., B123";
        } else if (classroom === "Gamma") {
            studentIdInput.placeholder = "e.g., G123";
        } else {
            studentIdInput.placeholder = "e.g., A123 if in Alpha";
        }
    }
</script>