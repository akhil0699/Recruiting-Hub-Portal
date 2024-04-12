<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
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
require_once '../config.php';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$res=mysqli_query($link,"select sector,id from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}
$content='';
$companyres = mysqli_query($link,"SELECT a.id as id,a.name as companyname, a.sectors as sectors,a.profile as companyprofile,b.firstname as name,a.address1,a.address2,a.address3,a.town,a.postcode,a.phone,a.benname,a.benbank,a.bensort,a.benacc,a.benswift,a.taxinfo,a.description,a.logo as logo,a.website,a.country,a.state, b.designation as designation,b.location as location,b.experience as experience FROM companyprofile a,members b WHERE a.id= b.companyid and b.memberid='$mid'"); 
$issubmitted = mysqli_num_rows($companyres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($companyres)){ 
		$company=$row;
				}
}
else
$content="No data.";
$tit= htmlspecialchars_decode($company['companyname'],ENT_QUOTES);
						$urlpart=str_replace(' ','-', strtolower ("$tit"));
						$urlpart=preg_replace('/[^A-Za-z0-9\-]/', '',$urlpart);
						$urlpart=str_replace('--','-', $urlpart);
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Best Practices | Recruiters Hub</title>
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
  </ul>
</div>


</div>

<div class="col-sm-10">
<div class="account-navi-content">
  <div class="tab-content">
 
	

    <div class="col-md-12 ui-sortable no-padding">
      

	<div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title"> Best Practices for Recruitment Vendors on our platform</h3>
      </header>
      <div class="panel-body">
              <div class="row results-table-filter">
                <div class="col-md-12">               
                    
                  </div>
                  
                  <p style = 'font-size: 16px;
            color: #a02121;'>We are an open source platform where employers post jobs and recruitment vendors supply candidates. Every client is different and there is no guarantee for a feedback and therefore we request you to submit only 1 or 2 candidates to qualify a client and their feedback timescales. Do not dump cvs and later chase clients for feedback. As soon as you click Engage button, the job will display the client details and you need to qualify client by checking their website before submitting any candidate.</p>


<p>If you have already registered, please check if you are receiving automated email notifications from RecruitingHub.com. Check both inbox and spam folder and if our emails are found in spam folder please mark it as not spam and move our emails to your inbox so you don't miss our important automatic notifications.</p>
  
  <p style = 'font-size: 16px;
            color: #a02121;'><b>IMPORTANT NOTE (Reg Feedback followups):</b> Our live chat tool is ONLY for technical support and NOT for chasing feedbacks for your CV submissions. You should use <b>ADD NOTES</b> from your submitted profile section (SUPPLY CANDIDATES on your left panel) to chase clients for feedback. Open the submitted profile and click <b>ADD NOTES</b> (an automated email will be sent to the registered email id of the client every time you use <b>ADD NOTES</b>). </p>
  
  We are providing you a FREE portal to reduce the gap between 1000's of Employers and 10's of 1000's of Recruitment Vendors in the market and help you earn placement fee on success (success means after candidate joining the employer). While all information about the job is provided by the client, at first, we recommend you to submit 1-2 CV's only for an engaged job to test the pulse of the client and DO NOT submit any more candidates if the client is not viewing the CV and not providing feedback. We DO NOT guarantee a feedback from the client for every submission of yours. What otherwise we have done to help solve this feedback issue is we have automated sending email notifications Mon-Fri to all clients that received cv's from vendors and did not update feedback. Client's do not have the option to turn off this automatic pending feedback reminder notifications as we believe the hard work of our vendors should not go waste. Beyond this if the client has not provided feedback we request you to STOP working for that client and the job will automatically be closed if no feedback is provided for a continuous period of 1 month. You have 6 months CV ownership over a submitted candidate if the client has viewed the cv and not duplicated and recruited the candidate within 6 months of cv submission. Our systems are built to maintain transparency and track every submission and therefore we can always go back to the client if this is the case and we can claim the credit for you.</p>
  
  <p>Your full time commitment to the platform will help you earn an average of $6000 per month per recruiter.</p>

<p><b>Q1.Understanding the role of our platform:</b></p>

<p><b>A.1.</b>We created Recruiting Hub to bridge the gap between the 1000’s of Corporates and 10’s of 1000’s of Recruitment Agencies and act as a medium (platform) to help companies post their jobs on our platform so our registered recruitment agencies can engage and supply candidates and earn their placement fee share. While every client (account) registered on our platform is verified and an Account Manager is tagged, we do not guarantee feedback for every profile you submit. Therefore, we recommend the agency to submit 1-2 cvs to begin with when you engage on a role from a new client and wait to test the pulse of the client and their TAT in updating feedback. Just so you know, our software sends automated email notification for every activity on the platform. E.g, when you submit a cv to a client, software sends automated email to the registered email of the client about your submission with all details and when they login to their employer account and view the cv, the status of your cv changes from “Yet to be viewed” to “Viewed” (with exact timestamp). When the client updates the feedback e.g Shortlist/CV Reject, you will get an automated email to your registered email about the status and so on. Also, while submitting a CV, the candidate that you are submitting to the client gets an one time automated email notification to the candidate email id about your submission with information like “Your CV has been submitted by “Agency Name” to “Client Name” for “Job Title” with a link to the job description so candidates can refer to the job description and prepare for the interview (if shortlisted). Please be reminded to inform the candidate to check their inbox/spam folder about the notification email as soon as you submit the profile to the client. </p>

