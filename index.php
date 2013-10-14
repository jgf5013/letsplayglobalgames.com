<?php
include 'common.php';



function addLearnContinentNameDropdown () {

	$query = 'SELECT DISTINCT Continent FROM Countries ORDER BY Continent';
	$result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
	$continent_array = array();
	if($result) {
		print "<select input type=\"submit\" id=\"learn_continent\" name=\"learn_continent\">\n";
		print "<option value=\"--Select Continent--\">--Select Continent--</option>";
		while($row = mysql_fetch_array($result))
		{
			print "<option value=\"" . $row[0] . "\">". $row[0] . "</option>\n";
		}
		/* take out the option for World until people know how to use the site ... don't want people leaving due to slow load times... */
		print "<option value=\"World\">World</option>\n";
		print "</select>\n";
	}
	else {
        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
	}
}
function addPlayContinentNameDropdown () {

	$query = 'SELECT DISTINCT Continent FROM Countries ORDER BY Continent';
	$result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
	$continent_array = array();
	if($result) {
		print "<select input type=\"submit\" id=\"play_continent\" name=\"play_continent\">\n";
		print "<option value=\"--Select Continent--\">--Select Continent--</option>";
		while($row = mysql_fetch_array($result))
		{
			print "<option value=\"" . $row[0] . "\">". $row[0] . "</option>\n";
		}
		/* take out the option for World until people know how to use the site ... don't want people leaving due to slow load times... */
		print "<option value=\"World\">World</option>\n";
		print "</select>\n";
	}
	else {
        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
	}
}
function loadCharities() {

	$query = 'SELECT Name, Amount FROM ' . DataAccessor::TABLE_CHARITIES . ' WHERE IsEnabled ORDER BY Amount DESC';
	$result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
	echo '[';
	if($result) {
		$j =  mysql_num_rows($result);
		for($i = 0; $i < $j-1 ; $i++) {
			$row = mysql_fetch_row($result);
			echo '{"name": "' . $row[0] . '", '
				. '"amount": "' . $row[1] . '"';
			echo '}, ';
		}
		/* the last entry */
		$row = mysql_fetch_row($result);
			echo '{"name": "' . $row[0] . '", '
				. '"amount": "' . $row[1] . '"';
		echo '}';
	}
	else {
        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
	}
	echo ']';
}
function loadDonors() {

	$query = 'SELECT Name, URL FROM ' . DataAccessor::TABLE_DONORS . ' ORDER BY Amount DESC';
	$result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
	echo '[';
	if($result) {
		$j =  mysql_num_rows($result);
		for($i = 0; $i < $j-1 ; $i++) {
			$row = mysql_fetch_row($result);
			echo '{"name": "' . $row[0] . '", '
				. '"url": "' . $row[1] . '"';
			echo '}, ';
		}
		/* the last entry */
		$row = mysql_fetch_row($result);
			echo '{"name": "' . $row[0] . '", '
				. '"url": "' . $row[1] . '"';
		echo '}';
	}
	else {
        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
	}
	echo ']';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<?php include_once("header_include.php"); ?>
		<?php include_once("google_analytics.php"); ?>
		
		
		<script type="text/javascript">		
			/* jQuery Methods */
			var min_slider_value = 1;
			var max_slider_value = 3;
            var temp_low = 1;
            var temp_high = 3;
			
			$(function() {
				
				/* difficulty slider and text */
				$( "#difficulty_slider" ).slider({
					min: 1,
					max: 10,
					range: true,
					values: [min_slider_value, max_slider_value],
					slide: function(event, ui) {
						min_slider_value = ui.values[0];
						max_slider_value = ui.values[1];
						$(this).prev().val(min_slider_value);
						$(this).next().val(max_slider_value);
						document.getElementById('dom_difficulty_text').innerHTML = "Difficulty range: " + min_slider_value + " - " + max_slider_value;
						$("#difficulty_level_bottom").val(min_slider_value);
						$("#difficulty_level_top").val(max_slider_value);
					}
				});
				document.getElementById('dom_difficulty_text').innerHTML = "Difficulty range: " + min_slider_value + " - " + max_slider_value;
				
				
				/* feedback_link */
				$(document).ready(function(){  
					$("#feedback_link").click(function(){
						$("#input_panel").slideToggle(400);
					})
				});
						
                $('#play_continent').change(function() {
                    if( $("#play_continent option:selected").text() === "North America" ) {
                        $("#difficulty_slider").slider('option',{min: 1, max: 8});
                    }
                    else {
                        $("#difficulty_slider").slider('option',{min: 1, max: 10});
                    }
                    
				    document.getElementById('dom_difficulty_text').innerHTML = "Difficulty range: " + $("#difficulty_slider").slider("values", 0) + " - " + $("#difficulty_slider").slider("values", 1);
                    $("#difficulty_level_bottom").val($("#difficulty_slider").slider("values", 0));
                    $("#difficulty_level_top").val($("#difficulty_slider").slider("values", 1));
                });
                
			});
			
			
			
		</script>

		<title>LetsPlayGlobalGames - Home</title>
	</head>

	<body onLoad="main()">
	
	
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper">
		
			<div id="two_column">
			
				
				<div id="left_column" class="main_column">
					<!--<div id="donated_to_console" class="console">
						<h2>Donations to Non-Profits</h2>
						<br/>
						<label><a href="/about">The more you play, the more we give!  Click here to find out how it works.</a></label>
						<table id="donated_to_table">
							<tr id="donated_to_header_tr">
							</tr>
						</table>
					</div>
					<div id="incoming_revenue_from_console" class="console">
						<h2>Contributors</h2>
						<table id="incoming_revenue_from_table">
							<tr id="incoming_revenue_from_header_tr" >
							</tr>
						</table>
					</div>-->
					<div id="leaderboard_console" class="console">
						<h2>Leader Board</h2>
						<table class="game_type_table" id="leaderboard_table">
							<tr id="leaderboard_header_tr" >
							</tr>
						</table>
					</div>
					
					<div id="learn_console" class="console">
						<h2>Learn</h2>
						<br/>
						<form name="learn_continent_form" method="post" onSubmit="return learnForm.validate()" action="/learn">
							<!--conitnent dropdown-->
							<?php addLearnContinentNameDropdown(); ?>
							<!--play button-->
							<button input type="submit">Learn!</button>
						</form>
					</div>
				</div>
				
				
				<div id="middle_column" class="main_column">
					<div id="instructions_console" class="console">
						<h2>Instructions</h2>
						<h6>(watch a quick video tutorial on how to play)</h6>
						<br/>
						<iframe width="420" height="275" src="http://www.youtube.com/embed/2eCCP7qri50" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				
				<div id="right_column" class="main_column">
				
					<div id="play_console" class="console">
						<h2>Play</h2>
						<!--game type options-->
						<form name="play_continent_form" method="post" onSubmit="return playForm.validate()" action="/play">
							<table class="game_type_table">
								<tr>
									<td class="game_type_table_td">
										<input id = "is_play_country_names_selected" name = "game_option" type="radio" value="Country Names" />
										<label class="icon_text"> Country Names</label>
									</td>
									<td class="game_type_table_td">
										<input id = "is_play_capital_names_selected" name = "game_option" type="radio" value="Capital Names" />
										<label class="icon_text"> Capital Names</label>
									</td>
								</tr>
								<tr>
									<td class="game_type_table_td" style="position: relative; left:60px;">
										<input id = "is_play_country_flags_selected" name = "game_option" type="radio" value="Country Flags" />
										<label class="icon_text"> Country Flags</label>
									</td>
								</tr>
							</table>
							
							<!-- difficulty slider -->
							<label id="dom_difficulty_text"></label>
							<div id="difficulty_slider" class="jquery" ></div>
							
							<!-- difficulty level -->
							<input type="hidden" id="difficulty_level_bottom" name="difficulty_level_bottom" />
							<input type="hidden" id="difficulty_level_top" name="difficulty_level_top" />
							<br/>
							
							<!--conitnent dropdown-->
							<?php addPlayContinentNameDropdown(); ?>
							<!--play button-->
							<button input type="submit">Play!</button>
						</form>
					</div>
					
				</div>
				
			</div><!--two_column-->
			<div id="ads_on_bottom" class="advertisement" >
				<p class="ad_text">Ads go here</p>
			</div>
			
			<br/>
			<br/>
			
		
		
		</div><!--main_wrapper-->
		
		<script type="text/javascript">
		
            /*difficultySlider = new DifficultySlider();*/
            leaderBoard = new LeaderBoard();
            learnForm = new LearnForm();
            playForm = new PlayForm();
            
			
			
			
			/* main */
			function main() {
				leaderBoard.populateTable();
			}
			
			function DifficultySlider() {			
                this.js_difficulty_bottom = 1;
                this.js_difficulty_top = 3;
			}
			
			function Charities() {
			    this.table = document.getElementById('donated_to_table');
			    this.charities  = <?php loadCharities(); ?>;
			}
			Charities.prototype.populateTable = function() {
				var row;
				var cell = new Array();
				for( var i = 0; i < this.charities.length; i++ ) {
					row = this.table.insertRow(i+1);//start after the header row
					/*
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = i + 1;
					*/
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = 'Information about ' + this.charities[i]["name"] + ' goes here';
					/*
					cell[1] = row.insertCell(1);
					cell[1].innerHTML = charities[i]["amount"];
					*/
					
				}
			}
			
			
			function Donors() {
				this.table = document.getElementById('incoming_revenue_from_table');
			    this.donors  = <?php loadDonors(); ?>;
			}
			Donors.prototype.populateTable = function() {
				var row;
				var cell = new Array();
				for( var i = 0; i < this.donors.length; i++ ) {
					row = this.table.insertRow(i+1);//start after the header row
					
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = '<a href="' + this.donors[i]["url"] + '" target="_blank">' + this.donors[i]["name"] + '</a>';
					
				}
			}
			
			
			function LeaderBoard() {
				this.table = document.getElementById('leaderboard_table');
		        this.leaders  = <?php $_SESSION['userSession']->highScores->loadLeaders(); ?>;
			}
			LeaderBoard.prototype.populateTable = function() {
				var row;
				var cell = new Array();
				for( var i = 0; i < this.leaders.length; i++ ) {
					row = this.table.insertRow(i+1);//start after the header row
					
					/*
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = i + 1;
					*/
				
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = this.leaders[i]["name"];
					
					cell[1] = row.insertCell(1);
					cell[1].innerHTML = this.leaders[i]["game_type"];
					
					cell[2] = row.insertCell(2);
					cell[2].innerHTML = this.leaders[i]["score"];
					
				}
			}
			
			
			function LearnForm() {
			    this.continent_dropdown = document.getElementById("learn_continent");
			    this.str_selected;
			    this.valid;
			}
			LearnForm.prototype.validate = function() {
			    this.str_selected = this.continent_dropdown.options[this.continent_dropdown.selectedIndex].value;
				this.valid = false;
				
				if(this.str_selected === "--Select Continent--") {
					alert('Please select a continent first');
				}
				else {
					this.valid = true;
				}
				
				return this.valid;
			}
			
			
			function PlayForm() {
				this.continent_dropdown = document.getElementById("play_continent");
				this.country_radio = document.getElementById("is_play_country_names_selected");
				this.capital_radio = document.getElementById("is_play_capital_names_selected");
				this.flag_radio = document.getElementById("is_play_country_flags_selected");
				this.str_selected;
				this.valid;
			}
			PlayForm.prototype.validate = function() {			
				this.str_selected = this.continent_dropdown.options[this.continent_dropdown.selectedIndex].value;
				this.valid = false;
				
				if (!( this.country_radio.checked || this.capital_radio.checked || this.flag_radio.checked )) {
					alert('Please select one of the options to play: Country Names, Capitals Names or Flags');
				}
				else if(this.str_selected === "--Select Continent--") {
					alert('Please select a continent first');
				}
				else {
					this.valid = true;
				}
				
				return this.valid;
			}
			

		</script>
		<script type="text/javascript" src="/js/Three.js"></script>
		<script type="text/javascript" src="/js/RequestAnimationFrame.js"></script>
		<script type="text/javascript" src="/js/Stats.js"></script>
		<script type="text/javascript" src="/js/social_networking.js"></script>
		<?php include_once("common_objects.php"); ?>
		
	</div><!--close the body_container div-->
	</body>
</html>
<?php ob_flush(); ?>