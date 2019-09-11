<?php
 
/*
 * All database connection variables
 */
 
/*define('DB_USER', "EBCC"); // db user
define('DB_PASSWORD', "tapebccprod"); // db password (mention your db password here)
define('DB_DATABASE', "tapapps"); // database name
define('DB_SERVER', "dboracle.tap-agri.com"); // db server*/

define('DB_USER', "ebcc"); // db user
define('DB_PASSWORD', "tapebccprod"); // db password (mention your db password here)
define('DB_DATABASE', "tapapps"); // database name
define('DB_SERVER', "10.20.1.207"); // db server


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
		//include("db_config.php");  // khusus offline pakai ini
 
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

//header("Refresh:3600");

//include("config/db_connect.php");
$con = connect();

//get all BA di EBCC
$query  = "
	select TJ.ID_BA as BA, 
                    SUBSTR(TE.ID_BA_AFD, 5,1) as AFD, 
                    TE.NIK as NIK, 
                    REPLACE(TE.EMP_NAME,'''','') as EMP_NAME,
                    TE.JOB_CODE as JOB_CODE from EBCC.T_JOBAUTHORITY TJ 
                 left join EBCC.T_EMPLOYEE TE on TE.ID_JOBAUTHORITY = TJ.ID_JOBAUTHORITY
                 where AUTHORITY = '13' and ID_BA_AFD = '4121B'
";
//$resultPt = oci_parse($con, $sql);
//oci_execute($resultPt, OCI_DEFAULT);

$sql1 = oci_parse($con,$query);
			oci_execute($sql1, OCI_DEFAULT);
	//$numRow = oci_fetch_array($sql1);
while ($ar=oci_fetch_array($sql1)){
				?>
				<tr>
					<td align='center'><?= $ar['BA'] ?></td>
					<td align='center'><?= $ar['AFD'] ?></td>
					<td align='center'><u><a href="javascript:pick('<?= $ar['NIK'] ?>');javascript:pickNAMA('<?= $ar['EMP_NAME'] ?>')"><?= $ar['NIK'] ?></a></u></td>
					<td align='center'><?= $ar['EMP_NAME'] ?></td>
					<td align='center'><?= $ar['JOB_CODE'] ?></td>
				</tr>
				<?php
			}

?>

<br/>
<br/>
<br/>
<br/>
<br/>
<a href="http://tap-motion.tap-agri.com/ebcc/array.rar"> donlot </a>
