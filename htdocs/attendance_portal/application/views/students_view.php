<!DOCTYPE html>
<html>
<head>
    <title>Students List with Attendance</title>
</head>
<body>
    <h1>Students List and Attendance for Today</h1>

    <form method="post" action="<?php echo site_url('students/save_attendance'); ?>">
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Classroom</th>
                    <th>Attendance Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Get list of students who have already marked attendance
                    $marked_ids = array_column($attendance_today, 'student_id');
                ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student->student_id; ?></td>
                        <td><?php echo $student->name; ?></td>
                        <td><?php echo $student->age; ?></td>
                        <td><?php echo $student->gender; ?></td>
                        <td><?php echo $student->classroom; ?></td>
                        <td>
                            <?php
                            if (in_array($student->student_id, $marked_ids)) {
                                // If attendance has already been marked
                                foreach ($attendance_today as $attendance) {
                                    if ($attendance->student_id == $student->student_id) {
                                        echo $attendance->status;
                                        break;
                                    }
                                }
                            } else { ?>
                                <!-- Attendance form for students who haven't marked attendance -->
                                <input type="radio" name="status_<?php echo $student->student_id; ?>" value="On time" required> On time
                                <input type="radio" name="status_<?php echo $student->student_id; ?>" value="Late" required> Late
                                <input type="radio" name="status_<?php echo $student->student_id; ?>" value="Absent" required> Absent
                                <input type="hidden" name="student_id[]" value="<?php echo $student->student_id; ?>">
                            <?php } ?>
                        </td>
                        <td>
                            <a href="<?php echo site_url('students/edit_student/'.$student->student_id); ?>">Edit</a> |
                            <a href="<?php echo site_url('students/delete_student/'.$student->student_id); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Submit Attendance</button>
    </form>
</body>
</html>
