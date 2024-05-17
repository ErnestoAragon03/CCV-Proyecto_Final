<?php
$serverName = "your_server_name"; // Nombre del servidor SQL Server
$connectionInfo = array( "Database"=>"your_database_name", "UID"=>"your_username", "PWD"=>"your_password");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
}

$allProductsQuery = "SELECT * FROM products";
$stmt = sqlsrv_query($conn, $allProductsQuery);

if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    echo $row['name'] . ': $' . $row['price'] . '<br/>';
}

sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);
?>
