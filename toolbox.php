<?php

//Parameters: None
//Parses ini file to gain access to the oracle database
//Outputs: returns a connection to the DBMS
function connectToDB(){
	$cfg = parse_ini_file('setup.ini');
    $conn=oci_connect($cfg['db_user'], $cfg['db_pass'], $cfg['db_path']);
    if(!$conn) {
         print "<br> connection failed:";
         exit;
    }
    return $conn;
 }

 //Parameters: none
 //pulls the form information from the searchbar, and formats the data before sending to the database
 //Outputs: none
function searchBar(){
	//connect to your database
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    # collect input data
		$fields = array($search = $_POST['search'],
			$type = $_POST['businesstype'],
			$city = $_POST['city']);
		
        foreach ($fields as $item) {
			prepareInput($item);
		}
		searchSQL($fields);
	}

	return;
}

//Parameters: array of search input fields
//Established connection to database, Builds the query statement to filter results, executes search, and outputs results
//Output: none
function searchSQL($fields){
	$conn=connectToDB();
	
    //	 Parse the SQL query
    $first = true;

    $search = 'SELECT businessName, phoneNo, city, type, businessId FROM ((Businesses Natural Join Verified) NATURAL JOIN Addresses)';


    if($fields[0] != '' OR $fields[1] != 'all' OR $fields[2] != ''){
        $search .= ' WHERE ';
        if($fields[0] != ''){
            $search .= ' lower(:busname) = lower(businessName)';
            $first = false;
        }
        if($fields[1] != 'all'){
            if(!$first){
                $search .= ' AND';
            }
            $search .= ' lower(:busType) = lower(type)';
            $first = false;
        }
        if($fields[2] != ''){
            if(!$first){
                $search .= ' AND';
            }
            $search .= ' lower(:city) = lower(city)';
            $first = false;
        }
    } 

    $query = oci_parse($conn, $search);

    if($fields[0] != ''){
	    oci_bind_by_name($query,':busname',$fields[0]);
    }
    if($fields[1] != 'all'){
	    oci_bind_by_name($query,':busType',$fields[1]);
    }
    if($fields[2] != ''){
	    oci_bind_by_name($query,':city',$fields[2]);
    }

	// Execute the query
	oci_execute($query);
	// Prepare to display results
    echo "<br>Business &emsp;/ &emsp; City &emsp;/ &emsp; Type";
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
	    // Use the uppercase column names for the associative array indices
        echo "<br>".'<a href="businessDetails.html?id='.urlencode($row[4]).'">'.$row[0]."&emsp;/&emsp;".$row[2]."&emsp;/&emsp;".$row[3].'</a>';
    }

    oci_free_statement($query);
	oci_close($conn);
}

