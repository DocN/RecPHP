<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
error_reporting(0);
$postdata = file_get_contents("php://input");

//echo $request->encryptionKey;
//$res_ar = array("foo"=> $_REQUEST['body']);
$tester = 0;
include "../dbCredentials.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$strtotime = date("o-\WW");
$start = strtotime($strtotime);
$end = strtotime("+6 days 23:59:59", $start);

$sql = "SELECT * FROM events LEFT JOIN classes ON events.classID = classes.classID WHERE eventDay BETWEEN {$start} AND {$end} ORDER BY eventDay";
$result = $conn->query($sql);
$count = 0;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[$count]['eventID'] = $row["eventID"];
        $data[$count]['classID'] =$row["classID"];
        $data[$count]['eventDay']= $row['eventDay'];
        $data[$count]['className'] = $row["className"];
        $data[$count]['classLocation'] =$row["classLocation"];
        $data[$count]['dayOfWeek'] =$row["dayOfWeek"];
        $data[$count]['beginHour'] =$row["beginHour"];
        $data[$count]['beginMin'] =$row["beginMin"];
        $data[$count]['endHour'] =$row["endHour"];
        $data[$count]['endMin'] =$row["endMin"];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>