<?php
      session_start();

$con = mysql_connect('localhost',$_SESSION['usr'],$_SESSION['rpw']);
if (!$con){
	echo 'Uh oh!';
	die('Error connecting to SQL Server, could not connect due to: ' . mysql_error() . ';  username=' . $_SESSION["username"]);
	}
else if($con){
	if(!mysql_select_db("CKXU")){header('Location: /logout.php');}
}
else{
	echo 'ERROR! cannot obtain access... this terminal may not be authorised for access';
}
	$error = array();
	$warning = array();
	
	//##########################//
	// Check Switch Status      //
	//##########################//
	$switchqu = "select * from switchstatus ORDER BY ID DESC limit 1 ";
	$switchre = mysql_query($switchqu);
	$switchArray = mysql_fetch_array($switchre);
	$broadcastcheck = $switchArray['Bank1'];
	$RadioDJ = substr($broadcastcheck, -16 , 1 );
	$booth1 = substr($broadcastcheck, -14 , 1 );
	$booth2 = substr($broadcastcheck, -12, 1 );
	if($RadioDJ == "1"){
		array_push($warning,"<strong><br/>Warning: At " . substr($switchArray['timestamp'],-8,5) . " the 24 Hour system was live to air<br/><br/></strong>");
	}
	// END Switch Check
	
        $SHOWQ = "select callsign from program where programname='" . addslashes($_POST['program']) . "' ";
        $SHOWQU = mysql_query($SHOWQ,$con);
        $CALLROWS = mysql_fetch_array($SHOWQU);
        $CALLSHOW = $CALLROWS["callsign"];

        $INSEPSEL = "select * from episode where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' order by date";
        $RESEPSEL=mysql_query($INSEPSEL,$con);
        $EPINFO=mysql_fetch_array($RESEPSEL);
        
		$SETTINGS = mysql_fetch_array(mysql_query("SELECT * FROM station WHERE callsign='".$CALLSHOW."' "));
		
		
		//echo $_POST['title'];
        if(mysql_numrows($RESEPSEL)=="0"){
        	if($_POST['brType']>0){
        		$inep = "insert into episode (callsign, programname, date, starttime, prerecorddate, description) values ( '" . addslashes($CALLSHOW) . "', '" . addslashes($_POST['program']) . "', '" . addslashes($_POST['user_date']) . "', '" . addslashes($_POST['user_time']) . "', '" . addslashes($_POST['prdate']) . "', '" . addslashes($_POST['description']) . "' )";
        	}
		  else if(!isset($_POST['enprerec']))
          {
            $inep = "insert into episode (callsign, programname, date, starttime, description) values ( '" . addslashes($CALLSHOW) . "', '" . addslashes($_POST['program']) . "', '" . addslashes($_POST['user_date']) . "', '" . addslashes($_POST['user_time']) . "', '" . addslashes($_POST['description']) . "' )";
            
          }
          else
          {

            $inep = "insert into episode (callsign, programname, date, starttime, prerecorddate, description) values ( '" . addslashes($CALLSHOW) . "', '" . addslashes($_POST['program']) . "', '" . addslashes($_POST['user_date']) . "', '" . addslashes($_POST['user_time']) . "', '" . addslashes($_POST['prdate']) . "', '" . addslashes($_POST['description']) . "' )";
          }
            if(!mysql_query($inep,$con))
            {
              echo 'SQL Error<br />';
              echo mysql_error() . "<br/>";
			  echo $inep . "<br/>";
            }
		$RESEPSEL=mysql_query($INSEPSEL,$con);
		$EPINFO=mysql_fetch_array($RESEPSEL);
		  
        }
        else if(mysql_numrows($RESEPSEL)>"1"){
          echo 'warning, multiple episodes with same information!';
        }
        $program = "select * from performs order by programname";
        $prog=mysql_query($program,$con);
        
        $options="";
        while ($row=mysql_fetch_array($prog)) {
            $name=$row["programname"];
            $options.="<OPTION VALUE=\"".$name."\">".$name."</option>";
        }
        if(!isset($_POST['title'])){
          //echo 'no title';
        }
        else{
          if($_POST['title']!=""){
			//echo "<p>VER NOT EMPTY</p>";
              //dynamic SQL CREATION
              
              $indyns = "insert into SONG (callsign, programname, date, starttime";
              $BUFFS = "'" . addslashes($CALLSHOW) . "' , '" . addslashes($_POST['program']) . "' , '" . addslashes($_POST['user_date']) . "' , '" . addslashes($_POST['user_time']) . "'";
              if (isset($_POST['instrumental'])){
                $indyns.=", instrumental";
                $BUFFS.=", '1' ";
              }
              if ($_POST['time']!=""){
                $indyns.=", time";
                $BUFFS.=", '" . addslashes($_POST['time']) . "' ";
              }
			  
              if (isset($_POST['title'])){
                	if($_POST['cat']=="51"){
                		$QRR = mysql_fetch_array(mysql_query("select AdName, Language from adverts where AdId='".addslashes($_POST['title'])."' "));
						$BUFFS.=", '" . $QRR['AdName'] . "' ";
                	}
					else{
						$BUFFS.=", '" . addslashes($_POST['title']) . "' ";
					}
                $indyns.=", title";
              }
              if (isset($_POST['album'])){
                $indyns.=", album";
                $BUFFS.=", '" . addslashes($_POST['album']) . "' ";
              }
              if (isset($_POST['composer'])){
                $indyns.=", composer";
                $BUFFS.=", '" . addslashes($_POST['composer']) . "' ";
              }
              if (isset($_POST['note'])&&$_POST['note']!=""){
                $indyns.=", note";
                $BUFFS.=", '" . addslashes($_POST['note']) . "' ";
              }
			  if ($_POST['spokenmin']!=""){
                $indyns.=", Spoken";
                $BUFFS.=", '" . addslashes($_POST['spokenmin']) . "' ";
              }
              if (isset($_POST['artist'])){
                $indyns.=", artist";
                $BUFFS.=", '" . addslashes($_POST['artist']) . "' ";
              }
              if (isset($_POST['cancon'])){
                $indyns.=", cancon";
                $BUFFS.=", '1' ";
              }
              if ($_POST['playlist']!=""){
                $indyns.=", playlistnumber";
                $BUFFS.=", '" . addslashes($_POST['playlist']) . "' ";
              }
              if (isset($_POST['cat'])){
              	if($_POST['cat']=='51'){
              		if(isset($_POST['AdNum'])){
              								
							// UPDATE Playcount
							$SPupSQL = "select SponsId from program where programname='" . addslashes($_POST['program']) . "' and callsign='" . addslashes($CALLSHOW) . "' and SponsId is not null";
							if(!$SPup = mysql_query($SPupSQL)){
								array_push($error, mysql_errno() . "</td><td>" . mysql_error()); 
							}
							//echo mysql_num_rows($SPup);
							if(mysql_num_rows($SPup)==0){
		              			$UPAD = "update adverts set Playcount=Playcount+1 where AdId=\"" . $_POST['AdNum'] . "\" ";
								$ADQN = mysql_query("select XREF from adverts where AdId='" . $_POST['AdNum'] . "' and XREF IS NOT NULL");
								if(mysql_num_rows($ADQN)!=0){
									$XREF=mysql_fetch_array($ADQN);
									$UPXREF = "update adverts set Playcount=Playcount+1 where AdId=\"" . $XREF['XREF'] . "\" ";
								}
								/*else{
								 	//Not Required to report as many ads do not have XREF
									//array_push($error, mysql_errno() . "</td><td>" . mysql_error());
									array_push($error,"999</td><td> XREF not Defined (ignore for now)"); 
								}*/
									// SET FLAG IF NOT AVAILABLE
		              			$result_Flag = mysql_query("select Playcount from adverts where AdId='" . addslashes($_POST['AdNum']) . "' and Category='51'");
		              			$FlCheck = mysql_fetch_array($result_Flag);
								//echo $FlCheck['Playcount'];
								$Sel51Flag = $minplaysql51 = "select MIN(Playcount) from adverts where Category='51' and Active='1' and Friend='1' ";
								$Min51Flag = mysql_query($Sel51Flag);
								$flagLevel = mysql_fetch_array($Min51Flag);
								//echo $flagLevel['MIN(Playcount)'];
								if($FlCheck['Playcount']>$flagLevel['MIN(Playcount)']){
									$indyns.=", AdViolationFlag";
		                			$BUFFS.=", '1' ";
								}
								
								if(!mysql_query($UPAD)){
									echo "AD ERROR".mysql_error();
								}
								else{
									if($UPXREF!=""){
										if(!mysql_query($UPXREF)){
											echo $UPXREF;
											echo "XREF ERROR:" . mysql_error();
										}
									}
								}
							}
							
					}
					/*else {
						$UPAD = "update adverts set Playcount=Playcount+1 where AdName LIKE \"%" . $_POST['title'] . "%\" and Category!='51'";
					}*/
					
					//echo $UPAD;
					
              	}
                $indyns.=", category";
                $BUFFS.=", '" . addslashes($_POST['cat']) . "' ";
              }
              if (isset($_POST['hit'])){
                $indyns.=", hit";
                $BUFFS.=", '1' ";
              }
              $BUFFS.=" )";
              $indyns.=") values ( ";
              $DYNAMIC = $indyns . $BUFFS;
			  //echo $DYNAMIC;
              if(!mysql_query($DYNAMIC,$con))
              {
                echo 'SQL Error<br />';
                echo mysql_error();
              }
              else //This is executed if the song is inserted
              {
              			$LASTLINK =  mysql_insert_id($con);
			  			if(!isset($QRR['Language'])){
			  				$LANGIN = addslashes($_POST['lang']);
						}
						else{
							$LANGIN = $QRR['Language'];
						}
                          $langDef = "insert into language values ('" . addslashes($CALLSHOW) . "', '". addslashes($_POST['program']) ."', '" . addslashes($_POST['user_date']) . "', '". addslashes($_POST['user_time']) . "', '" . addslashes($LASTLINK) . "', '" . $LANGIN . "')";
                          if(!mysql_query($langDef,$con))
                          {
                              echo 'SQL Error, Language Insertion<br />';
                              echo mysql_error();
                          }
              }
            }
        }

		$SQLProg = "SELECT Genre.*, Program.length from Genre, Program where Program.programname=\"" . addslashes($_POST['program']) . "\" and program.callsign=\"" . addslashes($CALLSHOW) . "\" and Program.genre=Genre.genreid";
		if(!($result = mysql_query($SQLProg))){
			echo "Program Error 001 " . mysql_error();
		}
		if(!($Requirements = mysql_fetch_array($result))){
			echo "Program Error 002 " . mysql_error();
		}
		$SQL2PR = "SELECT * from Program where programname=\"" . addslashes($_POST['program']) . "\" and callsign=\"" . addslashes($CALLSHOW) . "\" ";
		if(!($result2 = mysql_query($SQL2PR))){
			echo "Program Error 003 " . mysql_error();
		}
		if(!($Req2 = mysql_fetch_array($result2))){
			echo "Program Error 004 " . mysql_error();
		}
		
		if($Req2['CCX']!='-1'){
			$CC = ceil($Req2['CCX'] * $Requirements['length'] / 60);
		}
		else{
			$CC = ceil($Requirements['cancon'] * $Requirements['length'] / 60);
		}
		if($Req2['PLX']!='-1'){
			$PL = ceil($Req2['PLX'] * $Requirements['length'] / 60);
		}
		else{
			$PL = ceil($Requirements['playlist'] * $Requirements['length'] / 60);
		}
		
		//$PL = ceil($Requirements['playlist'] * $Requirements['length'] / 60);
		$CLA = $Requirements['genreid'];
		if(!isset($CLA)){
			$CC = "0";
			$PL = "0";
			$CLA = "Not Set";
		}
		
		// COUNT CANCON
		$SQLCOUNTCC = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and cancon='1' ";
		$resultCC = mysql_query($SQLCOUNTCC);
		$RECCC = mysql_num_rows($resultCC);
		
		// COUNT PLAYLIST
		$SQLCOUNTPL = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and playlistnumber IS NOT NULL ";
		if($SETTINGS['ST_PLLG']=='1'){
			$SQLCOUNTPL .="group by playlistnumber";	
		}
		$resultPL = mysql_query($SQLCOUNTPL);
		$RECPL = mysql_num_rows($resultPL);
		
		//COUNT ADS
		$SQLCOUNT51 = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and category='51' and AdViolationFlag is null";
		$result51 = mysql_query($SQLCOUNT51);
		$REC51 = mysql_num_rows($result51);
		
		//COUNT PSA
		$SQLCOUNTPROMO = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and category='45'";
		$SQLCOUNTPSA = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and category like '1%' and title like '%PSA%' ";
		//$SQLCOUNTPSA = "Select songid from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and category like '1%' and title like '%Promo%' ";
		$resultPSA = mysql_query($SQLCOUNTPSA);
		$resultPROMO = mysql_query($SQLCOUNTPROMO);
		$RECPSA = mysql_num_rows($resultPROMO);
		$RECPSA += mysql_num_rows($resultPSA);
