<?php if (session_status() == PHP_SESSION_NONE) {

    session_start();

}

$mid=$name=$email=$iam='';

if(isset($_SESSION['mid'])){

$mid=$_SESSION['mid'];

$name=$_SESSION['name'];

$email=$_SESSION['email'];

$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];
$notification='';

}

else{

$notification='Please login to access the page';

$_SESSION['notification']=$notification;

header('location: login'); 

exit;

}

		$eligible=0;
		
$param="";
if(isset($_GET['candidateid'])){
$candidateid=$_GET['candidateid'];
$param="&candidateid=".$candidateid;
}

		require_once '../config.php';
		
if(isset($_SESSION['mark'])){
$highlight=$_SESSION['mark'];
}else{
$highlight='';
}
		
//		function test_input($data) {
//  $data = trim($data);
//  $data = stripslashes($data);
//  $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
//  $data = str_replace("`","",$data);
//  return $data;
//}
//$description=test_input($_POST['description']);
//$description1=test_input($_POST['description1']);

		$paidstatus= $engagedcount=$permonthcount =$subsexpirydate='';

		$eligiblecheck=mysqli_fetch_assoc(mysqli_query($link,"select a.paidstatus,a.engagedcount,a.permonthcount,a.subsexpirydate,a.subsdate from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid'"));

		$paidstatus= $eligiblecheck['paidstatus'];

		$engagedcount= $eligiblecheck['engagedcount'];

		$permonthcount= $eligiblecheck['permonthcount'];

		$subsexpirydate= $eligiblecheck['subsexpirydate'];

		 $today = date("Y-m-d");

		if(($paidstatus=="success") && ($today < $subsexpirydate)){

		$eligible=1;

		}



$job='';

if(isset($_POST['EID']))

$jobid=$_POST['EID'];

$jobres = mysqli_query($link,"SELECT a.*,b.accountmanager,companyprofile.profile as companydescription,companyprofile.website as website,b.replacementperiod,b.specialnotes FROM jobs a,employers b,companyprofile,members WHERE a.jobid='$jobid' and a.memberid=members.memberid and members.companyid=companyprofile.id and companyprofile.registerid=b.id"); 

$isposted = mysqli_num_rows($jobres);

if($isposted > 0){ 

    while($row = mysqli_fetch_array($jobres)){ 

		$job=$row;

				}

}

$param="";
$params=$_SERVER['QUERY_STRING'];
if (strpos($params,'page') !== false) {
    $key="page";
	parse_str($params,$ar);
	$param=http_build_query(array_diff_key($ar,array($key=>"")));
}
else
$param=$params;

$isengaged=mysqli_num_rows(mysqli_query($link,"select * from request where jobid='$jobid' and recruiterid='$mid' and status not in ('Requested','Rejected')"));

$requested=mysqli_num_rows(mysqli_query($link,"select * from request where jobid='$jobid' and recruiterid='$mid' and status = 'Requested'"));

