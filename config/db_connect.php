<?php
 
/**
 * A class file to connect to database
 */
//class DB_CONNECT {
 
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }
 
    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }
 
    /**
     * Function to connect with database
     */
    function connect() {
        // import database connection variables
        //require_once __DIR__ . '/db_config.php';  //kalau online bisa di pakai
		include("db_config.php");  // khusus offline pakai ini
 
        // Connecting to mysql database
        $con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		
		
		
        // returing connection cursor
        return $con;
    }
 
    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        oci_close($con);
    }
 
//}
 
?>