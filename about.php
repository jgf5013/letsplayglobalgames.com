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
			
		<title>LetsPlayGlobalGames - About</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="highscores_page">
		
			<div id="inner" align="center" \>
				<h2 id="about_header" class="page_header">About</h2>
				
				<p id="basic_business_description">
                    LetsPlayGlobalGames is a website dedicated to educating
                    anyone interested about world geography and global
                    news & events. Through social-media, and competitive quizzes
                    LetsPlayGlobalGames hopes to raise awareness of non-first-world
                    countries in an increasingly connected world.
                    Have fun playing and make sure to share it with your friends!
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