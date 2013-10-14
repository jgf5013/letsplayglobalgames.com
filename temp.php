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
				alert($menu.toString());
            
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
                    $this.css('top',$menu.find('li:nth-child('+parseInt(i+2)+')')[0].offsetTop + 'px');
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
                        var offsetLeft = $this.offset().left + $(window).width() - ($this.outerWidth() + 20);
                        //slide in the description
                        $('#slidingMenuDesc > div:nth-child('+ parseInt($this.index()) +')').stop(true).animate({'width':offsetLeft+'px'},400, 'easeOutExpo');
                        //move the absolute div to this item
                        moveTo($this,400);
                      })
                      .bind('mouseleave',function(){
                        var $this = $(this);
                        var offsetLeft = $this.offset().left - 20;
                        //slide out the description
                        $('#slidingMenuDesc > div:nth-child('+ parseInt($this.index()) +')').stop(true).animate({'width':'0px'},400, 'easeOutExpo');
                      });
                  
                /**
                * moves the absolute div, 
                * with a certain speed, 
                * to the position of $elem
                */
                function moveTo($elem,speed){
                    $moving.stop(true).animate({
                        top		: $elem[0].offsetTop + 'px',
                        width	: $elem[0].offsetWidth + 'px'
                    }, speed, 'easeOutExpo');
                }
        });
            
                
            
			
			
		</script>

		<title>LetsPlayGlobalGames - Home</title>
	</head>

	<body onLoad="main()">
	
	
	<!--<div id="body_container">

		<div id="test_header_container">
			<?php include("test_header.php"); ?>
		</div>
		
        <div id="slidingMenuWrapper">-->
        
            <div id="slidingMenuDesc" class="slidingMenuDesc">
                <div><span><!--empty--></span></div>
                <div>
                    <span>
                        <!-- 
<div id="continent_chooser">
                            <div>Select Continent</div>
                            <div>
                                <div>
                                    <img src="" onClick=""/>
                                    <img src="" onMouseOver=""><!~~ regular black map of all continents ~~>
                                </div>
                            </div>
                        </div>
                        <div id="difficulty_chooser" class="hiddenMenuContainer">
                            <div>Difficulty?</div>
                            <!~~ some jquery slider ~~>
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
 -->

                    </span>
                </div>
                <div>
                    <span>
                      <!-- 
  <div>
                            <!~~ form and a text area ~~>
                        </div>
 -->

                    </span>
                </div>
                <div>
                    <span>
                       <!-- 
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
 -->
                    </span>
                </div>
                <div><span><!--empty--></span></div>
                <div><span><!--empty--></span></div>
            </div>
    
            <ul id="slidingMenu" class="slidingMenu">
                <li><a href="#">Learn</a></li>
                <li><a href="#">Quiz Myself</a></li>
                <li><a href="#">Your Input</a></li>
                <li><a href="#">High Scores</a></li>
                <li><a href="#">Twitter Feed</a></li>
                <li><a href="#">About</a></li>
            </ul>
            
		<!--<?php include_once("common_objects.php"); ?>
	</div>
 -->

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