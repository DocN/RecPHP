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
include "../dbCredentials.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$UID = mysqli_real_escape_string($conn, $request->UID);
$eventID = mysqli_real_escape_string($conn, $request->eventID);
$deductCredits = mysqli_real_escape_string($conn, $request->deductCredits);


$sqlSelect = "SELECT * FROM registeredevents WHERE UID='{$UID}' AND eventID='{$eventID}'";
$result = $conn->query($sqlSelect);
//terminate if the user already exists
if ($result->num_rows > 0) {
    $data['message'] = "*User has already been registered for this class, please enter a different email";
    $data['valid'] = 0;
    $conn->close();
    echo json_encode($data);
    die();
} 
//for credit deduction
if($deductCredits == 1) {
    $sqlSelect = "SELECT balance FROM externalusers WHERE UID='{$UID}'";
    $result = $conn->query($sqlSelect);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $currentBalance =$row["balance"];
            break;
        }
    } 
    if($currentBalance <= 0) {
        $data['message'] = "*Not enough credits to register for class";
        $data['valid'] = 0;
        $conn->close();
        echo json_encode($data);
        die();
    }
    else {
        $currentBalance = $currentBalance -1;
        $updateSQL = "UPDATE externalusers SET balance='{$currentBalance}' WHERE UID='{$UID}'";
        $result = $conn->query($updateSQL);
    }
}

$sql = "INSERT INTO registeredevents (UID, eventID)
VALUES ('{$UID}', '{$eventID}')";
if ($conn->query($sql) === TRUE) {
    $data['message'] = "User Successfully Registered";
    $data['valid'] = 1;
    $sql = "UPDATE events SET usedSlots = usedSlots + 1 WHERE eventID='{$eventID}'";
    $conn->query($sql);
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
?>