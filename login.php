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
$username = mysqli_real_escape_string($conn, $request->username);
$epassword = mysqli_real_escape_string($conn, $request->epassword);
$sql = "SELECT * FROM adminusers WHERE epassword='{$epassword}' AND username='{$username}'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data['uid'] = $row["UID"];
    	$data['username'] =$row["username"];
        //$data["epassword"] = $row["epassword"];
        $data['authLevel']= $row['authLevel'];
        $data['firstname'] = $row['firstname'];
        $data['lastname'] = $row['lastname'];
        $data['active'] = $row['active'];
        $response->authed = 1;
        break;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>