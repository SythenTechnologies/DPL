<?php session_start(); ?>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>DPL Administration</title>
</head>
<html>
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
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  username=' . $_SESSION["username"]);
	}
else if($con){
        if(!mysql_select_db("CKXU")){header('Location: /login.php');} // or die("<h1>Error ".mysql_errno() ."</h1><br />check access (privileges) to the SQL server db CKXU for this user <br /><br /><hr />Error details:<br />" .mysql_error() . "<br /><br /><a href=login.php>Return</a>");
        $callsql="SELECT callsign, stationname from STATION order by callsign";
        $callresult=mysql_query($callsql,$con);

        $calloptions="";//<OPTION VALUE=0>Choose</option>";
        while ($row=mysql_fetch_array($callresult)) {
            $name=$row["stationname"];
            $callsign=$row["callsign"];
            $calloptions.="<OPTION VALUE=\"$callsign\">".$name."</option>";
        }

        $djsql="SELECT * from DJ order by djname";
        $djresult=mysql_query($djsql,$con);

        $djoptions="";//<OPTION VALUE=0>Choose</option>";
        while ($djrow=mysql_fetch_array($djresult)) {
            $Alias=$djrow["Alias"];
            $name=$djrow["djname"];
            $djoptions.="<OPTION VALUE=\"$Alias\">".$name."</option>";
        }
		
		$coresult=mysql_query($djsql,$con);
        $cooptions="//<OPTION VALUE=0>None</option>";
        while ($corow=mysql_fetch_array($djresult)) {
            $Alias=$corow["Alias"];
            $name=$corow["djname"];
            $cooptions.="<OPTION VALUE=\"$Alias\">".$name."</option>";
        }
		
		$GENRE = "SELECT * from GENRE order by genreid asc";
		$GENRES = mysql_query($GENRE);
		$genop = "";//<OPTION VALUE=\"NULL\">Select Genre</option>";
		while ($genrerow=mysql_fetch_array($GENRES)) {
            $GENid=$genrerow["genreid"];
            $genop.="<OPTION VALUE=\"" . $GENid . "\">". $GENid ."</option>";
        }
        ?>

        <table align="left" border="0" height="100">
        <tr><td colspan="7">
        <h2>New Program</h2>
        </td></tr>

        <tr><th colspan="2" width="250">
        Program Name
        </th><th width="200">
        Station
        </th><th width="200">
        Length (min)
        </th><th width="250">
        Syndicate Source
        </th><th width="200">
        Host
        </th>
        </tr>
             <form name="newprog" action="/program/p2insert.php" method="post">
             <tr>
             <td colspan="2">
                 <input name="pname" required="true" type="text" size="30" autofocus/>
             </td>
             <td>
                 <select name="callsign">
                         <?php echo $calloptions;?>
                 </select>
             </td>
             <td>
                 <input name="length" required="true" type="text" size="15"/>
             </td>
             <td>
                 <input name="syndicate" type="text" size="35"/>
             </td>
             <td>
                 <select name="dj1">
                         <?php echo $djoptions;?>
                 </select>
             </td>
        </tr>
        <tr>
        	<td colspan="1">
        		<select name="genre">
        			<?php echo $genop;?>
        		</select>
        	</td>
        	<td colspan="6" align="right">
                <input type="submit" value="Create" />
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