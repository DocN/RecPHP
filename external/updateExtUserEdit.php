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
$firstname = mysqli_real_escape_string($conn, $request->firstname);
$lastname = mysqli_real_escape_string($conn, $request->lastname);
$balance = mysqli_real_escape_string($conn, $request->balance);
$sqlSelect = "UPDATE externalusers SET email='{$email}', firstname='{$firstname}', lastname='{$lastname}', balance='{$balance}' WHERE email='{$email}' AND UID='{$userid}'";
$result = $conn->query($sqlSelect);
if ($result->num_rows > 0) {
    $data['message'] = "*Username already exists";
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