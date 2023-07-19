<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if (isset($_POST['vote'])) {
		if (count($_POST) == 1) {
			$_SESSION['error'][] = 'Please vote at least one candidate';
		} else {
			$_SESSION['post'] = $_POST;
			$sql = "SELECT * FROM positions";
			$query = $conn->query($sql);
			$error = false;
			$sql_array = array();
			while ($row = $query->fetch_assoc()) {
				$position = slugify($row['description']);
				$pos_id = $row['id'];
				if (isset($_POST[$position])) {
					if ($row['max_vote'] > 1) {
						if (count($_POST[$position]) > $row['max_vote']) {
							$error = true;
							$_SESSION['error'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
						} else {
							foreach ($_POST[$position] as $key => $values) {
								// Check if the record already exists before inserting
								$existingSql = "SELECT 1 FROM votes WHERE voters_id = '".$voter['id']."' AND candidate_id = '$values' AND position_id = '$pos_id' LIMIT 1";
								$existingQuery = $conn->query($existingSql);
								if ($existingQuery->num_rows == 0) {
									$sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('" . $voter['id'] . "', '$values', '$pos_id')";
								}
								// Uncomment the following lines if you want to display an error message if the record already exists
								// else {
								// 	$error = true;
								// 	$_SESSION['error'][] = 'Record already exists for ' . $row['description'];
								// }
							}
						}
					} else {
						$candidate = $_POST[$position];
						// Check if the record already exists before inserting
						$existingSql = "SELECT 1 FROM votes WHERE voters_id = '".$voter['id']."' AND candidate_id = '$candidate' AND position_id = '$pos_id' LIMIT 1";
						$existingQuery = $conn->query($existingSql);
						if ($existingQuery->num_rows == 0) {
							$sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('" . $voter['id'] . "', '$candidate', '$pos_id')";
						}
						// Uncomment the following lines if you want to display an error message if the record already exists
						// else {
						// 	$error = true;
						// 	$_SESSION['error'][] = 'Record already exists for ' . $row['description'];
						// }
					}
				}
			}

			if (!$error) {
				foreach ($sql_array as $sql_row) {
					$conn->query($sql_row);
				}

				unset($_SESSION['post']);
				function text_position($text_position) 
				{
					// Get the variable value from your form or button click event
					$position = $text_position; // Change this line to get the actual variable value

					// Open the text file in write mode
					$file = fopen("admin/position.txt", "w");

					// Write the variable value to the text file
					fwrite($file, "position=" . $position);

					// Close the text file
					fclose($file);
				}
				text_position(0);
				$_SESSION['voted'] = "set";
				$_SESSION['success'] = 'Ballot Submitted';
				
			}
		}
	} else {
		$_SESSION['error'][] = 'Select candidates to vote first';
	}

	header('location: home.php');
?>
