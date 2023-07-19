<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

<?php
// Include the database connection and other required files

// Retrieve the winners from the database
$sql = "SELECT positions.description AS position, candidates.firstname, candidates.lastname, COUNT(votes.id) AS vote_count
        FROM positions
        INNER JOIN candidates ON positions.id = candidates.position_id
        INNER JOIN votes ON candidates.id = votes.candidate_id
        GROUP BY positions.id, candidates.id
        HAVING COUNT(votes.id) = (
          SELECT MAX(vote_count)
          FROM (
            SELECT candidates.id, COUNT(votes.id) AS vote_count
            FROM candidates
            INNER JOIN votes ON candidates.id = votes.candidate_id
            GROUP BY candidates.id
          ) AS counts
          WHERE counts.id = candidates.id
        )
        ORDER BY positions.priority ASC";

$query = mysqli_query($conn, $sql); // Use mysqli_query instead of $conn->query

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Winners Page</title>
  <!-- Include necessary CSS and JS files -->
</head>
<body>
  <h1>Winners</h1>

  <?php
  if (mysqli_num_rows($query) > 0) { // Use mysqli_num_rows instead of $query->num_rows
    // Display the winners
    while ($row = mysqli_fetch_assoc($query)) { // Use mysqli_fetch_assoc instead of $query->fetch_assoc()
      echo "<h3>" . $row['position'] . "</h3>";
      echo "<p>Winner: " . $row['firstname'] . " " . $row['lastname'] . "</p>";
      echo "<p>Votes: " . $row['vote_count'] . "</p>";
    }
  } else {
    echo "<p>No winners found.</p>";
  }
  ?>

</body>
</html>