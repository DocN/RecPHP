<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//echo $request->encryptionKey;
//$res_ar = array("foo"=> $_REQUEST['body']);
$tester = 0;
$response = '';
include "dbCredentials.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$UID = mysqli_real_escape_string($conn, $request->UID);

$sql = "SELECT UID FROM externalusers";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$data['numberOfExtUsers'] = $count;

$sql = "SELECT eventID FROM events";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$data['numberOfEvents'] = $count;

$sql = "SELECT reviewID FROM reviews";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$data['numberOfReviews'] = $count;

$sql = "SELECT instructorID FROM instructors";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$data['numberOfInstructors'] = $count;



$conn->close();

echo json_encode($data);

?>