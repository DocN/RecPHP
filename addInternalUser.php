<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->username)) {
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
$username = $request->username;
$epassword = $request->epassword;
$authLevel = $request->authlevel;
$firstname = $request->firstname;
$lastname = $request->lastname;
$active = $request->active;
$logintime = time();

$sqlSelect = "SELECT * FROM adminusers WHERE username='{$username}'";
$result = $conn->query($sqlSelect);
if ($result->num_rows > 0) {
    $data['message'] = "Account already exists";
    $conn->close();
    echo json_encode($data);
    die();
} else {

}


$sql = "INSERT INTO adminusers (UID, username, epassword, authLevel, firstname, lastname, active, logintime)
VALUES ('asdsadsad','{$username}', '{$epassword}', '{$authLevel}', '{$firstname}', '{$lastname}', '{$active}', '{$logintime}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "Account created successfully";
} else {
    $data['message'] = "Account creation failed";
}

$conn->close();
echo json_encode($data);

?>