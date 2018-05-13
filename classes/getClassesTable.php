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

$sql = "SELECT * FROM classes LEFT JOIN instructors ON classes.instructorID = instructors.instructorID LEFT JOIN classcategories ON classcategories.categoryID = classes.categoryID;";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[$count]['classID'] = $row["classID"];
    	$data[$count]['className'] =$row["className"];
        $data[$count]['classLocation']= $row['classLocation'];
        $data[$count]['instructorID'] = $row["instructorID"];
    	$data[$count]['categoryID'] =$row["categoryID"];
        $data[$count]['reservedSlots']= $row['reservedSlots'];
        $data[$count]['availableSlots'] = $row["availableSlots"];
    	$data[$count]['beginDate'] =$row["beginDate"];
        $data[$count]['endDate']= $row['endDate'];
        $data[$count]['beginHour'] = $row["beginHour"];
    	$data[$count]['beginMin'] =$row["beginMin"];
        $data[$count]['endHour']= $row['endHour'];
        $data[$count]['endMin'] = $row["endMin"];
    	$data[$count]['dayOfWeek'] =$row["dayOfWeek"];
        $data[$count]['classDescription']= $row['classDescription'];
        $data[$count]['classImageURL'] = $row["classImageURL"];
        //class categories 
        $data[$count]['categoryName'] = $row["categoryName"];
    	$data[$count]['hexColor'] =$row["hexColor"];
    	//instructors
        $data[$count]['firstname'] = $row["firstname"];
    	$data[$count]['lastname'] =$row["lastname"];
        $data[$count]['photoURL']= $row['photoURL'];
        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>