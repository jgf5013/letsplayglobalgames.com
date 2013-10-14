<?php
include 'common.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<?php include_once("test_header_include.php"); ?>
		<?php include_once("google_analytics.php"); ?>
		
		
		<script type="text/javascript">
			
			
            
            $(document).ready(function(){
                var $menu = $("#slidingMenu");
                var $menuLock = false;
            
                /**
                * the first item in the menu, 
                * which is selected by default
                */
                var $selected = $menu.find('li:first');
            
                /**
                * this is the absolute element,
                * that is going to move across the menu items
                * it has the width of the selected item
                * and the top is the distance from the item to the top
                */
                var $moving = $('<li />', {
                    'class' : 'move',
                    'top'   : $selected[0].offsetTop + 'px',
                    'width' : $selected[0].offsetWidth + 'px'
                    });
            
                /**
                * each sliding div (descriptions) will have the same top
                * as the corresponding item in the menu
                */
                $('#slidingMenuDesc > div').each(function(i){
                    var $this = $(this);
                    $this.attr('clicklock', "false");
                    $this.css('top',$menu.find('li:nth-child('+parseInt(i+1)+')')[0].offsetTop + 'px');
                });
            
                /**
                * append the absolute div to the menu;
                * when we mouse out from the menu 
                * the absolute div moves to the top (like innitially);
                * when hovering the items of the menu, we move it to its position 
                */
                $menu.bind('mouseleave',function(){
                        moveTo($selected,400);
                      })
                     .append($moving)
                     .find('li')
                     .not('.move')
                     .bind('mouseenter',function(){
                        var $this = $(this);
                        var $desc = $('#slidingMenuDesc > div:nth-child('+ parseInt($this.index()+1) +')');
                        var offsetLeft = $this.offset().left + $(window).width() - ($this.outerWidth() + 20);
                        if($desc.attr('clicklock') === "false" && !$menuLock ) {               
                            //slide in the description
                            $desc.stop(true).animate({'width':offsetLeft+'px'},400, 'easeOutExpo');
                            //move the absolute div to this item
                            moveTo($this,400);
                        }
                      })
                      .bind('mouseleave',function(){
                        var $this = $(this);
                        var $desc = $('#slidingMenuDesc > div:nth-child('+ parseInt($this.index()+1) +')');
                        var offsetLeft = $this.offset().left - 20;
                        if($desc.attr('clicklock') === "false" && !$menuLock ) {      
                            //slide out the description
                            $desc.stop(true).animate({'width':'0px'},400, 'easeOutExpo');
                        }
                      })
                      .bind('click', function(){
                        var $this = $(this);
                        var $desc = $('#slidingMenuDesc > div:nth-child('+ parseInt($this.index()+1) +')');
                        var offsetLeft = $this.offset().left - 20;
                        if( $desc.attr('clicklock') === "true") {
                            //slide out the description
                            $desc.stop(true).animate({'width':'0px'},400, 'easeOutExpo');
                            $desc.attr('clicklock',"false");
                            $menuLock = false;
                            switchDescClass($desc);
                        }
                        else {
                            /* there's no clicklock on the item that you clicked on but there could still be some other menu lock*/
                            if(!$menuLock) {
                                var offsetLeft = $this.offset().left + $(window).width() - ($this.outerWidth() + 20);
                                //slide in the description
                                $desc.stop(true).animate({'width':offsetLeft+'px'},400, 'easeOutExpo');
                                $desc.attr('clicklock',"true");
                                $menuLock = true;
                                switchDescClass($desc);
                            }
                        }
                        

                      });
                  
                    
                  
                /**
                * moves the absolute div, 
                * with a certain speed, 
                * to the position of $elem
                */
                function moveTo($elem, speed){
                    if(!$menuLock) {
                        $moving.stop(true).animate({
                            top		: $elem[0].offsetTop + 'px',
                            width	: $elem[0].offsetWidth + 'px'
                        }, speed, 'easeOutExpo');
                    }
                }
                
                function switchDescClass($elem) {
                    var className = "";
                    switch($elem.index()+1) {
                        /* learn */
                        case 1:
                            className = "learnDescription";
                            $elem.toggleClass(className, 400);
                            break;
                        /* quiz myself */
                        case 2:
                            className = "quizDescription";
                            $elem.toggleClass(className, 400);
                            break;
                        /* your input */
                        case 3:
                            className = "inputDescription";
                            $elem.toggleClass(className, 400);
                            break;
                        /*high scores */
                        case 4:
                            className = "highscoreDescription";
                            $elem.toggleClass(className, 400);
                            break;               
                    }
                }
                
        });
            
                
            
			
			
		</script>

		<title>LetsPlayGlobalGames - Home</title>
	</head>

	<body onLoad="main()">
	
	
	
	<div id="body_container">

		<div id="test_header_container">
			<?php include("test_header.php"); ?>
		</div>
		
		<div id="opaque_wrapper">
		    <div id="opaque_background_globe"></div> 
        </div>
        <div id="slidingMenuWrapper">
        
            <div id="slidingMenuDesc" class="slidingMenuDesc">
                <div>
                    <span>Learn about the countries, capitals and flags of the world!
                        <div id="learnInput" class="basic button_select">
                            <div class="basic button_select">
                                <img src="/images/buttons/africa_button_75x70y.png" onclick="highScoreNavForm.sendData('Country Names');"/>
                                <label>Africa</label>
                                <img src="/images/buttons/asia_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>Asia</label>
                                <img src="/images/buttons/central_america_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>Central America</label>
                                <img src="/images/buttons/europe_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>Europe</label>
                                <img src="/images/buttons/north_america_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>North America</label>
                            </div>
                            <div class="basic button_select">
                                <img src="/images/buttons/oceania_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>Oceania</label>
                                <img src="/images/buttons/south_america_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>South America</label>
                                <img src="/images/buttons/middle_east_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>The Middle East</label>
                                <img src="/images/buttons/the_world_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                                <label>The World</label>
                            </div>
                        </span>
                    </div>
                </div>

                <div>
                    <span>The most fun you've ever had! Click to get started
                        <div id="continent_chooser">
                            <div>Select Continent</div>
                            <div>
                                <div>
                                </div>
                            </div>
                        </div>
                        <div id="difficulty_chooser" class="hiddenMenuContainer">
                            <div>Difficulty?</div>
                            <!-- some jquery slider -->
                        </div>
                        <div id="gametype_chooser" class="hiddenMenuContainer">
                            <div>Game Type</div>
                            <div>
                                <span>
                                    flags
                                    <img src="" onClick=""/>
                                    countries
                                    <img src="" onClick=""/>
                                    capitals
                                    <img src="" onClick=""/>
                                </span>
                            </div>
                        </div>


                    </span>
                </div>
                
                <div>
                    <span>Good or bad, send us your thoughts and suggestions!                        
                        <form name="index_nav_form" id="index_nav_form" action="/index" method="post">
                            <p><textarea id="userInput" name="user_input" type="text" ></textarea></p>
                            <p><input  type="button" id="input_panel_submit" name="input_panel_submit" value="Send!" onClick="validateInputPanel();"></p>
                        </form>

                    </span>
                </div>
                
                <div>
                    <span>Are you on the High Scores list??
                        <div>Game Type</div>
                        <div id="highscoreInput" class="basic button_select">
                            <img src="/images/buttons/flag_button_75x70y.png" onclick="highScoreNavForm.sendData('Flags');"/>
                            <label>flags</label>
                            <img src="/images/buttons/country_button_75x70y.png" onclick="highScoreNavForm.sendData('Country Names');"/>
                            <label>countries</label>
                            <img src="/images/buttons/capital_button_75x70y.png" onclick="highScoreNavForm.sendData('Capitals');"/>
                            <label>capitals</label>
                            <form name="high_score_nav_game_form" id="high_score_nav_game_form" method="post" action="/high_scores">
                                <input name="high_score_nav_game_type" id="high_score_nav_game_type" type="hidden">
                            </form>
                        </div>
                    </span>
                </div>
                <div><span>Tweet-tweet!!</span></div>
                <div><span>All about LetsPlayGlobalGames</span></div>
            </div>
    
            <ul id="slidingMenu" class="slidingMenu">
                <li><a href="#">Learn</a></li>
                <li><a href="#">Quiz Myself</a></li>
                <li><a id="feedbackLink" href="#">Your Input</a></li>
                <li><a href="#">High Scores</a></li>
                <li><a href="/tweets">Twitter Feed</a></li>
                <li><a href="/about">About</a></li>
            </ul>
            
		
            </div>
                <?php include_once("common_objects.php"); ?>
            </div>
            
        </div>
    </div> <!--body_container-->

	</body>
	<script type="text/javascript">
		
            learnForm = new LearnForm();
            playForm = new PlayForm();
            
			
			
			
			/* main */
			function main() {
			}
			
			function DifficultySlider() {			
                this.js_difficulty_bottom = 1;
                this.js_difficulty_top = 3;
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
</html>
<?php ob_flush(); ?>