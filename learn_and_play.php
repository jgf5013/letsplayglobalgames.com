<script type="text/javascript" >



            $('#load_bottom').bind("click", function(event, ui) {
                loadingOverlay.setGlobeClicked();
            });
            
            $("body").css({
                overflow: "hidden"
            });
            
            var readyStateCheckInterval = setInterval(function() {
                /* they're still reading */
                if (!loadingOverlay.isGlobeClicked()) {
                    /* do nothing */
                }
                /* page isn't loaded yet but they're ready to play */
                else if ( (document.readyState !== "complete") && (loadingOverlay.isGlobeClicked()) ){
                    loadingOverlay.showSpinner();
                }
                /* page is loaded and they're ready to play */
                else if ( (document.readyState === "complete") && (loadingOverlay.isGlobeClicked()) ){
                    loadingOverlay.hide();
                    if( <?php $_SESSION['userSession']->writeLearnOrPlay(); ?> === "play" ) {
				        gameStatsPanel.draw();
                    }
                    clearInterval(readyStateCheckInterval);
                }
            }, 10);


            function Timer() {
                /* Extended by PanelTimer within play */
            }
            Timer.prototype.waitMilli = function(_ms) {
                /* http://www.sean.co.uk/a/webdesign/javascriptdelay.shtm */
                _ms += new Date().getTime();
                while (new Date() < _ms){}
            }
            
            

			/* functions used by both learn and by play pages */
			function JTabs() {
			    this.continent_game_choice = <?php $_CONTINENT->writeDirectoryName(); ?>;
			    this.tabList = <?php $_CONTINENT->writeTabNames(); ?>;
			    this.active_tab;
	
                $('#map_image_container_tabs ul').bind("click", function(event, ui) {
                    var selected = $("#map_image_container_tabs").tabs( "option", "active" );
                    var selectedTabTitle = $($("#map_image_container_tabs ul li")[selected]).text();
                    jTabs.setActiveTabContinentNameByDisplayName(selectedTabTitle);
                });
			}
			JTabs.prototype.getContinentGameChoice = function() {
				return this.continent_game_choice;
			}
			JTabs.prototype.setDefaultActiveTabContinentName = function() {
				if (jTabs.getContinentGameChoice() !== "world") {
					jTabs.setActiveTabContinentName(jTabs.getContinentGameChoice());
				}
				else {
					jTabs.setActiveTabContinentName("africa");
				}
			}
			JTabs.prototype.setActiveTabContinentName = function(_tab_slug_name) { //should really only be used by the event-bound jquery functions
				this.active_tab = _tab_slug_name;
			}
			JTabs.prototype.setActiveTabContinentNameByDisplayName = function(_tab_display_name) { //should really only be used by the event-bound jquery functions
			    
			    /* first get the slug */
                var local_tab;
                for(local_tab in jTabs.tabList) {
                    if( jTabs.tabList[local_tab]["displayName"] === _tab_display_name ) {
				        this.active_tab = jTabs.tabList[local_tab]["slug"];
				        break;
                    }
                }
			    
			}
			JTabs.prototype.getActiveTabContinentName = function() {
				return this.active_tab;
			}
			JTabs.prototype.getActiveTabName = function() {
				return "tabs_" + this.active_tab;
			}
			JTabs.prototype.openTabByContinentName = function(_tab_to_open) {
				/*$("#map_image_container_tabs").tabs("option", "active", $('#tabs_' + _tab_to_open).index());*/
				//doesn't seem like it's firing the event that's binded through jquery in the header for selecting a tab
				$("#map_image_container_tabs").tabs({selected: null});
				$("#map_image_container_tabs").tabs("option", "selected", $('#tabs_' + _tab_to_open).index() - 1);
				jTabs.setActiveTabContinentName(_tab_to_open);
			}
			JTabs.prototype.disableUnselected = function() {
                $("#map_image_container_tabs").tabs( {disabled: [0,1,2,3,4,5,6,7]} );
                $("#map_image_container_tabs").tabs("enable", $('#tabs_' + jTabs.getActiveTabContinentName()).index() - 1);
                $("#map_image_container_tabs").tabs("option", "active", $('#tabs_' + jTabs.getActiveTabContinentName()).index() - 1);
			}
			
			
			function Maps() {
                this.mapImage;
                this.current_country_down;
                this.is_country_down;
                this.click_coords;
                this.countryCapitalPair = <?php $_CONTINENT->writeCountryCapitalPairs(); ?>;
			}
			Maps.prototype.draw = function() {
			
				if (jTabs.getContinentGameChoice() !== "world") {
					/* only the map of the selected continent is activated */
                    this.mapImage = document.getElementById("map_image_" + jTabs.getActiveTabContinentName());
					this.mapImage.src = "/images/continents/" + jTabs.getActiveTabContinentName() + ".jpg";
					this.mapImage.alt = "map of " + jTabs.getActiveTabContinentName();
					jTabs.openTabByContinentName(jTabs.getActiveTabContinentName());
			        jTabs.disableUnselected();
				}
				else {
					var local_tab;
					for(local_tab in jTabs.tabList) {
						jTabs.setActiveTabContinentName(jTabs.tabList[local_tab]["slug"]);
						this.mapImage = document.getElementById("map_image_" + jTabs.tabList[local_tab]["slug"]);
						this.mapImage.src = "/images/continents/" + jTabs.getActiveTabContinentName() + ".jpg";
						this.mapImage.alt = "map of " + jTabs.getActiveTabContinentName();
					}
					jTabs.openTabByContinentName("africa");
				}
		
			}
			Maps.prototype.redraw = function(_event){
				
				<?php $_SESSION['userSession']->logger->writeLog("Maps.prototype.redraw", "redrawing map"); ?>
				this.is_country_down = false;
				this.click_coords = maps.getImageCoords(_event);
				for(var i = 0; i < continentPolygons.coordinates.length; i++) {
				
					if( continentPolygons.coordinates[i]["continent"] === jTabs.getActiveTabContinentName() ) {
						if(continentPolygons.isPointInPoly(continentPolygons.coordinates[i]["polygon"], this.click_coords)) {
							maps.setCurrentCountryDown(continentPolygons.coordinates[i]["name"]);
							maps.setCurrentDisplayCountryDown(continentPolygons.coordinates[i]["display_name_country"]);
							maps.setCurrentFlagDown(continentPolygons.coordinates[i]["flag_name"]);
							maps.setCurrentCountryCapitalDown(continentPolygons.coordinates[i]["display_name_capital"]);
							this.is_country_down = true;
							break;
						}
						if( i >= continentPolygons.coordinates.length - 1 ) {
							this.is_country_down = false;
							maps.setCurrentCountryDown("");
							maps.setCurrentDisplayCountryDown("");
							maps.setCurrentFlagDown("");
							maps.setCurrentCountryCapitalDown("");
						}
					}
				}
				
				this.mapImage = document.getElementById("map_image_" + jTabs.getActiveTabContinentName());
				if(!this.is_country_down) {
					this.mapImage.src = "/images/continents/" + jTabs.getActiveTabContinentName() + ".jpg";
					this.mapImage.alt = "map of " + jTabs.getActiveTabContinentName();
				}
				else {
					this.mapImage.src = "/images/continents/" + maps.getCurrentCountryDown() + ".jpg";
					this.mapImage.alt = "map of " + maps.getCurrentCountryDown();
				}

                return true;
			}
			Maps.prototype.setCurrentCountryDown = function(_country_down) {
				this.current_country_down = _country_down;
			}
			Maps.prototype.getCurrentCountryDown = function() {
				return this.current_country_down;
			}
			Maps.prototype.setCurrentDisplayCountryDown = function(_down) {
				this.current_display_country_down = _down;
			}
			Maps.prototype.getCurrentDisplayCountryDown = function() {
				return this.current_display_country_down;
			}
			Maps.prototype.setCurrentCountryCapitalDown = function(_down) {
				this.current_capital_down = _down;
			}
			Maps.prototype.getCurrentCountryCapitalDown = function() {
				return this.current_capital_down;
			}
			Maps.prototype.setCurrentFlagDown = function(_down) {
				this.current_flag_down = _down;
			}
			Maps.prototype.getCurrentFlagDown = function() {
				return this.current_flag_down;
			}
			Maps.prototype.getImageCoords = function(_event) {
			/* http://bugs.jquery.com/ticket/8523 <-- actually works!!! */
				
				pos_x = _event.offsetX?(_event.offsetX):_event.layerX-document.getElementById(jTabs.getActiveTabName()).offsetLeft;
				pos_y = _event.offsetY?(_event.offsetY):_event.layerY-document.getElementById(jTabs.getActiveTabName()).offsetTop;
				
				if( typeof _event.offsetX === "undefined" || typeof _event.offsetY === "undefined" ) {
				   var targetOffset = $(_event.target).offset();
				   pos_x = Math.round(_event.pageX - targetOffset.left);
				   pos_y = Math.round(_event.pageY - targetOffset.top);
				}
				
				return {
					'x': pos_x,
					'y': pos_y
				};
			}
			
			
			
			function ContinentPolygons() {
			
			    this.coordinates = <?php $_CONTINENT->getAllCountryCoords(); ?>;
			}
			ContinentPolygons.prototype.isPointInPoly = function(_poly, _pt){
				
				//+ Jonas Raoni Soares Silva
				//@ http://jsfromhell.com/math/is-point-in-poly [v1.0]
				for(var c = false, i = -1, l = _poly.length, j = l - 1; ++i < l; j = i)
					((_poly[i].y <= _pt.y && _pt.y < _poly[j].y) || (_poly[j].y <= _pt.y && _pt.y < _poly[i].y))
					&& (_pt.x < (_poly[j].x - _poly[i].x) * (_pt.y - _poly[i].y) / (_poly[j].y - _poly[i].y) + _poly[i].x)
					&& (c = !c);
				return c;
			}
			
			

			
						
			function Panel() {
			
			    categories = <?php GameData::loadAllCategories(); ?>;
			
                this.labelCountryName;
                this.labelCapitalName;
                this.flagImage;
			    this.flagColumnTab = document.getElementById("flag_column_tab");
			    this.isCountryNamesSelected = categories["is_country_names_selected"];
			    this.isCapitalNamesSelected = categories["is_capital_names_selected"];
			    this.isCountryFlagsSelected = categories["is_country_flags_selected"];
			    
			}
			
			

</script>