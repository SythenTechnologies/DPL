<?php
      session_start();

function getBrowser()
     {
         $u_agent = $_SERVER['HTTP_USER_AGENT'];
		 $broswer = get_browser(null, true);
         $ub = '';
         if(preg_match('/MSIE/i',$u_agent))
         {
             $ub = "Internet Explorer";
         }
         else if(preg_match('/Firefox/i',$u_agent))
         {
             $ub = "Mozilla Firefox";
         }
         else if(preg_match('/Safari/i',$u_agent))
         {
             $ub = "Apple Safari";
         }
         else if(preg_match('/Chrome/i',$u_agent))
         {
             $ub = "Google Chrome";
         }
         else if(preg_match('/Flock/i',$u_agent))
          {
             $ub = "Flock";
         }
         else if(preg_match('/Opera/i',$u_agent))
         {
             $ub = "Opera";
         }
         else if(preg_match('/Netscape/i',$u_agent))
         {
             $ub = "Netscape";
         }
		 else{
		 	$ub = "Undefined";
		 }
         return $ub;
     }

$con = mysql_connect('localhost',$_SESSION['usr'],$_SESSION['rpw']);
if (!$con){
	echo 'Uh oh!';
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  username=' . $_SESSION["username"]);
	}
else if($con){
	if(!mysql_select_db("CKXU")){header('Location: /login.php');} // or die("<h1>Error ".mysql_errno() ."</h1><br />check access (privileges) to the SQL server db CKXU for this user <br /><br /><hr />Error details:<br />" .mysql_error() . "<br /><br /><a href=login.php>Return</a>");
}
else{
	echo 'ERROR!';
}

