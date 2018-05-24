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

$sql = "SELECT * FROM reviews LEFT OUTER JOIN instructors ON reviews.instructorID = instructors.instructorID LEFT OUTER JOIN classes ON reviews.classID = classes.classID limit 10";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[$count]['reviewID'] = $row["reviewID"];
        $data[$count]['instructorID'] =$row["instructorID"];
        $data[$count]['classID']= $row['classID'];
        $data[$count]['reviewText'] = $row['reviewText'];
        $data[$count]['timeStamp'] = $row['timeStamp'];
        $data[$count]['firstname'] = $row["firstname"];
        $data[$count]['lastname'] =$row["lastname"];
        $data[$count]['starRating'] = $row['starRating'];
        $data[$count]['className'] = $row['className'];

        $count = $count +1;
    }
} else {
    //echo "0 results";
}
$conn->close();

echo json_encode($data);

?>