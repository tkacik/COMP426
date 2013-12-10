<?php

require_once('orm/Sections.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  // GET means either instance look up, index generation, or deletion

  // Following matches instance URL in form
  // /address.php/<id>

	if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    // Interpret <id> as integer
    $section_id = intval($path_components[1]);

    // Look up object via ORM
    $section = Sections::findByID($section_id);

    if ($section == null) {
      // section not found.
      header("HTTP/1.0 404 Not Found");
      print("Section id: " . $section_id . " not found.");
      exit();
    }

    // Check to see if deleting
    if (isset($_REQUEST['delete'])) {
      $section->delete();
      header("Content-type: application/json");
      print(json_encode(true));
      exit();
    } 

    // Normal lookup.
    // Generate JSON encoding as response
    header("Content-type: application/json");
    print($section->getJSON());
    exit();

  } else {
    // ID not specified, then must be asking for index of params

    $student = false;
    if (isset($_REQUEST['student'])) {
      $student = trim($_REQUEST['student']);
      $section = Sections::searchByStudent($student);
      header("Content-type: application/json");
      print(json_encode($section));
      exit();
    }

    $course = false;
    if (isset($_REQUEST['course'])) {
      $course = trim($_REQUEST['course']);
    }

    $cnum = false;
    if (isset($_REQUEST['cnum'])) {
      $cnum = trim($_REQUEST['cnum']);
    }

    $dept = false;
    if (isset($_REQUEST['dept'])) {
      $dept = trim($_REQUEST['dept']);
    }

    $equals = false;
    if (isset($_REQUEST['equals'])) {
      $equals = trim($_REQUEST['equals']);
    }

    $instructor = false;
    if (isset($_REQUEST['instructor'])) {
      $instructor = trim($_REQUEST['instructor']);
    }

    $honors = false;
    if (isset($_REQUEST['honors'])) {
      $honors = trim($_REQUEST['honors']);
    }

    $lab = false;
    if (isset($_REQUEST['lab'])) {
      $lab = trim($_REQUEST['lab']);
    }

    $section = Sections::searchByParam($cnum, $dept, $equals, $instructor, $honors, $lab);

    header("Content-type: application/json");
    print(json_encode($section));
  exit();
  }


}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");

?>