?>
<!DOCTYPE HTML>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	<script src="/js/jquery-blockui.js"></script>
	<script type="text/javascript"> 
 
    // unblock when ajax activity stops 
    //$(document).ajaxStop($.unblockUI); 
 
    function test() { 
        $.ajax({ url: 'wait.php', cache: false }); 
    } 
 
    $(document).ready(function() { 
        /*$('input[name=sub]').click(function() { 
            $.blockUI({ message: '<h1><img src="/images/GIF/ajax-loader1.gif" />Processing...</h1>' }); 
            //test(); 
            //$.blockUI({ message: '<h1><image src="/images/GIF/ajax-loader1.gif"/>Processing...</h1>' }); 
        	setTimeout(function() { 
            	$.unblockUI({ 
                	onUnblock: function(){ alert('The server was unable to process your request in a reasonable time. \nPlease resubmit your data'); } 
            	}); 
        	}, 4000);
        }); */
        $('form').submit(function() { 
            $.blockUI({ message: "<h1 style='width:width: max-content; text-align: center;' >Processing...</h1><progress id='pb_form_submit'></progress>" }); 
            //test(); 
            //$.blockUI({ message: '<h1><image src="/images/GIF/ajax-loader1.gif"/>Processing...</h1>' }); 
        	setTimeout(function() { 
            	$.unblockUI({ 
                	onUnblock: function(){ alert('The server was unable to process your request in a reasonable time.'); } 
            	}); 
        	}, 4000);
        }); 
    }); 
 
