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
  
  });
  
  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'voters_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('.id').val(response.id);
        $('#edit_firstname').val(response.firstname);
        $('#edit_lastname').val(response.lastname);
        $('#edit_password').val(response.password);
        $('.fullname').html(response.firstname+' '+response.lastname);
      }
    });
  }
