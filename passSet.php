<?php
    //include "toolbox.php";

    $cfg = parse_ini_file('setup.ini');
    $conn=oci_connect($cfg['db_user'], $cfg['db_pass'], $cfg['db_path']);
    if(!$conn) {
      print "<br> connection failed:";
      exit;
    }


    $action = readline("ENTER 1 to add credemtials, ENTER 2 to remove credentials");
    $username = readline("Enter a username: ");
    
    
    $conn = connectToDB();

    if($action == 1){
        $password = readline("Enter Password: ");
        $hash = password_hash($password, PASSWORD_DEFAULT);
                
        $query = oci_parse($conn, 'INSERT INTO CREDS VALUES(:username, :hash)');
        oci_bind_by_value($conn,':username', $username);
        oci_bind_by_value($conn, ':hash', $hash);
        
    }
    else{
        $query = oci_parse($conn, 'DELETE FROM CREDS WHERE username :username');
        oci_bind_by_value($conn, ':username',$username);
    }
    oci_execute($query);
    oci_free_statement($query);
    oci_close($conn);
?>
