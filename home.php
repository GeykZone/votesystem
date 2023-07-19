<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; 

// Query the SQL table to check for changes
$sql = 'SELECT COUNT(*) AS num_rows FROM candidates';
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $numRows = $row['num_rows'];
  

  ?> <script>
	var old_candidates_count = <?php echo json_encode($numRows); ?>
  </script> <?php

}

?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
		  <section class="content">
		  <ol class="breadcrumb">
        <li class="active"><span ><i class="fa fa-dashboard" style="margin-top: 8px; margin-right:10px; position:absolute;"></i><span id="time" style="margin-right:15px; margin-left:27px; padding-top:10px; font-weight:bold; font-size:20px">
        </span></span></li>
        <li class="active">Vote</li>
      </ol>
	      	<?php
	      		$parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
    			$title = $parse['election_title'];
	      	?>
	      	<h1 class="page-header text-center title"><b><?php echo strtoupper($title); ?></b></h1>
	        <div class="row">
	        	<div class="col-sm-10 col-sm-offset-1">
	        		<?php
				        if(isset($_SESSION['error'])){
				        	?>
				        	<div class="alert alert-danger alert-dismissible">
				        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					        	<ul>
					        		<?php
					        			foreach($_SESSION['error'] as $error){
					        				echo "
					        					<li>".$error."</li>
					        				";
					        			}
					        			unset($_SESSION['error']);
					        		?>
					        	</ul>
					        </div>
				        	<?php
				        }

				        if(isset($_SESSION['success'])){
				          	echo "
				            	<div class='alert alert-success alert-dismissible'>
				              		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				              		<h4><i class='icon fa fa-check'></i> Success!</h4>
				              	".$_SESSION['success']."
				            	</div>
				          	";
				          	unset($_SESSION['success']);
				        }

				        // Retrieve the current state of the election from the database
				        $sql = "SELECT * FROM positions";
				        $result = $conn->query($sql);
				        $election_open = false;
				        while ($row = $result->fetch_assoc()) {
				        	if ($row['is_open'] == 1) {
				        		$election_open = true;
				        		break;
				        	}
				        }

				        if ($election_open) {

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

							function vote_reset($vote_reset) 
							{
							// Get the variable value from your form or button click event
							$reset = $vote_reset; // Change this line to get the actual variable value

							// Open the text file in write mode
							$file = fopen("admin/vote.txt", "w");

							// Write the variable value to the text file
							fwrite($file, "reset=" . $reset);

							// Close the text file
							fclose($file);
							}							

							// Read the content of the text file
							$content = file_get_contents("admin/position.txt");
							// Extract the value from the content
							preg_match('/position=(.*)/', $content, $matches);
							$position = $matches[1];


							// Read the content of the text file
							$is_voted = file_get_contents("admin/vote.txt");
							// Extract the value from the content
							preg_match('/reset=(.*)/', $is_voted, $vote_reseted_var);
							$vote_reseted = $vote_reseted_var[1];

							if($vote_reseted === '1')
							{
								if(isset($_SESSION['voted']))
								{
									unset($_SESSION['voted']);
								}

								vote_reset(0) ;
							}

							if (!isset($_SESSION['voted']))
							{

								if( $position != '0')
								{
								   text_position(0);
								}
							}

							
				        	// Check if the voter has already voted for the current election
				        	$sql = "SELECT * FROM votes WHERE voters_id = '".$voter['id']."'";
				        	$vquery = $conn->query($sql);

				        	if ($vquery->num_rows > 0 && $position === '0' && isset($_SESSION['voted'])) {
								
				        		?>
				        		<div class="text-center">
						    		<h3>You have already voted for this election.</h3>
						    		<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">View Ballot</a>
						    	</div>

								
								<script>
								function checkForChanges() {
								$.ajax({
								url: 'check_for_changes _of_candidates.php',
								type: 'GET',
								dataType: 'json',
								success: function(response) {
								setTimeout(checkForChanges, 2000); // Continue checking every 5 seconds

								if(parseInt(response.candidates) != parseInt(old_candidates_count) || parseInt(response.reset) === 0)
								{
								location.reload();
								}
								},
								error: function() {
								console.log('Error occurred during the AJAX request.');
								setTimeout(checkForChanges, 2000); // Continue checking every 5 seconds
								}
								});

								}

								$(document).ready(function() {
								checkForChanges(); // Start checking for changes when the page loads
								});	
								</script>
				        		<?php

								
				        	}
							else 
							{
								 include 'candidate-list.php';
								 displayVotingBallot($conn, $position);
				        		
				        	}
				        } 
						else 
						{
				        	?>
							<div class="text-center">
								<h3>The election is currently closed.</h3>
							</div>
				        	<?php
				        }
				    ?>
	        	</div>
	        </div>
	    </div>
	  </div>
</div>
</div>
</div>
</section>

</div>
</div>
<?php include 'includes/ballot_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('.content').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$(document).on('click', '.reset', function(e){
	    e.preventDefault();
	    var desc = $(this).data('desc');
	    $('.'+desc).iCheck('uncheck');
	});

	$(document).on('click', '.platform', function(e){
		e.preventDefault();
		$('#platform').modal('show');
		var platform = $(this).data('platform');
		var fullname = $(this).data('fullname');
		$('.candidate').html(fullname);
		$('#plat_view').html(platform);
	});

	$('#preview').click(function(e){
		e.preventDefault();
		var form = $('#ballotForm').serialize();
		if(form == ''){
			$('.message').html('You must vote atleast one candidate');
			$('#alert').show();
		}
		else{
			$.ajax({
				type: 'POST',
				url: 'preview.php',
				data: form,
				dataType: 'json',
				success: function(response){
					if(response.error){
						var errmsg = '';
						var messages = response.message;
						for (i in messages) {
							errmsg += messages[i]; 
						}
						$('.message').html(errmsg);
						$('#alert').show();
					}
					else{
						$('#preview_modal').modal('show');
						$('#preview_body').html(response.list);
					}
				}
			});
		}
			
	});

});


</script>
</body>
</html>