<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->eventID)) {
    die();
}


//echo $request->encryptionKey;
//$res_ar = array("foo"=> $_REQUEST['body']);
$tester = 0;
$response = '';
$servername = "drnserver.duckdns.org";
$username = "DrN";
$password = "password123!";
$dbname = "recdatabase";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$eventID = mysqli_real_escape_string($conn, $request->eventID);
$msgSubject = mysqli_real_escape_string($conn, $request->msgSubject);
$msgBody = mysqli_real_escape_string($conn, $request->msgBody);
$cancelTime = time();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = "INSERT INTO mailqueue (eventID, timestamp, subject, message, active)
VALUES ('{$eventID}', '{$cancelTime}', '{$msgSubject}', '{$msgBody}', 1)";

if ($conn->query($sql) === TRUE) {
    $data['message'] = "All members of the class will be emailed";
    $data['valid'] = 1;
} else {
    $data['message'] = "Unknown error has occured.";
    $data['valid'] = 0;
}

$conn->close();

echo json_encode($data);

?>