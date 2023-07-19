<?php  include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Votes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>
        <span id="time" style="margin-right:15px; font-weight:bold; font-size:20px">
        </span> Home</a></li>
        <li class="active">Votes</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
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
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#reset" data-toggle="modal" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-refresh"></i> Reset</a>
              <button onclick="printWinners()" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-print"></i> Print Winners</button>
            </div>
            <div class="box-body" id="vote_res">
              <h3>Vote Results</h3>
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Position</th>
                  <th>Candidate</th>
                  <th>Voter</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT *, candidates.firstname AS canfirst, candidates.lastname AS canlast, voters.firstname AS votfirst, voters.lastname AS votlast FROM votes LEFT JOIN positions ON positions.id=votes.position_id LEFT JOIN candidates ON candidates.id=votes.candidate_id LEFT JOIN voters ON voters.id=votes.voters_id ORDER BY positions.priority ASC";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>".$row['description']."</td>
                          <td>".$row['canfirst'].' '.$row['canlast']."</td>
                          <td>".$row['votfirst'].' '.$row['votlast']."</td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
              
              <h3>Winners</h3>
              <table id="winnersTable" class="table table-bordered">
                <thead>
                  <th>Position</th>
                  <th>Winner</th>
                  <th>Votes</th>
                  <th>Status</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT position_id, candidate_id, COUNT(*) AS vote_count FROM votes GROUP BY position_id, candidate_id";
                    $result = $conn->query($sql);
                    $winners = array();
                    while ($row = $result->fetch_assoc()) {
                      $positionId = $row['position_id'];
                      $candidateId = $row['candidate_id'];
                      $voteCount = $row['vote_count'];

                      // Retrieve the winner's details
                      $sql = "SELECT firstname, lastname FROM candidates WHERE id = $candidateId";
                      $result2 = $conn->query($sql);
                      if ($result2->num_rows > 0) {
                        $row2 = $result2->fetch_assoc();
                        $winnerFirstname = $row2['firstname'];
                        $winnerLastname = $row2['lastname'];

                        $winners[$positionId][] = array(
                          'firstname' => $winnerFirstname,
                          'lastname' => $winnerLastname,
                          'vote_count' => $voteCount
                        );
                      }
                    }
                    

                    // Display the winners for each position
                    foreach ($winners as $positionId => $positionWinners) {
                      $sql = "SELECT description FROM positions WHERE id = $positionId";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $positionDescription = $row['description'];

                        foreach ($positionWinners as $key => $winner) {
                          $status = (count($positionWinners) === 1) ? "Winner" : "Tie";
                          echo "
                            <tr>
                              <td>".$positionDescription."</td>
                              <td>".$winner['firstname'].' '.$winner['lastname']."</td>
                              <td>".$winner['vote_count']."</td>
                              <td>".$status."</td>
                            </tr>
                          ";
                        }
                      }
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    

  <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<?php include 'includes/scripts.php'; ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="javascript/votes.php"?> </script>

  </script>

<script>
  function printWinners() {
    var printWindow = window.open('', '', 'height=500,width=800');
    printWindow.document.write('<html><head><title>Winners</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>Winners</h1>');
    printWindow.document.write(document.getElementById("winnersTable").outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }

  $(document).ready(function() {
      // Function to update the content
      function updateContent() {
        $('#vote_res').load('votes.php #vote_res');
      }

      // Refresh the content every 5 seconds
      setInterval(updateContent, 5000);
    });
</script>

</body>
</html>