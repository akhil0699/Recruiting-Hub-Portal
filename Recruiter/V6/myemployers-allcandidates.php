<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];

$name=$_SESSION['name'];

$email=$_SESSION['email'];
$cid=$_SESSION['cid'];

$adminrights=$_SESSION['adminrights'];
$admincountry= $_SESSION['admincountry'];
$usertype=$_SESSION['usertype'];

}

else{

$notification='Please login to access the page';

$_SESSION['notification']=$notification;

header('location: index'); exit;

}

require_once '../config.php';



//$jobarray=array();

//jobs posted recently

$candidatemsg='';

$param="";

$candidatearray=array();

//$candidateres = mysqli_query($link,"SELECT a.*,b.status FROM Candidates a, submitted_candidates b,jobs c WHERE b.jobid=c.jobid and c.memberid='$mid' and a.id=b.candidateid");

//$sql="SELECT a.name, b.designation,a.contact,b.currentcompany,b.salarylakhs,b.salarythousand,b.noticeperiod,a.years,a.months FROM canditate a,employment b where b.type='current'and a.id=b.canditateid order by a.id desc";



$employerres=$recruiterres=$jobtitleres=$jobidres=$candidateres=$statusres=$countryres=$id=$candidateemail=$commentres=$employernotesres='';

if(isset($_GET['submit'])){

if(isset($_GET['employer']) && ($_GET['employer']!='')){

$employer=$_GET['employer'];

$param='employer='.$employer;

$employerres="and r.employer = '$employer'";

}

if(isset($_GET['recruiter']) && ($_GET['recruiter']!='')){

$recruiter=$_GET['recruiter'];

$param='recruiter='.$recruiter;

$recruiterres="and r.recruiter = '$recruiter'";

}

if(isset($_GET['candidate']) && ($_GET['candidate']!='')){

$candidate=$_GET['candidate'];

$param='candidate='.$candidate;

$candidateres="and r.fname like '%$candidate%'";

}

if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){

$jobtitle=$_GET['jobtitle'];

$param='jobtitle='.$jobtitle;

$jobtitleres="and r.jobtitle like '%$jobtitle%'";

}

if(isset($_GET['jobid']) && ($_GET['jobid']!='')){

$jobid=$_GET['jobid'];

$param='jobid='.$jobid;

$jobidres="and r.jobid like '%$jobid%'";

}

if(isset($_GET['status']) && ($_GET['status']!='')){

$status=$_GET['status'];
if($status=='CV Rejected')
	$filter=" e.prestatus='Awaiting Feedback'";
	elseif($status=='Interview Reject')
	$filter=" e.prestatus='Shortlisted'";
	elseif($status=='Offer Rejected')
	$filter=" e.prestatus='Offered'";
	else 
	$filter=" e.status='$status'";

$param='status='.$status;

$statusres="and status ='$status'";

}

if(isset($_GET['comment']) && ($_GET['comment']!='')){

$comment=$_GET['comment'];

$param='comment='.$comment;

$commentres="and comment ='$comment'";

}

if(isset($_GET['employernotes']) && ($_GET['employernotes']!='')){

$employernotes=$_GET['employernotes'];

$param='employernotes='.$employernotes;

$employernotesres="and employernotes ='$employernotes'";

}

if(isset($_GET['country']) && ($_GET['country']!='')){

$country=$_GET['country'];

$param='country='.$country;

$countryres="and country ='$country'";

}

if(isset($_GET['candidateemail']) && ($_GET['candidateemail']!='')){

$candidateemail=$_GET['candidateemail'];

$param='candidateemail='.$candidateemail;

$candidateemail="and candidateemail ='$candidateemail'";

}

if(isset($_GET['id']) && ($_GET['id']!='')){

$id=$_GET['id'];

$param='id='.$id;

$id="and id ='$id'";

}

}

// if($usertype!='Super Admin'){
//   $country=$admincountry;
//   $countryres=" and EXISTS (SELECT clientmanager FROM employers where clientmanager='$mid' and employer =companyname )";

//  //var_dump($r);

