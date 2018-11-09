<?php
//SUPER helpful link
//https://www.oracle.com/webfolder/technetwork/tutorials/obe/db/11g/r2/prod/appdev/opensrclang/php/php.htm
//include "database.php";
  
function connectToDB(){
	$cfg = parse_ini_file('setup.ini');
    $conn=oci_connect($cfg['db_user'], $cfg['db_pass'], $cfg['db_path']);
    if(!$conn) {
         print "<br> connection failed:";
         exit;
    }
    return $conn;
 }
function searchBar(){
	//connect to your database
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    # collect input data
		$fields = array($search = $_POST['search'],
			$type = $_POST['businesstype'],
			$city = $_POST['city']);
		#check if empty
		/*foreach ($fields as $item) {
			prepareInput($item);
		}*/
		searchSQL($fields);
	}

	return;
}
function searchSQL($fields){
	$conn=connectToDB();
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}	
	//	 Parse the SQL query
    $first = true;

    $search = 'SELECT businessName, phoneNo, city, type FROM Businesses NATURAL JOIN Verified';
    
     
    if($fields[0] != '' OR $fields[1] != 'all' OR $fields[2] != 'all'){
        $search .= ' WHERE ';
        if($fields[0] != ''){
            $search .= ' lower(:busname) = lower(businesses.businessName)';
            $first = false;
        }
        if($fields[1] != 'all'){
            if(!$first){
                $search .= ' AND';
            }
            $search .= ' lower(:busType) = lower(businesses.type)';
            $first = false;
        }
        if($fields[2] != 'all'){
            if(!$first){
                $search .= ' AND';
            }
            $search .= ' lower(:city) = lower(businesses.city)';
            $first = false;
        }
    }

    //echo "<br>QUERY:".$search."<br>";
    //foreach($fields as $item){
    //    echo $item."<br>";
    //}

    $query = oci_parse($conn, $search);
    
    if($fields[0] != ''){
	    oci_bind_by_name($query,':busname',$fields[0]);
    }
    if($fields[1] != 'all'){
	    oci_bind_by_name($query,':busType',$fields[1]);
    }
    if($fields[2] != 'all'){
	    oci_bind_by_name($query,':city',$fields[2]);
    }
    
	// Execute the query
	oci_execute($query);
	// Prepare to display results
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
	    // Use the uppercase column names for the associative array indices
    echo "<br><b>".$row[0].",".$row[1].",".$row[2].",".$row[3]."</b><br>";
	}
	
    oci_free_statement($query);
	oci_close($conn);
}
function viewCurrent(){
    $conn = connectToDB();
	if(!$conn) {
	    print "<br> connection failed:";
	    exit;
	}
	// Parse the SQL query
	$query = oci_parse($conn, 'SELECT businessID, businessName, phoneNo, city, type FROM VERIFIED NATURAL JOIN Businesses'); 
	// Execute the query
	oci_execute($query);
	// Prepare to display results
    echo "<br>";
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
	    // Use the uppercase column names for the associative array indices
        echo "<br><b>".$row[0].",".$row[1].",".$row[2].",".$row[3].",".$row[4]."</b><br>";
	}
	// Log off
	OCILogoff($conn);
    
}
function viewQueue(){
	$conn=connectToDB();
	if(!$conn) {
	    print "<br> connection failed:";
	    exit;
	}
	// Parse the SQL query
	$query = oci_parse($conn, 'SELECT businessID, businessName, phoneNo FROM BUSINESSES MINUS (SELECT businessID, businessName, phoneNo FROM VERIFIED NATURAL JOIN Businesses)'); 
    //INNER JOIN Verified ON verified.businessID = businesses.businessID');
	// Execute the query
	oci_execute($query);
	// Prepare to display results
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
	    // Use the uppercase column names for the associative array indices
    echo "<br><b>".$row[0]."\t".$row[1]."</b><br>";
	}
	// Log off
	OCILogoff($conn);
}

function createListing(){
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    # collect input data
		$fields = array($Aname = $_POST['Aname'],
			$Bname = $_POST['Bname'],
			$city = $_POST['city'],
			$phone = $_POST['phone'],
			$businessType = $_POST['businessType']);
		#check if empty
		if(checkFill($fields)){
			foreach ($fields as $item) {
				prepareInput($item);
			}
			insertSQL($fields);
		}
		else{
			echo "Did not complete required fields <br>";
		}
	}
}
function insertSQL($fields){
	//connect to your database
	$conn=connectToDB();
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}	
	//	 Parse the SQL query
	$query = oci_parse($conn, 'BEGIN INSERTpro(:Aname, :Bname, :city, :phone, :businessType);END;');
	
	oci_bind_by_name($query,':Aname',$fields[0]);
	oci_bind_by_name($query,':Bname',$fields[1]);
	oci_bind_by_name($query,':city',$fields[2]);
	oci_bind_by_name($query,':phone',$fields[3]);
	oci_bind_by_name($query,':businessType',$fields[4]);

    //echo $query."\n";
	// Execute the query
	oci_execute($query);
	oci_free_statement($query);
	oci_close($conn);
	
}		

function verifyListing(){
    if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['Verify'])) {
	    # collect input data
		$busID = $_POST['businessID'];
        if(!empty($busID)){
            prepareInput($busID);
            $busID = (int)$busID;
            verifySQL($busID);
        }
    }
}
function verifySQL($input){
    //connect to your database
	$conn=connectToDB();
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}	
	//	 Parse the SQL query
	$query = oci_parse($conn, 'INSERT INTO VERIFIED VALUES(TO_NUMBER(:businessID))');

    oci_bind_by_name($query, ':businessID',$input); 
	// Execute the query
	oci_execute($query);
	oci_free_statement($query);
	oci_close($conn);
}


function checkFill($array){
	foreach($array as $item){
		if(empty($item)){
			return false;
		}
	}
	return True;
}

function prepareInput($inputData){
	// Removes any leading or trailing white space
	$inputData = trim($inputData);
	// Removes any special characters that are not allowed in the input
  	$inputData  = htmlspecialchars($inputData);

  	return $inputData;
}
?>
<!-- end PHP script -->