if($_SESSION['usr']=='user')
{
  header('location: /djhome.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>DPL Administration</title>
</head>
<body>
      <div class="topbar">
           User: <?php echo(strtoupper($_SESSION['fname'])); ?>
           </div>
        <table border="0" align="center" width="1000">
        <tr><td width="1000" colspan="4">
           <img src="/images/Ckxu_logo_PNG.png" alt="ckxu login"/>
        </td></tr>
        <tr><td width="1000" colspan="2" style="background-color:white;">
	<h2>Main Page</h2>
	<p>
	<?php
		echo "Welcome, " . strtoupper($_SESSION['account']);
	?>
	</p></td></tr>
        <!--<tr><td colspan="4" style="background-color:pink; font-size:12px;">
	MySQL Database Management must be done at the hosting location or via the MySQL Workbench
	</td></tr>-->
	<?php
             //echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
             $br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.
            //echo $br;

            if(ereg("opera", $br)) {
            echo "<!-- This browser has been verified to contain FULL SUPPORT for this page -->";
            	$ACCnew = FALSE;
				$ACCold = TRUE;
            }
			else if (ereg("chrome", $br)) {
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (getbrowser() == "Mozilla Firefox"){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (ereg("Apple Safari", $br)){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (getbrowser() == "Internet Explorer"){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
            else {
            	echo"<script>alert('Warning: Unsupported Browser');</script>";
              //header('Location: /browserUnsupported.php');
              //  header("location: alteredhomepage.php");
            }
        ?>
	<table border="0" width="1000" style="background-color:white;">
	<tr><td colspan="4" width="1000">
        <h2>Program Logs</h2>
        <h3>(Version 0.2)</h3><span style="font-size: 9px"><i>works with: Opera</i></span>
        </td></tr>
        <tr height="50" valign="middle">
                  <td width="450">
	            <!--<a href="Episode/p1insertep.php">New Program Log</a>-->
	            <button onclick="window.location.href='/Episode/EPV2/p1insert.php'" value="New Program Log">New Program Log</button>
	            <!--<button onclick="window.location.href='/Episode/EPV3/newLog.php'" value="New Program Log">Logs</button>-->
	     </td><td width="450">
	            <!--<a href="Episode/p1view.php">View Program Log</a>-->
	            <button onclick="window.location.href='/Episode/p1view.php'" value="View Program Log">View Program Log</button>
	     </td><td width="450">
	            <!--<a href="Episode/p1update.php">Edit Program Log</a>-->
	            <button onclick="window.location.href='/Episode/p1update.php'" value="Update Program Log">Update Program Log</button>
	     </td><td width="450">
	            <!--<a href="Episode/p1advremove.php">Remove Program Log</a>-->
	            <button onclick='window.location.href="/Episode/p1advremove.php"' value="Delete Program Log">Remove Program Log</button>
	           
	     </td></tr>
	 <tr><td colspan="4" width="1000">
     <h3>(Version 0.3)</h3><span style="font-size: 9px"><i>works with: IE, Safari, Firefox, Chrome</i></span>
        </td></tr>
        <tr height="50" valign="middle">
                  <td width="450">
	            <!--<a href="Episode/p1insertep.php">New Program Log</a>-->
	            <button onclick="window.location.href='/Episode/EPV3/logs.php'" <?php
	            	if($ACCnew != TRUE){
	            		 echo " disabled ";
	            	}
	            ?> value="New Program Log">Digital Program Logs</button>
	     </td></tr>
        <tr><td colspan="4">
        <hr /><h2>DJs / Hosts</h2>
        </td></tr><tr height="50" valign="middle">
               <td><button onclick='window.location.href="dj/p1newdj.php"' value="New Dj"> New DJ </button></td>
               <td><button onclick='window.location.href="dj/p1viewdj.php"' value="View Dj"> View DJ </button></td>
               <td><button onclick='window.location.href="dj/p1updatedj.php"' value="Update Dj"> Edit DJ </button></td>
               <td><button onclick='window.location.href="dj/p1remove.php"' value="Remove Dj"> Remove DJ </button></td>
        </tr>
	<tr><td colspan="4">
        <hr /><h2>Programs</h2>
        </td></tr>
              <tr height="50" valign="middle">
              	<td><button onclick='window.location.href="/program/p1insert.php"' value="New Program"> New Program </button></td>
	            <td><button onclick='window.location.href="/program/p1view.php"' value="View Program"> View Program </button></td>
	            <td><button onclick='window.location.href="/program/p1advupdate.php"' value="Edit Program"> Edit Program </button></td>
	            <td><button onclick='window.location.href="/program/p1remove.php"' value="Remove Program"> Remove Program </button></td>
             </tr>
             <tr height="50" valign="middle">
              	<td><button onclick='window.location.href="/Playlist/p1playlistmgr.php"' value="New Program"> Playlist Management </button></td>
	            <td><button onclick='window.location.href="/program/p1view.php"' value="View Program"> </button></td>
	            <td><button onclick='window.location.href="/program/p1advupdate.php"' value="Edit Program"> </button></td>
	            <td><button onclick='window.location.href="/program/p1remove.php"' value="Remove Program"> </button></td>
             </tr>
    <tr><td colspan="4">
        <hr /><h2>Commercials</h2>
        </td></tr>
              <tr height="50" valign="middle">
              	<td><button onclick='window.location.href="/Advertisements/p1advins.php"' value="New Commercial"> New Commercial </button></td>
	            <td><button onclick='window.location.href="/Advertisements/p1update.php"' value="View Commercials"> View Commercial </button></td>
	            <td><button onclick='window.location.href="/Advertisements/p1update.php"' value="Edit Commercial"> Edit Commecrial</button></td>
	            <td><button disabled="true" onclick='window.location.href="/program/p1update.php"' value="Remove Commercial"> Remove Commercial </button></td>
             </tr>
              <tr><td colspan="4">
        <hr /><h2>Users &amp; Membership</h2>
        </td></tr>
              <tr height="50" valign="middle">
              	<td><button onclick='alert("USE Active Directory Administrative Center on ckxuradio.su.uleth.ca \n\n [Remote Desktop: 142.66.48.28]");' value="New Users">New Users</button></td> <!--window.location.href="/Security/p1NewUser.php"-->
	            <td><button disabled onclick='window.location.href="/program/p1view.php"' value="View Commercials">  </button></td>
	            <td><button disabled onclick='window.location.href="/program/p1advupdate.php"' value="Edit Commercial"> Edit/Reset User </button></td>
	            <td><button disabled onclick='window.location.href="/program/p1update.php"' value="Remove Commercial"> Remove User </button></td>
             </tr>
        <tr><td colspan="4">
        <hr /><h2>Operations and Information</h2>
        </td></tr>
             <tr height="50" valign="middle">
                    <!--<td width="200"><a href="/station/p1create.php">Enter New Station</a></td>
                    <td width="200"><a href="/station/p1updatestation.php">Modify Station Information</a></td>
                    <td><button onclick='window.location.href="/station/p1viewstation.php"' value="Audit">View Station</button></td>-->
                    <td><button onclick='window.location.href="/station/p1settings.php"' value="Audit">Update Settings &amp; Information</button></td>
                    <td><button onclick='window.location.href="/station/Socan.php"' value="Socan">Socan &amp; Resound Audits</button></td>
                    <td><button onclick='window.location.href="Reports/Manual Logs.pdf"' value="MNL">Manual Log PDF</button></td>
                     <td><button onclick='window.location.href="Reports/Stats.php"' value="STAT">Statistics</button></td>
                    <!--
                    <td width="200"><a href="/station/p1remove.php">Remove Station</a></td>-->
             </tr>
        <tr><td colspan="4">
        <hr />  <h2>Reports</h2>
        </td></tr>
             <tr height="50" valign="middle">
                    <!--<td width="200"><a href="/PlayRep.php">Playlist Report</a></td>
                    <td width="200"><a href="/Top15Rep.php">Top 15 Report</a></td> -->
                 <td style="color: #4cff00"><button onclick="window.location.href='Reports/V3/Reports.php'">Reports (V3)</button></td>
                    <td><button onclick='window.location.href="/Episode/EPV3/Audit.php"' value="Audit">Audit</button></td>
                    <td><button onclick='window.location.href="Reports/PlaylistRep.php"' value="Audit">Charts</button></td>
                    <td><button onclick='window.location.href="Reports/MissingLogRep.php"' value="Audit">Missing Log</button></td>
                    <td><button onclick='window.location.href="Reports/p1SongSearch.php"' value="Audit">Song Search</button></td>
             </tr>
             <?php
             /*if(mysql_select_db("posts")){
	             echo "<tr><td colspan=\"4\">
	             <hr /><h2>Post Administration</h2>
	             </td></tr>
	             <tr height=\"50\" valign=\"middle\">
	                    <td width=\"200\"><button onclick='window.location.href=\"/Poster/p1new.php\"' value=\"New Post\">New Post</button></td>
	                    <!--<td width=\"200\"><a href=\"/Poster/p1update.php\">Edit Post</a></td>
	                    <td width=\"200\"><a href=\"/Poster/p1Archive.php\">Archive Post</a></td>
	                    <td width=\"200\"><a href=\"/Poster/p1Remove.php\">Remove Post</a></td>-->
	                    <td></td><td>"//<button onclick='window.location.href=\"/Poster/p1Update.php\"' value=\"Update Post\">Update Post</button>
	                    . "</td><td><button onclick='window.location.href=\"/Poster/p1Remove.php\"' value=\"Delete Post\">Remove Post</button></td>
	             </tr>";
			}*/
             ?>
             <tr>
             <td colspan="4" height="20">
             <hr/>
             </td>
             </tr>
             <tr>
             <td>
             <form name="logout" action="/logout.php" method="POST">
                   <input type="submit" value="Logout">
             </form>
             </td>
             <td colspan="2"></td>
             <td style="text-align:right;">
        <span>Version 0.5.04  </span><img src="/images/mysqls.png" alt="MySQL Powered"> 
        </td></tr>
        </table>
        </table>
</body>
</html>