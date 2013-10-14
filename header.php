		<!--<div id="title_wrapper"></div>-->
		<div id="social_networking_container">
			<div id="social_networking">
				<?php include("social_media.php"); ?>
			</div>
		</div>
		<div id="subtitle">Let's Play Global Games</div>	
		<!-- 'Learn about your world' should go in here somewhere -->	
		<div id="navigation_header">
		</div>
		
		<ul class="topnav">
			<li><a href="/">Home</a></li>
			<li>
				<a id="feedback_link" title="your input" href="#">Your Input</a>
				<form name="index_nav_form" id="index_nav_form" action="/index" method="post">
			
					<div id="input_panel">
						<label>Good or bad, send us your thoughts and suggestions!
							<textarea id="user_input" name="user_input" type="text" ></textarea>
						</label>
						<input  type="button" id="input_panel_submit" name="input_panel_submit" value="Send!" onClick="validateInputPanel();">
					</div>
				</form>
			</li>
			<li id="score_navigator">
				<a href="#">High Scores</a>
				<ul class="subnav">
					<li><a href="#" onClick="highScoreNavForm.sendData('Country Names');">Country Names</a></li>
					<li><a href="#" onClick="highScoreNavForm.sendData('Capitals');">Capitals</a></li>
					<li><a href="#" onClick="highScoreNavForm.sendData('Flags');">Flags</a></li>
				</ul>
				<form name="high_score_nav_game_form" id="high_score_nav_game_form" method="post" action="/high_scores">
					<input name="high_score_nav_game_type" id="high_score_nav_game_type" type="hidden"/>
				</form>
			</li>
			<li><a href="/tweets">Twitter Feed</a></li>
			<li><a href="/about">About</a></li>
		</ul>
		
		
		<div id="ad_header" class="advertisement" >
			<p class="ad_text">Ads go here</p>
		</div>
		
		<div id="banner">
		</div>