// }
if($usertype=='Super Admin'){
  $countryres='';
}
else if($usertype == 'Franchisee'){
  $countryres=" and EXISTS (SELECT clientmanager FROM employers where clientmanager='$mid' and employer =companyname )";
}else if($usertype == 'Sales Manager Admin'){
  $countryres=" and EXISTS (SELECT clientmanager FROM employers where approvedby='$mid' and employer =companyname )";
}else if(!isset($_GET['jobid'])){
 // $countryres="and compid ='$cid'";
  $countryres=" and EXISTS (SELECT clientmanager FROM employers where clientmanager='$cid' and employer =companyname )";

}



$sql="SELECT r.* from ((SELECT  e.status,e.comment,e.employernotes,e.recruiternotes,e.amnotes,e.prestatus,e.viewed,e.viewedtime,e.feedbackdate,e.submitdate,b.jobid,b.fee,b.jobtitle,d.name as employer,a.fname,a.desiredsalary,a.expectedcurrency,a.typesalary,a.contactnumber,a.id,a.email as candidateemail,a.addeddate,g.name as recruiter,f.firstname as subuser,f.country as country,f.email as subuseremail, c.firstname as esubname, c.email as esubemail,f.companyid as compid FROM submitted_candidates e,jobs b,members c, companyprofile d,candidates a,members f,companyprofile g WHERE e.candidateid=a.id and e.jobid=b.jobid and b.memberid=c.memberid and c.companyid=d.id and f.companyid=g.id and e.recruiterid=f.memberid ) UNION (SELECT  'New' as status,NULL as comment,NULL as employernotes,NULL as recruiternotes,NULL as amnotes,NULL as viewed,NULL as prestatus,NULL as viewedtime,NULL as feedbackdate,NULL as submitdate,NULL as jobid,NULL as fee,NULL as jobtitle,NULL as name,a.fname,a.desiredsalary,a.expectedcurrency,a.typesalary,a.contactnumber,a.id,a.email as candidateemail,a.addeddate,NULL as recruiter,NULL as subuser,NULL as esubname, NULL as esubemail, NULL as subuseremail,NULL as country,NULL as compid from candidates a,members f,companyprofile c where f.companyid=c.id  and a.recruiterid=f.memberid and a.id not in(select distinct candidateid from submitted_candidates))) r where 1 $employerres $recruiterres $jobtitleres $jobidres $candidateres $countryres $statusres $id $candidateemail $commentres $employernotesres order by submitdate desc";


$param="";

$params=$_SERVER['QUERY_STRING'];

if (strpos($params,'page') !== false) {

    $key="page";

    parse_str($params,$ar);

    $param=http_build_query(array_diff_key($ar,array($key=>"")));

}

else

$param=$params;

include('../ps_pagination.php');

$pager = new PS_Pagination($link, $sql, 20, 10, $param);


$candidateres = $pager->paginate();



if(@mysqli_num_rows($candidateres)){

    while($row = mysqli_fetch_array($candidateres)){

    $candidatearray[]=$row;

                }



    $pages =  $pager->renderFullNav();
    $total= $pager->total_rows;



}

else

$candidatemsg="No candidates found.";



?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Jobs Posted by my Employers | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

         
</head>
<body>

<div class="header-inner">
<div class="header-top">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Agency Profile</a>   </li>
      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>
       <?php if($admin){
       echo'<li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>';
	   /** if($rcountry=='India'){echo'
	   <li><a href="subscription_plans"><i class="fa fa-money"></i> Manage Subscription</a></li>';}
	   else{echo'
	    <li><a href="subscription-plans"><i class="fa fa-money"></i> Manage Subscription</a></li>';
		}echo'
     <li><a href="subscription-status"><i class="fa fa-info-circle"></i> Subscription Status</a></li>
	   ';**/
	 }
	   ?>
      <li><a href="rmanage-notification"><i class="fa fa-bell"></i>Manage Notification</a></li>
      <li class="divider"></li>
      <li> <a href="logout"><i class="fa fa-sign-out"></i>Logout</a></li>
    </ul>
  </li>
</ul>
</div>
</div>

<div class="summary-wrapper">
<div class="col-sm-12 account-summary no-padding">

<div class="col-sm-2 no-padding">
<div class="account-navi">
  <ul class="nav nav-tabs">
