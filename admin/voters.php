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
        Voters List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i><span id="time" style="margin-right:15px; font-weight:bold; font-size:20px">
        </span> Home</a></li>
        <li class="active">Voters</li>
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
              <button class="btn btn-info btn-sm btn-flat" onclick="printVotersList()"><i class="fa fa-print"></i> Print</button>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Lastname</th>
                  <th>Firstname</th>
                  <th>Photo</th>
                  <th>Voters ID</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM voters";
                    $query = $conn->query($sql);
                    $totalVoters = $query->num_rows;
                    while($row = $query->fetch_assoc()){
                      $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                      echo "
                        <tr>
                          <td>".$row['lastname']."</td>
                          <td>".$row['firstname']."</td>
                          <td>
                            <img src='".$image."' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'><span class='fa fa-edit'></span></a>
                          </td>
                          <td>".$row['voters_id']."</td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                          </td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
              <div class="total-voters">Total Voters: <?php echo $totalVoters; ?></div>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>

  <?php include 'includes/voters_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="javascript/voters.js"?> </script>  

<script>
  function printVotersList() {
    var printWindow = window.open('', '', 'height=500,width=800');
    printWindow.document.write('<html><head><title>Voters List</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>Voters List</h1>');
    printWindow.document.write('<table border="1">');
    printWindow.document.write('<thead><th>Lastname</th><th>Firstname</th><th>Voters ID</th></thead>');
    printWindow.document.write('<tbody>');
    var votersTableRows = document.querySelectorAll('#example1 tbody tr');
    for (var i = 0; i < votersTableRows.length; i++) {
      var cells = votersTableRows[i].getElementsByTagName('td');
      var lastname = cells[0].innerText;
      var firstname = cells[1].innerText;
      var votersId = cells[3].innerText;
      printWindow.document.write('<tr><td>' + lastname + '</td><td>' + firstname + '</td><td>' + votersId + '</td></tr>');
    }
    printWindow.document.write('</tbody>');
    printWindow.document.write('</table>');
    printWindow.document.write('<p>Total Voters: <?php echo $totalVoters; ?></p>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }
</script>

</body>
</html>