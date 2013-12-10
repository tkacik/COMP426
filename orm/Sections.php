<?php

class Sections
{
	private $id;
	private $course;
	private $dept;
	private $cnum;
	private $sec_num;
	private $size;
	private $max;
	private $prof;
	private $location;
	private $time_slot;

	public static function findByID($id){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		$result = $mysqli->query("select Section.*, Building.abbrev, Course.course_num, TimeSlot.* from Section, Course, Department, Building, TimeSlot where Course.id = Section.course AND Department.id = Course.dept AND TimeSlot.id = Section.time_slot AND Section.location = Building.id AND Section.id = " . $id);
	    if ($result) {
	      if ($result->num_rows == 0) {
		       return null;
	      }

	    $section_info = $result->fetch_array();
	      
	    $days = "";
	    if ($section_info['m']) $days = $days . 'M';
	    if ($section_info['t']) $days = $days . 'T';
	    if ($section_info['w']) $days = $days . 'W';
	    if ($section_info['r']) $days = $days . 'R';
	    if ($section_info['f']) $days = $days . 'F';
	    if ($section_info['s']) $days = $days . 'S';
	    if ($section_info['n']) $days = $days . 'N';
	    
	    array_push($section_info,"days"=>$days);
	    $section_info['time_slot'] = substr($section_info['start_time'], 0, 5) . '-' . substr($section_info['end_time'], 0, 5); 
	    
	    return new Sections(intval($section_info['id']),
			$section_info['course'],
    		$section_info['abbrev'],
	    	$section_info['course_num'],
			$section_info['sec_num'],
			$section_info['size'],
	        $section_info['max'],
	        $section_info['prof'],
	        $section_info['name'],
	        $section_info['time_slot'],
	    	$section_info['days']);
	    }
	    return null;
	}

	public static function getAllIDs() {
	    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

	    $result = $mysqli->query("select id from Section");
	    $id_array = array();

	    if ($result) {
	      while ($next_row = $result->fetch_array()) {
		$id_array[] = intval($next_row['id']);
	      }
	    }
	    return $id_array;
	}

	public static function searchByParam($cnum, $dept, $equals, $prof, $honors, $lab){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		if ($equals == -1){
			$op = "<=";
		} else if ($equals == 0){
			$op = "=";
		} else if ($equals == 1){
			$op = ">=";
		}

		$secret == "";

		if ($cnum == '' || $cnum == null){
			$secret = $secret."";
		} else {
			$secret = $secret."Course.course_num " .$op. " " .$cnum;
		}

		if ($dept == '' || $dept == null){
			$secret = $secret."";
		} else {
			if ($secret != ""){
				$secret = $secret." AND dept = (SELECT id FROM Department WHERE abbrev = '" .$dept. "')";
			} else {
				$secret = $secret."dept = (SELECT id FROM Department WHERE abbrev = '" .$dept. "')";
			}
		}

		if ($prof == '' || $prof == null){
			$secret = $secret."";
		} else{
			if ($secret != ""){
				$secret = $secret." AND prof = (SELECT pid FROM Professor WHERE last = '" .$prof. "')";
			} else {
				$secret = $secret."prof = (SELECT pid FROM Professor WHERE last = '" .$prof. "')";
			}
		}

		if ($honors == '' || $honors == null){
			$secret = $secret."";
		} else {
			if ($secret != ""){
				$secret = $secret." AND honors = " .$honors. "";
			} else {
				$secret = $secret."honors = " .$honors. "";
			}
		}

		if ($lab == '' || $lab == null){
			$secret = $secret."";
		} else {
			if ($secret != ""){
				$secret = $secret." AND lab = " .$lab. "";
			} else {
				$secret = $secret."lab = " .$lab. "";
			}
		}

		$secret = $secret." AND Section.course = Course.id";

		$result = $mysqli->query("SELECT Section.id from Section, Course WHERE ".$secret);

		$id_array = array();

	    if ($result) {
	      while ($next_row = $result->fetch_array()) {
		$id_array[] = intval($next_row['id']);
	      }
	    }
	    return $id_array;

	}

	public static function searchByStudent($student){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		$result = $mysqli->query("SELECT Section.id FROM Section, Grade WHERE Section.id = Grade.section AND Grade.student = " .$student);

		$id_array = array();

	    if ($result) {
	      while ($next_row = $result->fetch_array()) {
		$id_array[] = intval($next_row['id']);
	      }
	    }
	    return $id_array;
	}

	public function __construct($id, $course, $dept, $cnum, $sec_num, $size, $max, $prof, $location, $time_slot, $days){
		$this->id = $id;
		$this->course = $course;
		$this->dept = $dept;
		$this->cnum = $cnum;
		$this->sec_num = $sec_num;
		$this->size = $size;
		$this->max = $max;
		$this->prof = $prof;
		$this->location = $location;
		$this->time_slot = $time_slot;
		$this->days = $days;
	}

	public function getID(){
		return $this->id;
	}

	public function getCourse(){
		return $this->course;
	}

	public function getSecNum(){
		return $this->sec_num;
	}

	public function getSize(){
		return $this->size;
	}

	public function getMax(){
		return $this->max;
	}

	public function getProf(){
		return $this->prof;
	}

	public function getLocation(){
		return $this->location;
	}

	public function getTimeSlot(){
		return $this->time_slot;
	}

	public function getSemester(){
		return $this->semester;
	}
	
	public function getDept(){
		return $this->dept;
	}
	
	public function getCnum(){
		return $this->cnum;
	}
	
	public function getDays(){
		return $this->days;
	}

	public function getJSON() {
		$json_obj = array('id' => $this->id,
			'course' => $this->course,
			'dept' => $this->dept,
			'cnum' => $this->cnum,
			'sec_num' => $this->sec_num,
			'size' => $this->size,
			'max' => $this->max,
			'prof' => $this->prof,
			'location' => $this->location,
			'time_slot' => $this->time_slot,
			'semester' => $this->semester,
			'days' => $this->days
			);
		return json_encode($json_obj);
	}
}
?>