<?php

class Grade
{
	private $grade;
	private $section;
	private $student;

	public static function create($grade, $section_id, $student_id) {
    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

    $result = $mysqli->query("insert into Grade values ('".$grade."', ".$section_id.", ".$student_id.")");
    
    if ($result) {
      return new Grade($grade, $section_id, $student_id);
    }
    return null;
  }

  public function delete() {
    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");
    $mysqli->query("delete from Grade where grade = '" . $this->grade . "' AND section = " . $this->section . " AND student = " . $this->student);
  }

	public static function findByID($studentid, $sectionid){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		$result = $mysqli->query("select * from Grade where student = " . $studentid . " AND section = " . $sectionid);
	    if ($result) {
	      if ($result->num_rows == 0) {
		       return null;
	      }

	      $grade_info = $result->fetch_array();

	    return new Grade($grade_info['grade'],
			$grade_info['section'],
	        $grade_info['student']);
	    }
	    return null;
	}

	public static function getAllIDs() {
	    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

	    $result = $mysqli->query("select grade from Grade");
	    $id_array = array();

	    if ($result) {
	      while ($next_row = $result->fetch_array()) {
		$id_array[] = intval($next_row['grade']);
	      }
	    }
	    return $id_array;
	}

	public function __construct($grade, $section, $student){
		$this->grade = $grade;
		$this->section = $section;
		$this->student = $student;
	}

	public function getGrade(){
		return $this->grade;
	}

	public function getSection(){
		return $this->section;
	}

	public function getStudent(){
		return $this->student;
	}

	public function setGrade($grade) {
	    $this->grade = $grade;
	    return $this->update();
  	}

  	private function update() {
    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

    $result = $mysqli->query("UPDATE Grade SET grade = '" .$this->grade. "', section = " .$this->section. ", student = " .$this->student. " WHERE section = " .$this->section. " AND student = " .$this->student);

    return $result;
  }

	public function getJSON() {
		$json_obj = array('grade' => $this->grade,
			'section' => $this->section,
			'student' => $this->student
			);
		return json_encode($json_obj);
	}
}
?>