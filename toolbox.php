<?php
//SUPER helpful link
//https://www.oracle.com/webfolder/technetwork/tutorials/obe/db/11g/r2/prod/appdev/opensrclang/php/php.htm
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    # collect input data

	 }
}

function connectToDB(){
  $conn=oci_connect($username,$password, $dbpath);
	if(!$conn) {
	     print "<br> connection failed:";
        exit;
	}
  return $conn;
}
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
  $conn = connectToDB();

  $query = oci_parse($conn, 'SELECT businessName FROM Businesses INNER JOIN Verified ON verified.businessID = businesses.businessID WHERE VERIFIED = 1;')
}

function createListing(){
  $conn = connectToDB();


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