<p><b>Q2.What makes my recruitment agency account successful?</b></p>

<p><b>A.2.</b> Recruitment is a tough business to be in and a bit of a gamble! We recommend continuous workflow and constant login to your recruiter account to look for new roles posted by clients and continuous submission of cvs elevates your chances of filling vacancies and increasing revenues. Please create cordial relationship with the Account Managers who may be able to guide you with the requirements and your chances of winning more deals. Please double validate cvs before submission. Please double check with candidates their interest levels and if they will join upon offer before submitting a candidate. Use <b>ADD NOTES</b> link available on every profile you submit to gently follow up with clients for feedback. An automatic email notification will be sent to the registered email id of the client with the content of your message every time you add a note. Keep adding notes if you don't get a feedback just to continously remind the client about yout submission.</p>

<p><b>Q.3.Using the live chat:</b></p>

<p><b>A.3.</b> Please do NOT use the live chat for feedback followups as it is unlikely we can support, instead ADD NOTES on profile you submitted. We are opening many branches now and will have more local offices to cater to clients. Please use live chat for product and payment related support.</p>


<p><b>Q.4.Client has not posted the salary range in the job?</b></p>

<p><b>A.4.</b> Some clients deliberately do not post the salary range and keep it open. In such cases please submit available candidates in the market keeping the range open. Try negotiating hard with candidates in the 1st place and do not over commit to candidates.</p>


<p><b>Q.5.Client marked my candidate duplicate, what should I do?</b></p>

