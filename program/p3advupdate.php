<?php
    session_start();
?>
<!DOCTYPE HTML>
<head>
<link rel="stylesheet" type="text/css" href="/altstyle.css" />
<title>DPL Administration</title>
</head>
<html>
<body>
	<div class="topbar">
           Welcome, <?php echo(strtoupper($_SESSION['usr'])); ?>
    </div>
	<div id="header">
		<a href="/masterpage.php"><img src="/images/Ckxu_logo_PNG.png" alt="CKXU" /></a>
	</div>
	<div id="top">
		<h2>Edit Program</h2>
	</div>
	<div id="content">
		<table border="0" class="tablecss">
			<!--<tr><td colspan="100%"><h2>*** Work in Progress ***</h2></td></tr>-->
			
				<th width="300">
					Program Name
				</th>
				<th width="100px">
					Genre
				</th>
				<th width="50px">
					Length
				</th>
				<th width="200px">
					Syndicate
				</th>
				<!--<th width="300px">
					Hosts
				</th>-->
				<th width="30px">
					Active
				</th>
				<th width="100px">
					Callsign
				</th>
				<th width="100px">
					
				</th>
			</tr>
			

<?php

$con = mysql_connect('localhost',$_SESSION['usr'],$_SESSION['rpw']);
if (!$con){
	echo 'Uh oh!';
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  

	username=' . $_SESSION["username"]);
}
else if($con){
	if(!mysql_select_db("CKXU")){header('Location: /login.php');}	

	if(isset($_POST['namex'])){
		$PROGNAME = addslashes($_POST['namex']);
		$CALLS = addslashes($_POST['callsign']);
	}
	else if(isset($_POST['postval'])){
		$PIECES = explode("@&", $_POST['postval']);
		$PROGNAME = addslashes($PIECES['0']);
		$CALLS = addslashes($PIECES['1']);
	}
	else if(isset($_GET['resource'])){
		$PIECES = explode("@", $_GET['resource']);
		$PROGNAME = addslashes($PIECES['0']);
		$CALLS = addslashes($PIECES['1']);
	}
	else{
		$PROGNAME = addslashes($_POST['name']);
		$CALLS = addslashes($_POST['callsign']);
	}
	
	
	if(isset($_POST['changed'])){
			//$djsarc[] = $_POST['dj[]'];
			$CHNA = "Update program SET programname='" . addslashes($_POST['name']) . "' where programname='" . $PROGNAME . "' and callsign='" .$CALLS . "' ";
			if(!mysql_query($CHNA)){
				if(mysql_errno()==1451)
				{
					echo "<span style=\"background-color:red; color:white;\"><strong>Error 1451</strong><br />Logs have been entered using this program name<br />
					You must change this program to inactive and enter a new show to change the name with the logs in the archive</span>";
				}
				else{
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
			else{
				$PROGNAME = addslashes($_POST['name']);
			}
			
			// UPDATE EXCLUSIONS
			if(isset($_POST['EXCP'])){
				$PLEXC = "Update program SET PLX='" . addslashes($_POST['PLX']) . "' where programname='" . $PROGNAME . "' and callsign='" .$CALLS . "' ";
			}
			else{
				$PLEXC = "Update program SET PLX='-1' where programname='" . $PROGNAME . "' and callsign='" .$CALLS . "' ";
			}
			mysql_query($PLEXC);
			if(isset($_POST['EXCC'])){
				$CCEXC = "Update program SET CCX='" . addslashes($_POST['CCX']) . "' where programname='" . $PROGNAME . "' and callsign='" .$CALLS . "' ";
			}
			else{
				$CCEXC = "Update program SET CCX='-1' where programname='" . $PROGNAME . "' and callsign='" .$CALLS . "' ";
			}
			mysql_query($CCEXC);
			
			// UPDATE GENRE
			$CHGEN = "Update program SET genre='" . $_POST['genre'] . "' where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			if(!mysql_query($CHGEN)){
				if(mysql_errno()==1451)
				{
					echo "<span style=\"background-color:red; color:white;\"><strong>Error 1451</strong><br />Logs have been entered using this program name<br />
					You must change this program to inactive and enter a new show to change the name with the logs in the archive</span>";
				}
				else{
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
			
			$CHLEN = "Update program SET length='" . $_POST['length'] . "' where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			if(!mysql_query($CHLEN)){
				if(mysql_errno()==1451)
				{
					echo "<span style=\"background-color:red; color:white;\"><strong>Error 1451</strong><br />Logs have been entered using this program name<br />
					You must change this program to inactive and enter a new show to change the name with the logs in the archive</span>";
				}
				else{
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
			
			$CHSYN = "Update program SET syndicatesource='" . $_POST['syndicate'] . "' where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			if(!mysql_query($CHSYN)){
				if(mysql_errno()==1451)
				{
					echo "<span style=\"background-color:red; color:white;\"><strong>Error 1451</strong><br />Logs have been entered using this program name<br />
					You must change this program to inactive and enter a new show to change the name with the logs in the archive</span>";
				}
				else{
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
			if(isset($_POST['Active'])){
				$CHACT = "Update program SET Active='1' where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			}
			else{
				$CHACT = "Update program SET Active='0' where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			}
			if(!mysql_query($CHACT)){
				if(mysql_errno()==1451)
				{
					echo "<span style=\"background-color:red; color:white;\"><strong>Error 1451</strong><br />Logs have been entered using this program name<br />
					You must change this program to inactive and enter a new show to change the name with the logs in the archive</span>";
				}
				else{
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
			
			$remove = "Delete from Performs where programname='" . $PROGNAME . "' and callsign='" . $CALLS . "' ";
			//$remove = "Update Performs SET ENdate='" . date("Y-m-d H:i:s") . "' where programname'" . $PROGNAME . "' and callsign='" . $CALLS . "' and ENdate like '%' ";
			$runrem = FALSE;
			foreach ($_POST['dj'] as $value) {
				//echo $value;
				if($value=='-1'){
					$runrem=TRUE;
				}
				else{
					$remove .= " and Alias!='" . $value . "' ";
				}
			}
			if($runrem==TRUE){
				if(!mysql_query($remove)){
					if(mysql_errno()==1451)
					{
						echo "Logs have been entered using this program name<br />
						You must change this program to inactive and enter a new show to change the name with the logs in the archive";
					}
					else{
						echo mysql_errno() . "<br />". mysql_error();
					}
				}
			}
			
		if(isset($_POST['djadd'])){
			if($_POST['djadd']!='0'){
				$DJI = "Insert into Performs (callsign, programname, Alias, STdate) values ('" . $CALLS . "', '" . $PROGNAME . "' , '" . $_POST['djadd'] . "' , '" . date('Y-m-d H:i:s') . "' ) ";
				if(!mysql_query($DJI)){
					echo mysql_errno() . "<br />". mysql_error();
				}
			}
		}
		
	}
	
		$djsql="SELECT * from DJ where active!='0' order by djname";
    $djresult=mysql_query($djsql,$con);

    $djoptions="<option value=\"0\" selected=\"true\">New Host</option>";//<OPTION VALUE=0>Choose</option>";
    while ($djrow=mysql_fetch_array($djresult)) {
        $Alias=$djrow["Alias"];
        $name=$djrow["djname"];
        $djoptions.="<OPTION VALUE=\"" . $Alias . "\">" . $name . "</option>";
    }
	/*$SELDJ = "Select * from performs where programanme='" . addslashes($_POST['name']) . "' and callsign='" . addslashes($_POST['callsign']) . "' ";
	$CURRENTDJS = mysql_query($SELDJ);*/
	
	
	$SQLA = "Select PROGRAM.* from PROGRAM where program.programname LIKE '" . $PROGNAME. "' ";
	// build query
	if(isset($_POST['callsign'])){
		$SQLA .= "and program.callsign LIKE '" . $CALLS . "' ";
	}
	/*if(isset($_POST['dj1'])){
		if($_POST['dj1']!='0'){
			$SQLA .= "and performs.Alias LIKE '" . addslashes($_POST['dj1']) . "' ";
		}
	}*/
	/*if(isset($_POST['dj2'])){
		if($_POST['dj2']!='0')
		{
			$SQLA .= "and performs.CoAlias LIKE '" . addslashes($_POST['dj2']) . "' ";
		}
	}*/
	if(isset($_POST['length'])){
		$SQLA .= "and program.length LIKE '" . addslashes($_POST['length']) . "' ";
	}
	if(isset($_POST['syndicate'])){
		$SQLA .= "and program.syndicatesource LIKE '" . addslashes($_POST['syndicate']) . "' ";
	}
	if(isset($_POST['genre'])){
		$SQLA .= "and program.genre LIKE '" . addslashes($_POST['genre']) . "' ";
	}
	$SQLA .= " order by programname";
	
	$result = mysql_query($SQLA) or die(mysql_error());
             if(mysql_num_rows($result)=="0"){
               echo '<tr><td colspan="100%" style="background-color:yellow;">';
               echo 'No Results Found';
               echo '</tr></td>';
			   echo $SQLA;
             }
             else{
	$PRONAME = "";
	$CALLS = "";
               while($row=mysql_fetch_array($result)) {       	
		/*echo "<form name=\"row\" action=\"/program/p3advupdate.php\" method=\"POST\"><tr>
				<td>";*/
		echo "<form name=\"row\" action=\"/program/p3advupdate.php\" method=\"POST\"><tr>
				<td>";
				echo "<input name=\"name\"  value=\"" . $row['programname'] . "\" size=\"55\" maxlength=\"75\"/>";
				echo "<input name=\"namex\" value=\"" . $row['programname'] . "\" hidden />";
		echo "</td>
				<td>";
				//echo $row['genre'];
				echo "<input name=\"genre\" value=\"" . $row['genre'] . "\" hidden />";
				$GENRE = "SELECT * from GENRE order by genreid asc";
				$GENRES = mysql_query($GENRE,$con);
				$genop = "";
				while ($genrerow=mysql_fetch_array($GENRES)) {
			        $GENid=$genrerow["genreid"];
			        $genop.="<OPTION VALUE=\"" . $GENid . "\" ";
			        if( $GENid == $row['genre']){
			        	$genop.=" Selected=\"true\" ";
					}
			        $genop.= ">". $GENid ."</option>";
			    }
				echo "<select name=\"genre\"> 
				" . $genop . "
					</select>";
				
		echo "</td>
				<td>";
				//echo $row['length'];
				echo "<input type=\"text\" name=\"length\" value=\"" . $row['length'] . "\" maxlength='3' size='5' />";
				echo "<input type=\"text\" name=\"lengthx\" value=\"" . $row['length'] . "\" hidden />";
					
		echo "</td>
				<td>";
				//echo $row['syndicatesource'];
				echo "<input type=\"text\" name=\"syndicate\" value=\"" . $row['syndicatesource'] . "\" size=\"35\" />";
				echo "<input type=\"text\" name=\"syndicatex\" value=\"" . $row['syndicatesource'] . "\" hidden />";
				
		echo "</td>
				<td>";
					//echo $row['active'];
					echo "<input type=\"checkbox\" name=\"Active\" ";
					if($row['active']==0){
						echo " />"; 
					}
					else{
						echo "checked=\"1\" />"; 
					}
					
					echo "<input type=\"text\" name=\"Activex\" value=\"" . $row['active'] . "\" hidden />";
		echo "</td>
				<td>";
				echo $row['callsign'];
				echo "<input type=\"text\" name=\"callsign\" value=\"" . $row['callsign'] . "\" hidden />";	
		//echo "</td><td><input type=\"submit\" value=\"select\"/> </td></tr></form>";
		$PRONAME = $row['programname'];
		$CALLS = $row['callsign'];
			   }
		}

}
else{
	echo 'ERROR!';
}
?>
</table>
	<?php
		$sqlmaxpl = "select HitLimit, CCX, PLX, genre from program where programname=\"" . $PROGNAME . "\" and callsign=\"". $CALLS . "\" ";
			if(!$limits = mysql_fetch_array(mysql_query($sqlmaxpl))){
				echo mysql_error();
			}
		$sqlgenq2 = "select * from genre where genreid=\"" . $limits['genre'] .  "\" ";
			if(!$GENREQ = mysql_fetch_array(mysql_query($sqlgenq2))){
				echo mysql_error();
			}	
	 ?>
<table border="0" class="tablecss" style="text-align: center;">
<tr>
	<th>Playlist</th>
	<th>CanCon</th>
	<th><input type="checkbox" id="ck1" name="EXCP" <?php
	if($limits['PLX']!='-1'){
		echo " checked=\"true\" ";
		}
	?>/>
		<label for="ck1">Playlist Exception</label></th>
	<th><input type="checkbox" id="ck2" name="EXCC" <?php
	if($limits['CCX']!='-1'){
		echo " checked=\"true\" ";
		}
	?>/>
		<label for="ck2">CanCon Exception</label></th>
	<th>Hit Limit</th>
		
</tr>
<tr>
	<td>
		<input type="number" readonly="true" value="<?php
			echo $GENREQ['playlist'] . "\" ";
			if($limits['PLX']!='-1'){
				echo " disabled=\"true\" ";
			}
			?> />
	</td>
	<td>
		<input type="number" readonly="true" value="<?php
				echo $GENREQ['cancon']. "\" ";
			if($limits['CCX']!='-1'){
				echo " disabled=\"true\" ";
			}
			?>" />
	</td>
	<td>
		<input type="number" maxlength="3" max="99" min="0" size="12" name="PLX" value="<?php
			if($limits['PLX']!='-1'){
				echo $limits['PLX'];
			}
			else{
				echo " ";
			}
		?>"/>
	</td>
	<td>
		<input type="number" maxlength="3" max="99" min="0" size="12" name="CCX" value="<?php
			if($limits['CCX'] != '-1'){
				echo $limits['CCX'];
			}
			else{
				echo " ";
			}
		?>"/>
	</td>
	<td>
		<input type="number" maxlength="3" max="99" min="0" size="12" name="MHL" required="true" value="<?php
			echo $limits['HitLimit'];
		?>"/>
	</td>
</tr>
</table>
<table border="0" class="tablecss">
<tr>
	<th colspan="100%">Hosts</th>
</tr>
<tr colspan="100%">
	<?php 
		//$DJSQL
		//$CURRENT =   
		$SQDJ = "select Alias from PERFORMS where programname=\"" . addslashes($PRONAME) . "\" and callsign=\"" . addslashes($CALLS) . "\"";
		if(!($perfres = mysql_query($SQDJ))){
			echo mysql_error();
		}
		else{
			//$alias=mysql_fetch_array($perfres);				
			//echo $alias['Alias'];
			$size = mysql_num_rows($perfres);
			echo "<td colspan=\"". $size . "\">";
			//echo $size;
			$djexc[] = NULL; 
			$counter = 0;
			while($alias=mysql_fetch_array($perfres)){
				//echo "<td>" . $alias['Alias'] . "</td>";
				//echo "<input type=\"text\" name=\"djx[]\" hidden=\"true\" value=\"" . $alias['Alias'] . "\" />";
				echo "<select name=\"dj[]\">";
				$TEML = "<option value=\"-1\">Remove Host</option>";
				$TEMDJ = mysql_query($djsql);
				while ($djrow=mysql_fetch_array($TEMDJ)) {
        			$Arc=$djrow["Alias"];
        			$name=$djrow["djname"];
					for($cc=0; $cc<=$counter ; $cc++){
						//if($djexc[$cc]!=$Alias){
							$TEML .= "<option value=\"".$Arc . "\"";
							if($Arc == $alias['Alias']){
								$TEML .= " Selected=\"True\" ";
								$djexc[$counter]=$Arc;
								//++$counter;
							}
							$TEML .= " \" >" . $name . "</option>";
						//}
					}
    			}
				echo $TEML;	
				echo "</select>
				";
			}
			
		}
	?>
			<select name="djadd">
				<?php echo $djoptions; ?>
			</select>
		</td>
</td></tr>
</table>
		
		</div>
	<div id="foot">
		<table>
			<tr>
				<td>
					<input name="changed" value="true" hidden="true" />
					<input type="submit" value="Submit Changes"></form></td><td>
					<form action="/program/p1advupdate.php" method="POST">
				<input type="submit" value="Advanced Search"/></form></td><td>
					<form action="/program/p1update.php" method="POST">
				<input type="submit" disabled="true" hidden="true" value="Standard Search"/></form></td><td>
				<!--<input type="button" value="Reset" disabled="true" onClick="window.location.reload()"></td><td>-->
					<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
						<input type="text" hidden="true" value="<?php echo $PROGNAME . "@&" . $CALLS ?>" name="postval"/>
						<input type="submit" value="Reset" />
					</form></td><td>
				<form method="POST" action="/masterpage.php"><input type="submit" value="Menu"/></form>
				</td>
				<td width="100%" align="right"><img src="/images/mysqls.png" alt="MySQL Powered"/></td>
			</tr>
		</table>
	</div>

</body>
</html>