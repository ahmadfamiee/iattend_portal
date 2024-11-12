<?php
class Attendance_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Create daily attendance entries for all students if not already present
    public function create_daily_attendance_entries() {
        $students = $this->db->get('students')->result();
        $today = date('Y-m-d');

        foreach ($students as $student) {
            $this->db->where(['student_id' => $student->student_id, 'attendance_date' => $today]);
            if ($this->db->get('attendance')->num_rows() == 0) {
                $this->db->insert('attendance', [
                    'student_id' => $student->student_id,
                    'attendance_date' => $today,
                    'status' => 'Absent'
                ]);
            }
        }
    }

    // Retrieve today's attendance record for a student
    public function get_today_attendance($student_id) {
        $today_date = date('Y-m-d');
        $this->db->where(['student_id' => $student_id, 'attendance_date' => $today_date]);
        return $this->db->get('attendance')->row();
    }

    public function get_today_attendance_by_student($student_id) {
        $today_date = date('Y-m-d');
        return $this->get_today_attendance($student_id, $today_date);
    }

    // set/ mark students absent if past cutoff time without clocking in
    public function mark_absent_if_past_cutoff() {
        $cutoff_time = date('Y-m-d') . ' 09:10:00';
        $students = $this->db->get('students')->result();
        
        foreach ($students as $student) {
            $attendance = $this->get_today_attendance($student->student_id, date('Y-m-d'));
            if (!$attendance || !$attendance->clock_in) {
                $this->update_attendance($student->student_id, ['status' => 'Absent']);
            }
        }
    }


 // method in mark_attendance view( set absent if past cutoff time without clocking in)
    public function update_attendance_status($student_id, $date) {
        $this->db->where(['student_id' => $student_id, 'attendance_date' => $date]);
        $attendance = $this->db->get('attendance')->row();

        if ($attendance) {

            if ($attendance->clock_in) {
                $clock_in_time = date("H:i:s", strtotime($attendance->clock_in));
                if ($clock_in_time >= "08:00:00" && $clock_in_time <= "09:10:00") {
                    $status = 'On-time';
                } elseif ($clock_in_time > "09:10:00") {
                    $status = 'Late';
                } else {
                    $status = 'Absent'; // default if no clock_in 
                }

                $this->db->where(['student_id' => $student_id, 'attendance_date' => $date]);
                $this->db->update('attendance', ['status' => $status]);
                
                return $status; 
            }
            return $attendance->status;
        }
        return 'Absent'; // default if no attendance record is found
    }


    public function get_clock_in_time($student_id) {
        $today_date = date('Y-m-d');
        $this->db->select('clock_in');
        $this->db->where(['student_id' => $student_id, 'attendance_date' => $today_date]);
        return $this->db->get('attendance')->row()->clock_in ?? null;
    }

    public function update_attendance($student_id, $data) {
        $this->db->where(['student_id' => $student_id, 'attendance_date' => date('Y-m-d')]);
        $this->db->update('attendance', $data);
    }

    public function get_attendance_history($student_id) {
        $this->db->where('student_id', $student_id);
        $this->db->order_by('attendance_date', 'DESC');
        return $this->db->get('attendance')->result();
    }
    
    // adjust threeshold here
    public function get_attendance_status($clock_in) {
        $threshold_time = "09:20:00";
        $clock_in_time = date("H:i:s", strtotime($clock_in));
        return ($clock_in_time <= $threshold_time) ? 'On-time' : 'Late';
    }

    
    // public function get_attendance_reports() {
    //     $query = $this->db->get('students');
    //     return $query->result();
    // }

    public function get_attendance_reports() {
        $this->db->select('students.student_id, students.name, students.classroom, attendance.attendance_date, attendance.clock_in, attendance.clock_out, attendance.status, attendance.total_hours');
        $this->db->from('attendance');
        $this->db->join('students', 'attendance.student_id = students.student_id');
        $this->db->order_by('attendance.attendance_date', 'DESC');
        return $this->db->get()->result();
    }

    public function get_total_attendance_count($student_id) {
        $this->db->where('student_id', $student_id);
        $this->db->where('clock_in IS NOT NULL');
        return $this->db->count_all_results('attendance');
    }

    // public function get_average_clock_inout_time($student_id) {
    //     $this->db->select_avg('clock_in');
    //     $this->db->where('student_id', $student_id);
    //     $this->db->where('clock_in IS NOT NULL'); // Only include records where clock-in time is set
    //     $query = $this->db->get('attendance');
        
    //     $result = $query->row();
        
    //     return $result->clock_in ? date('g:i A', strtotime($result->clock_in)) : null;
    // }

    // public function get_average_clock_in_time($student_id) {
    //     $this->db->select('clock_in');
    //     $this->db->where('student_id', $student_id);
    //     $this->db->where('clock_in IS NOT NULL');
    //     $query = $this->db->get('attendance');
    
    //     $clock_in_times = $query->result();
        
    //     if (empty($clock_in_times)) {
    //         return null;
    //     }
    
    //     $total_seconds = 0;
    //     $count = count($clock_in_times);
        
    
    //     foreach ($clock_in_times as $time) {
    //         $total_seconds += strtotime($time->clock_in);
    //     }
    
    //     $average_seconds = $total_seconds / $count;
    //     $average_time = date('g:i A', $average_seconds);
    
    //     return $average_time;
    // }
    
    
    public function get_average_clock_in_time($student_id) {
        $this->db->select('clock_in');
        $this->db->where('student_id', $student_id);
        $this->db->where('clock_in IS NOT NULL');
        $query = $this->db->get('attendance');
    
        $clock_in_times = $query->result();
        
        if (empty($clock_in_times)) {
            return null;
        }
    
        $total_seconds = 0;
        $count = count($clock_in_times);
    
        foreach ($clock_in_times as $time) {
            $time_parts = explode(':', date('H:i', strtotime($time->clock_in)));
            $hours = $time_parts[0];
            $minutes = $time_parts[1];
            $seconds = ($hours * 3600) + ($minutes * 60);
    
            $total_seconds += $seconds;
        }
    
        // calculate the average in seconds
        $average_seconds = $total_seconds / $count;
        
        // convert back to hours and minutes
        $average_hours = floor($average_seconds / 3600);
        $average_minutes = floor(($average_seconds % 3600) / 60);
    
        // format
        $average_time = date('g:i A', strtotime("$average_hours:$average_minutes"));
    
        return $average_time;
    }
    
}
