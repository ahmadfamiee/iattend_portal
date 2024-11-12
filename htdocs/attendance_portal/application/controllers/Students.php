<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->model('Attendance_model');
        $this->load->library('form_validation'); 
    }

    public function index() {
        $classroom = $this->input->get('classroom'); 
        $student_id = $this->input->get('student_id'); 
        
        // fetch students based on classroom and/or student ID filter
        $data['students'] = $this->Student_model->get_filtered_students($classroom, $student_id);

        $data['total_students'] = $this->Student_model->get_total_students();

        $data['students_by_classroom'] = $this->Student_model->get_student_count_by_classroom();
        $data['alpha_count'] = 0;
        $data['beta_count'] = 0;
        $data['gamma_count'] = 0;

        foreach ($data['students_by_classroom'] as $classroom) {
            if ($classroom->classroom == 'Alpha') {
                $data['alpha_count'] = $classroom->student_count;
            } elseif ($classroom->classroom == 'Beta') {
                $data['beta_count'] = $classroom->student_count;
            } elseif ($classroom->classroom == 'Gamma') {
                $data['gamma_count'] = $classroom->student_count;
            }
        }
        
        $this->Attendance_model->create_daily_attendance_entries();

        
        $attendance = [];
        foreach ($data['students'] as $student) {
            $attendanceRecord = $this->Attendance_model->get_today_attendance_by_student($student->student_id);

            if ($attendanceRecord) {
                if ($attendanceRecord->clock_in) {
                    $attendanceRecord->status = $this->Attendance_model->get_attendance_status($attendanceRecord->clock_in);
                } else {
                    $attendanceRecord->status = 'Absent';
                }
                $attendance[$student->student_id] = $attendanceRecord;
            } else {
                $attendance[$student->student_id] = null;
            }
        }

       
        $data['attendance'] = $attendance;
        $data['selected_classroom'] = $classroom;
        $data['selected_student_id'] = $student_id;
        $data['content'] = 'list_students';
        $this->load->view('list_students', $data);
    }

    public function add() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('student_id', 'Student ID', 'required|callback_validate_student_id');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('age', 'Age', 'required|integer');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('classroom', 'Classroom', 'required');

            if ($this->form_validation->run() == FALSE) {
            
                $data['content'] = 'add_student';
                $this->load->view('templates/main_template', $data);
            } else {
                
                $student_data = [
                    'student_id' => $this->input->post('student_id'),
                    'name'       => $this->input->post('name'),
                    'age'        => $this->input->post('age'),
                    'gender'     => $this->input->post('gender'),
                    'classroom'  => $this->input->post('classroom')
                ];
                $this->Student_model->add_student($student_data);
                redirect('students');
            }
        } else {
            $data['content'] = 'add_student';  // Set the view to load as main content
            $this->load->view('templates/main_template', $data);
        }
    }

   
    public function validate_student_id($student_id) {
        $classroom = $this->input->post('classroom');
        
        if (!$classroom) {
            $this->form_validation->set_message('validate_student_id', 'Please select a classroom.');
            return FALSE;
        }

      
        $prefix = substr($classroom, 0, 1); // Gets the first letter, e.g., 'A' for Alpha

        if (strpos($student_id, $prefix) !== 0) {
            $this->form_validation->set_message('validate_student_id', "Student ID must start with '{$prefix}' for {$classroom} class.");
            return FALSE;
        }

        return TRUE; // CHECK validation passed or no
    }

    // In Students.php (Controller)
public function edit($student_id) {
    $data['student'] = $this->Student_model->get_student_by_id($student_id);
    $data['student_id'] = $student_id;

    // Fetch total attendance count and average clock-in time for this student
    $data['total_attendance_count'] = $this->Attendance_model->get_total_attendance_count($student_id);
    $data['average_clock_in_time'] = $this->Attendance_model->get_average_clock_in_time($student_id);

    if ($this->input->post()) {
        $student_data = [
            'name'      => $this->input->post('name'),
            'age'       => $this->input->post('age'),
            'gender'    => $this->input->post('gender'),
            'classroom' => $this->input->post('classroom')
        ];
        $this->Student_model->update_student($student_id, $student_data);
        redirect('students');
    }
    
    $this->load->view('edit_student', $data);
}


    public function delete($student_id) {
        $this->Student_model->delete_student($student_id);
        redirect('students');
    }
}