<?php include 'includes/session.php'; ?>
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
        Candidates List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i><span id="time" style="margin-right:15px; font-weight:bold; font-size:20px"></span> Home</a></li>
        <li class="active">Candidates</li>
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
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
              <button class="btn btn-info btn-sm btn-flat" onclick="printCandidatesList()"><i class="fa fa-print"></i> Print</button>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Position</th>
                  <th>Photo</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Platform</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN positions ON positions.id=candidates.position_id ORDER BY positions.priority ASC";
                    $query = $conn->query($sql);
                    $candidateCount = 0; // Initialize the counter
                    while($row = $query->fetch_assoc()){
                      $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>".$row['description']."</td>
                          <td>
                            <img src='".$image."' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['canid']."'><span class='fa fa-edit'></span></a>
                          </td>
                          <td>".$row['firstname']."</td>
                          <td>".$row['lastname']."</td>
                          <td><a href='#platform' data-toggle='modal' class='btn btn-info btn-sm btn-flat platform' data-id='".$row['canid']."'><i class='fa fa-search'></i> View</a></td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['canid']."'><i class='fa fa-edit'></i> Edit</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['canid']."'><i class='fa fa-trash'></i> Delete</button>
                          </td>
                        </tr>
                      ";
                      $candidateCount++; // Increment the counter for each candidate
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php
        // Initialize an array to store the count of candidates for each position
        $positionCounts = array();
      
        // Fetch the candidates and positions from the database
        $sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN positions ON positions.id=candidates.position_id ORDER BY positions.priority ASC";
        $query = $conn->query($sql);
      
        // Loop through the candidates
        while($row = $query->fetch_assoc()){
          // Increment the count for the corresponding position
          $positionCounts[$row['description']] = isset($positionCounts[$row['description']]) ? $positionCounts[$row['description']] + 1 : 1;
      
          // Rest of the code for displaying candidate information
          // ...
        }
      ?>
      
      <!-- Display the position counts -->
      <table>
        <thead>
          <th>Position</th>
          <th>Number of Candidates</th>
        </thead>
        <tbody>
          <?php
            foreach ($positionCounts as $position => $count) {
              echo "<tr><td>$position</td><td>$count</td></tr>";
            }
          ?>
        </tbody>
      </table>

    </section>   
  </div>

  <?php include 'includes/candidates_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
  $(function(){
    $(document).on('click', '.edit', function(e){
      e.preventDefault();
      $('#edit').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });
  
    $(document).on('click', '.delete', function(e){
      e.preventDefault();
      $('#delete').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });
  
    $(document).on('click', '.photo', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      getRow(id);
    });
  
    $(document).on('click', '.platform', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      getRow(id);
    });
  
  });
  
  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'candidates_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('.id').val(response.canid);
        $('#edit_firstname').val(response.firstname);
        $('#edit_lastname').val(response.lastname);
        $('#posselect').val(response.position_id).html(response.description);      
        $('#edit_platform').val(response.platform);
        $('.fullname').html(response.firstname+' '+response.lastname);
        $('#desc').html(response.platform);
      }
    });
  }


function printCandidatesList() {
  var printWindow = window.open('', '', 'height=500,width=800');
  printWindow.document.write('<html><head><title>Candidates List</title>');
  printWindow.document.write('</head><body>');
  printWindow.document.write('<h1>Candidates List</h1>');

  // Get the position and candidate counts
  var positionCounts = {};
  var candidatesTableRows = document.querySelectorAll('#example1 tbody tr');
  for (var i = 0; i < candidatesTableRows.length; i++) {
    var cells = candidatesTableRows[i].getElementsByTagName('td');
    var position = cells[1].innerText;
    if (positionCounts.hasOwnProperty(position)) {
      positionCounts[position] += 1;
    } else {
      positionCounts[position] = 1;
    }
  }

  // Print the position and candidate counts
  printWindow.document.write('<table border="1">');
  printWindow.document.write('<thead><th>Position</th><th>Number of Candidates</th></thead>');
  printWindow.document.write('<tbody>');
  for (var position in positionCounts) {
    var count = positionCounts[position];
    printWindow.document.write('<tr><td>' + position + '</td><td>' + count + '</td></tr>');
  }
  printWindow.document.write('</tbody>');
  printWindow.document.write('</table>');

  // Print the candidates table
  printWindow.document.write('<table border="1">');
  printWindow.document.write('<thead><th>Position</th><th>Firstname</th><th>Lastname</th></thead>');
  printWindow.document.write('<tbody>');
  for (var i = 0; i < candidatesTableRows.length; i++) {
    var cells = candidatesTableRows[i].getElementsByTagName('td');
    var position = cells[1].innerText;
    var firstname = cells[3].innerText;
    var lastname = cells[4].innerText;
    printWindow.document.write('<tr><td>' + position + '</td><td>' + firstname + '</td><td>' + lastname + '</td></tr>');
  }
  printWindow.document.write('</tbody>');
  printWindow.document.write('</table>');

  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

</script>
</body>
</html>
