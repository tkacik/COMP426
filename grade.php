<?php

require_once('orm/Grade.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  // GET means either instance look up, index generation, or deletion

  // Following matches instance URL in form
  // /address.php/<id>

  if ((count($path_components) >= 3) &&
      ($path_components[1] != "") && ($path_components[2] != "")) {

    // Interpret <id> as integer
    $student_id = intval($path_components[1]);
    $section_id = intval($path_components[2]);

    // Look up object via ORM
    $grade = Grade::findByID($student_id, $section_id);

    if ($grade == null) {
      // address not found.
      header("HTTP/1.0 404 Not Found");
      print("Student in section id: " . $section_id . " not found.");
      exit();
    }

    // Check to see if deleting
    if (isset($_REQUEST['delete'])) {
      $grade->delete();
      header("Content-type: application/json");
      print(json_encode(true));
      exit();
    } 

    // Normal lookup.
    // Generate JSON encoding as response
    header("Content-type: application/json");
    print($grade->getJSON());
    exit();

  }

  // ID not specified, then must be asking for index
  header("Content-type: application/json");
  print(json_encode(Grade::getAllIDs()));
  exit();

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Either creating or updating

  // Following matches /address.php/<id> form
  if ((count($path_components) >= 3) &&
      ($path_components[1] != "") && ($path_components[2] != "")) {

    //Interpret <id> as integer and look up via ORM
    $student_id = intval($path_components[1]);
    $section_id = intval($path_components[2]);

    // Validate values
    $new_grade = false;
    if (isset($_REQUEST['enroll'])) {
      $new_grade = Grade::create("current", $section_id, $student_id);
    }

    // Return JSON encoding of updated address
    header("Content-type: application/json");
    if($new_grade != null)
	    print($new_grade->getJSON());
    exit();
  }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");

?>