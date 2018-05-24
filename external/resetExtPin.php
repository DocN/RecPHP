<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->email)) {
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
$email = mysqli_real_escape_string($conn, $request->email);
$userid = mysqli_real_escape_string($conn, $request->userid);
$newepin = mysqli_real_escape_string($conn, $request->newepin);

$sqlSelect = "UPDATE externalusers SET epin='{$newepin}', resetPin='1' WHERE email='{$email}' AND UID='{$userid}'";
$result = $conn->query($sqlSelect);
if ($result === true) {
    $data['message'] = "Sucessfully Updated Pin";
    $data['valid'] = 1;
    $conn->close();
    echo json_encode($data);
    die();
} else {
	$data['valid'] = 0;
}
$conn->close();
echo json_encode($data);

?>