<?php
include 'common.php';


/* main */
$_CONTINENT = new Continent();
$_OVERLAY = new Overlay();
$_SESSION['userSession']->setHomePanelChoices();
$_CONTINENT->setTabNames();
$_CONTINENT->setContinentDirectoryImage();
$_CONTINENT->setContinentPolygonsAndCountries();
$_SESSION['userSession']->dataAccessor->insertPolygon();



function addSQLInsertForm() {
    global $_CONTINENT;
	

    //only add the dropdown if the user selected to edit polygons as an admin
    if( $_SESSION['userSession']->getUser() && ($_SESSION['userSession']->getUser() === 'admin') ) {
    

        if($_SESSION['userSession']->getContinentChoice() !== "World") {
            $query = 'SELECT ContinentDirectoryName, PolygonName, IsEnabled FROM Countries WHERE ContinentDirectoryName = "' . $_CONTINENT->getContinentDirectoryImage() . '" ORDER BY ContinentDirectoryName, PolygonName';
            $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
            $continent_array = array();
            if($result) {
                echo '<form id="polygon_edit_country_name" method="post" onSubmit="return polygonSQLForm.validate()" action="/learn">';
                echo '	<select input type="submit" name="edit_polygon_country_name">';
                echo '		<option value="--Select Country--">--Select Country--</option>';
                while($row = mysql_fetch_array($result))
                {
                    echo '		<option value="' . $row[0] . '_' . $row[1] . '">' . $row[0] . '_' . $row[1] . ': ' . $row[2] . '</option>';
                }
                echo '	</select>';
                echo '	Coordinates: <textarea name="polygon_coords" cols=50, rows=5></textarea>';
                echo '	<input type="submit" value="submit SQL">';
                echo '</form>';
            }
        }
        else {
            $query = 'SELECT ContinentDirectoryName, PolygonName, IsEnabled FROM Countries ORDER BY ContinentDirectoryName, PolygonName';
            $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
            $continent_array = array();
            if($result) {
                echo '<form id="polygon_edit_country_name" method="post" onSubmit="return polygonSQLForm.validate()" action="/learn">';
                echo '	<select input type="submit" name="edit_polygon_country_name">';
                echo '		<option value="--Select Country--">--Select Country--</option>';
                while($row = mysql_fetch_array($result))
                {
                    echo '		<option value="' . $row[0] . '_' . $row[1] . '">' . $row[0] . '_' . $row[1] . ': ' . $row[2] . '</option>';
                }
                echo '	</select>';
                echo '	Coordinates: <textarea name="polygon_coords" cols=50, rows=5></textarea>';
                echo '	<input type="submit" value="submit SQL">';
                echo '</form>';
            }
        }

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
			
				
				/* login_link */
				$(document).ready(function(){  
					$("#login_link").click(function(){
						$("#login_panel").slideToggle(400);
					})
				});

				$(document).ready(function(){  
					$("#logout_link").click(function() {
						$("#logout_input").val('yes');
						$("#logout_form").submit();
					})
				});
				
				
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
				
				
				
				
				$("#right_movable_container").tabs();
				
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
				
			});
			
			
			
			
			
		</script>
			
		<title>LetsPlayGlobalGames - Learn</title>
			
	</head>

	<body onLoad="main()">
	<div id="body_container">

		<div id="header_container">
			<?php include("header.php"); ?>
		</div>
		
		<div id="main_wrapper" class="learn_and_play">
		
			<div id="two_column">
				
                <div id="right_movable_container" class="main_column">
                    <div id="flag_column_tab" class="main_column">
                        <!--flag_column_tab-->
                        <h2>Highlighted Country</h2>
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
                    <div id="overlay_tabs_learn">
                        <?php Overlay::writeTabText(); ?>
                    </div>
                    <div id="overlay_map_learn">
                        <?php Overlay::writeMapText(); ?>
                    </div>
                    <div id="overlay_game_panel_learn">
                        <?php Overlay::writeGamePanelText(); ?>
                    </div>
                </div>
				
                <div id="overlay_bottom">
                    <img id="load_bottom" src="/images/globe_5_sunshine.png">
                    <p>Click the globe to begin!</p>
                </div>
			</div>
			
			<form name="high_score_form" method="post" action="high_scores">
				<input type="hidden" name="high_score_score" id="high_score_score" />
			</form>
			
			
			
		
			<br/>
			<br/>
			
			
		</div> <!--main_wrapper-->
		
		<?php addSQLInsertForm(); ?>
		
	
		<?php include_once("learn_and_play.php"); ?>
		<script type="text/javascript">
			
			
			/* javascript inheritance */
			LearnPanel.prototype = new Panel();
			LearnPanel.prototype.constructor = LearnPanel;
			function LearnPanel() {
			    /* only the attributes inherited from Panel are needed */
			}
			LearnPanel.prototype.draw = function() {
				
				var br_element = document.createElement("br");
				
				
				/* country name */
				if(this.isCountryNamesSelected === "yes") {
					var temp_label;
					temp_label = document.createElement("label");
                    temp_label.className += " quiz_question_heading_label"; /* do not omit the trailing space! */
					temp_label.innerHTML = "Country: ";
					
					this.flagColumnTab.appendChild(temp_label);
					
					this.labelCountryName = document.createElement("label");
					this.labelCountryName.id = "country_name";
                    this.labelCountryName.className += " quiz_question_label"; /* do not omit the trailing space! */
					
					this.flagColumnTab.appendChild(this.labelCountryName);
					this.flagColumnTab.appendChild(document.createElement("br"));
					
				}
				/* capital */
				if(this.isCapitalNamesSelected === "yes") {
					var temp_label;
					temp_label = document.createElement("label");
                    temp_label.className += " quiz_question_heading_label"; /* do not omit the trailing space! */
					temp_label.innerHTML = "Capital: ";
					
					this.flagColumnTab.appendChild(temp_label);
					
					this.labelCapitalName = document.createElement("label");
					this.labelCapitalName.id = "capital";
                    this.labelCapitalName.className += " quiz_question_label"; /* do not omit the trailing space! */
					
					this.flagColumnTab.appendChild(this.labelCapitalName);
					this.flagColumnTab.appendChild(document.createElement("br"));
					
				}
				/* flag */
				if(this.isCountryFlagsSelected === "yes") {
										
					this.flagImage = document.createElement("img");
					this.flagImage.id = "flag_image";
					this.flagImage.src = "/images/flags/_flag.jpeg";
					this.flagImage.alt = "flag logo";
					
					this.flagColumnTab.appendChild(this.flagImage);
					
					this.flagColumnTab.appendChild(document.createElement("br"));
					this.flagColumnTab.appendChild(document.createElement("br"));
								
				}
				

			}
			LearnPanel.prototype.redraw = function() {
				
				
				/* country name */
				if(learnPanel.isCountryNamesSelected === "yes") {
					learnPanel.labelCountryName.innerHTML = maps.getCurrentDisplayCountryDown();
				}
				/* capital */
				if(learnPanel.isCapitalNamesSelected === "yes") {
					learnPanel.labelCapitalName.innerHTML = maps.getCurrentCountryCapitalDown();
				}
				/* flag */
				if(learnPanel.isCountryFlagsSelected === "yes") {
					learnPanel.flagImage.src = "/images/flags/" + maps.getCurrentFlagDown() + "_flag.jpeg";
					learnPanel.flagImage.alt = "flag of " + maps.getCurrentFlagDown();
				}
				/* head of state */
				/* famous landmark */
				
			}
			
			
			/*Polygon SQL Functions*/
			function PolygonSQLForm() {
			    this.clickCoordinates;
			    this.valid;
			    this.coordinateForm = document.getElementById('polygon_edit_country_name');
			}
			PolygonSQLForm.prototype.addPoint = function(_event) {
				
				this.clickCoordinates = maps.getImageCoords(_event);
				
				this.coordinateForm.polygon_coords.value += "(" + this.clickCoordinates.x + ", " + this.clickCoordinates.y + "), ";
			}
			PolygonSQLForm.prototype.validate = function() {
				this.valid = false;
				if(this.coordinateForm.edit_polygon_country_name.value != "--Select Country--") {
					
					//take off the last comma and space in the list and add the semi-colon for SQL-statement ending
					this.coordinateForm.polygon_coords.value = this.coordinateForm.polygon_coords.value.slice(0, -2);
					this.coordinateForm.polygon_coords.value += ";";
					this.valid = true;
				}
				else {
					alert('Please select a country first');
				}
				return this.valid;
			}
			
			/* specific to learn */
			learnPanel = new LearnPanel();
			polygonSQLForm = new PolygonSQLForm();
            
			
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
				learnPanel.draw();
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
	</body>

</html>
<?php ob_flush(); ?>