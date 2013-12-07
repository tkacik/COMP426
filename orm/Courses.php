<?php

class Course
{
	private $id;
	private $dept;
	private $course_num;
	private $description;
	private $honors;
	private $lab;

	public static function findByID($id){
		$mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

		$result = $mysqli->query("select * from Course where id = " . $id);
	    if ($result) {
	      if ($result->num_rows == 0) {
		       return null;
	      }

	      $course_info = $result->fetch_array();

	    return new Address(intval($address_info['id']),
			$course_info['dept'],
			$course_info['course_num'],
			$course_info['description'],
	        $course_info['honors'],
	        $course_info['lab']);
	    }
	    return null;
	  }
	}

	public static function getAllIDs() {
	    $mysqli = new mysqli("classroom.cs.unc.edu", "guok", "CH@ngemenow99Please!guok", "guokdb");

	    $result = $mysqli->query("select id from Course");
	    $id_array = array();

	    if ($result) {
	      while ($next_row = $result->fetch_array()) {
		$id_array[] = intval($next_row['id']);
	      }
	    }
	    return $id_array;
	}

	public function __construct($id, $dept, $course_num, $description, $honors, $lab){
		$this->id = $id;
		$this->dept = $dept;
		$this->course_num = $course_num;
		$this->description = $description;
		$this->honors = $honors;
		$this->lab = $lab;
	}

	public function getID(){
		return $this->id;
	}

	public function getDept(){
		return $this->dept;
	}

	public function getCourseNum(){
		return $this->course_num;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getHonors(){
		return $this->honors;
	}

	public function getLab(){
		return $this->lab;
	}

	public function getJSON() {
		$json_obj = array('id' => $this->id,
			'dept' => $this->dept,
			'course_num' => $this->course_num,
			'description' => $this->description,
			'honors' => $this->honors,
			'lab' => $this->lab,
			);
		return json_encode($json_obj);
	}
}
?>