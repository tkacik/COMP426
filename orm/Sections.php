<?php

class Sections
{
	private $id;
	private $course;
	private $sec_num;
	private $size;
	private $max;
	private $prof;
	private $location;
	private $time_slot;
	private $semester;

	public static function findByID($id){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		$result = $mysqli->query("select * from Section where id = " . $id);
	    if ($result) {
	      if ($result->num_rows == 0) {
		       return null;
	      }

	      $section_info = $result->fetch_array();

	    return new Sections(intval($section_info['id']),
			$section_info['course'],
			$section_info['sec_num'],
			$section_info['size'],
	        $section_info['max'],
	        $section_info['prof'],
	        $section_info['location'],
	        $section_info['time_slot'],
	        $section_info['semester']);
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

	public static function searchByParam($cnum, $dept, $equals, $ge, $instructor, $semester, $honors, $lab){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		if ($equals == -1){
			$op = "<=";
		} else if ($equals == 0){
			$op = "=";
		} else if ($equals == 1){
			$op = ">=";
		}

		if ($semester[0] == "F"){
			$term = "fall";
		} else if ($semester[0] == "S"){
			$term = "spring";
		}
		$year = substr($semester,-4);

		$result = $mysqli->query("select Section.id from Section WHERE Section.course " .$op. " " .$cnum ." ");

	}

	public function __construct($id, $dept, $Section_num, $description, $honors, $lab){
		$this->id = $id;
		$this->course = $course;
		$this->sec_num = $sec_num;
		$this->size = $size;
		$this->max = $max;
		$this->prof = $prof;
		$this->location = $location;
		$this->time_slot = $time_slot;
		$this->semester = $semester;
	}

	public function getID(){
		return $this->id;
	}

	public function getCourse(){}
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

	public function getJSON() {
		$json_obj = array('id' => $this->id,
			'course' => $this->course,
			'sec_num' => $this->sec_num,
			'size' => $this->size,
			'max' => $this->max,
			'prof' => $this->prof,
			'location' => $this->location,
			'time_slot' => $this->time_slot,
			'semester' => $this->semester
			);
		return json_encode($json_obj);
	}
}
?>