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
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
$notification='';
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
$content='';
require_once '../config.php';
require '../helpers/general.php';

$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$employers=array();
$empres=mysqli_query($link,"SELECT  b.name as companyname,d.companyid as rcid,d.memberid as recruiter FROM jobs a,companyprofile b ,request c, members d WHERE c.employerid='$mid' and c.jobid=a.jobid and c.recruiterid=d.memberid and b.id=d.companyid  group by b.name order by d.memberid");

if(mysqli_num_rows($empres)){
while($row=mysqli_fetch_assoc($empres)){
$employers[]=$row;
}
}

?><!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Rating | Employers Hub</title>
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
<div class="logo">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
<a class="btn btn-primary" href="postdashboard">
     Sales Support +44 02030267557
</a>
</div>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>
       <li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>
        <li><a href="emanage-notification"><i class="fa fa-bell"></i>Manage Notification</a></li>
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
  <li><a href="dashboard" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
  <li><a href="postdashboard" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>
  

     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="postdashboard" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a>  </li>
   <li><a href="jobs" role="tab" ><i class="fa fa-list"></i>Active Jobs</a> </li>
    <li><a href="inactive" role="tab" ><i class="fa fa-list"></i>Inactive Jobs</a> </li>
    <li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i>Closed Jobs</a>  </li>
    <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled Jobs</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates (ATS)</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
   <li><a href="shortlist" role="tab" > <i class="fa fa-users"></i>Shortlisted</a>  </li>
        <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a> </li>
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i>Rejected</a> </li>
    <li><a href="filledcandidates" role="tab" > <i class="fa fa-users"></i>Filled</a> </li>
    </ul>
   </div>
   </li>
   
   <li><?php 
	   if($ecountry=='India'){echo'
	   <li><a href="rec-rec-india"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	   elseif($ecountry=='United Kingdom (UK)'){echo'
	    <li><a href="rec-rec"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    elseif($ecountry=='United States Of America (US)'){echo'
	    <li><a href="rec-rec-us"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    else{echo'
	    <li><a href="rec-rec"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    
	    echo'
	   ';
	   ?></li>
   
     <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<span class="label-primary"><?php echo $new; ?></span>)</a>    </li>
   <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu2" data-toggle="collapse" aria-controls="candidatesDropdownMenu2" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Rating/PSL/Block</a>

   <div class="collapse" id="candidatesDropdownMenu2">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Rating/PSL/Block</a> </li>
   <li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL List</a> </li>
    <li><a href="block-list" role="tab" > <i class="fa fa-list"></i>Block List</a> </li>
    </ul>
   </div>
   </li>
   
   <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu3" data-toggle="collapse" aria-controls="candidatesDropdownMenu3" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Company Settings</a>

   <div class="collapse" id="candidatesDropdownMenu3">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="company-profile" role="tab" > <i class="fa fa-plus"></i>Company Profile</a> </li>
   <li><a href="edit-profile" role="tab" > <i class="fa fa-plus"></i>Your Profile</a> </li>
<li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
<li><a href="emanage-notification" role="tab" > <i class="fa fa-plus"></i>Notifications</a> </li>
    </ul>
   </div>
   </li>
   
   <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu4" data-toggle="collapse" aria-controls="candidatesDropdownMenu4" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Bench Hiring <span class="label label-primary">Free</span></a>

   <div class="collapse" id="candidatesDropdownMenu4">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="resources" role="tab" > <i class="fa fa-database"></i>Upload Bench Resources </a> </li>
   <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Request for Your Bench Resources </a> </li>  
   <li><a href="view-resources" role="tab" > <i class="fa fa-database"></i>View Your Bench Resources </a> </li>  
   <li><a href="search-resources" role="tab" > <i class="fa fa-database"></i>Search Bench Resources from Others </a> </li>  
     
    </ul>
   </div>
   </li>
   
    <li> <a href="logout"><i class="fa fa-sign-out"></i>Logout</a></li>
   
	   </ul>
   
</div>
</div>
<div class="col-sm-10">
<div class="account-navi-content">
  <div class="tab-content">   
   

  <div class="row">
 <div class="col-xs-12">
      <section class="panel panel-default">
        <header class="panel-heading panel-heading--buttons wht-bg">
          <h4 class="gen-case">TOTAL ENGAGED AGENCIES LIST || <a href="psl-list">PSL List</a> | <a href="block-list">Block List</a></h4>

          <div class="text-right">
		<a title="Message Agency" class="btn btn-primary" id="select-all-submit" href="msg-agency?jobid='.$jobid.'&rids=<ids>">Message Agency</a></li>   
        </div>

        </header>
       
        <div class="panel-body minimal">			
          <div class="table-responsive ">
			<table class="table table-striped">
              <thead>
                <tr>
               <th> <div class="checkbox"><label><input value="1" name="allcandi" type="checkbox" id="select-all">Select All <br>to Message Agencies</label></div>
         </div></th>
                  <th>Recruiting Agency</th>
                  <th>Feedback Rating</th>
                  <th>Add/Remove PSL <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="You are adding the agency to your Preferred Supplier List so we can get the agencies to focus more on your jobs"> <i class="fa fa-question-circle"></i></span></th>
                  <th>Add/Remove Blocking <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="When you block an agency we will stop displaying your jobs for that agency and they wont be able to work on your roles and submit candidates"> <i class="fa fa-question-circle"></i></span> </th>
                  
                 
                </tr>
              </thead>
              
              
              <tbody>
                <?php
	    foreach($employers as $row){
		
		$tit= htmlspecialchars_decode($row['companyname'],ENT_QUOTES);
						$urlpart=str_replace(' ','-', strtolower ("$tit"));
						$urlpart=preg_replace('/[^A-Za-z0-9\-]/', '',$urlpart);
						$urlpart=str_replace('--','-', $urlpart);
						$pslstatus =0;
			//			var_dump(checkBlock($link,$mid,$cid,$row['recruiter'],$row['rcid']));
           $checkBlock = '<p><a class="title" href="#" data-toggle="modal" data-target="#block-modal" id='.$row['recruiter'].' data-show="true" data-id="'.$row['companyname'].'" data-value="'.$row['rcid'].'""> Block Agency</a>  ';
            if(checkBlock($link,$mid,$cid,$row['recruiter'],$row['rcid'])>0){
              $checkBlock = '<p><a class="title" href="#" data-toggle="modal" data-target="#block-modal" id='.$row['recruiter'].' data-show="false" data-id="'.$row['companyname'].'" data-value="'.$row['rcid'].'""> Remove Blocking</a>';

            }
		if (checkpsl($link,$mid,$cid,$row['recruiter'],$row['rcid'])>0)	{
		    $pslstatus =1;
		}else{
		    $pslstatus =0;
		}		
		$pslblock='<p><a class="title" href="#" data-toggle="modal" data-add="true" data-target="#psl-modal" id='.$row['recruiter'].' data-id="'.$row['companyname'].'" data-value="'.$row['rcid'].'""> Add to PSL</a> ';
		$pslremoveblock='<p><a class="title" href="#" data-toggle="modal" data-add="false" data-target="#psl-modal" id='.$row['recruiter'].' data-id="'.$row['companyname'].'" data-value="'.$row['rcid'].'""> Remove from PSL</a>';
			echo'
<tr>
<td><input type="checkbox" class="messageCheckbox" value="'.$row['recruiter'].'" name="allcheck"> '.$r['status'].' </td>

     <td>
		 <div class="btn-group">
		 <a class="title" href="../agency/'.$urlpart.'/'.$row['recruiter'].'" target="_blank" >'.$row['companyname'].'</a>
		 </div>
  </td>
  
  <td>
		 <div class="btn-group">
		 <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['recruiter'].' data-id="'.$row['companyname'].'">Rate</a>
		 </div>
		 </td>
		 
		 
  
  <td>
		 <div class="btn-group">
		 '.($pslstatus>0? $pslremoveblock :$pslblock).'
		 
		 </div>
  </td>
  
  <td>
		 <div class="btn-group">
'.$checkBlock .'
		
		 </div>
  </td>
  
</tr>';
}
?>
</tbody>
</table>
		 </div>
        </div>
      </section>
    </div>
  </div>
 </section>
       
     </div>
     </div>
     </div>
     <div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" id="feedback-modal-body">
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="psl-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" id="psl-modal-body">
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="block-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" id="block-modal-body">
        </div>
      </div>
    </div>
    <script>
    
$('#feedback-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
		element=$(e.relatedTarget),
            essayId = element.attr('id'),
			cname=element.data('id');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'feedback-modal.php',
            data: { EID : essayId,cname : cname},
            success: function(data) {
				$modal.find('#feedback-modal-body').html(data);
              
				
            }
        });
    });
	$('#psl-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
		element=$(e.relatedTarget),
            essayId = element.attr('id'),
			cname=element.data('id');
			rcid=element.data('value');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'psl.php',
            data: { rid : essayId,cname : cname,rcid : rcid,showAdd:element.data('add') },
            success: function(data) {
				$modal.find('#psl-modal-body').html(data);
              
				
            }
        });
    });
    
    $('#block-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
		element=$(e.relatedTarget),
            essayId = element.attr('id'),
			cname=element.data('id');
			rcid=element.data('value');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'block.php',
            data: { rid : essayId,cname : cname,rcid : rcid,show:element.data('show')},
            success: function(data) {
				$modal.find('#block-modal-body').html(data);
              
				
            }
        });
    });
    </script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");


$('#select-all').click(function(event) {
    if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = true;
      });
    }
    else {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = false;
      });
    }
  });


  $('#select-all-submit').click(function(event) {

let checked = [];
 $('input[name="allcheck"]:checked').each(function() {
   checked.push(this.value)
   return this.value;
});

if(checked.length > 0){
let url = "msg-recruiters?rids="+checked
 $(this).attr("href",url)
return true
}

return false;

  
  });
</script>
    
    </body>
</html>