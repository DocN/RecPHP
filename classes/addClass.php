<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->className)) {
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
$classID = gen_uuid();
$className = $request->className;
$instructorID = $request->instructorID;
$classLocation = $request->classLocation;
$reservedSlots = $request->reservedSlots;
$availableSlots = $request->availableSlots;
$beginDate = $request->beginDate;
$endDate = $request->endDate;
$beginHour = $request->beginHour;
$beginMin = $request->beginMin;
$endHour = $request->endHour;
$endMin = $request->endMin;
$dayOfWeek = $request->dayOfWeek;
$classDescription = $request->classDescription;
$classImageURL = $request->classImageURL;

$creationTime = time();

$sql = "INSERT INTO classes (classID, className, instructorID, classLocation, reservedSlots, availableSlots, beginDate, endDate, beginHour, beginMin, endHour, endMin, dayOfWeek, classDescription, classImageURL)
VALUES ('{$classID}', '{$className}', '{$instructorID}', '{$classLocation}', '{$reservedSlots}', '{$availableSlots}', '{$beginDate}', '{$endDate}', '{$beginHour}', '{$beginMin}', '{$endHour}', '{$endMin}', '{$dayOfWeek}', '{$classDescription}', '{$classImageURL}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "Class Successfully Added";
    $data['valid'] = 1;
} else {
    $data['message'] = "Failed to add Class";
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