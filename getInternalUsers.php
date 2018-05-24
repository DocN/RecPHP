<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");

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

$sql = "SELECT * FROM adminusers";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[$count]['UID'] = $row["UID"];
    	$data[$count]['username'] =$row["username"];
        $data[$count]['firstname']= $row['firstname'];
        $data[$count]['lastname'] = $row['lastname'];
        $data[$count]['authLevel'] = $row['authLevel'];
        $data[$count]['active'] = $row['active'];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>