<?php
    session_start();

$con = mysql_connect('localhost',$_SESSION['usr'],$_SESSION['rpw']);
if (!$con){
	echo 'Uh oh!';
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  

	username=' . $_SESSION["username"]);
}
else if($con){
	if(!mysql_select_db("CKXU")){header('Location: /login.php');}
	/*$GENRE = "SELECT * from GENRE order by genreid asc";
	$GENRES = mysql_query($GENRE,$con);
	$genop = "<OPTION VALUE=\"%\">Select Genre</option>";
	while ($genrerow=mysql_fetch_array($GENRES)) {
        $GENid=$genrerow["genreid"];
        $genop.="<OPTION VALUE=\"" . $GENid . "\">". $GENid ."</option>";
    }*/
    $catop =  "<option value=\"53\">53, Sponsored Promotion</option>
	           <OPTION value=\"52\">52, Sponsor Indentification</OPTION>
	           <OPTION VALUE=\"51\" selected=\"true\">51, Commercial</OPTION>
	           <option value=\"45\">45, Show Promo</option>
	           <option value=\"44\">44, Programmer/Show ID</option>
	           <option value=\"43\">43, Station ID</option>";
			   
	$POSTED = FALSE;
	if($_POST){
		$POSTED=TRUE;
		$INSad1 = "insert into adverts (";
		$INSad2 = ") values (";
		$append = false;		   
		if(isset($_POST['name'])){
			$append = TRUE;
			$INSad1 .= "AdName";
			$INSad2 .= "'".addslashes($_POST['name'])."'";
			$advertiser = $_POST['name'];
		}
		if(isset($_POST['category'])){
			if($append==TRUE){
				$INSad1 .= ",Category";
				$INSad2 .= ",'" . addslashes($_POST['category'])."'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "Category";
				$INSad2 .= "'".addslashes($_POST['category'])."'";
			} 
		}
		if(isset($_POST['length'])){
			if($append==TRUE){
				$INSad1 .= ",length";
				$INSad2 .= ",'" . addslashes($_POST['length'])."'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "length";
				$INSad2 .= "'".addslashes($_POST['length'])."'";
			}
			$length = $_POST['length'];
		}
		if(isset($_POST['language'])){
			if($append==TRUE){
				$INSad1 .= ",language";
				$INSad2 .= ",'" . addslashes($_POST['language'])."'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "language";
				$INSad2 .= "'".addslashes($_POST['language'])."'";
			}
			$language = $_POST['language'];
		}
		if(isset($_POST['dstart'])){
			if($append==TRUE){
				$INSad1 .= ",StartDate";
				$INSad2 .= ",'" . addslashes($_POST['dstart'])."'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "StartDate";
				$INSad2 .= "'".addslashes($_POST['dstart'])."'";
			}
			$startdate = $_POST['dstart'];
		}
		if($_POST['dend']!=""){
			if($append==TRUE){
				$INSad1 .= ",EndDate";
				$INSad2 .= ",'" . addslashes($_POST['dend'])."'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "EndDate";
				$INSad2 .= "'".addslashes($_POST['dend'])."'";
			}
			$enddate = $_POST['dend'];
		}
		if(isset($_POST['active'])){
			if($append==TRUE){
				$INSad1 .= ",Active";
				$INSad2 .= ",'0'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "Active";
				$INSad2 .= "'0'";
			}
			$active = TRUE;
		}
		else{
			if($append==TRUE){
				$INSad1 .= ",Active";
				$INSad2 .= ",'1'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "Active";
				$INSad2 .= "'1'";
			}
			$active = false;
		}
		if(isset($_POST['friend'])){
			if($append==TRUE){
				$INSad1 .= ",Friend";
				$INSad2 .= ",'0'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "Friend";
				$INSad2 .= "'0'";
			}
			$friend = TRUE;
		}
		else{
			if($append==TRUE){
				$INSad1 .= ",Friend";
				$INSad2 .= ",'1'";
			}
			else{
				$append = TRUE;
				$INSad1 .= "Friend";
				$INSad2 .= "'1'";
			}
			$friend = false;
		}
		$PLMIN = mysql_fetch_array(mysql_query("select MIN(Playcount) from adverts where Category='" . addslashes($_POST['category']) . "'"));
		$INSad = $INSad1 . $INSad2 . ")";
		if(!mysql_query($INSad)){
			echo mysql_error();
			echo "<br/>";
			echo $INSad;
			$ADIDNUM=mysql_insert_id($con);
		}
		else{
			$ADIDNUM=mysql_insert_id($con);
			if(!mysql_query("update adverts set Playcount=Playcount-".$PLMIN['MIN(Playcount)']." where Category='" . addslashes($_POST['category']) . "'")){
				echo mysql_error();	
				echo "<br/>";
				echo mysql_error();
			}
		}
	}
?>

<!DOCTYPE HTML>
<head>
<link rel="stylesheet" type="text/css" href="/altstyle.css" />
<title>Advertisement Administration</title>
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
		<h2>New Advertiser</h2>
	</div>
	<div id="content">
		<form name="search" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" class="tablecss">
			<tr>
				<th>
					<a onclick='javascript: alert("Unique Identifier for a entered Advertisement. Generated by system")'>Ad Number</a>
				</th>
				<th>
					<a onclick='javascript: alert("Required Field\n\nDefines the name of the advertised, this is what programmers (DJs) will see when logging their show")'>Advertiser</a>
				</th>
				<th>
					<a onclick='javascript: alert("Required Field\n\nEnter the category that the advertisement falls within. If the ad is used within two categories there must be two separate entries")'>Category</a>
				</th>
				<th>
					<a onclick='javascript: alert("Required Field\n\nEnter the length of the Advertisement/Commercial in seconds \n\nRequired for Ad Time reporting")'>Length</a>
				</th>
				<th>
					<a onclick='javascript: alert("Defaults to English \n\nSpecifies the language that is used in the advertisement")'>Language</a>
				</th>
				<th>
					<a onclick='javascript: alert("Defaults to current Server Date \n\nEnter date you want this advertisement to become available \n\nNOTE: The ads will become available midnight of the selected date [00:00]")' >Start</a>
				</th>
				<th>
					<a onclick='javascript: alert("Leave blank to set no end date \n\nOtherwise enter date you want this advertisement end being available \n\nNOTE: The ads will run until midnight of the selected date [00:00]")' >End</a>
					<!--<a onclick='javascript: document.getElementByID("dends").selected=" "'>clear</a>-->

				</th>
				<th>
					<a onclick='javascript: alert("Defaults to English \n\nSpecifies if a advertisement is available for play (advertisement needs to be within available time and Active to be visible to programmers [DJs])")'>Active</a>
				</th>
				<th>
					<a onclick='javascript: alert("Defaults to English \n\nSpecifies if a advertisement is available for play (advertisement needs to be within available time and Active to be visible to programmers [DJs])")'>Friend</a>
				</th>
			</tr>
			<tr>
				<td>
					<input name="adnum" size="10" type="text" readonly="true" <?php if($POSTED==TRUE){
						echo " value=\"" . $ADIDNUM . "\" ";
					}
					else{
						echo " value=\"N/A\" ";
					}?> />
				</td>
				<td>
					<input name="name" type="text" size="25%" required="true" <?php
					if(isset($advertiser)){
						echo "value=\"".$advertiser."\" ";
					}
					?>/>
				</td>
				<td>
					<select name="category">
						<?php echo $catop;?>
					</select>
				</td>
				<td>
					<input name="length" type="number" maxlength="4" max="9999" min="0" size="8" value="30" required="true"/>
				</td>
				<td>
					<input name="language" type="text" maxlength="25" size="15" <?php
					if(isset($language)){
						echo "value=\"".$language."\" ";
					}
					else{
						echo "value=\"English\" ";
					}
					?>/>
				</td>
				<td>
					<input name="dstart" type="date" <?php
					if(isset($startdate)){
						echo "value=\"".$startdate."\" ";
					}
					else{
						echo "value=\"".date("Y-m-d")."\" ";
					}
					?> />
				</td>
				<td>
					<input name="dend" id="dends" type="date" <?php
					if(isset($enddate)){
						echo "value=\"".$enddate."\" ";
					}
					else{
						echo "value=\"\" ";
					}
					?>/>
				</td>
				<td>
					<input name="Active" type="checkbox" checked value="1" />
				</td>
				<td>
					<input name="Friend" type="checkbox" checked value="1" />
				</td>
			</tr>
		</table>
		
		</div>
	<div id="foot">
		<table>
			<tr>
				<td>
				<?php if(!$POSTED){ echo "<input type=\"submit\" value=\"Submit\"/>";}?></form></td><td>
				<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>"><input type="submit" value="Reset" /></form></td><td>
				<form method="POST" action="/masterpage.php"><input type="submit" value="Menu"/></form>
				</td>
				<td width="100%" align="right"><img src="/images/mysqls.png" alt="MySQL Powered"/></td>
			</tr>
		</table>
	</div>
	<div id="content">
			<h4>Help</h4>
		<span>Clicking the Title of the entry box will assist with a definition of the field as well if it is required and any defaults</span>
		
	</div>
	
<?php

}
else{
	echo 'ERROR!';
}
?>
</body>
</html>