<p><b>A.5.</b> It’s a bit tricky situation. 1st thing 1st, you need to be aware that our software does not allow duplicate submission (same candidate (email id) for same job (job id) from multiple agencies. It is advisable to get consent from candidate (Right to represent.- RTR - link to consent letter available in ADD NEW section). After submission if the client marks your candidate as duplicate, please phone the candidate and clarify this. Make sure to collect as much information as possible from candidate before you go back to client to argue about the duplicity. When genuinely you feel the candidate should NOT be marked as Duplicate by the client, use the ADD NOTES section in the profile by following the steps.<br><br>

▪Add RTR file of candidate by login in to your reruiter Portal -> Supply Candidates -> Candidates All -> Click Action against this candidate - Edit Profile -> Upload the consolidated pages/files as one file in Additional Doc section which you will see at bottom of the page and Save Changes and go on ADD NOTES section of the Candidate and type - i have uploaded the additional files for this candidate (RTR, right to represent) which we collected from candidate while submitting the profile when candidate confirmed that the candidate is made aware of this opportunity only by us and neither the employer directly or any other agency has not contacted the candidate prior to submission. This RTR file can be downloaded from your Employer profile under Additional file section of this candidate. <br><br>
▪An automated email will be sent to the client with the notes.<br><br>
We will also do our due diligence to sort this matter amicably. Also to note any CV submission that’s not duplicated by client has a CV ownership up till 6 months from the date of submissiom, therefore please be in constant touch with your candidates and in case found to be recruited by client without our notice we can claim the credit for our placement fee.</p>

<p><b>Q.6.What is my share on the placement fee?</b></p>

<p><b>A.6.</b> <b>Permanent Roles:</b> One time Placement fee - Effective from 1st July'21 all CV submissions by Agencies will be based on 60:40 split, 60% of the advertised one time fee (placement fee %/flat fee as displayed on every job) is paid to Agency by Recruiting Hub upon successful completion of the free replacement guarantee period (every job displays this guarantee period as agreed by RH with the client)<br><br>

<b>Contract Roles:</b> Monthly Ongoing as long as Contract duration - Standard 80:10:10 split on the day/hourly/monthly rate as advertised/offered as end rate by the Employer (Client) to RH - RH pays 80% of offered rate to Contractor (Candidate) and 20% is split 50:50 by agency (source) and Recruiting Hub (platform fee) payable monthly by RH to all parties involved. When you submit a candidate for a contract role, you will submit +20% of the candidate asking rate. e.g if the candidate asking rate is £300/day you will submit £360/day to Client. 20% margin can increase from case to case basis and what ever is the margin on top of candidate/contractor asking rate will be split 50:50 between RH and Agency (Source). We may use third party payroll companies in countries we do not have legal entities who will also get a small cut from our monthly share. We collect the monies from Client & distribute it basis on split fee ratio to agencies and contractors and keep with us our platform share.</p>


<p><b>Q.7.How do I contact the client directly:</b></p>

<p><b>A.7.</b>You are welcome to use the <b>ADD NOTES</b> or MESSAGING system available on your left panel to send messages to client directly. Please avoid sharing/asking for client contact details as any activity found that circumvents the marketplace services will be red flagged to our IT team and your account may be suspended. You are more than welcome to follow up with clients directly asking them to update feedback for your submitted cvs or interview scheduling related queries. Recruiters that use our <b>ADD NOTES</b> or MESSAGING system effectively have seen more success and also gets clients attention. Please draft your messages professionally/politely and do not send rude contents. A client once gone is gone for ever. Just so you know, sending messages using the <b>ADD NOTES</b> or MESSAGING system is as good as your emailing the client directly as the software sends automated email with content of your message directly to the registered email id of the client. Please keep sending gentle reminders as sometimes clients will miss to respond.</p>

<p><b>Q.8. I have made a placement and the candidate has joined, what should I do?</b></p>

<p><b>A.8.</b> Please go to SUPPLY CANDIDATES (STS) -> FILLED -> follow Invoicing Procedure link, Download Invoice format, and update your bank details in the link provided. Use the live chat to let us know your candidate DOJ (date of joining) in case it is not already updated. We will contact the client and update the offer/billable details and your placement fee share including the date on which you will receive payment from us.</p>

<p><b>Q.9. My Agency is growing and how do I add my recruiters on to my agency account?</b></p>

<p><b>A.9.</b> Login to your Recruiter account and click ADD USERS to create as many sub users as your want. There are no limitations to number of users.</p>

<p><b>Q.10. I also need Candidates, can Recruiting Hub help?</b></p>

<p><b>A.10.</b> Yes! We have an exclusive jobsite that we run only for our registered recruitment agencies. On your Recruiter login on the left panel you can find JOBSEEKER SECTION that you can use for free to advertise jobs that you are recruiting for and also SEARCH CV’s for free (candidates that registered on Recruiting Hub directly)</p> <br>

<p>We closely monitor the usage of our users and track the activity of recruiters while on our platform. We track the number of times you login, the pages you visit, the number of roles you engage, the number of cv’s you submit versus the engaged roles etc. Your recruiter agency account may be restricted if we find any suspicious activity on your account. We tell this to all our recruitment vendors that's trying to circumvent the marketplace services - WHY BITE THE HAND THAT FEEDS YOU?</p>

*Keep an eye on this page as we are adding more content on best practices. Wishing you the best with your recruiter account and wishing you make more placements and earn a lot of money through us*
 



</p>


                </form>
<!--Contact End-->

<!-- custom script -->


<script>

$(function() {

$('#submit_btn').click(function(){	

var name = $("input#your_name").val();

var cname = $("input#your_cname").val();

var email = $("input#your_email").val();

var mobile = $("input#your_mobile").val();

var city = $("input#your_city").val();

var country = $("input#your_country").val();

var linkedin = $("input#your_linkedin").val();

var message = $("textarea#message").val();





var name = $("input#your_name").val();

  		if (name == "") {

       // $("label#fname_error").show();

        $("input#your_name").focus();

        return false;

      }

var cname = $("input#your_cname").val();

  		if (cname == "") {

       // $("label#fname_error").show();

        $("input#your_cname").focus();

        return false;

      }

var mobile = $("input#your_mobile").val();

  		if (mobile == "") {

        //$("label#lname_error").show();

        $("input#your_mobile").focus();

        return false;

      }

var email = $("input#your_email").val();

  if (email.trim() == "") {

	$("span#your_email_error").removeAttr('style');

	$("input#your_email").focus();

	return false;

  } else {

	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

	if (!reg.test(email)) {

		$("span#your_email_error").removeAttr('style');

	  return false;

        }



	 } 

var city = $("input#your_city").val();

  		if (city == "") {

       // $("label#fname_error").show();

        $("input#your_city").focus();

        return false;

      }
      
var country = $("input#your_country").val();

  		if (country == "") {

       // $("label#fname_error").show();

        $("input#your_country").focus();

        return false;

      }

var linkedin = $("input#your_linkedin").val();

  		if (linkedin == "") {

       // $("label#fname_error").show();

        $("input#your_linkedin").focus();

        return false;

      }

var message = $("textarea#message").val();

  		if (message == "") {

       // $("label#message_error").show();

        $("textarea#message").focus();

        return false;

      }



	 

var dataString = 'name='+ name + '&cname='+ cname + '&mobile='+ mobile +'&email='+ email +'&city='+ city +'&country='+ country +'&linkedin='+ linkedin + '&message=' + message;

  $.ajax({

    type: "POST",

    url: "send-franchise.php",

    data: dataString,

    success: function(data) {

      //$('#contact_form').html("<span id='message'></span>");

	 if(data=="<div class='msg-sent'>Your Message has been sent Successfully!.</div>"){

      $('#contact_form').html(data)

      //.append("<p>Agent will be in touch soon.</p>")

      

	  }else{

	$('#contact_form').html(data);

	}

    }

	

  });

 //return false;

    });

});

</script>


                </div>
       </div>
       
	   </div>



   
  
  
  

</div>
       
	   </div>
  
  </div>
    <div class="col-md-5 column ui-sortable no-padding-right">
    
 
  </div>
</div>
</div>
</div>             

     <div class="clearfix"></div>
     </div>
     </div>
<div class="clearfix"></div>

</div>
</div>
</div>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