</script> 
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>Log Addition</title>
</head>
<body onload="load()" 
<?php 
if(false){
	echo "onunload=\"return confirm('WARNING: Unfinalized Episode\\n\\nThis episode is not finalized. Are you sure you want to exit?')\" ";
}
?>
>
	 <script type="text/javascript" src="../TPSBIN/JS/Episode/V2CoreJS.js"></script>
      <div class="topbar">
           User: <?php echo(strtoupper($_SESSION['usr'])); ?>
           </div>

        <table border="0" align="center" width="1354">
        <tr><td colspan="6">
           <img src="/images/Ckxu_logo_PNG.png" alt="ckxu" height="90px"/>
        </td>
        <td width="200" id="switchstat" colspan="1">
        	<iframe src="/Episode/EPV3/Switch.php" height="90" width="100%" seamless="seamless" style="border:none">Iframe Not Supported</iframe>
        </td></tr>
        <tr><td width="800px" colspan="1" style="background-color:white;">
	<h2>Program Log Addition</h2></td>
	<?php
	echo "</td><td width=\"500px\"  style=\"background-color:white;\">";
	echo "Show Classification:  <strong>" . $CLA . "</strong>";
	echo "</td><td width=\"500px\" ";
		
		if(isset($Req2['SponsId'])){
			echo "style=\"background-color:".$SETTINGS['ST_ColorNote'].";\" /><span>Sponsor : ";
			$SPONS_SQL = " select * from adverts where AdId='".$Req2['SponsId']."' ";
			$SPONS = mysql_fetch_array(mysql_query($SPONS_SQL));
			echo $SPONS['AdName'];
		}
		else{
			echo " style=\"background-color:white\" /><span>Sponsor :  None ";
		}
		"</span>";
		
	// #################### ADS  ##################
	$ADS = ceil(($Requirements['length']*$SETTINGS['ST_ADSH'])/60);
	echo "</td><td width=\"250px\" style=\"background-color:";
	if($REC51<$ADS){
		echo $SETTINGS['ST_ColorFail'];
	}
	else{
		echo $SETTINGS['ST_ColorPass'];
	}
	echo ";\" >";
		echo "<span>ADs: <strong>".$REC51."/".$ADS."</strong></span>";
		
	// #################### PSA ##############################
	$PSA = ceil(($Requirements['length']*$SETTINGS['ST_PSAH'])/60);
	echo "</td><td width=\"250px\" style=\"background-color:";
	if($RECPSA<$PSA){
		echo $SETTINGS['ST_ColorFail'];
	}
	else{
		echo $SETTINGS['ST_ColorPass'];
	}
	echo ";\" >";
		echo "<span>PSA/Promo: <strong>".$RECPSA."/".$PSA."</strong></span>";
	echo "</td><td width=\"500px\""; 
	
	// ################ REQ CC PL ##################
	if($RECCC>=$CC){
	 	echo "style=\"background-color:".$SETTINGS['ST_ColorPass'].";\">";
	}
	else{
		echo "style=\"background-color:".$SETTINGS['ST_ColorFail'].";\">";
	}
		echo "Canadian Content Required:  <strong>" . $RECCC . "/" . $CC . "</strong>";
	echo "</td><td width=\"300px\"";
	 
	if($RECPL>=$PL){
	 	echo "style=\"background-color:".$SETTINGS['ST_ColorPass'].";\">";
	}
	else{
		echo "style=\"background-color:".$SETTINGS['ST_ColorFail'].";\">";
	}
		echo "Playlist Required:  <strong>" . $RECPL. "/" . $PL . "</strong>";
	echo "</td></tr>";
             $br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.

            if(ereg("opera", $br)) {
            }
            else if(ereg("chrome", $br)) {
              /*echo "<tr><td>
              <h3 width=\"100%\" style=\"background-color:yellow; color:black;\"><strong>WARNING: This browser does not support the needed HTML5 forms on Windows<br />
              please launch or download opera that supports these required forms. This does not apply to MAC OS</strong></h3>
              </td></tr>";*/
            }
            else if(ereg("safari", $br)) {
              echo "<tr><td>
              <h3 width=\"100%\" style=\"background-color:yellow; color:black;\"><strong>WARNING: This browser does not support the needed HTML5 forms on Windows<br />
              please launch or download opera that supports these required forms. This does not apply to MAC OS</strong></h3>
              </td></tr>";
            }
            else {
              echo "<tr><td>
              <h3 width=\"100%\" style=\"background-color:red; color:white;\"><strong>WARNING: This browser does not support the needed HTML5 forms
              please launch or download opera that supports these required forms</strong></h3>
              </td></tr>";
            }
			
			$SOCANC = "select * from socan where Enabled='1' and '" . addslashes($_POST['user_date']) ."' between start and end";
			$SOCANA = mysql_query($SOCANC);
			if(mysql_num_rows($SOCANA)>0){
				echo "<tr style=\"background-color:red; height:30px; color:white;\"><th colspan=\"100%\">
				NOTICE: A SOCAN audit is in effect at this station. Accurate reporting is vital at this time, extra information may be required. See your program director for more information
				</th></tr>";
			}
			if(sizeof($error) > 0){
				echo "<tr style=\"background-color:black; color:red;\"><th colspan=\"100%\">Errors</th></tr><tr>";
				$counter = 0;
				while($VAL = $error[$counter]){//array_pop($error)){
					echo "<tr style=\"background-color:white; color:red;\"><td>".$VAL."</td></tr>";
					$counter++;
				}
			}
			if(sizeof($warning) > 0){
				echo "<tr style=\"background-color:Black; color:yellow;\"><th colspan=\"100%\">Warnings &amp; Information</th></tr><tr>";
				while($VAL = array_pop($warning)){
					echo "<tr style=\"background-color:#FFFF99; color:black;\"><td colspan=\"100%\">".$VAL."</td></tr>";
				}
			}
        ?>
        </table>
        <table width="1350" style="background-color:white;">
        <tr><th width="8%">
        Air Date
        </th><th width="6%">
        Air Time
        </th><th width="14%">
        Program
        </th><th width="7%">
        Station
        </th><th width="58%">
        Description
        </th><th width="8%">
        Pre-Record
        </th><th width="5%">

        </th>
        </tr>
        
        <tr><td valign="top">

	    
	    <?php echo $EPINFO['date']; ?>
        </td><td valign="top">
	    
	    <?php echo $EPINFO['starttime']; ?>
        </td><td valign="top">
             
             <?php echo $EPINFO['programname'];?>
        </td><td valign="top">
             
             <?php echo $EPINFO['callsign'];?>
        </td><td valign="top">
             
             <?php echo $EPINFO['description']; ?>
        </td><td valign="top">
	    <?php 
	     	
	     	//chec if not enabled
	      if(!isset($_POST['enprerec']))
              {
                echo ' ';
              }
              else // if enabled execute
              {
                echo "<input type=\"date\" name=\"prerecord\" hidden=\"true\" value=\"" . $_POST['prdate'] . "\" />";
              }
			  
	    ?>

        </td></tr>
        <tr><td colspan="100%">
        	<hr>
        </td></tr>
        <!-- Row for displaying Ads and Friends -->
		<tr>
			<td colspan="2" style="color:green;">
				Available Friends Ads
			</td>
			<td colspan="2" style="color:blue;">
				<span title="NON OPTIONAL paid ads">Required Commercials This Hour</span>
			</td>
			<td colspan="2" style="color:orange;">
				<span title="Available Promos">Required PSA/Promo</span>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="adbox" id="friends" onchange="CHAVF()"><?php
					/*$REQAD_SQL = "select * from adrotation where '".date('H:i')."' between startTime and endTime and exists
					(select AdIdRef from addays where  Day='".date('l')."' and adrotation.RotationNum = addays.AdIdRef) and 
					(select count(songId) from song where song.title=(select AdName from adverts where adverts.AdId=adrotation.AdId)
					and song.time between adrotation.startTime and adrotation.endTime) < adrotation.BlockLimit";*/
					//$REQAD_SQL = "SELECT adverts.*,adrotation.* FROM adrotation,addays,adverts WHERE '".date('H').":00:00' BETWEEN adrotation.startTime AND adrotation.endTime AND addays.AdIdRef=adrotation.RotationNum AND adrotation.AdId=adverts.AdId AND addays.Day='".date('l')."' AND adverts.active='1'";
					$REQAD_SQL = "SELECT adverts.*,adrotation.* FROM adrotation,addays,adverts WHERE '".date('H:i:s')."' BETWEEN adrotation.startTime AND adrotation.endTime AND addays.AdIdRef=adrotation.RotationNum AND adrotation.AdId=adverts.AdId AND addays.Day='".date('l')."' AND adverts.active='1' AND '".date('Y-m-d')."' BETWEEN adverts.StartDate AND adverts.EndDate";
					
					$RQADSIDS = array();
					$REQAD = "";
					if(!$READS = mysql_query($REQAD_SQL))
					{
						$REQAD .= "<option value='-1'>ERROR - AdRotation</option>";
					}
					else if(mysql_num_rows($READS)==0){
						$REQAD .= "<option value='-1'>No Paid Commercials</option>";
					}
					else{
						while($PdAds=mysql_fetch_array($READS)){
							if($PdAds['Limit'] == NULL || $PdAds['Playcount'] < $PdAds['Limit']){
								// Check BlockLimit
								$CHECKBLIM = "SELECT count(song.songid) FROM adrotation,song WHERE adrotation.AdId='".$PdAds['AdId']."' AND song.title='".$PdAds['AdName']."' and song.date='".$EPINFO['date']."' and song.time BETWEEN '".$PdAds['startTime']."' AND '".$PdAds['endTime']."' ";
								$BL_lim_R = mysql_query($CHECKBLIM);
								$BL_lim = mysql_fetch_array($BL_lim_R);
								if(mysql_error()){
									echo "<option value='-3'>ERROR SQL</option>";
								}
								if($BL_lim['count(song.songid)']<$PdAds['BlockLimit']){
									//echo "<option value='-2'>BL_Lim:".$BL_lim['count(song.songid)']."</option>";
									$REQAD .= "<option value='".$PdAds['AdId']."'>".$PdAds['AdName']."</option>";
									array_push($RQADSIDS,$PdAds['AdId']);
                                    $SQL_PL_AD = "INSERT INTO promptlog (EpNum,AdNum) VALUES (".$EPINFO['EpNum'].",".$PdAds['AdId'].")";
                                    if(!mysql_query($SQL_PL_AD)){
                                        echo "<!-- ERROR: " . mysql_error() . "-->";
                                    }
                                    else{
                                        echo "<!-- Inserted into Log -->";
                                    }

								}	
							}
						}
						
						/*while($RQADS = mysql_fetch_array($READS)){
							if(!$ADINFOARR = mysql_query("select * from adverts where AdId='".$RQADS['AdId']."'")){
								$REQAD .= "<option value='-1'>ERROR - Adverts</option>";
							}
							else{
								$adinf = mysql_fetch_array($ADINFOARR);
								$HR = date('H');
								$HRN = $HR+1;
								$SQL_ADS_HR = "select count(songid) from song where category = '".$adinf['Category']."' and title='".$adinf['AdName']."' and time between '".$HR.":00' and '".$HRN.":00'";
								//echo $SQL_ADS_HR;
								if(!$adcount = mysql_query($SQL_ADS_HR)){
									$REQAD .= "<option value=\"-1\">ERROR - Hourly Limit</option>";//mysql_error();
								}
								$ADCO = mysql_fetch_array($adcount);
								if($ADCO['count(songid)']<$RQADS['HourlyLimit']){
									$ADCO['count(songid)'];
									//echo $RQADS['HourlyLimit'];
									echo "<option value=\"".$adinf['AdId']."\">".$adinf['AdName']."</option>";
									array_push($RQADSIDS,$adinf['AdId']);	
								}
								else{
									//echo "<option value='-1'>No Paid Commercials [#E2]</option>";
								}
								//echo "<option value=\"".$ADCO['count(songid)']."\">".$ADCO['count(songid)']."</option>";
							}
						}*/
					}

				// Friends Ads
				if(sizeof($RQADSIDS) > 0){
					$ADOPT .= "<option>Paid Ad Required [".sizeof($RQADSIDS)."]</option>";
				}
				else
				{
					$ADIDS = array();
					if(isset($SPONS)){
						$ADOPT .= "<option value='".$SPONS['AdId']."'>".$SPONS['AdName']."</option>";
						array_push($ADIDS,$avadi['AdId']);
					}
					else{
						//$selcom51 is origin
						$minplaysql51 = "select MIN(Playcount) from adverts where Category='51' and Active='1' and Friend='1' ";
						if(!$minplay51Array = mysql_fetch_array(mysql_query($minplaysql51))){
							$selcom51 = "select * from adverts where Category='51'";
						}
						else{
							$minplay51 = $minplay51Array['MIN(Playcount)'];
							$selcom51 = "select * from adverts where Category='51' and '" . addslashes($_POST['user_date']) . "' between StartDate and EndDate and Friend='1' and Active='1' and Playcount='".$minplay51."' ";
							//echo $minplay51;
						}
						$selspon = "select MIN(Playcount) from adverts where Category!='51' and '" . addslashes($_POST['user_date']) . "' is between EndDate and StartDate ";
						
						if($comsav=mysql_query($selcom51)){
							$ADOPT = "";
							while($avadi = mysql_fetch_array($comsav)){
								$ADOPT .= "<option value=\"" . $avadi['AdId'] . "\">" . $avadi['AdName'] . "</option>";
								array_push($ADIDS,$avadi['AdId']);
							} 
						}
						else{
							$ADOPT = "<option value=\"-1\">ERROR - SQL Command</option>";
							//echo mysql_error(); 
						}
					}
				}
				echo $ADOPT;
					
				?>
				</select>
			</td>
			<td colspan="2">
				<select name="rqAds">
				<?php
				if($REQAD!=""){
					echo $REQAD;
				}
				else{
					echo "<option>No Required Ads [Er3]</option>";
				}
				?>
				</select>
			</td>
			<td>
				<?php
					//echo sizeof($ADIDS);
				?>
				<span style="font-style: italic;color:darkred;">Notice to all DJs: Ad Requirements have changed you are now required to play <strong>two (2) ads per hour</strong></span>
			</td>
			<td>
				<!--<iframe src="http://free.timeanddate.com/clock/i39aufy9/n1100/fn17/fs20/fc009/tct/pct/ftb/th1" frameborder="0" width="102" height="26" allowTransparency="true"></iframe>-->
			</td>
		</tr>
        
        <tr> <!-- This is the second Row Of Data -->

        <!-- horizontal line -->
        <td colspan="7">
        <hr />
        </td></tr><!-- End Second Row -->



        <!-- Third Row (Song Data) of Data

        So Stuff Gets A Little complicated here,
        Using a PHP Generated HTML Table data
        already entered is listed but first there
        must be the working row as this should be
        on the top for ease of access. plus the rows
        below will then be modifiable as we can end
        the form tag and have individual update tags
        for the rows. this may get ugly...
        -->
        <!--<tr>-->
        </table>
        <div id="Alert"></div>
         <div id="processing" style="width: 1350px; background-color: white; text-align: center; float:middle; display: none">
        			<!--<img src="/images/GIF/spinner.gif" height="50px" alt="Processing"/>-->
        			<img src="/images/GIF/ajax-loader2.gif" alt="..."/><span>Processing...</span>
        </div>
        
        <!--/////////////////////////// Input Form (Advertisements) //////////////////////////-->
        	<form name="formad" method="post" id="frm1" action="p2insertEP.php" onsubmit="formsubmit()">  
        	<input type="date" name="user_date" hidden value=<?php echo "\"" . $_POST['user_date'] . "\"";?>/>
        	<input type="time" name="user_time" hidden value=<?php echo "\"" . $_POST['user_time'] . "\"";?>/>
        	<input type="text" name="program" hidden value=<?php echo "\"" . $_POST['program'] . "\"";?> />
        	<input type="text" name="station" hidden value=<?php echo "\"" . $CALLSHOW . "\"";?> />
        	<input type="text" name="description" hidden value=<?php echo "\"" . $_POST['description'] . "\""; ?> />
        	<input type="hidden" name="artist" hidden value=<?php echo "\"" . $CALLSHOW . "\""; ?> />
        	<input type="hidden" name="album" hidden value="Advertisement" />
        	<!--<input type="text"-->
        	  
        	   <!-- //// END FORM DEFINITION //// --> 
        <div id="InputAdvert" style="width: 100%; text-align: center; display: none;">
            <table colspan="7" width="1350" valign="top" style="background-color:white;">
                  <tr><!-- Header Definitions for Advertisements -->
                  	
                       <th width="5%">
                           Type  <input type="button" value="Define" onclick="return popitup('/help/definetype.html')"/>
                       </th>
                       <th width="5%" id="Adnumer">
                           Identifier
                       </th>
                       <th id="Adtime">
                           Time
                       </th>
                       <th id="Adname">
                           All Commercials
                       </th colspan="100%">
                       <!--<th id="arHead">
                           Artist
                       </th>
                       <th>
                           Album (Release Title)
                       </th>
                       
                       <th width="2%">
                           CC
                       </th>
                       <th width="2%">
                           Hit
                       </th>
                       <th width="2%">
                           Ins
                       </th>
                       <th width="5 %">
                           Language
                       </th>
                       <th width="5%">-->

                       </th>
              </tr>
              <tr ><!-- Blank Row for song insertion -->
                       <td>
                           <select name="cat" id="DDLAdvert" onchange="UnCHtype()">
                                   <!--
                                   <OPTION VALUE="5">5, Commercial</OPTION>
                                   <OPTION VALUE="4">4, Musical Production</option>
                                   <OPTION VALUE="3">3, Special Interest</option>
                                   <OPTION VALUE="2" selected="selected">2, Popular Music</option>
                                   <option value="1">1, Spoken</option>
                                   -->
                                   <!-- Using Sub Categories -->
                                   
                                   <option value="53">53, Sponsored Promotion</option>
                                   <OPTION value="52">52, Sponsor Indentification</OPTION>
                                   <OPTION VALUE="51">51, Commercial</OPTION>
                                   <option value="45">45, Show Promo</option>
                                   <option value="44">44, Programmer/Show ID</option>
                                   <option value="43">43, Musically Produced Stat. ID</option>
                                   <option value="42">42, Tech Test</option>
                                   <option value="41">41, Themes</option>
                                   	<option value="36">36, Experimental</option>
                                   	<option value="35">35, NonClassical Religious</option>
                                   	<option value="34">34, Jazz and Blues</option>
                                   	<option value="33">33, World/International</option>
                                   	<option value="32">32, Folk</option>
                                   	<option value="31">31, Concert</option>
                                   	<!--<option value="3">3, Special Interest</option>-->
                                   	<option value="24">24, Easy Listening</option>
                                   	<option value="23">23, Acoustic</option>
                                   	<option value="22">22, Country</option>
                                   	<option value="21">21, Pop, Rock and Dance</option>
                                   	<!--<option value="2" selected="True">2, Popular Music</option>-->
                                   <option value="12">12, PSA/Spoken Word Other</option>
                                   <OPTION VALUE="11">11, News</option>

                           </select>
                       </th>
                       <th id="plcont">
                           <input type="text" id="AdNum" name="AdNum" readonly="true" size="10"/>
                       </th>
                       <th>
                           <input type="time" name="time" value=<?php
                           if(isset($_POST['ENPREC']) || isset($EPINFO['prerecorddate'])){
                           	if(isset($EPINFO['endtime'])){
	                             echo "\"" . $EPINFO['endtime'] . "\"";
	                           }
	                           else if(isset($_POST['time'])){
	                             echo "\"" . $_POST['time'] . "\"";
	                           }
	                           else{
	                             echo "\"" . $_POST['user_time'] . "\"";
	                           }
						   }
							else{
								echo "\"" . date('H:i') . "\" ";
							}
                             ?>/>
                       </th>
                       <th>
                           <?php 
                           //<input type="text" name="title" id="title001" size="33" required="true" maxlength="45">
                           	echo "<select id=\"ADLis\" name=\"title\" onChange=\"ADCH()\" >";
								$SLADS = "select * from adverts where Category='51' and '" . addslashes($_POST['user_date']) . "' between StartDate and EndDate and Active='1' order by AdName";
                           		if(!$SRZ = mysql_query($SLADS)){
                           			echo "<option value='0'>NO ADS AVAILABLE</option>";
                           		}
								else{
									while($ADZL=mysql_fetch_array($SRZ)){
										echo "<option value=\"" . $ADZL['AdId'] . "\" ";
										if(in_array($ADZL['AdId'], $ADIDS)){
											echo " style=\"background-color:green; color:white\" ";
										}
										else if(in_array($ADZL['AdId'], $RQADSIDS)){
											echo " style=\"background-color:blue; color:white\" ";
										}
										echo " >". $ADZL['AdName'] ."</option>
				
										";
									}
								}
                           	echo "</select>";
                           ?>
                       </th>
                       <th>
                           <input name="sub" type="submit" value="Insert" onclick="formSubmit()"/>
                           </form>
                       </th>
                       <th colspan="100%">
                       </th>
              </tr>
              </table>
              </div>
        
         <!--/////////////////////////// Input Form (Sponsor 53) //////////////////////////-->
        	<form name="formad" method="post" id="frm3" action="p2insertEP.php" onsubmit="formsubmit()">  
        	<input type="date" name="user_date" hidden value=<?php echo "\"" . $_POST['user_date'] . "\"";?>/>
        	<input type="time" name="user_time" hidden value=<?php echo "\"" . $_POST['user_time'] . "\"";?>/>
        	<input type="text" name="program" hidden value=<?php echo "\"" . $_POST['program'] . "\"";?> />
        	<input type="text" name="station" hidden value=<?php echo "\"" . $CALLSHOW . "\"";?> />
        	<input type="text" name="description" hidden value=<?php echo "\"" . $_POST['description'] . "\""; ?> />
        	<input type="text" name="artist" hidden="true" value=<?php echo "\"" . $CALLSHOW . "\""; ?> />
        	<input type="text" name="album" hidden="true" value="Advertisement" />
        	<!--<input type="text"-->
        	  
        	   <!-- //// END FORM DEFINITION //// --> 
        <div id="InputSponsor" style="width: 100%; text-align: center; display: none;">
            <table colspan="7" width="1350" valign="top" style="background-color:white;">
                  <tr><!-- Header Definitions for Advertisements -->
                  	
                       <th width="5%">
                           Type  <input type="button" value="Define" onclick="return popitup('/help/definetype.html')"/>
                       </th>
                       <th width="5%" id="Adnumer">
                           Identifier
                       </th>
                       <th id="Adtime">
                           Time
                       </th>
                       <th id="Adname">
                           All Sponsors
                       </th colspan="100%">
 
                       </th>
              </tr>
              <tr ><!-- Blank Row for song insertion -->
                       <td>
                           <select name="cat" id="DDLAdvert" onchange="UnCHtype()">
                                   <!--
                                   <OPTION VALUE="5">5, Commercial</OPTION>
                                   <OPTION VALUE="4">4, Musical Production</option>
                                   <OPTION VALUE="3">3, Special Interest</option>
                                   <OPTION VALUE="2" selected="selected">2, Popular Music</option>
                                   <option value="1">1, Spoken</option>
                                   -->
                                   <!-- Using Sub Categories -->
                                   
                                   <option value="53">53, Sponsored Promotion</option>
                                   <OPTION value="52">52, Sponsor Indentification</OPTION>
                                   <OPTION VALUE="51">51, Commercial</OPTION>
                                   <option value="45">45, Show Promo</option>
                                   <option value="44">44, Programmer/Show ID</option>
                                   <option value="43">43, Musically Produced Stat. ID</option>
                                   <option value="42">42, Tech Test</option>
                                   <option value="41">41, Themes</option>
                                   	<option value="36">36, Experimental</option>
                                   	<option value="35">35, NonClassical Religious</option>
                                   	<option value="34">34, Jazz and Blues</option>
                                   	<option value="33">33, World/International</option>
                                   	<option value="32">32, Folk</option>
                                   	<option value="31">31, Concert</option>
                                   	<!--<option value="3">3, Special Interest</option>-->
                                   	<option value="24">24, Easy Listening</option>
                                   	<option value="23">23, Acoustic</option>
                                   	<option value="22">22, Country</option>
                                   	<option value="21">21, Pop, Rock and Dance</option>
                                   	<!--<option value="2" selected="True">2, Popular Music</option>-->
                                   <option value="12">12, PSA/Spoken Word Other</option>
                                   <OPTION VALUE="11">11, News</option>

                           </select>
                       </th>
                       <th id="plcont">
                           <input type="text" id="AdNum" name="AdNum" readonly="true" size="10"/>
                       </th>
                       <th>
                           <input type="time" name="time" value=<?php
                           if(isset($_POST['ENPREC']) || isset($EPINFO['prerecorddate'])){
                           	if(isset($EPINFO['endtime'])){
	                             echo "\"" . $EPINFO['endtime'] . "\"";
	                           }
	                           else if(isset($_POST['time'])){
	                             echo "\"" . $_POST['time'] . "\"";
	                           }
	                           else{
	                             echo "\"" . $_POST['user_time'] . "\"";
	                           }
						   }
							else{
								echo "\"" . date('H:i') . "\" ";
							}
                             ?>/>
                       </th>
                       <th>
                           <?php 
                           //<input type="text" name="title" id="title001" size="33" required="true" maxlength="45">
                           	echo "<select id=\"ADLis\" name=\"title\" onChange=\"ADCH()\" >";
								$SLADS = "select * from adverts where Category='53' and '" . addslashes($_POST['user_date']) . "' between StartDate and EndDate and Active='1' order by AdName";
                           		if(!$SRZ = mysql_query($SLADS)){
                           			echo "<option value='0'>NO ADS AVAILABLE</option>";
                           		}
								else{
									while($ADZL=mysql_fetch_array($SRZ)){
										echo "<option value=\"" . $ADZL['AdId'] . "\" ";
										if(in_array($ADZL['AdId'], $ADIDS)){
											echo " style=\"background-color:green; color:white\" ";
										}
										else if(in_array($ADZL['AdId'], $RQADSIDS)){
											echo " style=\"background-color:blue; color:white\" ";
										}
										echo " >". $ADZL['AdName'] ."</option>
				
										";
									}
								}
                           	echo "</select>";
                           ?>
                       </th>
                       <th>
                           <input name="sub" type="submit" value="Insert" onclick="formSubmit()"/>
                           </form>
                       </th>
                       <th colspan="100%">
                       </th>
              </tr>
              </table>
              </div>
        
        
        <!--/////////////////////////// Input Form (regular) //////////////////////////-->
        	<form name="form1" method="post" id="frm2" action="p2insertEP.php" onsubmit="formsubmit()">  
        	<input type="date" name="user_date" hidden value=<?php echo "\"" . $_POST['user_date'] . "\"";?>/>
        	<input type="time" name="user_time" hidden value=<?php echo "\"" . $_POST['user_time'] . "\"";?>/>
        	<input type="text" name="program" hidden value=<?php echo "\"" . $_POST['program'] . "\"";?> />
        	<input type="text" name="station" hidden value=<?php echo "\"" . $CALLSHOW . "\"";?> />
        	<input type="text" name="description" hidden value=<?php echo "\"" . $_POST['description'] . "\""; ?> />
        	   <!-- //// END FORM DEFINITION //// --> 
        <div id="inputdiv" style="width: 100%; text-align: center; ">
            <table colspan="7" width="1350" valign="top" style="background-color:white;">
                  <tr><!-- Header Definitions for songs -->
                  	
                       <th width="5%">
                           Type  <input type="button" value="Define" onclick="return popitup('/help/definetype.html')"/> <input type="button" value="Notes" name="NButton" onclick="GetNotes();" />
                       </th>
                       <th width="5%" id="plhead" onchange="fetchplaylist()">
                           Playlist
                       </th>
                       <th width="5%" id="spokenc" style="display: none">
                       		Minutes
                       	</th>
                       <th>
                           Time
                       </th>
                       <th>
                           Title
                       </th>
                       <th id="arHead">
                           Artist
                       </th>
                       <th>
                           Album (Release Title)
                       </th>
                       <th>
                           Composer
                       </th>
                       <th width="2%">
                           CC
                       </th>
                       <th width="2%">
                           Hit
                       </th>
                       <th width="2%">
                           Ins
                       </th>
                       <th width="5 %">
                           Language 
                       </th>
                       <th width="5%">
							<input type="hidden" name="note" id="NF1"/>
							
                       </th>
              </tr>
              <tr><!-- Blank Row for song insertion -->
                       <td>
                           <select name="cat" id="DDLNormal" onchange="CHtype()">
                                   <!--
                                   <OPTION VALUE="5">5, Commercial</OPTION>
                                   <OPTION VALUE="4">4, Musical Production</option>
                                   <OPTION VALUE="3">3, Special Interest</option>
                                   <OPTION VALUE="2" selected="selected">2, Popular Music</option>
                                   <option value="1">1, Spoken</option>
                                   -->
                                   <!-- Using Sub Categories -->
                                   
                                   <option value="53">53, Sponsored Promotion</option>
                                   <OPTION value="52">52, Sponsor Indentification</OPTION>
                                   <OPTION VALUE="51">51, Commercial</OPTION>
                                   <option value="45">45, Show Promo</option>
                                   <option value="44">44, Programmer/Show ID</option>
                                   <option value="43">43, Musically Produced Stat. ID</option>
                                   <option value="42">42, Tech Test</option>
                                   <option value="41">41, Themes</option>
                                   	<option value="36">36, Experimental</option>
                                   	<option value="35">35, NonClassical Religious</option>
                                   	<option value="34">34, Jazz and Blues</option>
                                   	<option value="33">33, World/International</option>
                                   	<option value="32">32, Folk</option>
                                   	<option value="31">31, Concert</option>
                                   	<!--<option value="3">3, Special Interest</option>-->
                                   	<option value="24">24, Easy Listening</option>
                                   	<option value="23">23, Acoustic</option>
                                   	<option value="22">22, Country</option>
                                   	<option value="21">21, Pop, Rock and Dance</option>
                                   	<!--<option value="2" selected="True">2, Popular Music</option>-->
                                   <option value="12">12, PSA/Spoken Word Other</option>
                                   <OPTION VALUE="11">11, News</option>

                           </select>
                       </th>
                       <th id="plbody">
                           <input type="number" name="playlist" size="5" min="0" onmousewheel="javascript: return false">
                       </th>
                       <th id="spokcon" style="display:none;">
                       		<input type="number" step="0.25" name="spokenmin" size="5" max="480" min="0" onmousewheel="javascript: return false">
                       </th>
                       <th>
                           <input type="time" name="time" value=<?php
                           if(isset($_POST['ENPREC']) || isset($EPINFO['prerecorddate'])){
                           	if(isset($EPINFO['endtime'])){
	                             echo "\"" . $EPINFO['endtime'] . "\"";
	                           }
	                           else if(isset($_POST['time'])){
	                             echo "\"" . $_POST['time'] . "\"";
	                           }
	                           else{
	                             echo "\"" . $_POST['user_time'] . "\"";
	                           }
						   }
							else{
								echo "\"" . date('H:i') . "\" ";
							}
                             ?> onmousewheel="javascript: return false"/>
                       </th>
                       <th>
                           
                           <input type="text" name="title" id="title001" size="25" required="true" maxlength="90">
                           <input list="spoken" name="title" id="data1" size="25" required="true" disabled="disabled" maxlength="90" style="display:none"/>
                           <datalist id="spoken">
                           		<option value="Spoken Word / Talk">
                           		<option value="PSA / Promo">
                           		<option value="News">
                           		<option value="Station ID">
                           </datalist>
                           	
                           	
                       </th>
                       <th>
                           
                           <input type="text" id="artin" name="artist" size="25" maxlength="90"/>
                       </th>
                       <th>
                           
                           <input type="text" id="albin" name="album" size="25" maxlength="90"/>
                       </th>
                       <th>
                           <input type="text" id="composer" name="composer" size="25" maxlength="90"/>
                       </th>
                       <th>
                           <input type="checkbox" id="ccin" name="cancon" value="1"/>
                       </th>
                       <th>
                           <input type="checkbox" id="hitin" name="hit" value="1"/>
                       </th>
                       <th>
                           <input type="checkbox" id="insin" name="instrumental" value="1"/>
                       </th>
                       <th>
                           <input type="text" name="lang" required="true" value="English" size="10" maxleng="40"/>
                       </th>
                       <th>
                           <input name="sub" type="submit" value="Insert" onclick="formSubmit()"/>
                           </form>
                       </th>
              </tr>
              </table>
              </div>
              <table width="1350" valign="top" style="background-color:white;">
              	<td colspan="100%">Recorded Information</td>
              	<tr><th width="100px">Category</th><th width="75px">Playlist</th><th width="75px">Spoken</th><th width="100px">Time</th><th width="230px">Title</th><th width="230px">Artist</th><th width="230px">Album</th><th width="250px">Composer</th><th width="20px">CC</th><th width="20px">Hit</th><th width="20px">Ins</th><th width="200px">Language</th></tr>
               <tr> <!-- Row for displaying already entered data -->
                   <?php
                   	$ORDERque = "select displayorder from program where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' ";
					$ORDER = mysql_fetch_array(mysql_query($ORDERque));
                    $query = "select * from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' order by time " . $ORDER['displayorder'] .", songid " . $ORDER['displayorder'];
                    $listed=mysql_query($query,$con);
                     if(mysql_num_rows($listed)=="0"){
                       echo "<tr><td colspan=\"100%\" style=\"background-color:".$SETTINGS['ST_ColorFail'].";\">No Songs Recorded Yet</td></tr>";
                     }
					 else if(mysql_errno())
					 {
					 	echo mysql_error();
					 }
                     else
                     {
                         while ($list=mysql_fetch_array($listed))
                         {
                           echo "<tr>";
                           echo "<td>";
                                echo $list['category'];
							/*if($list['AdViolationFlag']=='1'){
						   		echo "<img src=\"/images/ICONS/ERROR.PNG\" alt=\"notice\" height=\"15px\" width=\"15px\"
						   		onclick=\"alert('Notice \\n \\nThis ad was not listed in the available friends list, \\nthis will not be counted toward your requirements\\n\\nplease only play ads from the required and available friends lists\\n This Ad's Priority has been decreased')\" />";	
						   	}*/
                           echo "</td><td>";
                                echo $list['playlistnumber'];
                           echo "</td><td>";
						   echo $list['Spoken'];
                           echo "</td><td>";
                                echo $list['time'];
                           echo "</td><td>";
                                echo $list['title'];
                           echo "</td><td>";
                                echo $list['artist'];
                           echo "</td><td>";
                                echo $list['album'];
                           echo "</td><td>";
						   		echo $list['composer'];
                           echo "</td><td>";
                                echo $list['cancon'];
                           echo "</td><td>";
                                echo $list['hit'];
                           echo "</td><td>";
                                echo $list['instrumental'];
                           $songlang = mysql_query("select languageid from LANGUAGE where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' and songid='". addslashes($list['songid']) ."'");
                           $rowlang = mysql_fetch_array($songlang);
                           echo "</td><td>";
                                echo $rowlang['languageid'];
                           echo "</td>";
                           echo "</tr>";
						   if(isset($list['note'])){
						   	echo "<tr  style=\"background-color:".$SETTINGS['ST_ColorNote']."\"><td colspan=\"100%\">".$list['note']."</td></tr>";
						   }
                         }
                     }

                   ?>
               </tr>
        </tr>
        <form name="Complete" method="POST" action="p3insertEP.php">
        <tr>
        <td colspan="12" height="10">
        <hr />
        </td>
        </tr>
        <tr>
        <th colspan="2">
        Status
        </th>
        <th colspan="2">
        Calculated Spoken Time
        </th>
        <th colspan="2">
        Time Complete
        </th>
        </tr>
        <tr>
        
        	<?php
        	if(!isset($EPINFO['endtime'])){
        		echo "<td colspan=\"2\" style=\"background-color:white; color:darkred;\"><span>Active : Not Finalized</span>";
        	}
			else{
				echo "<td colspan=\"2\" style=\"background-color:red; color:white;\"><span>Complete : Finalized</span>";
			}
        	?>
        </td>
        <td colspan="2">
        <input type="text" name="spoken" value=<?php
                           if(isset($EPINFO['totalspokentime'])){
                             echo "\"" . $EPINFO['totalspokentime'] . "\" readonly=\"true\"";
                           }
                           else{
                           	$SUMAR = "select sum(Spoken) from SONG where callsign='" . addslashes($CALLSHOW) . "' and programname='" . addslashes($_POST['program']) . "' and date='" . addslashes($_POST['user_date']) . "' and starttime='" . addslashes($_POST['user_time']) . "' order by time desc";
							   if($spokensum = mysql_fetch_array(mysql_query($SUMAR))){
							   	if($spokensum['sum(Spoken)'] > 0){
                           			echo " \"".$spokensum['sum(Spoken)'] . "\" readonly=\"true\"";
								}
								else{
									echo "\"0\" style=\"color:red;\" readonly=\"true\"";
								}
							   }
							   else{
							   	echo "\"0\"";
							   }
                             //echo "\"0\"";
                           }
                             ?>/>
        </td>
        <td colspan="1">
        <input type="time" name="end" value=<?php
                           if(isset($_POST['ENPREC']) || isset($EPINFO['prerecorddate'])){
                           	if(isset($EPINFO['endtime'])){
	                             echo "\"" . $EPINFO['endtime'] . "\"";
	                           }
	                           else if(isset($_POST['time'])){
	                             echo "\"" . $_POST['time'] . "\"";
	                           }
	                           else{
	                             echo "\"" . $_POST['user_time'] . "\"";
	                           }
						   }
							else{
								echo "\"" . date('H:i') . "\" ";
							}
                             ?>/>
        </td>
        <td colspan="1">
        <input type="text" hidden="true" name="callsign" value=<?php echo "\"" . $CALLSHOW . "\"" ?> />
        <input type="text" hidden="true" name="program" value=<?php echo "\"" . $_POST['program'] . "\"" ?> />
        <input type="text" hidden="true" name="user_date" value=<?php echo "\"" . $_POST['user_date'] . "\"" ?> />
        <input type="text" hidden="true" name="user_time" value=<?php echo "\"" . $_POST['user_time'] . "\"" ?> />
        <input type="submit" value="Finish Episode" />
        </td>
        </tr>
        </form>
        <tr><td colspan="12" height="20">
        <hr />
        </td></tr>
        <tr>
        <?php
          if($_SESSION['usr']=='user')
          {
            echo "<form name=\"exit\" action=\"/djhome.php\" method=\"POST\" ";
            if(!isset($EPINFO['endtime'])){
            	echo " onSubmit=\"return confirm('WARNING: Unfinalized Episode\\n\\nThis episode is not finalized. Are you sure you want to exit?')\">";
            }
			else{
				echo "\">";
			}
                  echo "<td colspan=\"1\">
                  <input type=\"text\" hidden=\"true\" name=\"callsign\" value=\"" . $CALLSHOW . "\" />
                  <input type=\"text\" hidden=\"true\" name=\"program\" value=\"" . $_POST['program'] . "\"/>
                  <input type=\"text\" hidden=\"true\" name=\"user_date\" value=\"" . $_POST['user_date'] . "\"/>
                  <input type=\"text\" hidden=\"true\" name=\"user_time\" value=\"" . $_POST['user_time'] . "\"/>
                  <input type=\"submit\" value=\"Exit\" /></form>
                  </td><td></td>";
          }
          else
          {
            echo "<form name=\"exit\" action=\"/masterpage.php\" method=\"POST\" ";
            if(!isset($EPINFO['endtime'])){
            	echo " onSubmit=\"return confirm('WARNING: Unfinalized Episode\\n\\nThis episode is not finalized. Are you sure you want to exit?')\">";
            }
			else{
				echo "\">";
			}
                  echo "<td colspan=\"1\">
                  <input type=\"text\" hidden=\"true\" name=\"callsign\" value=\"" . $CALLSHOW . "\" />
                  <input type=\"text\" hidden=\"true\" name=\"program\" value=\"" . $_POST['program'] . "\"/>
                  <input type=\"text\" hidden=\"true\" name=\"user_date\" value=\"" . $_POST['user_date'] . "\"/>
                  <input type=\"text\" hidden=\"true\" name=\"user_time\" value=\"" . $_POST['user_time'] . "\"/>
                  <input type=\"submit\" value=\"Exit\" /></form>
                  </td>";
          }
        ?>
        <td colspan="1">
        <!--<form name="exit" action="/Episode/p3update.php" method="POST">-->
        <form name="edit" action="/Episode/EPV2/p3update.php" method="POST">
        <input type="text" hidden="true" name="callsign" value=<?php echo "\"" . $CALLSHOW . "\"" ?> />
        <input type="text" hidden="true" name="program" value=<?php echo "\"" . $_POST['program'] . "\"" ?> />
        <input type="text" hidden="true" name="user_date" value=<?php echo "\"" . $_POST['user_date'] . "\"" ?> />
        <input type="text" hidden="true" name="user_time" value=<?php echo "\"" . $_POST['user_time'] . "\"" ?> />
        <input type="submit" value="Edit" />
        </form>
        </td>
        <td colspan="1">
        <form name="refresh" action="/Episode/p2insertEP.php" method="POST">
        <input type="text" hidden="true" name="callsign" value=<?php echo "\"" . $CALLSHOW . "\"" ?> />
        <input type="text" hidden="true" name="program" value=<?php echo "\"" . $_POST['program'] . "\"" ?> />
        <input type="text" hidden="true" name="user_date" value=<?php echo "\"" . $_POST['user_date'] . "\"" ?> />
        <input type="text" hidden="true" name="user_time" value=<?php echo "\"" . $_POST['user_time'] . "\"" ?> />
        <input type="submit" value="Refresh" />
        </form>
        </td>
        <td colspan="7">
        </td><td>
        <img src="/images/mysqls.png" alt="MySQL Powered" />
        </td></tr>

        </table>
        
        <script>
        	
        </script>
</body>
</html>