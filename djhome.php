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

/*if($_SESSION['usr']=='user')
{
  header('location: episode/p1insertEP.php');
}*/
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>DPL User</title>
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
	<?php
             //echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
             $br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.
            //echo $br;

            if(ereg("opera", $br)) {
              //echo 'Browser Supported';
            //    header("location: originalhomepage.php");
            echo "<!-- This browser has been verified to contain FULL SUPPORT for this page -->";
            	$ACCnew = TRUE;
				$ACCold = TRUE;
            }
			else if (ereg("chrome", $br)) {
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (ereg("Mozilla Firefox", $br)){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (ereg("Apple Safari", $br)){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			else if (getBrowser()=="Internet Explorer"){
				$ACCnew = TRUE;
				$ACCold = FALSE;
			}
			 // following block access revoked at Program Directors Request
            /*else if(ereg("chrome", $br)) {
              echo "<!-- This browser has been verified to contain PARTIAL SUPPORT for this page -->";
            }*/
            else if(getBrowser()=="Mozilla Firefox") {
              echo "<!-- This browser has been verified to contain PARTIAL SUPPORT for this page -->";
				$ACCnew = TRUE;
				$ACCold = FALSE;
            }
            else {
              // header('Location: /browserUnsupported.php');
              //  header("location: alteredhomepage.php");
            }
        ?>
	<table border="0" width="1000" style="background-color:white;">
	<tr><td colspan="4" width="1000">
        <h2>Program Logs</h2>
        <?php
        	if($ACCold != TRUE){
        		 echo " <!-- ";
				header("location:/Episode/EPV3/logs.php");
        	}
        ?>
        <h3>(Version 0.2)</h3><span style="font-size: 9px"><i>works with: Opera</i></span>
        </td></tr>
        <tr height="50" valign="middle">
                  <td width="450">
	            <button onclick="window.location.href='/Episode/EPV2/p1insert.php'" <?php
	            	if($ACCold != TRUE){
	            		 echo " disabled ";
	            	}
	            ?>value="New Program Log">New Program Log</button>
	     </td><td width="450">
	            <button onclick="window.location.href='/Episode/p1update.php'"<?php
	            	if($ACCold != TRUE){
	            		 echo " disabled ";
	            	}
	            ?> value="View Program Log">Retrieve Program Log</button>
	     </td><td width="450">
	            
	     </td></tr>
	 	<?php
	    if($ACCold != TRUE){
	    	echo " --> ";
	    }
		?>
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
        <hr /><h2>Account Maintenance</h2>
        </td></tr><tr height="50" valign="middle">
               <td><button onclick='window.location.href="dj/p1viewdj.php"' value="Update Account" disabled="true">Update Account</button></td>
        </tr>
	<tr><td colspan="4">
        <hr /><h2>Program Maintenance</h2>
        </td></tr>
              <tr height="50" valign="middle">
	            <td><button onclick='window.location.href="/program/p1view.php"' value="View Program" disabled="true">Update Program</button></td>
             </tr>
        <!--<tr><td colspan="4">
        <hr />  <h2>Reports</h2>
        </td></tr>
             <tr height="50" valign="middle">
                    <td width="200"><a href="/PlayRep.php">Playlist Report</a></td>
                    <td width="200"><a href="/Top15Rep.php">Top 15 Report</a></td> 
                    <td><button onclick='window.location.href="/Episode/p1Audit.php"' value="Audit">Audit</button></td>
                    <td><button onclick='window.location.href="Reports/PlaylistRep.php"' value="Audit">Charts</button></td>
             </tr>-->
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
             <td colspan=""></td>
             <td style="text-align:right;">
             	<span>Version 0.5.15 </span>
        <img src="/images/mysqls.png" alt="MySQL Powered"> 
        </td></tr>
        </table>
        </table>
</body>
</html>