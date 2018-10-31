
<html>
<head>
</head>
<body>
hello
<?php
//connect to your database
// You must edit the line below to give your user name, password
// and a correct path to your database

$conn=oci_connect('ajacob','BroncoDirectory', '//dbserver.engr.scu.edu/db11g');
if(!$conn) {
    print "<br> connection failed:";
    exit;
}


// Parse the SQL query
$query = oci_parse($conn, "SELECT * FROM Businesses ");//INNER JOIN Verified ON verified.businessID = businesses.businessID");
// Execute the query
oci_execute($query);

// Prepare to display results
echo "<b>";
// Fetch each row. the first column is 0, then 1, etc.
while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
echo "<h1> helloworld</h1>";
    // Use the uppercase column names for the associative array indices
    echo"<h2>" .$row[1].$row[0] ."</h2>\n";
}


echo "</b>";


// Log off
OCILogoff($conn);
?>
</body>
</html>
