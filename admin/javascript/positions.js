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
  });
  
  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'positions_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('.id').val(response.id);
        $('#edit_description').val(response.description);
        $('#edit_max_vote').val(response.max_vote);
        $('.description').html(response.description);
      }
    });
  }
  
  function printPositionsList() {
    window.print();
  }