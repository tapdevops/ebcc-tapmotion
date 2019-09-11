<html>
	<head>
		<title>Graphic Report Image</title>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.slides.min.js"></script>
		<style>	
		.container {
			width: 650px;
			height: 650px;
		  }
		  
		.slidesjs-pagination {
			display:none;
		}
		
		.slidesjs-navigation {
			display:none;
		}
		</style>
		<script type="text/javascript">
		$(function() {
		  $('#slides').slidesjs({
			width: 240,
			height: 228,
			play: {
			  active: true,
			  auto: true,
			  interval: 4000,
			  swap: true
			}
		  });
		  
		  $('#slides2').slidesjs({
			width: 240,
			height: 228,
			play: {
			  active: true,
			  auto: true,
			  interval: 4000,
			  swap: true
			}
		  });
		});
		
		</script>
	</head>
	<body>
		<table>
			<tr>
				<td>
				<div class="container">
					<div id="slides">
						<img class="" src="cekimagepie.php?id=5121">
						<img class="" src="cekimagepie.php?id=5131">
						<img class="" src="cekimagepie.php?id=5132">
						<img class="" src="cekimagepie.php?id=2121">
						<img class="" src="cekimagepie.php?id=4121">
					</div>
				</div>
				</td>
				<td>
				<div class="container">
					<div id="slides2">
						<img class="" src="cekimagepie.php?id=4122">
						<img class="" src="cekimagepie.php?id=4123">
						<img class="" src="cekimagepie.php?id=4221">
						<img class="" src="cekimagepie.php?id=4321">
						<img class="" src="cekimagepie.php?id=4421">
					</div>
				</div>
				</td>
			</tr>
		</table>	
		<!--<table>
			<tr>
				<td>
					<img class="" src="cekimagepie.php?id=5121" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=5131" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=5132" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=2121" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=4121" style="width:260px; height:260px;">
				</td>
			</tr>
			<tr>
				<td>
					<img class="" src="cekimagepie.php?id=4122" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=4123" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=4221" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=4321" style="width:260px; height:260px;">
				</td>
				<td>
					<img class="" src="cekimagepie.php?id=4421" style="width:260px; height:260px;">
				</td>
			</tr>
		</table>-->
	</body>
</html>