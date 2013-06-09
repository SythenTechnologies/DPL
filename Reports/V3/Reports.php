<?php
    session_start();
    $dbxml = simplexml_load_file("../../TPSBIN/XML/DBSETTINGS.xml");

    
    if(isset($_POST['server'])){
        $SRVPOST = $_POST['server'];
        $_SESSION['SRVPOST']=$_POST['server'];
    }
    elseif(isset($_SESSION['SRVPOST'])){
        $SRVPOST = $_SESSION['SRVPOST'];
    }
    else{
        $SRVPOST = "NULL";
    }
    if(isset($_POST['RPT_TYPE']))
    {
        $REPORT_TYPE = $_POST['RPT_TYPE'];
    }
    //$SRVPOST = $_POST['']
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>TPS Reports</title>
        <link rel="stylesheet" href="../../altstyle.css" type="text/css"/>
        <link href="../../js/jquery/css/ui-lightness/jquery-ui-1.10.0.custom.min.css" type="text/css" rel="stylesheet"/>
        <script src="../../js/jquery/js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../../js/jquery/js/jquery-ui-1.10.0.custom.min.js" type="text/javascript"></script>
        <script src="../../TPSBIN/JS/Reports/Reports.js" type="text/javascript"></script>
    </head>
    <body class="hasstatictop">
        <div class="statictop" id="topbar">
            <form id="settings" method="post" action="Reports.php">
            <div style="float: left">
                <ul id="menu" style="float:left;">
                    <li><a href="#"><span class="ui-icon ui-icon-disk"></span>Save</a></li>
                    <li><a href="/masterpage.php"><span class="ui-icon ui-icon-close"></span>Exit</a></li>
                </ul>
                <span style="float:left;"><strong>Report Settings</strong></span>
                <progress id="check_db" style="float:left;  display: none;"></progress>
                <select name="server" id="servers" onchange="this.form.submit();" style="float:left;">
                    <?php
                        foreach( $dbxml->SERVER as $convars):
                            if($convars->ACTIVE==1){
                                echo "<option value='".($convars->ID)."'";
                                if($convars->ID==$SRVPOST){
                                    echo " selected ";
                                }
                                echo ">".($convars->NAME)."</option>";
                            }
                        endforeach;

                    ?>
                </select>
                <span id="alert_icon" class="ui-icon ui-icon-alert" style="float: left; display: none; background-image: url('../../js/jquery/css/ui-lightness/images/ui-icons_228EF1_256x240.png');" title="Connection Test Result"></span>
            </div>
            <div id="" style="float: next">
                
            </div>
            </form>
        </div>
        <div >
        </div>
        <div id="db-error-dialog" title="Connection Error" style="display: none;">
          <p>
            <span class="ui-icon ui-icon-transferthick-e-w" style="float: left; margin: 0 7px 50px 0;"></span>
            There was a database connection error, The following result was returned
          </p>
          <p id="dberror_notify" class="ui-state-error ui-corner-all">
            Error to go here
          </p>
        </div>
    </body>
</html>
