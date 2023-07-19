<?php
// Include necessary files and establish database connection
include 'includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if the required parameters are set
  if (isset($_POST['candidate_id']) && isset($_POST['position_id'])) {
    $candidateId = $_POST['candidate_id'];
    $positionId = $_POST['position_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
      // Delete the candidate
      $deleteCandidateQuery = "DELETE FROM candidates WHERE id = $candidateId";
      $conn->query($deleteCandidateQuery);

      // Delete the associated votes for the candidate
      $deleteVotesQuery = "DELETE FROM votes WHERE candidate_id = $candidateId";
      $conn->query($deleteVotesQuery);

      // Check if the position has any candidates left
      $checkCandidatesQuery = "SELECT COUNT(*) as count FROM candidates WHERE position_id = $positionId";
      $result = $conn->query($checkCandidatesQuery);
      $row = $result->fetch_assoc();
      $candidateCount = $row['count'];

      if ($candidateCount === '0') {
        // Delete the position if there are no candidates left
        $deletePositionQuery = "DELETE FROM positions WHERE id = $positionId";
        $conn->query($deletePositionQuery);

        // Delete the associated votes for the position
        $deleteVotesQuery = "DELETE FROM votes WHERE position_id = $positionId";
        $conn->query($deleteVotesQuery);
      }

      // Delete the associated voters for the candidate
      $deleteVotersQuery = "DELETE FROM voters WHERE candidate_id = $candidateId";
      $conn->query($deleteVotersQuery);

      // Commit the transaction
      $conn->commit();

      // Set success message
      $_SESSION['success'] = "Candidate deleted successfully";
    } catch (Exception $e) {
      // Rollback the transaction if an error occurred
      $conn->rollback();

      // Set error message
      $_SESSION['error'] = "Failed to delete candidate: " . $e->getMessage();
    }

    // Redirect back to the page where the deletion was initiated
    header("Location: votes.php");
    exit();
  }
}

// If the request method is not POST or the required parameters are not set, redirect back to the page where the deletion was initiated
header("Location: votes.php");
exit();
?>