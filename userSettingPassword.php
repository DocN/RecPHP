<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->user)) {
    die();
}

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
$user = mysqli_real_escape_string($conn, $request->user);
$userid = mysqli_real_escape_string($conn, $request->userid);
$newpassword = mysqli_real_escape_string($conn, $request->newpassword);
$oldPassword = mysqli_real_escape_string($conn, $request->oldpassword);

$sqlSelect = "UPDATE adminusers SET epassword='{$newpassword}' WHERE epassword='{$oldPassword}' AND UID='{$userid}'";
$conn->query($sqlSelect);
 
if(mysqli_affected_rows($conn) >0) {
    $data['message'] = "Password Successfully Changed.";
    $data['valid'] = 1;
    $conn->close();
    echo json_encode($data);
    die();
}

$data['message'] = "Failed to change password";
$data['valid'] = 0;
$conn->close();
echo json_encode($data);

?>