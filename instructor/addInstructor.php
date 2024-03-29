<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->firstname)) {
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
$firstname = mysqli_real_escape_string($conn, $request->firstname);
$lastname = mysqli_real_escape_string($conn, $request->lastname);
$photoURL = mysqli_real_escape_string($conn, $request->photoURL);
$bio = mysqli_real_escape_string($conn, $request->bio);

$creationTime = time();

$uid = gen_uuid();

$sql = "INSERT INTO instructors (instructorID, firstname, lastname, photoURL, bio, creationTime)
VALUES ('{$uid}', '{$firstname}', '{$lastname}', '{$photoURL}', '{$bio}', '{$creationTime}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "Instructor Successfully Added";
    $data['valid'] = 1;
} else {
    $data['message'] = "Instructor Failed to Add Instructor";
    $data['valid'] = 0;
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