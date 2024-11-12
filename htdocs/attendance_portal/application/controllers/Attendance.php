<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->model('Attendance_model');
        $this->Attendance_model->create_daily_attendance_entries();
        $this->load->library('form_validation');
    }

    // Helper method to check if attendance is complete for the day
    private function is_attendance_complete($student_id) {
        $today_date = date('Y-m-d');
        $attendance = $this->Attendance_model->get_today_attendance($student_id, $today_date);
        return $attendance && $attendance->clock_in && $attendance->clock_out;
    }

    // Method to display the attendance view for a specific student
    public function mark_attendance($student_id = null) {
        if ($student_id === null) {
            show_error("Student ID is required");
            return;
        }

        $student = $this->Student_model->get_student_by_id($student_id);
        if (!$student) {
            show_error("Student not found");
            return;
        }

        $today_date = date('Y-m-d');
        $attendance = $this->Attendance_model->get_today_attendance($student_id, $today_date);
        
        // Determine attendance state based on today's records
        $attendance_state = 'clock_in';
        if ($attendance && $attendance->clock_in && !$attendance->clock_out) {
            $attendance_state = 'clock_out';
        } elseif ($attendance && $attendance->clock_in && $attendance->clock_out) {
            $attendance_state = 'completed';
        }

        // Fetch attendance history
        $attendance_history = $this->Attendance_model->get_attendance_history($student_id);

        // Data to be passed to the view
        $data = [
            'student_name' => $student->name,
            'student_id' => $student_id,
            'attendance_date' => $today_date,
            'clock_in_time' => $attendance ? $attendance->clock_in : null,
            'clock_out_time' => $attendance ? $attendance->clock_out : null,
            'attendance_state' => $attendance_state,
            'disable_button' => $attendance_state === 'completed',
            'disable_reason' => $attendance_state === 'completed' ? 'Attendance already completed for today.' : '',
            'attendance_history' => $attendance_history
        ];

        $this->load->view('mark_attendance', $data);
    }

    // TEST

public function toggle_clock() {
    $student_id = $this->input->post('student_id');
    $state = $this->input->post('state');
    $current_time = date('Y-m-d H:i:s');
    $today_date = date('Y-m-d');

    if ($this->is_attendance_complete($student_id)) {
        echo json_encode(['status' => 'disabled', 'message' => 'Attendance already marked for today']);
        return;
    }

    $data = [];
    if ($state === 'clock_in') {
        $data = ['clock_in' => $current_time];
    } else {
        $clock_in_time = $this->Attendance_model->get_clock_in_time($student_id);
        $data = [
            'clock_out' => $current_time,
            'total_hours' => $this->calculate_total_hours($clock_in_time, $current_time)
        ];
    }

    // Update attendance with clock-in or clock-out time
    $this->Attendance_model->update_attendance($student_id, $data);

    // Update and get the latest status
    $status = $this->Attendance_model->update_attendance_status($student_id, $today_date);

    echo json_encode([
        'status' => 'success',
        'state' => $state === 'clock_in' ? 'clock_out' : 'completed',
        'time' => $current_time,
        'attendance_status' => $status
    ]);
}

    // AJAX method to toggle clock-in and clock-out times

    // public function toggle_clock() {
    //     $student_id = $this->input->post('student_id');
    //     $state = $this->input->post('state');
    //     $current_time = date('Y-m-d H:i:s');

    //     if ($this->is_attendance_complete($student_id)) {
    //         echo json_encode(['status' => 'disabled', 'message' => 'Attendance already marked for today']);
    //         return;
    //     }

    //     $data = [];
    //     if ($state === 'clock_in') {
    //         $data = [
    //             'clock_in' => $current_time,
    //             'status' => $current_time < date('Y-m-d') . ' 09:10:00' ? 'On-time' : 'Late'
    //         ];
    //         $new_state = 'clock_out';
    //     } else {
    //         $clock_in_time = $this->Attendance_model->get_clock_in_time($student_id);
    //         $data = [
    //             'clock_out' => $current_time,
    //             'total_hours' => $this->calculate_total_hours($clock_in_time, $current_time)
    //         ];
    //         $new_state = 'completed';
    //     }

    //     $this->Attendance_model->update_attendance($student_id, $data);

    //     echo json_encode([
    //         'status' => 'success',
    //         'state' => $new_state,
    //         'time' => $current_time
    //     ]);
    // }

    // Helper function to calculate total hours worked
    private function calculate_total_hours($clock_in, $clock_out) {
        $start = new DateTime($clock_in);
        $end = new DateTime($clock_out);
        $interval = $start->diff($end);
        return $interval->format('%H:%I:%S');
    }

    public function report_attendance() {
        // Fetch all attendance records or customize as needed
        $data['attendance_reports'] = $this->Attendance_model->get_attendance_reports();
    
        // Load the report_attendance view with attendance data
        $this->load->view('report_attendance', $data);
    }
    
}