<h4>Vendor Section (Business)</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
   <li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED / DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add New Candidate</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Shortlisted</a>  </li>
     <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a>  </li>
      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>
     
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<?php echo $new; ?>) </a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
<li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Agency Settings</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
    <li><a href="company-profile" role="tab" > <i class="fa fa-briefcase"></i>Agency Profile</a> </li>
    <li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
    <li><a href="edit-profile" role="tab" > <i class="fa fa-plus"></i>Your Profile</a> </li>
    
    </ul>
   </div>
   </li>
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring </a> </li>
    <h4>Jobseeker Section (Jobsite)</h4>

<li><a href="candidate-search" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> CV Search <span class="label label-primary">Free</span></a>    </li>
    <li><a href="post-job" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Post Jobs <span class="label label-primary">Free</span></a>    </li>
    <li><a href="manage-responses" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage responses</a>    </li>
    <li><a href="manage-jobs" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage Jobs</a>    </li>
    
    <!-- Client Section (Admin) -->
<h4>Client Section (Admin)</h4>

<li><a href="employers-registered-by-me" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> My EMPLOYERS </a>    </li>
    <li><a href="jobs-posted-by-my-employers" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> My Employers JOBS </a>    </li>
    <li><a href="myemployers-allcandidates" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> My Employers CANDIDATES</a>    </li>
    
  </ul>
</div>


</div>


<div class="col-sm-10">

<div class="account-navi-content">

  <div class="tab-content">



    <div role="tabpanel" class="tab-pane active" id="account.php">

          <div class="dashboard">

        <div class="dashboard-head"><h2><i class="fa fa-th-large"></i>My Employers CANDIDATES || <span class="title text-center"><b>Your Unique Employer Sign Up Link -></b> : 
 <a id="copy" href="<?php echo 'https://www.recruitinghub.com/fprecruiter-employers?cp='.strtolower(str_replace(' ','-',$company['companyname'])).'_'.$company['id'].' '; ?> " target="_blank">Copy Link <i class="fa fa-solid fa-copy"></i> <p id="copied"></p>
</a>

</span></h2>

        <ul class="breadcrumb">

<li class="active"><b>My Employers CANDIDATES </b></li>
<li><a href="jobs-posted-by-my-employers"><i class="fa fa-plus"></i> My Employers JOBS</a></li>
<li><a href="employers-registered-by-me"><i class="fa fa-plus"></i> My EMPLOYERS </a></li>

</ul>
          </div>

<div class="text-right">

 <!-- <a class="btn btn-primary" href="allcandidate-download">

   <i class="fa fa-file-excel-o"></i> Download xls

</a> -->

</div>





  <div class="row">

    <div class="col-xs-12">



      <div class="panel panel-default">

       <div>

         <!-- <form method="get" action="adminall">

            <div class="form-group">        <br>



                                            <div class="col-sm-2 no-padding">

                                            <input name="employer" class="form-control ui-autocomplete-input" id="employer" placeholder="Search by Employer" autocomplete="off" type="text">

        </div>

        <div class="col-sm-2">

                                            <input name="recruiter" class="form-control ui-autocomplete-input" id="recruiter" placeholder="Search by Agency" autocomplete="off" type="text">

        </div>
        
        <div class="col-sm-2">

                                            <input name="jobtitle" class="form-control ui-autocomplete-input" id="jobtitle" placeholder="Job title" autocomplete="off" type="text">

        </div>
        
        <div class="col-sm-2">

                                            <input name="jobid" class="form-control ui-autocomplete-input" id="jobid" placeholder="Job ID" autocomplete="off" type="text">

        </div>



         <div class="col-sm-2">

                                            <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Candidate Name" autocomplete="off" type="text">

        </div>
        
        <div class="col-sm-2">

                                            <input name="id" class="form-control ui-autocomplete-input" id="id" placeholder="Candidate ID" autocomplete="off" type="text">

        </div>

        <div class="col-sm-2">

                                            <input name="candidateemail" class="form-control ui-autocomplete-input" id="candidateemail" placeholder="Search Cand Email" autocomplete="off" type="text">

        </div>

        <div class="col-sm-2">

                                            <select name="country" class="form-control ui-autocomplete-input" type="text">

                                            <option value="">Search By Country</option>

                                            <?php $query=mysqli_query($link,"select * from country");

 while($res=mysqli_fetch_assoc($query)){

                                            $row[]=$res;

                                        }

                                            foreach($row as $c){

                                            echo '<option value="'.$c['country'].'">'.$c['country'].'</option>';

                                            }

                                            ?></select>

        </div>



         <div class="col-sm-2">

                                            <select name="status" class="form-control ui-autocomplete-input">

                                            <option value="">Select Status</option>

                                            <option value="New">Yet to be Submitted by Agency</option>

                                            <option value="Awaiting Feedback">Awaiting Feedback</option>

                                            <option value="Shortlisted">Shortlisted</option>

                                            <option value="Offered">Offered</option>

                                            <option value="Rejected">Rejected</option>
                                            
                                            <option value="Filled">Filled</option>

                                           <!-- <option value="Duplicate">Duplicate</option>

                                            <option value="Job Closed">Job Closed</option>

                                             

                                              <option value="Job Filled">Job Filled</option>--->

                                          <!--   </select>

        </div>



        <div class="col-sm-2">





        <div class="form-group">

                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">

                                             </div><br>

        </div>

        </div>

        </form> -->

        </div>
        

           <div class="clearfix"></div>

      <header class="panel-heading wht-bg ui-sortable-handle">

