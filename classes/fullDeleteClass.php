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
$servername = "drnserver.duckdns.org";
$username = "DrN";
$password = "password123!";
$dbname = "recdatabase";


$classID = $request->classID;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//credit users
$sql = "SELECT * FROM registeredevents LEFT JOIN events ON registeredevents.eventID = events.eventID LEFT JOIN externalusers ON registeredevents.UID = externalusers.UID WHERE classID='{$classID}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	    $currentUID = $row["UID"];
	    $currentBalance = $row["balance"] +1;
	    $sqlUpdate = "UPDATE externalusers SET balance='{$currentBalance}' WHERE UID='{$currentUID}";
	    $conn->query($sqlUpdate);
    }
} else {
    echo "0 results";
}