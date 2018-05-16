<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->UID)) {
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

$UID = mysqli_real_escape_string($conn, $request->UID);
$eventID = mysqli_real_escape_string($conn, $request->eventID);
$sql = "SELECT UID FROM registeredevents WHERE UID='{$UID}';";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $sqlSelect = "DELETE FROM registeredevents WHERE UID='{$UID}' AND eventID='{$eventID}'";
        //terminate if the user already exists
        if ($conn->query($sqlSelect)) {
            $data['message'] = "Successfully Cancelled Reservation";
            $data['valid'] = 1;

            $sql = "UPDATE externalusers SET balance = balance + 1 WHERE UID='{$UID}'";
            $conn->query($sql);

            $sql = "UPDATE events SET usedSlots = usedSlots - 1 WHERE eventID='{$eventID}'";
            $conn->query($sql);

            $conn->close();
            echo json_encode($data);
            die();
        }
        break;
    }
} else {
    //echo "0 results";
}

$data['message'] = "Failed to Cancel Reservation";
$data['valid'] = 0;
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