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
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$classID = mysqli_real_escape_string($conn, $request->classID);
$className = mysqli_real_escape_string($conn, $request->className);
$classLocation = mysqli_real_escape_string($conn, $request->classLocation);
$instructorID = mysqli_real_escape_string($conn, $request->instructorID);
$categoryID = mysqli_real_escape_string($conn, $request->categoryID);
$classImage = mysqli_real_escape_string($conn, $request->classImage);
$classBio = mysqli_real_escape_string($conn, $request->classDescription);

$sqlSelect = "UPDATE classes SET className='{$className}', classLocation='{$classLocation}', instructorID='{$instructorID}', categoryID='{$categoryID}', classImageURL='{$classImage}', classDescription='{$classBio}' WHERE classID='{$classID}'";
if ($conn->query($sqlSelect)) {
    $data['message'] = "Successfully Updated Class Details";
    $data['valid'] = 1;
    $conn->close();
    echo json_encode($data);
    die();
} else {

}
$data['valid'] = 0;
$conn->close();
echo json_encode($data);

?>