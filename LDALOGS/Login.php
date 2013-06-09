<?php
function authenticate($user, $password) {
    // Active Directory server
    $ldap_host = "ckxuradio.su.uleth.ca";

    // Active Directory DN
    $ldap_dn = "CN=Users,DC=ckxu,DC=com";

    // Active Directory user group
    $ldap_user_group = "WebUsers";

    // Active Directory manager group
    $ldap_manager_group = "WebAdmins";

    // Domain, for purposes of constructing $user
    $ldap_usr_dom = "@ckxu.com";

    // connect to active directory
    $ldap = ldap_connect($ldap_host);

    // verify user and password
    if($bind = @ldap_bind($ldap, $user . $ldap_usr_dom, $password)) {
        // valid
        // check presence in groups
        $filter = "(sAMAccountName=" . $user . ")";
        $attr = array("memberof");
        $result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
        $entries = ldap_get_entries($ldap, $result);
        ldap_unbind($ldap);

        // check groups
        foreach($entries[0]['memberof'] as $grps) {
            // is manager, break loop
            if (strpos($grps, $ldap_manager_group)) { $access = 2; break; }

            // is user
            if (strpos($grps, $ldap_user_group)) $access = 1;
        }

        if ($access != 0) {
            // establish session variables
            if($access == 1){
            	$_SESSION['usr'] = "user";
            	$_SESSION['rpw'] = "abuser";
				$_SESSION['access'] = $access;
            }
			else if($access == 2){
				$_SESSION['usr'] = "program";
            	$_SESSION['rpw'] = "pirateradio";
				$_SESSION['access'] = $access;
			}
			$_SESSION['account'] = $user;
            return true;
        } else {
            // user has no rights
            return false;
        }

    } else {
        // invalid name or password
        return false;
    }
}

// Establish Session
session_start();

// using ldap bind [Get Credentials]
$postuser  = $_POST['name'];// "@ckxu.com";//'uname';     // ldap rdn or dn
$postpass = $_POST['pass'];//'password';  // associated password

if($postpass == "abuser" && $postuser == "user"){
	$postpass = "Login123";
}

if(authenticate($postuser, $postpass)){
	header('Location: /masterpage.php');
}
else{
	echo "<h1>Error: Login Failed</h1><span><br/>You do not have DNS authenticated access<br/></span><input type='button' value='Return' onClick=\"javascript:window.location='\'\" />";
}


/*
// connect to ldap server
$ldapconn = ldap_connect('142.66.48.28')
    or die("Could not connect to LDAP server.");

if ($ldapconn) {

    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";
		
		ldap_unbind($ldapconn);
		session_start();
		$_SESSION['usr'] = "program";
		$_SESSION['rpw'] = "pirateradio";
		header('Location: https://ckxuradio.su.uleth.ca:8000/masterpage.php');
    }
    else {
        echo "LDAP bind failed...";
		ldap_unbind($ldapconn);
		
    }

}
*/
?>

