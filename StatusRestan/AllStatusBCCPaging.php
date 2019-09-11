<?php
	session_start();

	
if(isset($_SESSION[NIK]) ){

	$Job_Code = $_SESSION[Job_Code];
	$username = $_SESSION[NIK];	
	$Emp_Name = $_SESSION[Name];
	$Jenis_Login = $_SESSION[Jenis_Login];	
	$subID_BA_Afd = $_SESSION[subID_BA_Afd];		//mengambil session yang berisi username dan jenisuser
	$sortby = $_GET[sortby];
	//echo $sortby;
	
		if($username == "")		//cek apa ada user masuk ke web tanpa login
		{
			$_SESSION[err] = "tolong login dulu!";
			header("location:../../login.php");
		}
		else
		{
			include '../../db_connect.php';
			$db = new DB_CONNECT();	

			$pagesize = 30;		// 1
			
			if($sortby == null) $sortby = "";
			$sql_valuec = "
			SELECT count(*) 
			FROM t_hasil_panen t1 
			INNER JOIN t_detail_rencana_panen t2 ON t1.No_Rekap_BCC = t2.No_Rekap_BCC 
			WHERE SUBSTRING(ID_BA_Afd_Blok,1,4) = '$subID_BA_Afd'
			$sortby
			"; 										// 2 
			
			$rs_valuec = mysql_query($sql_valuec);
			$fetch_valuec = mysql_fetch_array($rs_valuec);
			$totaldata = $fetch_valuec[0];
			
			$totalpage = ceil($totaldata/$pagesize);	// 3
			
			$sql_value = "
			SELECT t1.*, t2.ID_BA_Afd_Blok, t2.ID_Rencana, t2.Luasan_Panen 
			FROM t_hasil_panen t1 
			INNER JOIN t_detail_rencana_panen t2 ON t1.No_Rekap_BCC = t2.No_Rekap_BCC 
			WHERE SUBSTRING(ID_BA_Afd_Blok,1,4) = '$subID_BA_Afd'
			$sortby
			";
			$rs_value = mysql_query($sql_value);

			
			
			$ctr = 0;		//langkah 4
			$p = $_GET[p];
			if($p == null)
				$p = 0;
				$calcu = ($p*$pagesize);

			for($i=0;$i < $p*$pagesize; $i++)
			{
			$fetch_value = mysql_fetch_array($rs_value);
			}
		}
?>
<style type="text/css">
<!--tambahan-->
   body {
       margin:0; padding:0;
   }
   html, body, #background {
       height:auto;
       width:100%;
   }
   #background {
       position:absolute; 
       left:0;
       right:0;
       bottom:0;
       top:-20px;
       overflow:hidden;
       z-index:0;
   }
   #background img {
       width:100%;
	   height:100%;
       min-width:100%;
       min-height:100%;
	   max-height:100%;
	   max-width:100%;
	   
   }
   #isi {
	   position:fixed; 
       left:0;
       right:0;
       bottom:0;
       top:0;
       z-index:1;
	   overflow:scroll;
   } 
   <!--tambahan-->
</style>
<div id="background">
   <img style="display:block;" src="../../image/greenBack.png">
