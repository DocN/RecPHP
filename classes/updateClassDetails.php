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

$classID = $request->classID;
$className = $request->className;
$classLocation = $request->classLocation;
$instructorID = $request->instructorID;
$categoryID = $request->categoryID;
$classImage = $request->classImage;

$sqlSelect = "UPDATE classes SET className='{$className}', classLocation='{$classLocation}', instructorID='{$instructorID}', categoryID='{$categoryID}', classImageURL='{$classImage}' WHERE classID='{$classID}'";
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