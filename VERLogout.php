<?php 
      session_start(); 
	  header("location: /djhome.php");
?>
<!DOCTYPE HTML>
<head>
<link rel="stylesheet" type="text/css" href="/phpstyle.css" />
<title>Logout Check</title>
</head>
<html>
<body>
      <div class="topbar">
           <a class="right" href="logout.php"> Logout </a>Welcome, <?php echo(strtoupper($_SESSION['usr'])); ?>
           </div>

      <table border="0" align="center" width="1000">
      <tr>
           <td align="center" colspan="2"><img src="/images/Ckxu_logo_PNG.png" alt="ckxu"/></td>
      </tr>
      <tr style="background-color:white;">
      <td colspan="2">


        <table align="left" border="0">
        <tr><td colspan="6">
        <h2>Logout Verification</h2>
        </td></tr>
        
        <tr>
        <td colspan="1" rowspan="100%" style="background-color:yellow;text-align:center;"  width="200">
        <strong>WARNING</strong>
        </td>
        <td colspan="3">
        <strong>Logging out will leave your log as currently entered. please ensure you have entered, edited (if applicable) and Finalzed your log before proceding.<br /> You do not have permission to search logs and modify after the fact.</strong>
        <br />
        </td>
        </tr>
        <tr>
        <td colspan="1"></td>
        <td colspan="1" style="text-align:right;" width="40%">
        <?php
        if(isset($_POST['program'])){
          echo "<form name=\"append\" action=\"/episode/p2insertEP.php\" method=\"POST\">";
        }
        else{
          echo "<form name=\"append\" action=\"/episode/p1insertEP.php\" method=\"POST\">";
        }
        ?>
        <input type="text" hidden="true" name="callsign" value=<?php echo "\"" . $PROGRAMARRAY['callsign'] . "\"" ?> />
            <input type="text" hidden="true" name="program" value=<?php echo "\"" . $_POST['program'] . "\"" ?> />
            <input type="text" hidden="true" name="user_date" value=<?php echo "\"" . $_POST['user_date'] . "\"" ?> />
            <input type="text" hidden="true" name="user_time" value=<?php echo "\"" . $_POST['user_time'] . "\"" ?> />
            <input type="submit" value="Return to Log">
        </form>
        </td>
        <td colspan="1" style="text-align:left;">
        <form name="logout" action="/logout.php" method="POST">
            <input type="submit" value="Comfirm Logout">
        </form>
        </td>
        <td colspan="1"></td>
        </tr>
        </table>
        </td><tr>
        <td height="10" style="text-align:left">
        <img src="/images/mysqls.png" alt="MySQL"></td><td height="10" style="text-align:right">Internet Simulcast Server status: <span id="cc_stream_info_server"></span>
<script language="javascript" type="text/javascript" src="http://cent4.serverhostingcenter.com/system/streaminfo.js"></script>
<script language="javascript" type="text/javascript" src="http://cent4.serverhostingcenter.com/js.php/zuomcwvv/streaminfo/rnd0"></script>
</td></tr>
</table>
</body>
</html>