</div>
<div id="isi">
<table width="905" height="424" border="0" align="center">
  <tr style="background:url(../../image/logo.png) no-repeat; background-position:center">
    <th width="301" height="115" scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th height="372" scope="row" valign="top"><table width="893" border="0" align="center">
      <tr style="color:#FFF">
        <th width="295" align="left" scope="row" valign="baseline"><span style="font:normal">Welcome,</span> <span style="font:bold"><?=$Emp_Name?> (<?=$username?>)</span></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Bussiness Area :
          <?=$subID_BA_Afd?></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Job Code/  Login Type:
          <?=$Job_Code?> / <?=$Jenis_Login?></th>
      </tr>
      <tr>
        <td height="201" scope="row" style="font:Georgia, 'Times New Roman', Times, serif; font-size:18px ; font-style: italic; color: #8A0000" align="center" valign="top">
        
        <table width="857" height="220" border="0"  align="center">
          <tr>
            <th height="46" scope="row" style="font:Georgia, 'Times New Roman', Times, serif; font-size:24px ; font-style: italic; color: #666666; border-bottom:double #666666">Show All Hasil Panen</th>
          </tr>
          <tr>
            <th height="15" align="center" scope="row"><table width="1114" border="0">
              <tr>
                <th width="531" height="34" align="left" scope="row"><a href="../../welcome.php">
                  <input type="button" name="Report_U2" id="Report_U2" value="Home" style="width:140px; height:30px; color:#333"/>
                </a></th>
                <td width="589" align="right"><a href="../../db_disconnect.php">
                  <input type="button" name="Report_U2" id="Report_U3" value="Logout" style="width:140px; height:30px; color:#333"/>
                </a></td>
              </tr>
            </table></th>
          </tr>
          <tr>
            <th height="6" scope="row" style="font:Georgia, 'Times New Roman', Times, serif; font-size:18px ; font-style: italic; color: #8A0000" align="center"><?php
			$err = $_SESSION[err];
			if($err!=null){
				echo $err;
				unset($_SESSION[err]);
			}
		?></th>
          </tr>
          <tr>
            <th height="7" align="left" style="font:Georgia, 'Times New Roman', Times, serif; font-size:18px ; font-style: italic; color: #8A0000" scope="row"><form id="form1" name="form1" method="get" action="AllStatusBCCPaging.php">
              <table width="228" border="0">
                <tr>
                  <td width="59">Sort By</td>
                  <td width="9">:</td>
                  <td width="110">
                  <select  name="sortby" id="sortby" onchange="this.form.submit();">
                    <option value="">-- select table --</option>
                    <option value="ORDER BY t2.ID_Rencana">ID_Rencana</option>
                    <option value="ORDER BY t2.ID_BA_Afd_Blok">ID_BA_Afd_Blok</option>
                    <option value="ORDER BY t1.No_BCC">No_BCC</option>
                    <option value="ORDER BY t1.No_TPH">No_TPH</option>
                    <option value="ORDER BY t1.Kode_Delivery_Ticket">Kode_Delivery_Ticket</option>
                    <option value="ORDER BY t1.Status_BCC">Status_BCC</option>
                    <option value="ORDER BY t1.ID_NAB_tgl">ID_NAB_tgl</option>
                  </select>
                  </td>
                </tr>
              </table>
            </form></th>
          </tr>
          <tr>
            <th height="32" scope="row"><table width="877" border="1">
              <tr>
                <td width="28" scope="row">No.</td>
                <td width="29" scope="row">ID_Rencana</td>
                <td width="110">ID_BA_Afd_Blok</td>
                <td width="50">Luasan_Panen</td>
                <td width="50">No_BCC</td>
                <td width="60">No_TPH</td>
                <td width="36">Kode_Delivery_Ticket</td>
                <td width="60">Latitude</td>
                <td width="76">Longitude</td>
                <td width="182">Picture_Name</td>
                <td width="182">Status_BCC</td>
                <td width="186">ID_NAB_tgl</td>
                <td width="186">Edit</td>
              </tr>
          <?	
		 while ($fetch_value = mysql_fetch_array($rs_value))
	 	  {
				$calcu++;
				//echo $calcu;
			  
		  /*$trtd = "<tr>
		  <td>$y</td>
		  <td>$NIKJA[$x]</td>
		  <td>$Emp_NameJA[$x]</td>
		  <td>$Job_TypeJA[$x]</td>
		  <td>$Job_CodeJA[$x]</td>
		  <td>$ID_BA_AfdJA[$x]</td>
		  <td>$Activity_CodeJA[$x]</td>
		  <td>$ID_JobAuthorityJA[$x]</td>
		  <td>$AuthorityJA[$x]</td>
		  <td>$Authority_DescJA[$x]</td>
		  <td><a href=\"PreEditJA.php?NIK=$NIK[$x]\">
          <input type=\"button\" name=\"Report_U\" id=\"Report_U\" value=\"Edit\" style=\"width:140px; height:30px; color:#333\"/>
          </a></td>
		  
		  </tr>";	
		  echo $trtd; */
		  ?>
          <tr>
        	
            <td><?=$calcu?></td>
            <td><?=$fetch_value[ID_Rencana]?></td>
            <td><?=$fetch_value[ID_BA_Afd_Blok]?></td>
            <td><?=$fetch_value[Luasan_Panen]?></td>
            <td><?=$fetch_value[No_BCC]?></td>
            <td><?=$fetch_value[No_TPH]?></td>
            <td><?=$fetch_value[Kode_Delivery_Ticket]?></td>
            <td><?=$fetch_value[Latitude]?></td>
            <td><?=$fetch_value[Longitude]?></td>
            <td><?=$fetch_value[Picture_Name]?></td>
            <td><?=$fetch_value[Status_BCC]?></td>
            <td><?=$fetch_value[ID_NAB_tgl]?></td>
            <td><a href="EditStatusBCC.php?No_TPH=<?=$fetch_value[No_TPH]?>&No_BCC=<?=$fetch_value[No_BCC]?>">
          <input type="button" name="Report_U" id="Report_U" value="Edit" style="width:140px; height:30px; color:#333"/>
          </a></td>
          </tr>
		  
		  
		  	<?
				$ctr ++;
				//echo $ctr. " and ". $pagesize;
				if($ctr == $pagesize) break;
				}
		  	?>
            </table>
            
            <?
				for($j=0; $j < $totalpage; $j++)		//langkah 5
				{
			?>
				<a href="AllStatusBCCPaging.php?p=<?=$j?>&sortby=<?=$sortby?>"><?=$j+1?></a>
			<?		
				}
			?>
            
            </th>
          </tr>
          <tr>
            <th height="32" scope="row">&nbsp;</th>
          </tr>
          <tr>
            <th height="32" scope="row">&nbsp;</th>
          </tr>
        </table></td>
      </tr>
      <tr>
        <th scope="row"><a href="../../db_disconnect.php">
          <input type="button" name="Report_U" id="Report_U" value="Logout" style="width:140px; height:30px; color:#333"/>
        </a></th>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
      </tr>
      <tr>
        <th align="left" scope="row">&nbsp;</th>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
      </tr>
    </table></th>
  </tr>
  <tr style="background:url(../../image/footer.png) no-repeat; background-position:center; margin-top: -2px">
    <th height="30" scope="row" style="font-size:12px" align="center"> Copyright 2013 - Sola Interactive</th>
  </tr>
</table>
</div>
<?
}
else{
	$_SESSION[err] = "tolong login dulu!";
	header("location:../login.php");
}
?>