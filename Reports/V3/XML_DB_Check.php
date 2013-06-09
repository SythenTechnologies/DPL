<?php
    session_start();
    header("Content-type: text/xml");
    $HOST = $_SESSION['DBHOST'];
    $PORT = $_SESSION['PORT'];
    $DBNAME = $_SESSION['DBNAME'];
    $USER = $_SESSION['usr'];
    //$USER = "TESTFAKEUSER";
    $PASS = $_SESSION['rpw'];
    //Check for DB Access
    $db = new mysqli($HOST, $USER, $PASS, $DBNAME);

    if(!isset($USER)||!isset($DBNAME)){ 
        echo "<?xml version=\"1.0\" standalone='yes'?>
<CONNECTION>
    <RESULT>
        <PASS>0</PASS>
        <ERROR>Please Login</ERROR>
        <USER>".$USER."</USER>
    </RESULT>
</CONNECTION>";
    }
    else if($db->connect_errno > 0){
        echo "<?xml version=\"1.0\" standalone='yes'?>
<CONNECTION>
    <RESULT>
        <PASS>0</PASS>
        <ERROR>".$db->connect_error."</ERROR>
        <USER>".$USER."</USER>
    </RESULT>
</CONNECTION>";
    }
    else{ 
        echo "<?xml version=\"1.0\" standalone='yes'?>
<CONNECTION>
    <RESULT>
        <PASS>1</PASS>
        <ERROR>No Error</ERROR>
        <USER>".$USER."</USER>
    </RESULT>
</CONNECTION>";
    }
    $db->close();
?>
