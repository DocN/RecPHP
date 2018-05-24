<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->classID)) {
    die();
}

//echo $request->encryptionKey;
//$res_ar = array("foo"=> $_REQUEST['body']);
$tester = 0;
$response = '';
include "../dbCredentials.php";


$classID = $request->classID;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$events = array();
$users = array();

//credit users
$sql = "SELECT * FROM registeredevents LEFT JOIN events ON registeredevents.eventID = events.eventID LEFT JOIN externalusers ON registeredevents.UID = externalusers.UID WHERE classID='{$classID}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	    $currentUID = $row["UID"];
	    $currentBalance = $row["balance"] +1;
	    $sqlUpdate = "UPDATE externalusers SET balance = balance + 1 WHERE UID='{$currentUID}'";
	    $conn->query($sqlUpdate);
	    array_push($events, $row["eventID"]);
	    array_push($users, $row["UID"]);
    }
} else {

}

//delete registeredevents
for ($i = 0; $i < count($events); $i++) {
  $deleteStatement = "DELETE FROM registeredevents WHERE UID='{$users[$i]}' AND eventID='{$events[$i]}'";
  $conn->query($deleteStatement);
}

//delete events
for ($i = 0; $i < count($events); $i++) {
  $deleteStatement = "DELETE FROM events WHERE classID='{$classID}'";
  $conn->query($deleteStatement);
}

//delete class 
$deleteStatement = "DELETE FROM classes WHERE classID='{$classID}'";
$conn->query($deleteStatement);

$conn->close();

$data['message'] = "Successfully deleted class and credited all users";
$data['valid'] = 1;

echo json_encode($data);

?>


