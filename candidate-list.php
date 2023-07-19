<?php

function displayVotingBallot($conn, $position){

    ?>
    <!-- Voting Ballot -->
    <form method="POST" id="ballotForm" action="submit_ballot.php">
    <?php
    include 'includes/slugify.php';
    $candidate = '';

    if($position != '0' && isset($_SESSION['voted']))
    {
        $pos_id = "WHERE id = '$position'";
    }
    else
    {
        $pos_id = '';
    }

    
    $sql = "SELECT * FROM positions ".$pos_id." ORDER BY priority ASC";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc())
    {


    $sql = "SELECT * FROM candidates WHERE position_id='".$row['id']."'";
    $cquery = $conn->query($sql);
    while($crow = $cquery->fetch_assoc()){
    $slug = slugify($row['description']);
    $checked = '';
    if(isset($_SESSION['post'][$slug])){
    $value = $_SESSION['post'][$slug];

    if(is_array($value)){
    foreach($value as $val){
    if($val == $crow['id']){
    $checked = 'checked';
    }
    }
    }
    else{
    if($value == $crow['id']){
    $checked = 'checked';
    }
    }
    }
    $input = ($row['max_vote'] > 1) ? '<input type="checkbox" id = "'.$slug.'"  class="flat-red '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id'].'" '.$checked.'>' : '<input type="radio" required class="flat-red '.$slug.'" name="'.slugify($row['description']).'" value="'.$crow['id'].'" '.$checked.'>';
    $image = (!empty($crow['photo'])) ? 'images/'.$crow['photo'] : 'images/profile.jpg';
    $candidate .= '
    <li>
    '.$input.'<button type="button"  class="btn btn-primary btn-sm btn-flat clist platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'"><i class="fa fa-search"></i> Platform</button><img src="'.$image.'" height="100px" width="100px" class="clist"><span class="cname clist">'.$crow['firstname'].' '.$crow['lastname'].'</span>
    </li>
    ';
    }

    $instruct = ($row['max_vote'] > 1) ? 'You may select up to '.$row['max_vote'].' candidates' : 'Select only one candidate';

    echo '
    <div class="row"  style=" border-radius: 15px;">
    <div class="col-xs-12  " style=" border-radius: 15px;">
    <div class="box box-solid " id="'.$row['id'].'" style=" border-radius: 15px;">
    <div class="box-header with-border bg-blue-active" style="border-radius: 15px 15px 0px 0px ;">
    <h3 class="box-title" id="pos_'.$row['max_vote'].'" style="margin-left:10px;"><b>'.$row['description'].'</b></h3>
    </div>
    <div class="box-body" style=" border-radius: 0px 0px 15px 15px;">
    <div id="candidate_list">
    <ul class="vote_ob" id ='.$row['max_vote'].' identity='.$row['description'].' >
    <li class="header bg-red-active" style="padding:3px; padding-left:15px; padding-top: 5px; padding-bottom:5px; min-width:300px; font-size:15px; border-radius:3px; display:none;" identity="0" ></li>
    <p style="margin-bottom:50px;">'.$instruct.'
    <span class="pull-right">
    <button style ="margin-right:20px; border-radius:5px; padding:10px;" type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['description']).'"><i class="fa fa-refresh"></i> Reset</button>
    </span>
    </p> 
    '.$candidate.'
    </ul>
    </div>
    </div>
    </div>
    </div>
    </div>
    ';

    $candidate = '';

    }
    
    ?>
    <div class="text-center">
    <button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Preview</button> 
    <button type="submit" id="vote" class="btn btn-primary btn-flat" name="vote"><i class="fa fa-check-square-o"></i> Submit</button>
    </div>
    </form>

    <script>
document.getElementById("vote").addEventListener("click", function(event) {
$('.vote_ob').each(function() {
var divId = $(this).attr('id');
console.log('Div ID: ' + divId);

// Get the checkboxes within the current div
var checkboxes = $(this).find('input[type="checkbox"]');
var candidates = $('#pos_'+divId+'').text()
var alertmsg = $(this).find('li[identity="0"]');

if(parseInt(divId)>1)
{
var checkedCount = checkboxes.filter(':checked').length;
console.log('Checked Count: ' + checkedCount);

if (parseInt(checkedCount) > parseInt(divId))
{

    alertmsg.text(" You can only select up to "+parseInt(divId)+' '+candidates+' candidates' )
    alertmsg.css('display', 'block');
    setTimeout(function()
    {
        alertmsg.css('display', 'none');
    },5000)
    event.preventDefault(); // Prevent form submission
}
else if(parseInt(checkedCount) < 1)
{
    

    alertmsg.text(" Please select atleast 1 "+candidates+' candidate')
    alertmsg.css('display', 'block');
    setTimeout(function()
    {
        alertmsg.css('display', 'none');
    },5000)

    event.preventDefault(); // Prevent form submission
}
}
else
{
    var radbtn = $(this).find('input[type="radio"]').is(':checked');
    if (!radbtn) {

    alertmsg.text(" Please select atleast 1 "+candidates+' candidate')
    alertmsg.css('display', 'block');
    setTimeout(function()
    {
        alertmsg.css('display', 'none');
    },5000)

    event.preventDefault(); // Prevent form submission
    }
}

});
});

</script>
    <?php
}

?>
