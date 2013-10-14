<?php

include 'common.php';


$_SESSION['userSession']->setHighScoreGameType();


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
			
		<title>LetsPlayGlobalGames - High Scores</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="highscores_page">
		
			<div id="inner" align="center" \>
				<h2 id="game_type_header" class="page_header"></h2>
				<div class="highscore_arrow">
					<img id="high_score_up_arrow" src="images/up_arrow.png" onClick="highScores.updateTable('previous')" alt="Up Arrow"/>
				</div>
				<table id="high_scores_table" width="75%">
					<tr id="high_scores_header_tr">
						<td>Rank</td>
						<td>Name</td>
						<td>Score</td>
						<td>Time</td>
						<td>Continent</td>
						<td>Difficulty</td>
					</tr>
				</table>
				<div class="highscore_arrow">
					<img id="high_score_down_arrow" src="images/down_arrow.png" onClick="highScores.updateTable('next')" alt="Down Arrow"/>
				</div>
			</div>
			<div id="ads_on_bottom" class="advertisement highscores" >
				<p class="ad_text">Ads go here</p>
			</div>
		
		
		</div><!--main_wrapper-->
		
		
		<script type="text/javascript">
			
			
			function GameTypeHeader() {
				this.domHeader = document.getElementById('game_type_header');
			}
			GameTypeHeader.prototype.setType = function(_highScoreGameType) {
			    this.domHeader.innerHTML = "High Scores - " + _highScoreGameType;
			}
			
			
			function HighScores() {
			    this.scores = <?php $_SESSION['userSession']->highScores->loadHighScores(); ?>;
				this.new_high_score_rank = <?php $_SESSION['userSession']->writeRank(); ?>; /* only set to a number > 0 if they just got a new high score.  Else, set to 0 */
				this.table = document.getElementById('high_scores_table');
			    this.high_score_table_length = 25;
			    this.current_start_rank = 0;
				this.current_start_rank_increment;
                
                if(this.new_high_score_rank === 0) {
                    this.current_start_rank = 1;
                }
                else {
                    this.current_start_rank = ( Math.floor( ( this.new_high_score_rank  - 1 ) / this.high_score_table_length ) * this.high_score_table_length ) + 1;
                }
			}
			HighScores.prototype.populateTable = function() {
				
				var row;
				var cell = new Array();
				var row_iter = 1;
				var i = this.current_start_rank;
				for( row_iter; i < (this.current_start_rank + this.high_score_table_length) && i <= this.scores.length ; i++, row_iter++ ) {
					row = this.table.insertRow(row_iter);//start after the header row
					cell[0] = row.insertCell(0);
					cell[0].innerHTML = i;
					
					cell[1] = row.insertCell(1);
					cell[1].innerHTML = this.scores[i - 1]["name"];
					
					cell[2] = row.insertCell(2);
					cell[2].innerHTML = this.scores[i - 1]["score"];
					
					cell[3] = row.insertCell(3);
					cell[3].innerHTML = this.scores[i - 1]["time"];
					
					cell[4] = row.insertCell(4);
					cell[4].innerHTML = this.scores[i - 1]["continent"];
					
					cell[5] = row.insertCell(5);
					cell[5].innerHTML = this.scores[i - 1]["difficulty"];
					
					if( i === this.new_high_score_rank ) {
						row.bgColor='grey';
					}
					else {
						row.bgColor='white';
					}
				}
				
				/* create the rest of the table for the remaining highscore rows that aren't yet populated */
				if ( i >= this.scores.length ) {
					for ( row_iter; row_iter <= this.high_score_table_length; row_iter++ ) {
						row = this.table.insertRow(row_iter);//start after the header row
						for( var j = 0; j<=5; j++ ) {
							cell[j] = row.insertCell(j);
							cell[j].innerHTML = "";
						}
					}
				}
				
				arrows.drawArrows();
			}
			HighScores.prototype.updateTable = function(_high_score_direction) {
				
				var row;
				var cell = new Array();
				
				if( _high_score_direction === "next" ) {
					this.current_start_rank_increment = this.high_score_table_length;
				}
				else {
					this.current_start_rank_increment = -this.high_score_table_length;
				}
			
				this.current_start_rank = this.current_start_rank + this.current_start_rank_increment;

				var row_iter = 1;
				var i = this.current_start_rank;
				for( row_iter; i < (this.current_start_rank + this.high_score_table_length) && i <= this.scores.length ; i++, row_iter++ ) {
					row = this.table.rows.item(row_iter);//start after the header row
					
					row.cells.item(0).innerHTML = i;
					row.cells.item(1).innerHTML = this.scores[i - 1]["name"];
					row.cells.item(2).innerHTML = this.scores[i - 1]["score"];
					row.cells.item(3).innerHTML = this.scores[i - 1]["time"];
					row.cells.item(4).innerHTML = this.scores[i - 1]["continent"];
					row.cells.item(5).innerHTML = this.scores[i - 1]["difficulty"];
					if( i === this.new_high_score_rank ) {
						row.bgColor='grey';
					}
					else {
						row.bgColor='white';
					}
				}
				
				/* clear out the table text for the remaining highscore rows */
				if ( i >= this.scores.length ) {
					for ( row_iter; row_iter <= this.high_score_table_length; row_iter++ ) {
						row = this.table.rows.item(row_iter);
						for( var j = 0; j<=5; j++ ) {
							row.cells.item(j).innerHTML = "";
						}
					}
				}
				
				arrows.drawArrows();
				
			}
			
			
			function Arrows() {
			
			    this.upArrow = document.getElementById('high_score_up_arrow');
			    this.downArrow = document.getElementById('high_score_down_arrow');   
			}
			Arrows.prototype.drawArrows = function() {
				//only give the option to update the table if there are more values than are currently being displayed.
				if( highScores.current_start_rank - highScores.high_score_table_length > 0 ) {
					this.upArrow.style.visibility = "visible";
				}
				else {
					this.upArrow.style.visibility = "hidden";
				}
				if( highScores.current_start_rank + highScores.high_score_table_length <= highScores.scores.length ) {
					this.downArrow.style.visibility = "visible";
				}
				else {
					this.downArrow.style.visibility = "hidden";
				}
			
			}
			
			
    	    arrows = new Arrows();
			highScores = new HighScores();
            gameTypeHeader = new GameTypeHeader();
            
			
			
			/* main */
			function main() {
                gameTypeHeader.setType(<?php $_SESSION['userSession']->writeHighScoreGameType(); ?>);
				highScores.populateTable();
			}
			
		</script>
		
		<script type="text/javascript" src="/js/social_networking.js"></script>
		<?php include_once("common_objects.php"); ?>
		
	</div><!--close the body_container div-->
	</body>

</html>

