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
include "../dbCredentials.php";
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

$sql = "SELECT * FROM events LEFT JOIN classes ON events.classID = classes.classID WHERE eventID='{$eventID}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$className = $row["className"];
    	$eventDay = $row["eventDay"];
    	$beginTime = $row["beginHour"];
    	$beginTime = $beginTime . ":" . $row["beginMin"];
    	break;
    }
} else {
    echo "0 results";
}


//add cancellation to queue for email
$cancelTime = time();
$unixToDate = date('Y-m-d',($eventDay-25200));
$reminderSubject = "{$className} on {$unixToDate} has been cancelled! - BCIT Rec Center";
$reminderMessage = "<p>This is an automated message from the BCIT Recreation Center to inform you that unfortunately {$className} on {$unixToDate} at {$beginTime} has been cancelled. If you used a punch pass your account will be credited for the class. If you have any questions feel free to contact us at the BCIT Recreation Center.</p>";


$sqlCancelReminder = "INSERT INTO mailqueue (eventID, timestamp, subject, message, active)
VALUES ('{$eventID}', '{$cancelTime}', '{$reminderSubject}', '{$reminderMessage}', 1)";
$conn->query($sqlCancelReminder);

//delete registered events
//$sql = "DELETE FROM registeredevents WHERE eventID='{$eventID}'";
//$result = $conn->query($sql);

//cancel event
$sql = "UPDATE events SET active='0' WHERE eventID='{$eventID}'";

if($conn->query($sql)) {
    $data['message'] = "Class successfully cancelled, all punchpass users have been credited and emails will be sent out to all class members";
    $data['valid'] = 1;
}
else {
    $data['message'] = "Failed to cancel class";
    $data['valid'] = 0;
}
$conn->close();

echo json_encode($data);

?>