//Parameters: businessID
//Uses a businessID to query information for the business
//Output: returns an array containing the business information
function getDetails($busID){
    $conn = connectToDB();
    
    $query = oci_parse($conn, 'Select businessName, phoneNo, type, email, description, website, addressLine1, addressLine2, city, state,zipcode, country, businessID FROM ((Businesses NATURAL JOIN Addresses) NATURAL JOIN Verified) WHERE businessID = :busID');
    oci_bind_by_name($query, ':busID',$busID);
    oci_execute($query);

    $row = oci_fetch_array($query, OCI_BOTH);

    return $row;
}
//Parameters: businessID to locate the corresponding security check info
//Queries the database for security check information used in editing posts
//Outputs: returns an array containing the security question, answer, and businessID
function retrieveSecurityCheck($busID){
    $conn = connectToDB();
            
    $query = oci_parse($conn, 'Select securityQuestion, questionAns, businessID FROM ((Businesses NATURAL JOIN Addresses) NATURAL JOIN Verified) WHERE businessID = :busID');
    oci_bind_by_name($query, ':busID',$busID);
    oci_execute($query);

    $row = oci_fetch_array($query, OCI_BOTH);

    return $row;

}
//Parameters: none
//conducts a query search to display the current verified Businesses in the directory
//OUtput: none
function viewCurrent(){
    $conn = connectToDB();
	if(!$conn) {
	    print "<br> connection failed:";
	    exit;
	}
	// Parse the SQL query
	$query = oci_parse($conn, 'SELECT businessID, businessName, phoneNo, type FROM VERIFIED NATURAL JOIN Businesses');
	// Execute the query
	oci_execute($query);
	// Prepare to display results
    echo "<br>";
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false){
	    // Use the uppercase column names for the associative array indices
        echo "<br><b>".$row[0].",".$row[1].",".$row[2].",".$row[3]."</b><br>";
	}
	// Log off
	OCILogoff($conn);

}
//Parameters: none
//conducts a query search to display all pending businesses
//Output: none
function viewQueue(){
	$conn=connectToDB();
	if(!$conn) {
	    echo "<br> connection failed:";
	    exit;
	}
	// Parse the SQL query
	$query = oci_parse($conn, 'SELECT businessID, businessName, phoneNo FROM BUSINESSES MINUS (SELECT businessID, businessName, phoneNo FROM VERIFIED NATURAL JOIN Businesses)');
    //INNER JOIN Verified ON verified.businessID = businesses.businessID');
	// Execute the query
	oci_execute($query);

	// Prepare to display results
	while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
    echo "<br><b>".$row[0]."\t".$row[1]."\t".$row[2]."</b><br>";
	    // Use the uppercase column names for the associative array indices
	}
	// Log off
	OCILogoff($conn);
}
//Parameters: none
//Gathers and prepares the input fields from _POST on the create listing page
//Output: an array containing the input data fields to create listing 
function createListing(){
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    # collect input data
		$fields = array($Aname = $_POST['aName'],
            $phone = $_POST['pPhone'],
            $pEmail = $_POST['personalEmail'],
            $gradYear = $_POST['gradYear'],
            $securityQuestion = $_POST['securityQuestion'],
            $answer = $_POST['SQAnswer'], 
			$businessType = $_POST['businessType'],
			$bName = $_POST['bName'],
            $bPhone = $_POST['bPhone'],
            $bEmail = $_POST['businessEmail'],
            $aLine1 = $_POST['addressL1'],
            $aLine2 = $_POST['addressL2'],
			$city = $_POST['city'],
            $state = $_POST['state'],
            $zip = $_POST['zipCode'],
            $country = $_POST['country'],
			$description = $_POST['bDescription'],
            //$image = file_get_contents($_FILES['bImage']['tmp_name']),
            $website = $_POST['website']);
			
            foreach ($fields as $item) {
				prepareInput($item);
			}
			insertSQL($fields);
	}
}
//Parameters: an array containing the information used to create a business
//constructs the query instruction to insert data into the database
//Outputs: none
function insertSQL($fields){
	//connect to your database
	$conn=connectToDB();
	//Parse the SQL query
	$query = oci_parse($conn, 'BEGIN INSERTpro(:aName, :pPhone, :personalEmail, :gradYear, :securityQuestion, :SQAnswer, :businessType, :bName, :bPhone, :businessEmail, :addressL1, :addressL2, :city, :zipCode, :state, :country, :bDescription, :website);END;');

    //$blob = oci_new_descriptor($conn, OCI_D_LOB);
    oci_bind_by_name($query,':aName',$fields[0]);
    oci_bind_by_name($query,':pPhone',$fields[1]);
    oci_bind_by_name($query,':personalEmail',$fields[2]);
    oci_bind_by_name($query,':gradYear',$fields[3]);
    oci_bind_by_name($query,':securityQuestion',$fields[4]);
    oci_bind_by_name($query,':SQAnswer',$fields[5]);
    oci_bind_by_name($query,':businessType',$fields[6]);
    oci_bind_by_name($query,':bName',$fields[7]);
    oci_bind_by_name($query,':bPhone',$fields[8]);
    oci_bind_by_name($query,':businessEmail',$fields[9]);
    oci_bind_by_name($query,':addressL1',$fields[10]);
    oci_bind_by_name($query,':addressL2',$fields[11]);
    oci_bind_by_name($query,':city',$fields[12]);
    oci_bind_by_name($query,':zipCode',$fields[13]);
    oci_bind_by_name($query,':state',$fields[14]);
    oci_bind_by_name($query,':country',$fields[15]);
    oci_bind_by_name($query,':bDescription',$fields[16]);
    oci_bind_by_name($query,':website',$fields[17]);
    //oci_bind_by_name($result, ":bImage", $blob, -1, OCI_B_BLOB);

    //oci_execute($query, OCI_DEFAULT) or die ("Unable to execute query");
    
    //if(!$blob->save($image)) {
    //        oci_rollback($conn);
    //}
    //else {
    //        oci_commit($conn);
    //}
    //echo $query."\n";
	// Execute the query
	oci_execute($query);
	oci_free_statement($query);
	//$blob->free();
    oci_close($conn);

}
//Parameters: none
//Gets the business ID of the business to be verified 

function verifyListing(){
    if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['Verify'])) {
	    # collect input data
		$busID = $_POST['businessID'];
        if(!empty($busID)){
            prepareInput($busID);
            $busID = (int)$busID;
            //connect to your database
	        $conn=connectToDB();
	        //Parse the SQL query
	        $query = oci_parse($conn, 'BEGIN VERIFYbusiness(TO_NUMBER(:businessID));END;');//'INSERT INTO VERIFIED VALUES(TO_NUMBER(:businessID))');

            oci_bind_by_name($query, ':businessID',$input);
	        // Execute the query
	        oci_execute($query);
	        oci_free_statement($query);
	        oci_close($conn);
        }
    }
}
//Parameters: input data from the forms of the webpages
//trims excess whitespace and specialchars from the data
//Output: the cleaned up data
function prepareInput($inputData){
	// Removes any leading or trailing white space
	$inputData = trim($inputData);
	// Removes any special characters that are not allowed in the input
  	$inputData  = htmlspecialchars($inputData);

  	return $inputData;
}
?>
<!-- end PHP script -->
