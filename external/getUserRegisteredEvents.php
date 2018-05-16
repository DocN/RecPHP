<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
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

$UID = $request->UID;

$sql = "SELECT * FROM registeredevents LEFT OUTER JOIN events ON registeredevents.eventID = events.eventID LEFT OUTER JOIN classes ON events.classID = classes.classID WHERE UID='{$UID}'";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[$count]['eventID'] = $row["eventID"];
    	$data[$count]['classID'] =$row["classID"];
        $data[$count]['className']= $row['className'];
        $data[$count]['eventDay'] = $row['eventDay'];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>