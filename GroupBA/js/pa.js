// JavaScript Document
	function lihat_food()
			{
				var food;
				if (window.XMLHttpRequest)
				{ 
					food = new XMLHttpRequest(); 
				} 
				else
				{ 
					food = new ActiveXObject("Microsoft.XMLHTTP"); 
				}
				
				<!-- proses send -->
				var food_id = document.getElementById("cek_food").value;
				food.open("GET","cek_food.php?food_id="+food_id,true);
				food.send();
				
				<!-- proses nangkapnya -->
				
				food.onreadystatechange=function() 
				{ 
					if (food.readyState==4 && food.status==200) 
					{ 
						document.getElementById("data_food").innerHTML = food.responseText; 
					} 
				}
			}
			
	function lihat_guest()
			{
				var guest;
				if (window.XMLHttpRequest)
				{ 
					guest = new XMLHttpRequest(); 
				} 
				else
				{ 
					guest = new ActiveXObject("Microsoft.XMLHTTP"); 
				}
				
				<!-- proses send -->
				var guest_id = document.getElementById("cek_guest").value;
				guest.open("GET","cek_guest.php?reservation_id="+guest_id,true);
				guest.send();
				
				<!-- proses nangkapnya -->
				
				guest.onreadystatechange=function() 
				{ 
					if (guest.readyState==4 && guest.status==200) 
					{ 
						document.getElementById("data_guest").innerHTML = guest.responseText; 
					} 
				}
			}
			
	function toggleColumn(n) {
			var currentClass = document.getElementById("mytable").className;
			if (currentClass.indexOf("show"+n) != -1) {
				document.getElementById("mytable").className = currentClass.replace("show"+n, "");
			}
			else {
				document.getElementById("mytable").className += " " + "show"+n;
			}
		}
		
    $(document).ready(function(){
    
        // hide #back-top first
        $("#back-top").hide();
        
        // fade in #back-top
        $(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#back-top').fadeIn();
                } else {
                    $('#back-top').fadeOut();
                }
            });
    
            // scroll body to 0px on click
            $('#back-top a').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        });
    
    });	
	
	$(document).ready(function(){
	$(".flip").click(function(){
		$(".panel").slideToggle("slow");
	  });
	});
	
	$(function() {
		$('#datepicker').datepicker({
		      changeMonth: true,
		      changeYear: true
	        });
	});
	