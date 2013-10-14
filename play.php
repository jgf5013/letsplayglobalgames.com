<?php
include 'common.php';


/* main */
$_CONTINENT = new Continent();
$_OVERLAY = new Overlay();
$_SESSION['userSession']->setHomePanelChoices();
$_CONTINENT->setTabNames();
$_CONTINENT->setContinentDirectoryImage();
$_CONTINENT->setContinentPolygonsAndCountries();



function loadAllQuestions() {
    $difficulty = $_SESSION['userSession']->getDifficultyLevel();
	
	
    //PolygonName gives the lowercase format for the country which is also how the flags are named		

    if($_SESSION['userSession']->getContinentChoice() !== "World") {
        $query = 'SELECT PolygonName, Country, Capital FROM Countries '
            . 'WHERE IsEnabled '
            . 'AND Continent = "' . $_SESSION['userSession']->getContinentChoice() . '" '
            . 'AND Difficulty >= '. $difficulty['bottom'] . ' AND Difficulty <= '. $difficulty['top'] . ' '
            . 'AND IsPrimary '
            . 'ORDER BY IsSurrounded DESC, PolygonName';
    }
    else {//They are playing the world so get all the flags
        $query = 'SELECT DISTINCT PolygonName, Country, Capital FROM Countries '
            . 'WHERE IsEnabled '
            . 'AND Difficulty >= '. $difficulty['bottom'] . ' AND Difficulty <= '. $difficulty['top'] . ' '
            . 'AND IsPrimary '
            . 'ORDER BY IsSurrounded DESC, PolygonName';
    }
    $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
    if($result) {
        $j =  mysql_num_rows($result);
        for($i = 0; $i < $j - 1; $i++) {
            $row = mysql_fetch_row($result);
            echo '{"name": "' . $row[0] . '", "display_name": "' . $row[1] . '", "capital": "' . $row[2] . '", "passed": false}, ';
        }
        $row = mysql_fetch_row($result);
            echo '{"name": "' . $row[0] . '", "display_name": "' . $row[1] . '", "capital": "' . $row[2] . '", "passed": false}';
    }
    else {
        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
    }
        
}


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
				
				$("#map_image_container_tabs").tabs();
				$('#map_image_container_tabs').bind('tabsselect', function(event, ui) {
					jTabs.setActiveTabContinentName(($(ui.tab).attr("href")).substring(6));
				}); 
				
				
				$("#game_stats_panel").tabs();
				$("#flag_column_tab").tabs();
				/*$("#right_movable_container").tabs();*/
				
				var $window = $(window);
				var $dom_main_wrapper = $('#two_column');
				var $dom_right_movable_box = $('#right_movable_container');
				var $dom_map = $('#map_column');

				var dom_right_movable_box_top = $dom_right_movable_box.offset().top;

				$window.scroll(function() {
					var window_top = $window.scrollTop();
					if(window_top > dom_right_movable_box_top) {
						$dom_right_movable_box.css({position:"fixed"});
						$dom_right_movable_box.css({top:"0px"});
						$dom_right_movable_box.css("left",  $dom_main_wrapper.position().left + $dom_main_wrapper.innerWidth() - $dom_right_movable_box.outerWidth() - 20);
					} else {
						$dom_right_movable_box.css({position:"relative"});
						$dom_right_movable_box.css({top:"50px"});
						$dom_right_movable_box.css({left:"0px"});
					}
					
				});
				
				flashPanelCorrect = null;
				flashPanelIncorrect = null;
				function jqFlashQuestionCorrect() {
					setTimeout(
						function() {            
							$('#flag_column_tab').effect("highlight", {color: "#1F8200"}, 500);
						}, 3);
				}
				function jqFlashQuestionIncorrect() {
					setTimeout(
						function() {            
							$('#flag_column_tab').effect("highlight", {color: "#FF0000"}, 500);
						}, 3);
				}
				flashPanelCorrect = jqFlashQuestionCorrect;
				flashPanelIncorrect = jqFlashQuestionIncorrect;

                
			});
			
			
		</script>
			
		<title>LetsPlayGlobalGames - Play</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="learn_and_play">
		
			<div id="two_column">
				
				<div id="right_movable_container" class="main_column">
                    <div id="game_stats_panel">
                        <!--flag_column_tab-->
                        <h2>Game Stats</h2>
                        <table id="game_stats_table">
                            <tr><td align="right">Score: </td><td align="left" width="60px" id="display_score"></td></tr>
                            <tr><td align="right">Time: </td><td align="left" width="60px" id="game_time"></td></tr>
                            <tr><td align="right">Possible Points: </td><td align="left" width="60px" id="display_possible_points"></td></tr>
                            <tr><td align="right">Lives: </td><td align="left" width="60px" id="display_lives"></td></tr>
                        </table>
                    </div>
                    
                    <div id="flag_column_tab" >
                        <h2>Quiz Question</h2>
                        <!--flag_column_tab-->
                        <!--populated with javascript later-->
                    </div>
                </div>
				
				<div id="map_column" class="main_column">
					
					<!--Continent Image & Continent Map-->
					<div id="map_image_container_tabs">
						<?php $_CONTINENT->addTabsToPage(); ?>
					</div>

				</div>
								
			</div>
			
			<div id="overlay">
			    <div id="overlay_loading">
                    <p>Loading...</p>
                    <img src="/images/ajax-loader.gif">
				</div>
                <div id="overlay_instructions">
                    <div id="overlay_tabs_play">
                        <?php Overlay::writeTabText(); ?>
                    </div>
                    <div id="overlay_map_play">
                        <?php Overlay::writeMapText(); ?>
                    </div>
                    <div id="overlay_game_panel_play">
                        <?php Overlay::writeGamePanelText(); ?>
                    </div>
                    <div id="overlay_game_data_play">
                        <?php Overlay::writeGameDataText(); ?>
                    </div>
                </div>
				
                <div id="overlay_bottom">
                    <img id="load_bottom" src="/images/globe_5_sunshine.png">
                    <p>Click the globe to begin!</p>
                </div>
			</div>
			
			<form name="high_score_play_game_form" id = "high_score_play_game_form" method="post" action="high_scores">
				<input type="hidden" name="high_score_name" id="high_score_name" />
				<input type="hidden" name="high_score_score" id="high_score_score" />
				<input type="hidden" name="high_score_time" id="high_score_time" />
				<input type="hidden" name="high_score_play_game_type" id="high_score_play_game_type" />
			</form>
			
		</div> <!--main_wrapper-->
		
	
		<?php include_once("learn_and_play.php"); ?>
		<script type="text/javascript">
		
		
			var coordinate_form = document.getElementById('polygon_edit_country_name');
			
			
			function GameStatsPanel() {
                this.timer = new PanelTimer();
			    this.points_total = 0;
			    this.score_container = document.getElementById('display_score');
                this.possible_score_container = document.getElementById('display_possible_points');
			    this.lives_container = document.getElementById('display_lives');
                this.lives = 3;
			}                    
			GameStatsPanel.prototype.draw = function() {
				this.timer.startQuestionTime();
				this.score_container.innerHTML = gameStatsPanel.getGamePoints();
				this.possible_score_container.innerHTML = gameStatsPanel.getPossibleQuestionPoints();
				this.lives_container.innerHTML = gameStatsPanel.getLives();
                gameStatsPanel.timer.start();
				gameStatsPanel.tick();
				
			}
            GameStatsPanel.prototype.tick = function() {
                gameStatsPanel.timer.container.innerHTML = gameStatsPanel.timer.getGameTime();
                gameStatsPanel.possible_score_container.innerHTML = gameStatsPanel.getPossibleQuestionPoints();
                window.setTimeout("gameStatsPanel.tick()", 1000);
            }
            GameStatsPanel.prototype.redraw = function() {
                /* function is obsolete; use addQuestionPointsToGamePoints instead */
            }
			GameStatsPanel.prototype.getPossibleQuestionPoints = function() {
			
				var returned_points = 100 - 5*Math.ceil((this.timer.getCurrentQuestionTime()/1000)); 
				
				if(returned_points < 0) {
					returned_points = 0;
				}
				
				return returned_points;
			}
			GameStatsPanel.prototype.addQuestionPointsToGamePoints = function() {
			
				this.points_total = this.points_total + gameStatsPanel.getPossibleQuestionPoints();
				this.score_container.innerHTML = gameStatsPanel.getGamePoints();
				this.timer.startQuestionTime();
			}
			GameStatsPanel.prototype.getGamePoints = function() {
			
				return this.points_total;
			}
			GameStatsPanel.prototype.decreaseLives = function() {
			
				this.lives = this.lives - 1;
				if (this.lives < 1) {
					gameOver();
				}
			}
			GameStatsPanel.prototype.getLives = function() {
			
				return this.lives;
			}
			
			
			/* javascript inheritance */
			PanelTimer.prototype = new Timer();
			PanelTimer.prototype.constructor = PanelTimer;
			function PanelTimer() {
			
                this.container = document.getElementById('game_time');
                this.current_flag_time_start;
                this.current_flag_time_stop;
                this.time_start;
                this.time_stop;
                this.time_difference;
                this.current_flag_time_difference;
			}
            PanelTimer.prototype.getGameTime = function() {
            
                if (isNaN(this.time_start)) {
                    return 0;
                }
                return Math.ceil((new Date() - this.time_start)/1000);
            }
            PanelTimer.prototype.start = function() {
                this.time_start = new Date();
            }
            PanelTimer.prototype.startQuestionTime = function() {

                this.current_flag_time_start = new Date();
            }
            PanelTimer.prototype.stopQuestionTime = function() {
            
                this.current_flag_time_stop = new Date();
            }
            PanelTimer.prototype.getCurrentQuestionTime = function() {              
                
                if (isNaN(this.current_flag_time_start)) {
                    return 0;
                }
                return new Date() - this.current_flag_time_start;
            }
			
			
			
			/* javascript inheritance */
			PlayPanel.prototype = new Panel();
			PlayPanel.prototype.constructor = PlayPanel;
			function PlayPanel() {
                this.currentQuestionId;
                this.isCurrentQuestionIdSet;
			    this.gameType;
			    this.questionList = [<?php loadAllQuestions(); ?>];
			}
		 	PlayPanel.prototype.draw = function() {
			
				playPanel.setFirstQuestionId();
				
				var br_element = document.createElement("br");
				
				
				/* country name */
				if(playPanel.isCountryNamesSelected === "yes") {
					var temp_label;
					temp_label = document.createElement("label");
                    temp_label.className += " quiz_question_heading_label"; /* do not omit the trailing space! */
					temp_label.innerHTML = "Country: ";
					
					this.flagColumnTab.appendChild(temp_label);
					
					this.labelCountryName = document.createElement("label");
					this.labelCountryName.id = "country_name";
                    this.labelCountryName.className += " quiz_question_label"; /* do not omit the trailing space! */
					this.labelCountryName.innerHTML = playPanel.questionList[playPanel.getCurrentQuestionId()]["display_name"];
					
					this.flagColumnTab.appendChild(this.labelCountryName);
					this.flagColumnTab.appendChild(document.createElement("br"));
					
					
				}
				/* capital */
				if(playPanel.isCapitalNamesSelected === "yes") {
					var temp_label;
					temp_label = document.createElement("label");
                    temp_label.className += " quiz_question_heading_label"; /* do not omit the trailing space! */
					temp_label.innerHTML = "Capital: ";
					
					this.flagColumnTab.appendChild(temp_label);
					
					this.labelCapitalName = document.createElement("label");
					this.labelCapitalName.id = "capital";
                    this.labelCapitalName.className += " quiz_question_label"; /* do not omit the trailing space! */
					this.labelCapitalName.innerHTML = playPanel.questionList[playPanel.getCurrentQuestionId()]["capital"];
					
					this.flagColumnTab.appendChild(this.labelCapitalName);
					this.flagColumnTab.appendChild(document.createElement("br"));
					
				}
				
				/* flag */
				if(playPanel.isCountryFlagsSelected === "yes") {
										
					this.flagImage = document.createElement("img");
					this.flagImage.id = "flag_image";
					this.flagImage.alt = "flag of " + playPanel.questionList[playPanel.getCurrentQuestionId()]["name"];
					this.flagImage.src = "images/flags/" + playPanel.questionList[playPanel.getCurrentQuestionId()]["name"] + "_flag.jpeg";
					this.flagColumnTab.appendChild(this.flagImage);
					
					this.flagColumnTab.appendChild(document.createElement("br"));
					this.flagColumnTab.appendChild(document.createElement("br"));
								
				}

			}
			PlayPanel.prototype.redraw = function() {
			
				if( ((maps.getCurrentCountryDown() === jTabs.getActiveTabContinentName() + "_" + playPanel.questionList[playPanel.getCurrentQuestionId()]["name"]) && playPanel.isCurrentQuestionIdSet ) ||
					/* second criteria of capital is required because Jerusalem is claimed by both Israel and Palestine... what if you're playing ONLY capitals??? */
					/* you need the capital of the selected country and the quiz question capital */
					(maps.isOnlyPlayingCapitals() && maps.isCapitalDown( playPanel.questionList[playPanel.getCurrentQuestionId()]["capital"])) ) {
					
					/* current quiz question */
					gameStatsPanel.timer.stopQuestionTime();
					playPanel.passCurrentQuestionId();
					gameStatsPanel.addQuestionPointsToGamePoints();
					flashPanelCorrect();
					
					/* next quiz question */
					playPanel.setCurrentQuestionId();
					
					
					/* country name */
					if(playPanel.isCountryNamesSelected === "yes") {
					    playPanel.labelCountryName.innerHTML = playPanel.questionList[playPanel.getCurrentQuestionId()]["display_name"];
					}
					/* capital */
					if(playPanel.isCapitalNamesSelected === "yes") {
						playPanel.labelCapitalName.innerHTML = playPanel.questionList[playPanel.getCurrentQuestionId()]["capital"];
					}
					
					/* flag */
					if(playPanel.isCountryFlagsSelected === "yes") {
						playPanel.flagImage.src = "images/flags/" + playPanel.questionList[playPanel.getCurrentQuestionId()]["name"] + "_flag.jpeg";
						playPanel.flagImage.alt = "flag of " + playPanel.questionList[playPanel.getCurrentQuestionId()]["name"];
					}
					
				}
				else if(!playPanel.isCurrentQuestionIdSet) {
					//don't do anything - they just clicked too fast
				}
				else if (maps.getCurrentCountryDown() === "") {
					//don't do anything - they clicked but not on a highlighted country
				}
				else {
					//flag is set and ready to go but they got it wrong
					flashPanelIncorrect();
					gameStatsPanel.decreaseLives();
					gameStatsPanel.lives_container.innerHTML = gameStatsPanel.getLives();
				}
				
			}
			PlayPanel.prototype.setListType = function() {
			
                /* country names */
                if(this.isCountryNamesSelected === "yes") {
                    this.gameType = "Country Names";
                }
                /* capital */
                if(this.isCapitalNamesSelected === "yes") {
                    this.gameType = "Capitals";
                }
                /* flag */
                if(this.isCountryFlagsSelected === "yes") {
                    this.gameType = "Flags";
                }
			}
			PlayPanel.prototype.setFirstQuestionId = function() {
			
				this.currentQuestionId = Math.floor(Math.random() * this.questionList.length);
				this.isCurrentQuestionIdSet = true;
			}
			PlayPanel.prototype.passCurrentQuestionId = function() {
			
				this.isCurrentQuestionIdSet = false;
				this.questionList[playPanel.getCurrentQuestionId()]["passed"] = true;
			}
			PlayPanel.prototype.setCurrentQuestionId = function() {
			
				if(playPanel.isQuestionRemaining()) {
					while(this.questionList[playPanel.getCurrentQuestionId()]["passed"]) {
						this.currentQuestionId = Math.floor(Math.random() * this.questionList.length);
					}
					this.isCurrentQuestionIdSet = true;
				}
				else {
					submitWinner();
				}
			}
			PlayPanel.prototype.getCurrentQuestionId = function() {
			
				return this.currentQuestionId;
			}
			PlayPanel.prototype.isQuestionRemaining = function() {
				
				var flag_remaining = false;
				for(var i = 0; i < this.questionList.length; i++) {
					if (!this.questionList[i]["passed"]) {
						flag_remaining = true;
					}
				}
				return flag_remaining;
			}
			PlayPanel.prototype.getGameType = function() {
			
			    return this.gameType;
			}
			
			
			/* javascript inheritance */
            HighScoreGameForm.prototype = new HighScoreForm();
            HighScoreGameForm.prototype.constructor = HighScoreGameForm;
            function HighScoreGameForm() {
                HighScoreForm.call(this);
	            this.form = document.getElementById("high_score_play_game_form");
	            this.gameType = document.getElementById("high_score_play_game_type");
                this.winnerName = document.getElementById("high_score_name");
                this.gamePoints = document.getElementById("high_score_score");
                this.gameTime = document.getElementById("high_score_time");
            }
            HighScoreGameForm.prototype.sendData = function(_gameType, _name, _points, _time) {
                this.gameType.value = _gameType;
                this.winnerName.value = _name;
                this.gamePoints.value = _points;
                this.gameTime.value = _time;
                this.form.submit();
            }


			Maps.prototype.isOnlyPlayingCapitals = function() {
				return 	( playPanel.isCountryNamesSelected !== "yes" && 
						playPanel.isCapitalNamesSelected === "yes" &&
						playPanel.isCountryFlagsSelected !== "yes" );
						
			}
			Maps.prototype.isCapitalDown = function(_question_capital) {
				return _question_capital === maps.getCurrentCountryCapitalDown();
				
			}
			Maps.prototype.getCountry = function(_capital) {
			
                for(var i = 0; i < maps.countryCapitalPair.length; i++) {
                    if (_capital  === maps.countryCapitalPair[i]["country"] ) {
                        questionCapital_ = maps.countryCapitalPair[i]["country"];
                        break;
                    }
                }
                return questionCapital_;
			}
			
			
			
			/* Game Play Functions */
			function submitWinner() {
					time_stop = new Date();
					var name = "";
					while (name === "") {
					    name = prompt('You win!  Enter your name below:');
					}
					highScoreGameForm.sendData(playPanel.getGameType(), name, gameStatsPanel.getGamePoints(), gameStatsPanel.timer.getGameTime());
			}
			function gameOver() {
					alert('Sorry, too many incorrect clicks.  Try again!');
					location.reload();
			}
			
			
			/*Polygon SQL Functions*/
			function addSQLPoint(_event) {
				
				var click_coords = maps.getImageCoords(_event);
				
				coordinate_form.polygon_coords.value += "(" + click_coords.x + ", " + click_coords.y + "), ";
			}
			function ValidatePolygonSQL() {
				var valid = false;
				if(coordinate_form.edit_polygon_country_name.value != "--Select Country--") {
					
					//take off the last comma and space in the list and add the semi-colon for SQL-statement ending
					coordinate_form.polygon_coords.value = coordinate_form.polygon_coords.value.slice(0, -2);
					coordinate_form.polygon_coords.value += ";";
					valid = true;
				}
				else {
					alert('Please select a country first');
					valid = false;
				}
				return valid;
			}
			
			
			/* specific to play */
			gameStatsPanel = new GameStatsPanel();
			playPanel = new PlayPanel();
			highScoreGameForm = new HighScoreGameForm();
			
			/* both learn and play pages require these new objects on each page load */
			loadingOverlay = new LoadingOverlay();
			continentPolygons = new ContinentPolygons();
            pageTimer = new Timer();
			jTabs = new JTabs();
			maps = new Maps();
			
            
			/* main */
			function main() {
				<?php $_CONTINENT->writeCanvasListeners(); ?>
				jTabs.setDefaultActiveTabContinentName();
				playPanel.setListType();
				playPanel.draw();
				maps.draw();
				maps.setCurrentCountryDown("");//goes through a php function because needs session variables
			}
			
			
		</script>
		<script type="text/javascript" src="/js/social_networking.js"></script>
		<?php include_once("common_objects.php"); ?>
            
	
	</div><!--close the body_container div-->
	<div id="preload" class="hidden">
		<script type="text/javascript">
            <!--//--><![CDATA[//><!--
            if (document.images) {
                <?php $_CONTINENT->addPreloadedImages(); ?>
            }
            //--><!]]>
        </script>
	</div>
	</body>

</html>
<?php ob_flush(); ?>