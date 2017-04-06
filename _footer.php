	<script type="text/javascript"> Cufon.now();

$(".nav a").mouseenter(function() {

	$(".effect").css({left:$(this).position().left + ($(this).width()*0.1)
   });
   });

$(".nav a").mouseleave(function() {
	$(".effect").css({left:'-10%'
   });
   });	</script>
	<div id="footer"> Copyright &copy WAGO Corporation <?php echo date("Y "); ?>
	<A HREF="http://www.wago.us">www.wago.us</A>
	</div> <!-- footer -->