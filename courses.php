<?php

require_once('orm/Courses.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  // GET means either instance look up, index generation, or deletion

  // Following matches instance URL in form
  // /address.php/<id>

	if ((count($path_components) = 2) &&
      ($path_components[1] != "")) {

    // Interpret <id> as integer
    $course_id = intval($path_components[1]);

    // Look up object via ORM
    $course = Courses::findByID($course_id);

    if ($course == null) {
      // course not found.
      header("HTTP/1.0 404 Not Found");
      print("Course id: " . $course_id . " not found.");
      exit();
    }

    // Check to see if deleting
    if (isset($_REQUEST['delete'])) {
      $address->delete();
      header("Content-type: application/json");
      print(json_encode(true));
      exit();
    } 

    // Normal lookup.
    // Generate JSON encoding as response
    header("Content-type: application/json");
    print($course->getJSON());
    exit();

  }

  // ID not specified, then must be asking for index
  header("Content-type: application/json");
  print(json_encode(Courses::getAllIDs()));
  exit();

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Either creating or updating

  // Following matches /address.php/<id> form
  if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    //Interpret <id> as integer and look up via ORM
    $address_id = intval($path_components[1]);
    $address = Address::findByID($address_id);

    if ($address == null) {
      // address not found.
      header("HTTP/1.0 404 Not Found");
      print("Address id: " . $address_id . " not found while attempting update.");
      exit();
    }

    // Validate values
    $new_first_name = false;
    if (isset($_REQUEST['first_name'])) {
      $new_first_name = trim($_REQUEST['first_name']);
      if ($new_first_name == "") {
	header("HTTP/1.0 400 Bad Request");
	print("Bad title");
	exit();
      }
    }

    $new_last_name = false;
    if (isset($_REQUEST['last_name'])) {
      $new_last_name = trim($_REQUEST['last_name']);
    }

    $new_street = false;
    if (isset($_REQUEST['street'])) {
      $new_street = trim($_REQUEST['street']);
    }

    $new_city = false;
    if (isset($_REQUEST['city'])){
      $new_city = trim($_REQUEST['city']);
    }

    $new_state = false;
    if (isset($_REQUEST['state'])){
      $new_state = trim($_REQUEST['state']);
    }

    $new_zip = false;
    if (isset($_REQUEST['zip'])){
      $new_zip = trim($_REQUEST['zip']);
    }

    $new_phone = false;
    if (isset($_REQUEST['phone'])){
      $new_phone = trim($_REQUEST['phone']);
    }

    // Update via ORM

    if($new_first_name != false){
      $address->setFirstName($new_first_name);
    }

    if($new_last_name != false){
      $address->setLastName($new_last_name);
    }

    if($new_street != false){
      $address->setStreet($new_street);
    }

    if($new_city != false){
      $address->setCity($new_city);
    }

    if($new_state != false){
      $address->setState($new_state);
    }

    if($new_zip != false){
      $address->setZIP($new_zip);
    }

    if($new_phone != false){
      $address->setPhone($new_phone);
    }

    // Return JSON encoding of updated address
    header("Content-type: application/json");
    print($address->getJSON());
    exit();
  } else {

    // Creating a new address item

    // Validate values

    if (!isset($_REQUEST['first_name'])) {
      header("HTTP/1.0 400 Bad Request");
      print("Missing name");
      exit();
    }

    $first_name = trim($_REQUEST['first_name']);
    if ($first_name == "") {
      header("HTTP/1.0 400 Bad Request");
      print("Bad name");
      exit();
    }

    $last_name = "";
    if (isset($_REQUEST['last_name'])) {
      $note = trim($_REQUEST['last_name']);
    }

    $street = false;
    if (isset($_REQUEST['street'])) {
      $street = trim($_REQUEST['street']);
    }

    $city = false;
    if (isset($_REQUEST['city'])){
      $city = trim($_REQUEST['city']);
    }

    $state = false;
    if (isset($_REQUEST['state'])){
      $state = trim($_REQUEST['state']);
    }

    $zip = false;
    if (isset($_REQUEST['zip'])){
      $zip = trim($_REQUEST['zip']);
    }

    $phone = false;
    if (isset($_REQUEST['phone'])){
      $phone = trim($_REQUEST['phone']);
    }


    // Create new address via ORM
    $new_address = Address::create($first_name, $last_name, $street, $city, $state, $zip, $phone);

    // Report if failed
    if ($new_address == null) {
      header("HTTP/1.0 500 Server Error");
      print("Server couldn't create new address.");
      exit();
    }
    
    //Generate JSON encoding of new address
    header("Content-type: application/json");
    print($new_address->getJSON());
    exit();
  }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");

?>