<h3>Total Candidates:<?php echo $total; ?> (displaying all candidates from the beginning. Look for submissions made in 6 months)</h3>

  </header>

        <div class="panel-body">



          <div class="row">

            <div class="col-md-12 table-responsive">

               <?php if(!$candidatemsg){ ?>

                <table class="table table-striped jobs-list">

                  <thead>

                    <tr>

                   <th>Candidate Name/ID</th>

                  <th>Job Title/ID/Employer/Fee</th>

                  <th>Recruiting Agency</th>

                  <th>Exp Salary/Rate</th>

                  <th>Status/Feedback Date</th>
                  
                  <th>Reason/Notes</th>

                    </tr>

                  </thead>

                  <tbody>

                   <?php
//var_dump($candidatearray);
        foreach($candidatearray as $row){
          if($row['viewed'] == 0){
            $viewed = '<small style="
            font-size: 10px;
            color: #a02121;
        ">Yet to be Viewed</small>';
          }else{
  
            $viewed = '<small style="
            font-size: 10px;
            color: #21a048;
        "> Viewed on '.$row['viewedtime'].' </small>';
  
          }
        if($row['prestatus']=='Awaiting Feedback')
				$row['status']="CV Rejected";
			elseif($row['prestatus']=='Shortlisted')
				$row['status']="Interview Rejected";
				elseif($row['prestatus']=='Offered')
				$row['status']="Offer Rejected";
            echo'

          <tr>

           <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['id'].'>'.$row['fname'].' </a><br>

   <small>Submitted: '.$row['submitdate'].' / '.$row['id'].'</small><br> '.$viewed.'</td>';



  if($row['jobtitle'])

   echo '<td><a class="title" href="/recruiter/myemployers-allcandidates?jobtitle='.urlencode($row['jobtitle']).'&candidate=&agency=&status=&submit=Search" >'.$row['jobtitle'].'</a><br><b>'.$row['employer'].'</b> / <small>'.$row['jobid'].'</small> / <b>'.$row['fee'].'%</b> / <b>'.$row['esubname'].'</b> / '.$row['esubemail'].' </td>';

  else

   echo "<td>Not Applied</td>";

 if($row['status']!=='New')



   echo '<td>'.$row['recruiter'].'<br><small>'.$row['subuser'].'</small> | '.$row['country'].'</td>';

   else

   echo "<td>-</td>";

    echo'
    

  <td>';

  if($row['expectedcurrency'])
  	echo $row['expectedcurrency'];
  if($row['desiredsalary'])
  	echo $row['desiredsalary'];
  if($row['typesalary'])
  	echo $row['typesalary'];

  else

   echo "Not Mentioned";

  echo '</td> ';

  echo'<td><span class="badge badge-default">'.$row['status'].'</span><br>'.$row['feedbackdate'].'</td>';
  
  echo'<td>Reason -> <span class="badge badge-default">'.$row['comment'].'</span><br>Emp Notes -> <span class="badge badge-default">'.$row['employernotes'].'</span> <br>Rec Notes -> <span class="badge badge-default">'.$row['recruiternotes'].'</span> <br>AM Notes -> <span class="badge badge-default">'.$row['amnotes'].'</span></td>



</tr>';

}

?>



 </tbody>

 </table>

  <?php echo $pages; }else echo'<p>No Candidates found</p>'; ?>

    </div>

   </div>

  </div>

   </div>

    </div>

  </div>

