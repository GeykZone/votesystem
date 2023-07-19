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
        $('.message').html('You must vote at least one candidate');
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