<?php

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
	if(!mysql_select_db("CKXU")){echo "Auth Error";} // or die("<h1>Error ".mysql_errno() ."</h1><br />check access (privileges) to the SQL server db CKXU for this user <br /><br /><hr />Error details:<br />" .mysql_error() . "<br /><br /><a href=login.php>Return</a>");
}
else{
	echo 'ERROR!';
}
	$query = "Select * from playlist";
	if($from = $_GET['f']){
		if($limit = $_GET['l']){
			$query .= " limit " . $from . "," . $limit;
		}
	}
    $array_playlist = mysql_query($query);
	$i = 0;
	echo "<form action=\"submitPlaylist.php\" method=\"post\" accept-charset=\"utf-8\">
	<table>";
	echo "<tr>
				<th width=\"10%\">Playlist #</th><th width=\"25%\">Artist</th><th width=\"25%\">Album</th><th width=\"10%\">CanCon</th><th width=\"10%\">Label Size</th><th width=\"15%\">Genre</th><th width=\"5%\">Delete</th>
			</tr>";
	while($row = mysql_fetch_array($array_playlist)){
		//if($i%2){
			echo "<tr" . $row['number'] . ">";
		/*}
		else{
			echo "<tr style=\"background-color:blue\">";
		}
		*/
		
		echo "<td><input type=\"text\" style=\"width:99%;\" name=\"num[]\" value=\"" . $row['number'] . "\" /></td>";
		echo "<input type=\"hidden\" name=\"source[]\" value=\"" . $row['number'] . "\" />
			 <input type=\"hidden\" name=\"change[]\" value=\"false\">";
		echo "<td><input type=\"text\" style=\"width:99%;\" name=\"artist[]\" value=\"" . $row['Artist'] . "\" /></td>";
		echo "<td><input type=\"text\" style=\"width:99%;\" name=\"album[]\" value=\"" . $row['Album'] . "\" /></td>";
		echo "<td><select name=\"cancon[]\" style=\"width:99%;\">
				<option value=\"NC\" ></option>
				<option";
				if($row['cancon']=="CC"){
					echo " selected ";
				}
				echo " value=\"CC\" >Canadian</option>
				<option";
				if($row['cancon']=="AC"){
					echo " selected ";
				}
				echo " value=\"AC\" >Alberta</option>
				<option";
				if($row['cancon']=="LC"){
					echo " selected ";
				}
				echo " value=\"LC\" >Local</option>
				</select></td>";
		echo "<td><select name=\"label[]\" style=\"width:99%;\">
				<option";
				if($row['label']=="IL"){
					echo " selected ";
				}
				echo " value=\"IL\" >Independent</option>
				<option";
				if($row['label']=="SL"){
					echo " selected ";
				}
				echo " value=\"SL\" >Small</option>
				<option";
				if($row['label']=="ML"){
					echo " selected ";
				}
				echo " value=\"ML\" >Medium</option>
				<option";
				if($row['label']=="LL"){
					echo " selected ";
				}
				echo " value=\"LL\" >Large</option>
				</select></td>";
		echo "<td><input type=\"text\" style=\"width:99%;\" name=\"year[]\" value=\"" . $row['year'] . "\" /></td>";
		echo "<td><input type=\"checkbox\" style=\"width:99%;\" name=\"del[]\" value=\"" . $row['number'] . "\" /></td>";
		echo "</tr>";
		$i++;
	}
//Tie Braking, Independent, Small Label, Major Label
// How local get top preference, then AB, then, continental. then sub tie break of small, med, large
	echo "<table>";
	echo "<div id=\"foot\" name=\"listCon\">
		
		<table>
			<tr>
				<td>
				<input type=\"submit\" value=\"Submit\"/></form></td><td>
				<input type=\"button\" value=\"Refresh\" onClick=\"loadinit()\"></td><td>
				<form method=\"POST\" action=\"/masterpage.php\"><input type=\"submit\" value=\"Menu\"/></form>
				</td>
				<td width=\"100%\" align=\"right\"><img src=\"/images/ajax.png\" height=\"30px\" alt=\"AJAX Utilization\"/>
				<img src=\"/images/mysqls.png\" alt=\"MySQL Powered\"/></td>
			</tr>
		</table>
	</div>";
	mysql_close($con);
?>