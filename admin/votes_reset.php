<?php
	include 'includes/session.php';

	function vote_reset($vote_reset) 
	{
	// Get the variable value from your form or button click event
	$reset = $vote_reset; // Change this line to get the actual variable value

	// Open the text file in write mode
	$file = fopen("vote.txt", "w");

	// Write the variable value to the text file
	fwrite($file, "reset=" . $reset);

	// Close the text file
	fclose($file);
	}

	$sql = "DELETE FROM votes";
	if($conn->query($sql)){

		vote_reset(1);

		$_SESSION['success'] = "Votes reset successfully";
	}
	else{
		$_SESSION['error'] = "Something went wrong in reseting";
	}

	header('location: votes.php');

?>