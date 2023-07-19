  $(document).ready(function() {
      // Handle candidate deletion
      $('.delete-candidate').click(function() {
        var candidateId = $(this).data('candidate-id');
        var positionId = $(this).data('position-id');

        if (confirm("Are you sure you want to delete this candidate?")) {
          // Send AJAX request to delete candidate
          $.ajax({
            url: 'candidate_delete.php',
            type: 'POST',
            data: {
              candidate_id: candidateId,
              position_id: positionId
            },
            success: function(response) {
              // Refresh the page after successful deletion
              location.reload();
            },
            error: function(xhr, status, error) {
              console.error(error);
            }
          });
        }
      });
    });