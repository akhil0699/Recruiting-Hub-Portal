<?php
if (session_status() == PHP_SESSION_NONE) {
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
}
$job='';
require_once '../config.php';

if(isset($_SESSION['mark'])){
$highlight=$_SESSION['mark'];
}else{
$highlight='';
}

if(isset($_POST['EID']))
$candidateid=$_POST['EID'];

$candidateres =mysqli_query($link,"SELECT a.*,b.email as recruiteremail,c.name as agency,sc.status as candidatestatus,sc.candidateid as candidateid,sc.employernotes as employernotes,sc.employernotesdate as employernotesdate,sc.empduplicatenotes as empduplicatenotes,sc.empduplicatenotesdate as empduplicatenotesdate,sc.recduplicatenotes as recduplicatenotes,sc.recduplicatenotesdate as recduplicatenotesdate,sc.recruiternotes as recruiternotes,sc.recruiternotesdate as recruiternotesdate,sc.amnotes as amnotes,sc.amnotesdate as amnotesdate,sc.recruiterid as recruiterid,sc.jobid from candidates a,members b, companyprofile c,submitted_candidates sc  where a.id=$candidateid and b.memberid=a.recruiterid and a.id=sc.candidateid and c.id=b.companyid"); 

$ispresent = mysqli_num_rows($candidateres);
if($ispresent > 0){ 
   $row = mysqli_fetch_array($candidateres);
}
if($row){
?>



<div id="candidate_name" data-value="<?php echo $row['fname'];?> - ID: <?php echo $row['id'];?> <br><br>Employer Notes (if any) - <b><?php echo $row['employernotes'];?> <small><?php echo $row['employernotesdate'];?></small></b> <br><br>Employer Duplicate Notes (if any) - <b><?php echo $row['empduplicatenotes'];?></b> <small><?php echo $row['empduplicatenotesdate'];?></small> <br><br>Recruiter Notes (if any) - <b><?php echo $row['recruiternotes'];?></b> <small><?php echo $row['recruiternotesdate'];?></small> <br><br>Account Manager Notes (if any) - <b><?php echo $row['amnotes'];?></b> <small><?php echo $row['amnotesdate'];?></small>"> </div>



<table class="table table-striped">
<thead></thead>
  <tbody>
      
      <tr>
      <td>Add Notes:</td>
      <td><?php 

        echo '<li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['id'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>';
?>
  </td>
    </tr>
    
    <tr>
       <td>Download CV:</td>
     <?php  if(file_exists ('../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv'])) {?>
      <td><a href="<?php echo '../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv']; ?>" target="_blank"  title="view cv"><b>DOWNLOAD CV</b></a></td>
      <?php } else{ ?>
      <td> CV not found </td>
      <?php } ?>
    </tr>
    
    <tr>
       <td>Download Additional/Audio/Video file of Candidate:</td>
     <?php  if(file_exists ('../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']) && $row['video']) {?>
      <td><a href="<?php echo '../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']; ?>" target="_blank"  title="view video"><b>DOWNLOAD ADDITIONAL/AUDIO/VIDEO FILE OF CANDIDATE (Available)</b></a></td>
      <?php } else{ ?>
      <td> No Additional/Audio/Video file found </td>
      <?php } ?></a> </td>
    </tr>

  
    <tr>
      <td>Email:</td>
      <td><?php echo $row['email'];?> </td>
    </tr>

      <tr>
      <td>Phone:</td>
      <td><?php echo $row['contactnumber'];?> </td>
    </tr>
  
    <tr>
      <td>Current Employer:</td>
      <td><?php echo $row['currentemployer'];?> </td>
    </tr>

    <tr>
      <td>Current Job Title:</td>
      <td><?php echo $row['currentjobtitle'];?> </td>
    </tr>
    
    <tr>
      <td>Job Type:</td>
      <td><?php echo $row['jobtype'];?> </td>
    </tr>
	
     <tr>
      <td>Current Salary/Rate:</td>
      <td><?php echo $row['currentcurrency'];?> <?php echo $row['currentsalary'];?> / <?php echo $row['typesalary'];?> </td>
    </tr>
    
	 <tr>
      <td>Expected Salary/Rate:</td>
      <td><?php echo $row['currentcurrency'];?> <?php echo $row['desiredsalary'];?> / <?php echo $row['typesalary'];?> </td>
      </tr>
    
	<tr>
      <td>Nationality:</td>
      <td><?php echo $row['nationality'];?> </td>
    </tr>

	 <tr>
      <td>Notice Period:</td>
      <td><?php echo $row['notice'];?> </td>
    </tr>
    
    <tr>
      <td>Current Country:</td>
      <td><?php echo $row['country'];?> </td>
    </tr>

	 <tr>
      <td>Current City:</td>
      <td><?php echo $row['location'];?> </td>
    </tr>
    
    <tr>
      <td>Willing to Relocate:</td>
      <td><?php if($row['relocate']) echo "Yes"; else echo "No";?> </td>
    </tr>
        
	    
  </tbody>
</table>


<hr>
 <h5>Additional Information</h5>	
  <p><?php echo $row['additionalinfo'];?></p>
<h5>CV</h5>
<textarea rows="30" cols="80" class="form-control"  name="cvcopy" readonly="readonly"><?php echo $row['resume'];?></textarea>    
  <hr>
  


<?php 



} ?>

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
 