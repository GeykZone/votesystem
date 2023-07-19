<?php
include 'includes/conn.php';


$sql = 'SELECT COUNT(*) AS num_rows FROM candidates';
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $numRows = $row['num_rows'];

  $candidates =  $numRows;

} else {
    $candidates =  0;
}


$sql = 'SELECT COUNT(*) AS num_rows FROM votes';
$result = $conn->query($sql);
$reset = 0;

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $numRows = $row['num_rows'];

  $reset = $numRows;
}
else
{
    $reset = 0;

}


$data = [
    'candidates' => $candidates,
    'reset' => $reset
];

echo json_encode($data);

?>