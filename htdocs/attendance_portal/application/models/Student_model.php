<?php
class Student_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_total_students() {
        return $this->db->count_all('students'); 
    }  

    public function get_student_count_by_classroom() {
        $this->db->select('classroom, COUNT(*) as student_count');
        $this->db->group_by('classroom');
        return $this->db->get('students')->result(); 
    }
    
    // Retrieve students filtered by classroom and/or student ID
    public function get_filtered_students($classroom = null, $student_id = null) {
        if ($classroom) {
            $this->db->where('classroom', $classroom);
        }
        if ($student_id) {
            $this->db->where('student_id', $student_id);
        }
        return $this->db->get('students')->result();
    }

    // Fetch all students
    public function get_students() {
        $query = $this->db->get('students');
        return $query->result();
    }

    // Add a new student
    public function add_student($data) {
        return $this->db->insert('students', $data);
    }

    // Fetch a single student by ID
    public function get_student_by_id($student_id) {
        $this->db->where('student_id', $student_id);
        $query = $this->db->get('students');
        return $query->row();
    }

    public function get_students_by_classroom($classroom) {
        $this->db->where('classroom', $classroom);
        return $this->db->get('students')->result();
    }
    

    // Update student information
    public function update_student($student_id, $data) {
        $this->db->where('student_id', $student_id);
        return $this->db->update('students', $data);
    }

    // Delete a student
    public function delete_student($student_id) {
        $this->db->where('student_id', $student_id);
        return $this->db->delete('students');
    }
}