<?php

function getBrowser()
     {
         $u_agent = $_SERVER['HTTP_USER_AGENT'];
         $ub = '';
         if(preg_match('/MSIE/i',$u_agent))
         {
             $ub = "Internet Explorer";
         }
         elseif(preg_match('/Firefox/i',$u_agent))
         {
             $ub = "Mozilla Firefox";
         }
         elseif(preg_match('/Safari/i',$u_agent))
         {
             $ub = "Apple Safari";
         }
         elseif(preg_match('/Chrome/i',$u_agent))
         {
             $ub = "Google Chrome";
         }
         elseif(preg_match('/Flock/i',$u_agent))
          {
             $ub = "Flock";
         }
         elseif(preg_match('/Opera/i',$u_agent))
         {
             $ub = "Opera";
         }
         elseif(preg_match('/Netscape/i',$u_agent))
         {
             $ub = "Netscape";
         }
         return $ub;
     }
     

      session_start();

//if($_SESSION['usr']=='user')
//{
  //header('location: login.php');
//}
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

        $program = "select * from program where active='1' order by programname";
        $prog=mysql_query($program,$con);

        $options="<OPTION VALUE=0>Select Your Show [REQUIRED]</option>";
        while ($row=mysql_fetch_array($prog)) {
            $name=$row["programname"];
//            $callsign=$row["callsign"];
//            $alias=$row["Alias"];
            $options.="<OPTION VALUE=\"".$name."\">".$name."</option>";
        }
/*
        $calls = "select * from STATION order by callsign";
        $stat=mysql_query($calls,$con);

        $stations="";//<OPTION VALUE=0>Choose</option>";
        while ($cal=mysql_fetch_array($stat)) {
            $name=$cal["stationname"];
            $callsign=$cal["callsign"];
//            $alias=$row["Alias"];
            $stations.="<OPTION VALUE=\"$callsign\">".$name."</option>";
        }*/
?>
<!DOCTYPE HTML>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>New Log</title>
</head>
<html>
<body>
      <div class="topbar">
           Welcome, <?php echo(strtoupper($_SESSION['usr'])); ?>
           </div>
        <table border="0" align="center" width="1350">
        <tr><td width="1350" colspan="4">
           <img src="/images/Ckxu_logo_PNG.png" alt="ckxu login"/>
        </td></tr>
        <tr><td width="1350" colspan="2" style="background-color:white;">
	<h2>New Program Log</h2>
	<script>
		function ChSub(VAL)
		{
			if(VAL!=0){
				document.getElementById("SM").disabled = false;
			}
			else{
				document.getElementById("SM").disabled = true;
			}
		}
		
		function SetTimeLess(){
			
		}
	</script>
	</td></tr>
        <?php
             //echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
             $br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.
            //echo $br;

            if(ereg("opera", $br)) {
              //echo 'Browser Supported';
            //    header("location: originalhomepage.php");
            echo "<!-- This browser has been verified to contain FULL SUPPORT for this page -->";
            }
            else if(ereg("chrome", $br)) {
              echo "<tr><td colspan=\"100%\>
              <h3 width=\"100%\" style=\"background-color:yellow; color:black;\"><strong>NOTICE: Google Chrome has limited support on this site,<br />
              please launch or download a browser that supports HTML5</strong></h3>
              </td></tr>";
            //    header("location: originalhomepage.php");
            }
            else if(getBrowser()=="Mozilla Firefox") {
              echo "<tr><td colspan=\"100%\>
              <h3 width=\"100%\" style=\"background-color:yellow; color:black;\"><strong>NOTICE: " . getBrowser() . " has limited support on this site,<br />
              please launch or download a browser that supports HTML5</strong></h3>
              </td></tr>";
            }
            else {
              header('Location: /browserUnsupported.php');
              //  header("location: alteredhomepage.php");
            }
        ?>
        <table width="1350" style="background-color:white;">
        <tr><th width="5%">
        Air Date
        </th><th width="6%">
        Air Time
        </th><th width="14%">
        Program
        </th><!--<th width="5%">
        Station
        </th>--><th width="50%">
        Description
        </th><th width="9%">
        Timeless/PreRecord
        </th><th width="8%">
        Record Date
        </th><th width="5%">

        </th>
        </tr>
        <form name="form1" method="post" action="p2insertEP.php">
        <tr><td valign="top">
        <?php
        //date_default_timezone_set("UTC");
        if(ereg("opera", $br)) {
	    echo "<input type=\"date\" name=\"user_date\" value=\"". 
					date('Y-m-d') . "\" />";
        }
        else
        {
             echo "<input type=\"text\"  size=\"12\" name=\"user_date\" required=\"true\" value=\"yyyy-mm-dd\"/>";
			 
        }

        echo "</td><td valign=\"top\">";
        if(ereg("opera", $br)) {
	    echo "<input type=\"time\" name=\"user_time\" value=\"" . date('H') . ":00\" />";
			
        }
        else
        {
             echo "<input type=\"text\"  size=\"12\" name=\"user_time\" value=\"hh:00\"/>";
        }
        ?>
        </td><td valign="top">
             <select name="program" onchange="ChSub(this.selectedIndex)">
                         <?php echo $options;?>
             </select>
        </td><!--<td valign="top">
             <select name="station">
                         <?php echo $stations;?>
             </select>
        </td>--><td valign="top">
             <input type="text" name="description" size="90"/>
        </td><td valign="top">
	    <input type="checkbox" name="enprerec"/>  <!--Should NEVER be set to prerecord by default EVER, in a million years, DO NOT CHANGE!-->
        </td><td valign="top">
        <?php
        if(ereg("opera", $br)) {
	    echo "<input type=\"date\" name=\"prdate\" />";
        }
        else
        {
             echo "<input type=\"text\" size=\"15\" name=\"prdate\" value=\"yyyy-mm-dd\" />";
        }
             //<input type="date" name="prdate"/>
        ?>

        </td><td>
        <input type="submit" id="SM" disabled value="Submit" />
        </td></tr>
        </form>

        <tr><td colspan="7" height="20">
        <hr />
        </td></tr>
        <tr><td>
        <?php
          if($_SESSION['usr']=='user')
          {
            //echo "<a href=\"/logout.php\" >Exit Discarting any Unsaved Changes</a>";
            echo "<form name=\"exit\" action=\"/VERLogout.php\" method=\"POST\">
            <input type=\"submit\" value=\"Logout\"></form></td><td colspan=\"5\">
            <form name=\"refresh\" action=\"/Episode/p1insertEP.php\" method=\"POST\">
            <input type=\"submit\" value=\"Refresh\"></form>
            ";
          }
          else
          {
            echo "<form name=\"exit\" action=\"/logout.php\" method=\"POST\">
            <input type=\"submit\" value=\"Logout\"></form></td><td>
            <form name=\"exit\" action=\"/masterpage.php\" method=\"POST\">
            <input type=\"submit\" value=\"Return\"></form></td><td colspan=\"4\">
            <form name=\"refresh\" action=\"/Episode/p1insertEP.php\" method=\"POST\">
            <input type=\"submit\" value=\"Refresh\"></form>
            ";
          }
        ?>
        </td><td>
        <img src="/images/mysqls.png" alt="MySQL Powered" />
        </td></tr>

        </table>
</body>
</html>