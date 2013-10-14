<?php
include 'common.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

	<head>
		<?php include_once("header_include.php"); ?>
		<?php include_once("google_analytics.php"); ?>
		
		
		<script type="text/javascript">		
			/* jQuery Methods */
			
			$(function() {
							
				/* feedback_link */
				$(document).ready(function(){  
					$("#feedback_link").click(function(){
						$("#input_panel").slideToggle(400);
					})
				});
			});
			
		</script>
			
		<title>LetsPlayGlobalGames - Error</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="error_page">
		
			<div id="inner" align="center" \>
				<h2 id="error_header" class="page_header">Error</h2>
				
				<p id="error_page_text">
                    Unfortunately, we've encountered an error - We'll work to address
                    the issue as soon as possible, however Sam from the office has
                    decided to bring her dog to work today leading to a general lack
                    of interest from our developers... this may take some time.  Can 
                    you really blame them?
				</p>
			</div>
		
		
		</div><!--main_wrapper-->
		<div id="last_updated">
		</div>
		
		
		<script type="text/javascript">

			/*Main*/
			function main() {
			}
			
		</script>
		
		<script type="text/javascript" src="/js/social_networking.js"></script>
		<?php include_once("common_objects.php"); ?>
		
	</div><!--close the body_container div-->
	</body>

</html>
<?php ob_flush(); ?>