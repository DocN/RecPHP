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

//credit balance
$sql = "UPDATE externalusers eu
LEFT OUTER JOIN registeredevents re 
    ON eu.UID = re.UID 
SET eu.balance = eu.balance +1
WHERE re.eventID='{$eventID}'";

$result = $conn->query($sql);

//delete registered events
//$sql = "DELETE FROM registeredevents WHERE eventID='{$eventID}'";
//$result = $conn->query($sql);

//cancel event
$sql = "UPDATE events SET active='0' WHERE eventID='{$eventID}'";

if($conn->query($sql)) {
    $data['message'] = "Class successfully cancelled, all users have been credited";
    $data['valid'] = 1;
}
else {
    $data['message'] = "Failed to cancel class";
    $data['valid'] = 0;
}
$conn->close();

echo json_encode($data);

?>