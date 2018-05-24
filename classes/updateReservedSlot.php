<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
//error_reporting(0);
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
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$classID = mysqli_real_escape_string($conn, $request->classID);
$email = mysqli_real_escape_string($conn, $request->email);
$slotNumber = mysqli_real_escape_string($conn, $request->slotNumber);

$sqlSelect = "UPDATE emailreserved SET email='{$email}' WHERE classID='{$classID}' AND slotNumber='{$slotNumber}'";
if ($conn->query($sqlSelect)) {
	$incrementSlot = $slotNumber +1;
    $data['message'] = "Successfully updated reserved slot {$incrementSlot}";
    $data['valid'] = 0;
    $conn->close();
    echo json_encode($data);
    die();
} else {

}
$data['valid'] = 1;
$conn->close();
echo json_encode($data);

?>