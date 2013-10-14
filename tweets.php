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
			
		<title>LetsPlayGlobalGames - Twitter Feed</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="highscores_page">
		
			<div id="inner" align="center" \>
				<h2 id="twitter_header" class="page_header">Twitter Feed</h2>
				<!-- twitter feed goes here -->
				<a class="twitter-timeline" href="https://twitter.com/PlayGlobalGames" data-widget-id="299689197848109057">Tweets by @PlayGlobalGames</a>
				<script>
					!function(d,s,id){
						var js,fjs=d.getElementsByTagName(s)[0];
						if(!d.getElementById(id)){
							js=d.createElement(s);
							js.id=id;
							js.src="//platform.twitter.com/widgets.js";
							fjs.parentNode.insertBefore(js,fjs);
						}
					}(document,"script","twitter-wjs");
				</script>

			</div>
		
		
		</div><!--main_wrapper-->
		
		
		<script type="text/javascript">

			/* main */
			function main() {
			}
			
		</script>
		
		<script type="text/javascript" src="/js/social_networking.js"></script>
		<?php include_once("common_objects.php"); ?>
		
	</div><!--close the body_container div-->
	</body>

</html>




