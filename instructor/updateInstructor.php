<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->instructorID)) {
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
$instructorID = mysqli_real_escape_string($conn, $request->instructorID);
$firstname = mysqli_real_escape_string($conn, $request->firstname);
$lastname = mysqli_real_escape_string($conn, $request->lastname);
$photourl = mysqli_real_escape_string($conn, $request->photourl);
$bio = mysqli_real_escape_string($conn, $request->bio);
$sqlSelect = "UPDATE instructors SET firstname='{$firstname}', lastname='{$lastname}', photoURL='{$photourl}', bio='{$bio}' WHERE instructorID='{$instructorID}'";
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