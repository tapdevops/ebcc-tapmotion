<style type="text/css">
.class_ul
{
list-style: none;
margin: 1px 2px 2px 1px;
width: 630px;
}
.class_li
{
display: block;
padding: 5px;
background-color: #8AE65C;
border-bottom: 1px solid #367;
}
.class_a{
	background-color: #ccc;
	color:#000;
	display:block;
}
a:hover{
	background:#000;
	color:#fff;
}
#content
{
padding:50px;
width:500px; border:1px solid #666;
float:left;
}
#clear
{ clear:both; }
#box
{
float:left;
margin:0 0 20px 0;
text-align:justify;
}
input[type=text]
{width:330px; height:35px;}
input[type=submit]
{
width:90px; height:35px;
}

</style>
<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		//$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		
		if(isset($_POST['afdeling']) && isset($_POST['var_tgl']) && isset($_POST['deliv_ticket'])){
			$afdeling = $_POST['afdeling'];
			$tgl_panen = strtoupper(date("d-M-y", strtotime($_POST['var_tgl']))); 
			$deliv_ticket=$_POST['deliv_ticket'];
			$sql_t_HP  = "select THP.ID_RENCANA as ID_RENCANA,
								 TANGGAL_RENCANA, 
								 SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3) as BLOK, 
								 NIK_PEMANEN, 
								 REPLACE(EMP_NAME,'''','') as EMP_NAME,
								 NO_TPH, 
								 KODE_DELIVERY_TICKET, 
								 NO_BCC,
								--(NVL(THK1.QTY,0)+NVL(THK2.QTY,0)+NVL(THK3.QTY,0)+NVL(THK4.QTY,0)) as JJG, 
								NVL (F_GET_HASIL_PANEN_BUNCH (SUBSTR(TDRP.ID_BA_AFD_BLOK,1,4), thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) as JJG, 
                                NVL(f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD'),0) BRD,
                                NVL(F_GET_BJR (SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3), THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK),0) BJR,
                                NVL(F_GET_BJR (SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3), THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK),0) * 
                                NVL (F_GET_HASIL_PANEN_BUNCH (SUBSTR(TDRP.ID_BA_AFD_BLOK,1,4), thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) ESTIMASI_BERAT
								 --THK5.QTY as BRD
						from T_HASIL_PANEN THP
						left join T_DETAIL_RENCANA_PANEN TDRP on (TDRP.ID_RENCANA = THP.ID_RENCANA and TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
						left join T_HEADER_RENCANA_PANEN THRP on (THRP.ID_RENCANA = THP.ID_RENCANA)
						-- left join T_HASILPANEN_KUALTAS THK1 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK1.ID_BCC = THP.NO_BCC and THK1.ID_KUALITAS = '1')
						-- left join T_HASILPANEN_KUALTAS THK2 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK2.ID_BCC = THP.NO_BCC and THK2.ID_KUALITAS = '2')
						-- left join T_HASILPANEN_KUALTAS THK3 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK3.ID_BCC = THP.NO_BCC and THK3.ID_KUALITAS = '3')
						-- left join T_HASILPANEN_KUALTAS THK4 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK4.ID_BCC = THP.NO_BCC and THK4.ID_KUALITAS = '4')
						-- left join T_HASILPANEN_KUALTAS THK5 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK5.ID_BCC = THP.NO_BCC and THK5.ID_KUALITAS = '5')
						left join T_EMPLOYEE TE on (TE.NIK = THRP.NIK_PEMANEN)
						where STATUS_BCC = 'RESTAN' and KODE_DELIVERY_TICKET like '$deliv_ticket%' and TDRP.ID_BA_AFD_BLOK like '$afdeling%' and TANGGAL_RENCANA <= TO_DATE('$tgl_panen', 'DD/MON/YY HH24:MI:SS')
						order by ID_RENCANA, TANGGAL_RENCANA, SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3), EMP_NAME";
			//echo $sql_t_HP;
			$result_t_HP = oci_parse($con, $sql_t_HP); 
			oci_execute($result_t_HP, OCI_DEFAULT);
			echo "<ul class='class_ul'>";
			while ($p=oci_fetch($result_t_HP)){
				?>
				<a class='class_a'><li class='class_li' onclick='fill("<?php echo oci_result($result_t_HP, "TANGGAL_RENCANA") . " - " . oci_result($result_t_HP, "BLOK") . " - " . oci_result($result_t_HP, "NIK_PEMANEN") . " - " . oci_result($result_t_HP, "EMP_NAME")
				. " - " . oci_result($result_t_HP, "NO_TPH") . " - " . oci_result($result_t_HP, "KODE_DELIVERY_TICKET") . " - " . oci_result($result_t_HP, "NO_BCC"); ?>")'><?php echo oci_result($result_t_HP, "TANGGAL_RENCANA") . " - " . oci_result($result_t_HP, "BLOK") . " - " . oci_result($result_t_HP, "NIK_PEMANEN") . " - " . oci_result($result_t_HP, "EMP_NAME")
				. " - " . oci_result($result_t_HP, "NO_TPH") . " - " . oci_result($result_t_HP, "KODE_DELIVERY_TICKET") . " - " . oci_result($result_t_HP, "NO_BCC"); ?></li></a>
				<?php
			}
		}
							
?>
</ul>

							
							