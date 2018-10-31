<?php
//SUPER helpful link
//https://www.oracle.com/webfolder/technetwork/tutorials/obe/db/11g/r2/prod/appdev/opensrclang/php/php.htm
include "database.php";

// function connectToDB(){
//   $conn=oci_connect($username,$password, $dbpath);
// 	if(!$conn) {
// 	     print "<br> connection failed:";
//         exit;
// 	}
//   return $conn;
// }
function searchBar($name){
	//connect to your database
	$conn = connectToDB();
	//	 Parse the SQL query
	$query = oci_parse($conn, "SELECT FROM Businesses where name= :bv");

	oci_bind_by_name($query,':bv',$name);
	// Execute the query
	oci_execute($query);


	oci_free_statement($query);
	oci_close($conn);

	return;
}

function viewQueue(){
	$conn=oci_connect('ajacob','BroncoDirectory', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	    print "<br> connection failed:";
	    exit;
	}
	// Parse the SQL query
	$req = oci_parse($conn, 'SELECT businessID,businessName FROM BUSINESSES INNER JOIN Verified ON verified.businessID = businesses.businessID');
	// Execute the query
	oci_execute($req);
	// Prepare to display results
	while (($row = oci_fetch_array($req, OCI_BOTH)) != false) {
	    // Use the uppercase column names for the associative array indices
	    echo"<h2>" .$row[0].$row[1]."</h2>\n";
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
				prepareInput(&$item);
			}
			insertSQL($fields)
		}
		else{
			echo "Did not complete required fields <br>"
		}



	}
}
function insertSQL($fields){
	//connect to your database
	$conn=oci_connect('ajacob','BroncoDirectory', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}	
	//	 Parse the SQL query
	$query = oci_parse($conn, "EXEC INSERTpro(:Aname,:Bname,:city, :phone, :businessType)");
	
	oci_bind_by_name($query,':Aname',$fields[0]);
	oci_bind_by_name($query,':Bname',$fields[1]);
	oci_bind_by_name($query,':city',$fields[2]);
	oci_bind_by_name($query,':phone',$fields[3]);
	oci_bind_by_name($query,':businessType',$fields[4]);

	// Execute the query
	oci_execute($query);
	
	if (($row = oci_fetch_array($query, OCI_BOTH)) != false) {		
		// We can use either numeric indexed starting at 0 
		// or the column name as an associative array index to access the colum value
		// Use the uppercase column names for the associative array indices		
		echo $row[0] . " and " . $row['TITLE']   . " are the same<br>\n";
		$title = $row['TITLE'];
	}
	else {
		echo "No such employee <br>\n";
	}
	oci_free_statement($query);
	oci_close($conn);
	
	return $title;
}		
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
