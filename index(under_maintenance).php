<?php
	session_start();
?>
<link rel="stylesheet" type="text/css" href="newcss/default.css" />
<link rel="stylesheet" href="css/slidorion.css" />

<script src="js/jquery.min.js" type="text/javascript"/></script>
<script src="js/jquery.slidorion.min.js"></script>

    
<script>
$(document).ready(function(){
	$('#slidorion').slidorion({
	 	speed: 1001,
		interval: 4000,
		effect: 'random'		
	});
});
</script>

<style type="text/css">
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
body,td,th {
	font-family: Tahoma;
	font-size: 16px;
}
p#blinking {text-decoration: blink;}
</style>
<script type="text/javascript" language="javascript">
 window.onload=blinkOn;
 
function blinkOn()
{
  document.getElementById("blink").style.color="#ff0000"
  setTimeout("blinkOff()",1000)
}
 
function blinkOff()
{
  document.getElementById("blink").style.color=""
  setTimeout("blinkOn()",1000)
}
 
 
 
</script>

<body bgcolor="#FFF" onLoad="waitPreloadPage();calc();">


<table width="901" height="725" border="0" align="center"> <!--table besar-->
  <tr style="background:url(image/logo.png) no-repeat; background-position:center">
    <th width="895" height="129" scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th height="360" scope="row">
    
    
                               	<div id="slidorion" style="visibility:hidden; z-index:8; margin-bottom: -2px" align="right">
                                <div id="slider" style="visibility:visible" align="right">
                                    <div id="slide1" class="slide" >
                                       <img src="image/pic.jpg" alt="" title="#accordion" border="0" width="896px" height="393px"/>
                                    </div>
                                    <div id="slide2" class="slide" >
                                        <img src="image/pic.jpg" border="0" width="896px" height="393px"/>
                                    </div>
                                    <div id="slide3" class="slide" >
                                        <img src="image/pic.jpg" border="0" width="896px" height="393px"/>
                                    </div>
                                    <div id="slide4" class="slide" >
                                       <img src="image/pic.jpg" border="0" width="896px" height="393px"/>
                                    </div>
                                </div>
                                
                                <div id="accordion" style="display:none;">
                                    <div class="link-header">a</div>
                                    <div class="link-content"></div>
                                    
                                    <div class="link-header">b</div>
                                    <div class="link-content"></div>
                                    
                                    <div class="link-header">c</div>
                                    <div class="link-content"></div>
                                    
                                    <div class="link-header">d</div>
                                    <div class="link-content"></div>
                                </div>
                            	</div>
    
    
    
    </th>
  </tr>
  <tr style="background:url(image/greenBack.png) no-repeat;background-position:center;">
    <th height="148" scope="row"><form id="frmlog" name="formlogin" action="alogin.php" method="post">
          <table width="297" height="144" border="0" align="center">
            <!--table login-->
            <tr>
              <td align='center' id="blink"><h1>UNDER MAINTENANCE</h1></td>
            </tr>
            <tr>
              <th colspan="2" align="center" scope="row"><?php
							if(isset($_SESSION['err'])){
								$err = $_SESSION['err'];
								if($err!=null)
								{
									echo $err;
									unset($_SESSION['err']);
								}
							}
			  			?></th>
            </tr>
          </table>
          <!--table login-->
    </form></th>
  </tr>
  <tr style="background:url(image/footer.png) no-repeat; background-position:center; margin-top: -2px">
    <th height="30" scope="row" style="font-size:12px" align="center">  Copyright 2013 - Sola Interactive</th>
  </tr>
</table> <!--tutup table besar-->
</body>