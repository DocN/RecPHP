<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
//error_reporting(0);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(!isset($request->classID)) {
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
$classID = mysqli_real_escape_string($conn, $request->classID);

$sql = "SELECT * FROM emailreserved WHERE classID='{$classID}'";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$data[$count]['email'] =$row["email"];
        $data[$count]['slotNumber']= $row['slotNumber'];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>