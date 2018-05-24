<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->eventID)) {
    echo "wtf";
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
$eventID = mysqli_real_escape_string($conn, $request->eventID);

$sql = "SELECT * FROM registeredevents LEFT OUTER JOIN externalusers ON registeredevents.UID = externalusers.UID WHERE registeredevents.eventID = '{$eventID}'";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$data[$count]['UID'] =$row["UID"];
        $data[$count]['firstName']= $row['firstName'];
        $data[$count]['lastName']= $row['lastName'];
        $data[$count]['email']= $row['email'];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>