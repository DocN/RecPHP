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
include "dbCredentials.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$username = mysqli_real_escape_string($conn, $request->username);
$epassword = mysqli_real_escape_string($conn, $request->epassword);
$authLevel = mysqli_real_escape_string($conn, $request->authlevel);
$firstname = mysqli_real_escape_string($conn, $request->firstname);
$lastname = mysqli_real_escape_string($conn, $request->lastname);
$active = mysqli_real_escape_string($conn, $request->active);
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

$uid = gen_uuid();
$sql = "INSERT INTO adminusers (UID, username, epassword, authLevel, firstname, lastname, active, logintime)
VALUES ('{$uid}','{$username}', '{$epassword}', '{$authLevel}', '{$firstname}', '{$lastname}', '{$active}', '{$logintime}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "Account created successfully";
} else {
    $data['message'] = "Account creation failed";
}

$conn->close();
echo json_encode($data);


function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

?>