if($job){



?>



<div id="job_title" data-value="<b><?php echo $job['jobtitle'];?></b> | Job ID: <b><?php echo $job['jobid'];?></b> | Job Status: <b><?php echo $job['status'];?></b> | 1st Posted: <?php echo date("F j, Y",strtotime($job['postdate']));  ?> | Last Updated: <b><?php echo date("F j, Y",strtotime($job['updatedate']));  ?></b>"></div>


<table class="table table-striped">

<thead></thead>

  <tbody>
      
      
      <tr>
    <td>
        <?php if(!$isengaged) 
 if(in_array($job['jobsector'],$mysector))
						echo '<a title="Engage" class="btn btn-default" href="engage?jobid='.$row['jobid'].'&employerid='.$job['memberid'].'">Engage</a>';
						else
						echo '<a title="Engage" class="btn btn-default" href="engage?jobid='.$job['jobid'].'&employerid='.$job['memberid'].'">Engage</a>';
 ?>

    </div>
  </td>
  </tr>
 <!--     Job Status <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Open - Means the requirement needs cvs immediately, Shortlisted - Means the requirement still needs cvs but one of the candidates submitted for this requirement by you or other vendors is shortlisted"><i class="fa fa-question-circle"> -> <b><?php echo $job['status']; ?></b> -->
 
 <tr>

      <td>Job Status <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Open - Means the requirement needs cvs immediately, Shortlisted - Means the requirement still needs cvs but one of the candidates submitted for this requirement by you or other vendors is shortlisted"><i class="fa fa-question-circle"></i>:
    </span></td>

      <td><?php echo $job['status'];?></td>

      </tr>
      
     <tr>
      <td><b>ACTION:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['requeststatus']!='Requested' &&$row['requeststatus']!='Rejected' &&$row['requeststatus']!='Disengaged' &&($isengaged)  && !in_array($job['status'],array('Closed','Hold'))) {   

              echo '<li><a title="Submit Candidate" href="application?jobid='.$job['jobid'].'&'.$param.'"><b>Submit Candidate</b></a></li>

            <li><a title="Message Employer" href="new-msg?eid='.$job['memberid'].'&jobid='.$job['jobid'].'">Message Employer</a></li>
       <li><a title="Disengage" class="confirmation" href="disengage?jobid='.$job['jobid'].'">Disengage</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
  
  <tr>

  <td>Best Practices:</td>

  <td><a href="bestpractices-recruiter" target="_blank"><b>BEST PRACTICES (Read this before working on any requirement)</b></a> </td>

</tr>
  
  <?php //} ?>

      
      <?php if($job['description1']){ ?>
    <tr>
    <td><b>Job Description (uploaded as file)</b></td>
    <td><b><a href="../employer/jobdescription/<?php echo $job['memberid'] ?>/<?php echo $job['jobid'] ?>/<?php echo $job['description1']; ?>" target="_blank"><?php echo $job['description1']; ?></a></b>
    </td>
    </tr>    
    <?php } ?>


    <tr>

      <td>Job Sector:</td>

      <td><?php echo $job['jobsector'];?> </td>

    </tr>
    
    <tr>

      <td>Interviewer / Hiring Manager Comments:</td>

      <td><p style = 'font-size: 16px;
            color: #a02121;'><b><?php echo $job['interviewcomments'];?></b></p></td>

      </tr>
    
    <tr>

      <td>Job Type:</td>

      <td><b><?php echo ucwords($job['jobtype']);?></b> </td>

    </tr>
    
    <tr>

      <td>Working:</td>

      <td><?php echo ucwords($job['working']);?> </td>

    </tr>
    
    <tr>

      <td>Country:</td>

      <td><?php echo $job['country'];?> </td>

    </tr>



    <tr>

      <td>Location:</td>

      <td><?php echo $job['joblocation'];?> </td>

    </tr>

    <tr>

      <td>City:</td>

      <td><?php echo $job['city'];?> </td>

    </tr>


	<tr>

      <td>Experience:</td>

      <td><?php echo $job['minex'];?> - <?php echo $job['maxex'];?> Years</td>

    </tr>

    <tr>
<?php if(($job['country']=='UK') && ($job['jobtype']=='Contract')) { 

      echo'<td>End Rate to RH from Client</td>'; }elseif(($job['country']=='US') && (($job['jobtype']=='Contract')||($job['jobtype']=='Corp-To-Corp'))) { echo '<td>End Rate</td>'; }else{ echo '<td>Candidate Salary Range / End Rate</td>';  } ?>

      <td> <?php if(($job['country']=='India')&&($job['jobtype']=='Contract')){  if($job['minlakh']) echo $job['minlakh']; else echo '0';echo '-';if($job['maxlakh']) echo $job['maxlakh'];else echo '0';   if($job['currency']=='GBP')echo '&pound;';elseif($job['currency']=='USD') echo '$'; else echo $job['currency'];     } elseif($job['country']=='India'){echo'Rs. ';if($job['minlakh']) echo $job['minlakh'].',';  if($job['minthousand']==0) echo '00,'; else echo $job['minthousand'].','; echo '000'; echo' - '; if($job['maxlakh']) echo $job['maxlakh'].',';  if($job['maxthousand']==0) echo '00,'; else echo $job['maxthousand'].','; echo '000';}else{ if($job['minlakh']) echo $job['minlakh']; else echo '0';echo '-';if($job['maxlakh']) echo $job['maxlakh'];else echo '0';   if($job['currency']=='GBP')echo '&pound;';elseif($job['currency']=='USD') echo '$';else echo $job['currency'];    }?><?php if($job['ratetype']) echo '/'.$job['ratetype']; else echo ''; ?> </td>

    </tr>	
    
       
<?php if((($job['country']!='UK') && ($job['jobtype']!='Contract')) &&(($job['country']!='US') && ($job['jobtype']!='Contract')) &&(($job['country']!='US') && ($job['jobtype']!='Corp-To-Corp'))) { ?>

    <tr>
      <td><b>Placement Fee:</b></td>

      <td>

      <b><?php if($job['fee']){ ?>  <?php echo $job['fee'];?> <?php } else { ?> (<?php  echo $job['currency'] ?> / ) <?php echo $job['rfee']; }?><?php if($job['ratetype']) echo '/'.$job['ratetype']; else echo ''; ?> %</b> of the Candidate's Offered Annual Base Salary (ABS/CTC) as our one time placement fee.

      </td>

    </tr><?php  } ?>

<tr>
    
    <tr>

      <td><b>What you will earn from the fee?</b></td>

      <td>Please read <b>"Point.9"</b> from this link -> <a href="https://recruitinghub.com/forrecruiters" target="_blank"><b>Recruiter FAQ's</b></a></td>

    </tr>
    
    <tr>

      <td>When will i get paid my fee?</td>

      <td><?php echo $job['replacementperiod'];?></td>

      </tr>
      
      <tr>

      <td><b>Want to manage this Client?</b></td>

     <td><a href="becomefranchise" target="_blank"><b>Become our Franchise Partner</b></a></td>

      </tr>
      
     <!--  <tr>

      <td>Currency:</td>

      <td><?php if($job['currency']=='GBP') { echo '&pound'; } elseif($job['currency']=='USD'){ echo '$';} else { echo $job['currency']; } ?> </td>

    </tr>-->
<?php //if( (($job['minex']=='0')&&($job['maxex']=='2'))||(($job['minex']=='1')&&($job['maxex']=='2'))||(($job['minex']=='0')&&($job['maxex']=='1')) && ($job['country']=='India')){ ?>

  <!--    <tr>

        <td><b>RH Account Manager</b>:</td>

        <td><?php echo $job['accountmanager'];?>

        </td>

      </tr> -->

<tr>

        <td>Who should i contact regarding this role?</td>

        <td>Keeping it between you (Vendor) and Employer (Client) with no more need for a middle man, you can now "Message Employer" directly after clicking Engage button for clarification (if any) about this role. Click <b>"ADD NOTES"</b> after submitting your candidate to follow up for feedback. An email notification will automatically be sent to the registered email id of the client copying the Account Manager of this client.

        </td>

      </tr>


    <!--<tr>

      <td>Reason for Vacancy:</td>

      <td><?php echo $job['vacancyreason'];?></td>

    </tr>-->



    <!--<tr>

      <td>Education:</td>

      <td><?php echo $job['degree'];?></td>

    </tr>-->

   <!-- <tr>

      <td>IR35:</td>

      <td><?php echo $job['ir35'];?></td>

    </tr> -->


    <!--<tr>

      <td>Predicted time for CV feedback:</td>

      <td><?php echo $job['feedback'];?></td>

    </tr>-->



    <!--<tr>

      <td>Key Skills:</td>

      <td><?php echo $job['keyskills'];?></td>

      </tr>-->

	

 	<tr>

      <td>Notice Period (How Soon Client Wants Candidate to Join):</td>

      <td><?php echo $job['notice'];?></td>

      </tr>
      
      <tr>

      <td>No of Vacancies:</td>

      <td><?php echo $job['novacancies'];?> Vacancy</td>

      </tr>

      
      <tr>

      <td>CV limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of CV's you can submit"><i class="fa fa-question-circle"></i>:
    </span></td>

      <td><?php echo $job['cvlimit'];?> CV's per Agency</td>

      </tr>

      <tr>

      <td>Agency Limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of Agencies Engaged Vs No of Agencies Allowed to work on this role"><i class="fa fa-question-circle"></i>:
    </span></td>

      <td><?php echo $job['agencycount'].'/'.$job['agencylimit'];?> Agencies Allowed to Work on this Role</td>

    </tr>

<?php //} ?>
      
      <?php if($job['description1']){ ?>
    <tr>
    <td><b>Job Description (uploaded as file)</b></td>
    <td><b><a href="../employer/jobdescription/<?php echo $job['memberid'] ?>/<?php echo $job['jobid'] ?>/<?php echo $job['description1']; ?>" target="_blank"><?php echo $job['description1']; ?></a></b>
    </td>
    </tr>    
    <?php } ?>
      

      <!--<tr>

      <td>CV Submission Closing Date:</td>

      <td><?php echo $job['closingdate'];?></td>

      </tr>-->
      
      <!--<tr>

      <td>Stages of Interview Process:</td>

      <td><?php echo $job['interviewstages'];?> stages</td>

    </tr>-->
      

      
      
      <tr>

  <tr>

  <td>Special Notes about the Client (if any):</td>

  <td><p style = 'font-size: 14px;
            color: #a02121;'><b><?php echo $job['specialnotes'];?></b></p></td>

  </tr>
  
  <tr>
  
 <td> <p><b>Job Description:</b></td>
 
 <td><textarea rows="30" cols="480" class="form-control" readonly><?php echo $job['description'];?></textarea></p> </td>
      
  </tr>
  
  <?php if($job['description1']){ ?>
    <tr>
    <td><b>Job Description (uploaded as file)</b></td>
    <td><b><a href="../employer/jobdescription/<?php echo $job['memberid'] ?>/<?php echo $job['jobid'] ?>/<?php echo $job['description1']; ?>" target="_blank"><?php echo $job['description1']; ?></a></b>
    </td>
    </tr>    
    
    <?php } ?>
    
    <tr>
      <td><b>ACTION:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['requeststatus']!='Requested' &&$row['requeststatus']!='Rejected' &&$row['requeststatus']!='Disengaged' &&($isengaged)  && !in_array($job['status'],array('Closed','Hold'))) {   

              echo '<li><a title="Submit Candidate" href="application?jobid='.$job['jobid'].'&'.$param.'"><b>Submit Candidate</b></a></li>

            <li><a title="Message Employer" href="new-msg?eid='.$job['memberid'].'&jobid='.$job['jobid'].'">Message Employer</a></li>
       <li><a title="Disengage" class="confirmation" href="disengage?jobid='.$job['jobid'].'">Disengage</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
  
  <tr>

      <td>Employer Profile:</td>
      
   <td>   <p><textarea rows="10" cols="80" class="form-control" readonly><?php echo $job['companydescription'];?></textarea></p> </td>


    </tr>
    
    <tr>

      <td>Client Website:</td>

      <td><?php echo $job['website'];?> </td>

    </tr>
    
    
    
    <tr>
      <td><b>ACTION:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['requeststatus']!='Requested' &&$row['requeststatus']!='Rejected' &&$row['requeststatus']!='Disengaged' &&($isengaged)  && !in_array($job['status'],array('Closed','Hold'))) {   

              echo '<li><a title="Submit Candidate" href="application?jobid='.$job['jobid'].'&'.$param.'"><b>Submit Candidate</b></a></li>

            <li><a title="Message Employer" href="new-msg?eid='.$job['memberid'].'&jobid='.$job['jobid'].'">Message Employer</a></li>
       <li><a title="Disengage" class="confirmation" href="disengage?jobid='.$job['jobid'].'">Disengage</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
  
  </tbody>

</table>



<tr>
    <td>
    <?php if(!$isengaged) 
 if(in_array($job['jobsector'],$mysector))
						echo '<a title="Engage" class="btn btn-default" href="engage?jobid='.$row['jobid'].'&employerid='.$job['memberid'].'">Engage</a>';
						else
						echo '<a title="Engage" class="btn btn-default" href="engage?jobid='.$job['jobid'].'&employerid='.$job['memberid'].'">Engage</a>';
 ?>

    </div>
  </td>
  </tr>

<br><br>

 

<?php if(!$isengaged) {?>

  <!--<p>The full job description and employer's details will become available once you have been approved to work on this vacancy. </p>-->
  <p><b>Employer's detail will become available once you hit the "ENGAGE" button. All Contract and 0-15 experience Permanent Jobs Engagement gets auto approved.</b></p>

<?php 

 if(!$requested){ 

 	if($eligible) {

 ?>

 <p> <br/><br/><a class="btn btn-primary" title="Engage" href="engage?jobid=<?php echo $job['jobid'].'&employerid='.$job['memberid']; ?>">Request Engagement</a></p>

<?php 	

	} 

	else { ?>

    <!--<p>Unfortunately you are not eligible to engage on this vacancy on your current plan. <a href="<?php if($rcountry=='India'){echo 'subscription_plans'; }else{ echo 'subscription-plans'; } ?> ">Upgrade/Buy subscription</a>

  </p>-->

<?php	

		}

   }

}

else {



 //echo ' <hr> ';
 
 // echo ' <p><b>Employer Profile:</b> <p><textarea rows="10" cols="80" class="form-control" readonly>'.$job['companydescription'].'</textarea></p>
  
//  <br> <b>Client Website</b>: '.$job['website'].' ';
?>

<hr>


<form id="engage_form" action="new-msg" method="post">

      <input type="hidden" name="recipid" value="<?php echo $job['memberid']; ?>" />

       <input type="hidden" name="subject" value="Engagement Request for <?php echo $job['jobtitle'];?> - <?php echo $job['jobtype'];?> role" />


					 
  
<!--<div class="form-group">

        <label for="engagement_message">Message this Employer directly</label>

        <textarea class="form-control" rows="3" required="required" name="message" id="engagement_message"></textarea>

      </div>

      <div class="form-group">

       <input class="btn-lg btn-default"  type="submit" name="send" value="Send Message"  />

       </div>-->

       
  <!--     <tr>
      <td><b>ACTION:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['requeststatus']!='Requested' &&$row['requeststatus']!='Rejected' &&$row['requeststatus']!='Disengaged' &&($isengaged)  && !in_array($job['status'],array('Closed','Hold'))) {   

              echo '<li><a title="Submit Candidate" href="application?jobid='.$job['jobid'].'&'.$param.'"><b>Submit Candidate</b></a></li>

            <li><a title="Message Employer" href="new-msg?eid='.$job['memberid'].'&jobid='.$job['jobid'].'">Message Employer</a></li>
       <li><a title="Disengage" class="confirmation" href="disengage?jobid='.$job['jobid'].'">Disengage</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr> -->
       

</form>



<?php }

 } 

 ?>
 
 <script>
$('.confirmation').on('click', function () {
        return confirm('Are you sure you want to dis-engage from this role as it cannot be re-engaged by you again?');
    });
    </script>
    
 <script>
/**$(document).ready(function(){

if($('#searchandmark').val().length !== 0){

       $('#paragraph').each(function(){
	   
       $(this).html($(this).html().replace($('#searchandmark').val(),"<span class = 'highlight'>"+$('#searchandmark').val()+"</span>"));
    });
	
  }});**/
$(document).ready(function(){
var src_str = $("#paragraph").html();
var term = $("#searchandmark").val();
term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
var pattern = new RegExp("("+term+")", "gi");

src_str = src_str.replace(pattern, "<mark style='background-color:yellow;'>$1</mark>");
src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

$("#paragraph").html(src_str);

});
</script>
