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
$className = mysqli_real_escape_string($conn, $request->className);
$instructorID = mysqli_real_escape_string($conn,$request->instructorID);
$categoryID = mysqli_real_escape_string($conn, $request->categoryID);
$classLocation = mysqli_real_escape_string($conn, $request->classLocation);
$reservedSlots = mysqli_real_escape_string($conn, $request->reservedSlots);
$availableSlots = mysqli_real_escape_string($conn, $request->availableSlots);
$beginDate = mysqli_real_escape_string($conn, $request->beginDate);
$endDate = mysqli_real_escape_string($conn, $request->endDate);
$beginHour = mysqli_real_escape_string($conn, $request->beginHour);
$beginMin = mysqli_real_escape_string($conn, $request->beginMin);
$endHour = mysqli_real_escape_string($conn, $request->endHour);
$endMin = mysqli_real_escape_string($conn, $request->endMin);
$dayOfWeek = mysqli_real_escape_string($conn, $request->dayOfWeek);
$classDescription = mysqli_real_escape_string($conn, $request->classDescription);
$classImageURL = mysqli_real_escape_string($conn, $request->classImageURL);
$openSlots = $availableSlots - $reservedSlots;
$creationTime = time();

$sql = "INSERT INTO classes (classID, className, instructorID, categoryID, classLocation, reservedSlots, availableSlots, beginDate, endDate, beginHour, beginMin, endHour, endMin, dayOfWeek, classDescription, classImageURL)
VALUES ('{$classID}', '{$className}', '{$instructorID}', '{$categoryID}', '{$classLocation}', '{$reservedSlots}', '{$availableSlots}', '{$beginDate}', '{$endDate}', '{$beginHour}', '{$beginMin}', '{$endHour}', '{$endMin}', '{$dayOfWeek}', '{$classDescription}', '{$classImageURL}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "Class Successfully Added";
    parse_weeks($beginDate, $endDate, $dayOfWeek);
    $data['valid'] = 1;
} else {
    $data['message'] = $sql + $conn->error;
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

function parse_weeks($start_date, $end_date, $weekday) {
	$wd = date('N', $start_date); // 1-7
	$start_date += abs($weekday - $wd) * 86400;
	while ($start_date < $end_date) {
		$uid = gen_uuid();
		$sql = "INSERT INTO events (eventID, classID, eventDay, usedSlots, maxSlots, active)
		VALUES ('{$uid}', '{$GLOBALS['classID']}', '{$start_date}', '0', '{$GLOBALS['openSlots']}', '1')";
		if($GLOBALS['conn']->query($sql)) {

		}
		else {
		}
	    //echo gmdate("Y-m-d\TH:i:s\Z", $start_date).date("l", $start_date).  "<br>";
	    $start_date +=  604800;
	}
}

?>