</div>

</div>





     </div>

     </div>

     </div>

<div class="clearfix"></div>

</div>

</div>



<div class="modal fade" id="candidate-modal" tabindex="-1" role="dialog" aria-hidden="true">

      <div class="modal-dialog modal-lg">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

            <h4 class="modal-title" id="candidate-modal-label"></h4>

          </div>

          <div class="modal-body" id="candidate-modal-body">

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          </div>

        </div>

      </div>

    </div>
    
    <div class="modal fade" id="notes-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="notes-modal-label"></h4>
          </div>
          <div class="modal-body" id="notes-modal-body">
          </div>
         
        </div>
      </div>
    </div>  
    
    <div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="feedback-modal-label"></h4>
          </div>
          <div class="modal-body" id="feedback-modal-body">
          </div>
         
        </div>
      </div>
    </div>  



<div class="modal fade" id="engage-modal" tabindex="-1" role="dialog" aria-hidden="true">

      <div class="modal-dialog modal-lg">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

            <h4 class="modal-title" id="engage-modal-label"></h4>

          </div>

          <div class="modal-body" id="engage-modal-body">

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>



          </div>

        </div>

      </div>

    </div>

<script>

    $('#engage-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),

            essayId = e.relatedTarget.id;

        $.ajax({

            cache: false,

            type: 'POST',

            url: 'engage-modal-content.php',

            data: 'EID=' + essayId,

            success: function(data) {

                $modal.find('#engage-modal-body').html(data);

                $modal.find('#engage-modal-label').html( $('#job_title').data('value'));



            }

        });

    });

    $('#candidate-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),

            essayId = e.relatedTarget.id;

        $.ajax({

            cache: false,

            type: 'POST',

            url: 'candidate-detail.php',

            data: 'EID=' + essayId,

            success: function(data) {

                $modal.find('#candidate-modal-body').html(data);

                $modal.find('#candidate-modal-label').html( $('#candidate_name').data('value'));



            }

        });

    });
    
 
$('#feedback-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            essayId = element.attr('id'),
			jobids = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'reject-candidate.php',
            data: {EID:essayId,jobids:jobids},
            success: function(data) {
				$modal.find('#feedback-modal-label').html(data);
                $modal.find('#feedback-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });

 
    $('#notes-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            cid = element.attr('id'),
			jobid = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'employernotes.php',
            data: {CID:cid,jobid:jobid},
            success: function(data) {
				$modal.find('#notes-modal-label').html(data);
                $modal.find('#notes-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });
    
    $('#duplicate-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            cid = element.attr('id'),
			jobid = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'empduplicatenotes.php',
            data: {CID:cid,jobid:jobid},
            success: function(data) {
				$modal.find('#duplicate-modal-label').html(data);
                $modal.find('#duplicate-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });
 


     $( "#employer" ).autocomplete({

    minLength: 2,

    source:'esearch.php',

     response: function(event, ui) {

            // ui.content is the array that's about to be sent to the response callback.

            if (ui.content.length === 0) {

                $("#e").text("No results found");

            } else {

                $("#e").empty();

            }

    },

    change: function(event, ui) {

            if (!ui.item) {

               $(this).val('');

            }

    }

    });

    $( "#recruiter" ).autocomplete({

    minLength: 2,

    source:'rsearch.php',

     response: function(event, ui) {

            // ui.content is the array that's about to be sent to the response callback.

            if (ui.content.length === 0) {

                $("#e").text("No results found");

            } else {

                $("#e").empty();

            }

    },

    change: function(event, ui) {

            if (!ui.item) {

               $(this).val('');

            }

    }

    });

</script>

<script>
 $('#copy').on('click',function(){

 var link = $(this).attr('href')

navigator.clipboard.writeText(link);

$('#copied').text("✓ Link Copied");

  return false;
 }) 
</script>

 </body>

</html>


