<?php session_start(); 

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
?>
<!DOXTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>DPL Administration</title>
</head>
<body>
      <div class="topbar">
           Welcome, <?php echo(strtoupper($_SESSION['usr'])); ?>
           </div>

      <table border="0" align="center" width="1000">
      <tr>
           <td align="center"><img src="/images/Ckxu_logo_PNG.png" alt="ckxu"/></td>
      </tr>
      <tr style="background-color:white;">
      <td>
<?php

$con = mysql_connect('localhost',$_SESSION['usr'],$_SESSION['rpw']);
if (!$con){
	echo 'Uh oh!';
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  

username=' . $_SESSION["username"]);
	}
else if($con){
        if(!mysql_select_db("CKXU")){header('Location: /login.php');} // or die("<h1>Error ".mysql_errno() ."</h1><br />check access (privileges) to the SQL server db CKXU for this user <br /><br /><hr />Error details:<br />" .mysql_error() . "<br /><br /><a href=login.php>Return</a>");
        $callsql="SELECT callsign, stationname from STATION order by callsign";
        $callresult=mysql_query($callsql,$con);

        $calloptions="<option value=%>Any Station</option>";
        while ($row=mysql_fetch_array($callresult)) {
            $name=$row["stationname"];
            $callsign=$row["callsign"];
            $calloptions.="<OPTION VALUE=\"$callsign\">".$name."</option>";
        }

        $djsql="SELECT * from DJ order by djname";
        $djresult=mysql_query($djsql,$con);

        $djoptions="<option value=\"%\">Any Host</option>";//<OPTION VALUE=0>Choose</option>";
        while ($djrow=mysql_fetch_array($djresult)) {
            $Alias=$djrow["Alias"];
            $name=$djrow["djname"];
            $djoptions.="<OPTION VALUE=\"$Alias\">" . $name . "</option>";
        }
        ?>

        <table align="left" border="0" height="100">
        <tr><td colspan="7">
        <h2>Remove Program</h2>
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
        <tr><th width="25%" colspan="2">
        Program Name [% is wildcard]
        </th><th width="20%">
        Station Callsign
        </th><th width="20%">
        Length (min)
        </th><th width="25%">
        Syndicate Source
        </th><th width="10%">
        Host
        </th>
        </tr>
             <form name="selections" action="p2remove.php" method="post">
             <tr>
             <td colspan="2">
                 <input name="name" type="text" size="30" value="%"/>
             </td>
             <td>
                 <select name="callsign">
                         <?php echo $calloptions;?>
                 </select>
             </td>
             <td>
                 <input name="length" type="text" size="15" value="%"/>
             </td>
             <td>
                 <input name="syndicate" type="text" size="35" value="%"/>
             </td>
             <td>
                 <select name="dj1">
                         <?php echo $djoptions;?>
                 </select>
             </td>
            <td colspan="7">
                <input type="submit" value="Search" />
                </form>
            </td>
        </tr>

        <?php

}
else{
	echo 'ERROR!';
}

echo '<tr height="20"><td colspan="7" style="text-align:bottom;"><hr/></td></tr>';

?>
<tr>
        <td>
        <form name="logout" action="/logout.php" method="POST">
              <input type="submit" value="Logout">
        </form>
        </td>
        <td>
        <form name="return" action="/masterpage.php" method="POST">
              <input type="submit" value="Return">
        </form>
        </td>
        <td colspan="4"></td>
        <td style="text-align:right;">
        <img src="/images/mysqls.png" alt="MySQL Powered" />
        </td>
        </tr>
        
        </table>
        </td>
        </tr>
        </table>
</body>
</html>