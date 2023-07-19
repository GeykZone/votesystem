<?php
	include 'includes/session.php';

	function text_position($text_position) 
	{
		// Get the variable value from your form or button click event
		$position = $text_position; // Change this line to get the actual variable value

		// Open the text file in write mode
		$file = fopen("position.txt", "w");

		// Write the variable value to the text file
		fwrite($file, "position=" . $position);

		// Close the text file
		fclose($file);
	}

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$pos_id = 0;		

		///
		$sql = "SELECT * FROM candidates WHERE id = '$id' ";
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc())
		{
			$pos_id = $row['position_id']; 
		}
		///

		$sql = "DELETE FROM candidates WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Candidate deleted successfully';

			text_position($pos_id);
		}
		else{

			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Select item to delete first';
	}

	header('location: candidates.php');
	
?>