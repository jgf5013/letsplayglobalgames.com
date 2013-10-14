<?php
class Continent {

    
    /* private */
    private $continentImageDirectory;
    private $map_names_array;
    private $continent_countries_array;
    private $points_for_country;
    private $tab_labels_array;
    private $tab_names_array;
    
    
    

    function writeCanvasListeners() {
        $_SESSION['userSession']->logger->writeLog('Continent::writeCanvasListeners', 'entering');
    
    
        if($_SESSION['userSession']->getContinentChoice() !== "World") {

            $_SESSION['userSession']->logger->writeLog('Continent::writeCanvasListeners', 'User is not playing World');
        
        
        
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                if( $_SESSION['userSession']->getUser() && ($_SESSION['userSession']->getUser() === 'admin') ) {
                    echo '$("#map_image_' . $this->getContinentDirectoryImage() . '").bind({ '
                        . 'mousedown: function(event) { polygonSQLForm.addPoint(event); } '
                        . '});';               
                }
                else {
                    echo '$("#map_image_' . $this->getContinentDirectoryImage() . '").bind({ '
                        . 'mousemove: function(event) { maps.redraw(event); learnPanel.redraw(event); pageTimer.waitMilli(3); } '
                        . '});';		
                }
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '$("#map_image_' . $this->getContinentDirectoryImage() . '").bind({ '
                    . 'mousemove: function(event) { maps.redraw(event); pageTimer.waitMilli(3); }, '
                    . 'mousedown: function(event) { playPanel.redraw(event); } '
                    . '});';
            }
        }
        else {//They are playing the world so add listeners on each continent then deal with overlapping polygon issues later
    
            $_SESSION['userSession']->logger->writeLog('Continent::writeCanvasListeners', 'User is playing World');
    
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                if( $_SESSION['userSession']->getUser() && ($_SESSION['userSession']->getUser() === 'admin') ) {
                    $arr = $this->getMapNamesArray();
                    foreach ($arr as &$continent_country_pair) {
                        echo '$("#' . $continent_country_pair . '").bind({ '
                            . 'mousedown: function(event) { polygonSQLForm.addPoint(event); } '
                            . '});';
                    }
                }
                else {
                    $arr = $this->getMapNamesArray();
                    foreach ($arr as &$continent_country_pair) {
                        echo '$("#' . $continent_country_pair . '").bind({ '
                            . 'mousemove: function(event) { maps.redraw(event); learnPanel.redraw(event); pageTimer.waitMilli(3); } '
                            . '});';
                    }
                }
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                $arr = $this->getMapNamesArray();
                foreach ($arr as &$continent_country_pair) {
                    echo '$("#' . $continent_country_pair . '").bind({ '
                        . 'mousemove: function(event) { maps.redraw(event); pageTimer.waitMilli(3); }, '
                        . 'mousedown: function(event) { playPanel.redraw(event); } '
                        . '});';
                }
            }
        }
        
    
        $_SESSION['userSession']->logger->writeLog('Continent::writeCanvasListeners', 'exiting');

    }
    
    
    
    public function addPreloadedImages() {
    
    
        $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'entering');
        $count = 0;
        if($_SESSION['userSession']->getContinentChoice() !== "World") {
            $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'not playing world');
            foreach ($this->continent_countries_array as &$continent_country_pair) {
                /* only load images for the continent user will need */
                if( $this->getContinentDirectoryImage() === $continent_country_pair[0] ) {
                    echo 'img' . $count . ' = new Image();';
                    echo 'img' . $count . '.src="/images/continents/' . $continent_country_pair[0] . '_'
                        . $continent_country_pair[1] . '.jpg";';
                    $count = $count + 1;
                }
            }
            $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'preloading images complete');
            unset($country);
        }
        else {//They are playing the world so get all the country images of each continent.... shit, this may take a while....
            $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'playing world');
            foreach ($this->continent_countries_array as &$continent_country_pair) {
                echo 'img' . $count . ' = new Image();';
                echo 'img' . $count . '.src="/images/continents/' . $continent_country_pair[0] . '_'
                    . $continent_country_pair[1] . '.jpg";';
                    $count = $count + 1;
            }
            $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'preloading images complete');
            unset($continent_country_pair);
        }
        $_SESSION['userSession']->logger->writeLog('Continent::addPreloadedImages', 'exiting');
    
    }
        
    
    public function getMapNamesArray() {
        
        return $this->map_names_array;
    }
    
    public function addTabsToPage() {
    
        echo '<ul>';
        for($i = 0; $i < count($this->tab_names_array); $i++) {
            echo '<li><a href="#' . $this->tab_names_array[$i] . '">' . $this->tab_labels_array[$i] .'</a></li>';
        }
        echo '</ul>';
    
        /* $this->tab_names_array and $this->map_names_array are guarunteed to be the same length by the query in Continent::setTabNames... */
    
        for($i = 0; $i < count($this->tab_names_array); $i++) {
            echo '<div id="' . $this->tab_names_array[$i] . '">';
            echo '<img id="' . $this->map_names_array[$i] . '"/>';
            echo '</div>';
        }

    }
    
    
    public function writeTabNames() {
    
        //just stores the lower-case-directory names of all continents ... north_america
        $query = 'SELECT DISTINCT ContinentDirectoryName, Continent FROM Countries ORDER BY ContinentDirectoryName';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        echo '[';
        if($result) {
            $j =  mysql_num_rows($result);
            for($i = 0; $i < $j-1 ; $i++) {
                $row = mysql_fetch_row($result);
                echo '{slug: "' . $row[0] . '", displayName: "' . $row[1] . '"}, ';
            }
            $row = mysql_fetch_row($result);
            echo '{slug: "' . $row[0] . '", displayName: "' . $row[1] . '"} ';	
        }
        echo ']';

    }

    public function writeDirectoryName() {
    
        echo '"' . $this->getContinentDirectoryImage() . '"';
    }
    
    public function setTabNames() {
    
        $query = 'SELECT DISTINCT Continent, ContinentDirectoryName FROM Countries ORDER BY Continent';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        $this->tab_names_array = array();
        $this->map_names_array = array();
        $this->tab_labels_array = array();
        if($result) {
            for($i = 0; $i < mysql_num_rows($result); $i++) {
                $row = mysql_fetch_row($result);
                array_push($this->tab_labels_array, $row[0]);
                array_push($this->tab_names_array, 'tabs_' . $row[1]);
                array_push($this->map_names_array, 'map_image_' . $row[1]);
            }
        }
    }
    
    
    public function getAllCountryCoords() {
    
    
        if($_SESSION['userSession']->getContinentChoice() !== "World") {
        
            echo '[';
            for ($country_iter = 0; $country_iter < count($this->continent_countries_array); $country_iter++) {
                $continentName = $this->continent_countries_array[$country_iter][0];
                $country = $this->continent_countries_array[$country_iter][1];
                $include_nonprimary = $this->continent_countries_array[$country_iter][2];
                $display_name_country = $this->continent_countries_array[$country_iter][3];
                $display_name_capital = $this->continent_countries_array[$country_iter][4];
            
                if( $continentName === $this->getContinentDirectoryImage() ) {//The php array is populated with all continent/country pairs

                    $query = 'SELECT * FROM ' . $continentName . '_' . $include_nonprimary; //queries the polygon database
                    $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getPolygonConnection());
                    $this->points_for_country = array();
                    if($result) {
                        $j =  mysql_num_rows($result);
                        for($i = 0; $i < $j-1 ; $i++) {
                            $row = mysql_fetch_row($result);
                            $this->points_for_country[$i] = array("x" => $row[0], "y" => $row[1]);
                        }
                        $row = mysql_fetch_row($result);
                        $this->points_for_country[$j] = array("x" => $row[0], "y" => $row[1]);
                        $primary_country = self::getPrimary($country);
                        
                        echo '{"name": "' . $continentName . '_' . $primary_country . '", '
                            . '"continent": "' . $continentName . '", '                        
                            . '"display_name_country": "' . $display_name_country . '", '
                            . '"display_name_capital": "' . $display_name_capital . '", '
                            . '"flag_name": "' . $primary_country . '", '
                            . '"polygon": [';
                        if($j > 0) {
                            for($i=0; $i<$j-1; $i++) {
                                echo '{x: ' . $this->points_for_country[$i]["x"] . ', y: ' . $this->points_for_country[$i]["y"] . '},';
                            }
                            echo '{x: ' . $this->points_for_country[$j]["x"] . ', y: ' . $this->points_for_country[$j]["y"] . '}';
                        }
                        echo ']}';

                        if($country_iter < count($this->continent_countries_array) - 1) {
                            echo ', '; //still have another country to process in the main 2d array, so throw in a comma
                        }
                    }
                }
            }
            echo ']';
        }
        else {//They are playing the world so get all the country coordinates of each continent.... shit, this may take a while....

            echo '[';
            for ($country_iter = 0; $country_iter < count($this->continent_countries_array); $country_iter++) {
                $continentName = $this->continent_countries_array[$country_iter][0];
                $country = $this->continent_countries_array[$country_iter][1];
                $include_nonprimary = $this->continent_countries_array[$country_iter][2];
                $display_name_country = $this->continent_countries_array[$country_iter][3];
                $display_name_capital = $this->continent_countries_array[$country_iter][4];

                $query = 'SELECT * FROM ' . $continentName . '_' . $include_nonprimary; //queries the polygon database
                $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getPolygonConnection());
                $this->points_for_country = array();
                if($result) {
                    $j =  mysql_num_rows($result);
                    for($i = 0; $i < $j-1 ; $i++) {
                        $row = mysql_fetch_row($result);
                        $this->points_for_country[$i] = array("x" => $row[0], "y" => $row[1]);
                    }
                    $row = mysql_fetch_row($result);
                    $this->points_for_country[$j] = array("x" => $row[0], "y" => $row[1]);
                    $primary_country = self::getPrimary($country);   
                    echo '{"name": "' . $continentName . '_' . $primary_country . '", '
                        . '"continent": "' . $continentName . '", '                        
                        . '"display_name_country": "' . $display_name_country . '", '
                        . '"display_name_capital": "' . $display_name_capital . '", '
                        . '"flag_name": "' . $primary_country . '", '
                        . '"polygon": [';
                    if($j > 0) {
                        for($i=0; $i<$j-1; $i++) {
                            echo '{x: ' . $this->points_for_country[$i]["x"] . ', y: ' . $this->points_for_country[$i]["y"] . '},';
                        }
                        echo '{x: ' . $this->points_for_country[$j]["x"] . ', y: ' . $this->points_for_country[$j]["y"] . '}';
                    }
                    echo ']}';

                    if($country_iter < count($this->continent_countries_array) - 1) {
                        echo ', '; //still have another country to process in the main 2d array, so throw in a comma
                    }
                }
            }
            echo ']';
        }
    

    }

    public static function getPrimary($_in_polygon) {
        $primary_country;
        $primary_polygon;
    
        /* just getting the upper-case name of the country */
        $query = 'SELECT Country FROM Countries '
            . 'WHERE PolygonName = "' . $_in_polygon . '"';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $row = mysql_fetch_row($result);
            $primary_country =  $row[0];
        }
    
        $query = 'SELECT PolygonName FROM Countries '
            . 'WHERE IsPrimary '
            . 'AND Country = "' . $primary_country . '"';
        
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $row = mysql_fetch_row($result);
            $primary_polygon =  $row[0];
        }
    
        return $primary_polygon;
    }
    
    public function writeCountryCapitalPairs() {
    
    
        //just stores the lower-case-directory name of the continent ... north_america
        if($_SESSION['userSession']->getContinentChoice() !== "World") {
            $query = 'SELECT Country, Capital FROM Countries Where Continent = "' . $_SESSION['userSession']->getContinentChoice() . '"';
        }
        else {
            $query = 'SELECT Country, Capital FROM Countries';
        }
        
        echo '[';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $j =  mysql_num_rows($result);
            for($i = 0; $i < $j-1 ; $i++) {
                $row = mysql_fetch_row($result);
                echo '{"country": "' . $row[0] . '", "capital": "' . $row[1] . '"}, ';
            }
            /* the last row of the query so the last entry of the array */
            $row = mysql_fetch_row($result);
            echo '{"country": "' . $row[0] . '", "capital": "' . $row[1] . '"}';
        }
        else {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
        }
        echo ']';
        
        $_SESSION['userSession']->logger->writeLog('Continent::writeCountryCapitalPairs', 'exiting method');
        
    }

    public function setContinentDirectoryImage() {
    

        //just stores the lower-case-directory name of the continent ... north_america
        if($_SESSION['userSession']->getContinentChoice() !== "World") {
            $query = 'SELECT ContinentDirectoryName FROM Countries Where Continent = "' . $_SESSION['userSession']->getContinentChoice() . '"';
            $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
            if($result) {
                $row = mysql_fetch_row($result);
                $this->continentImageDirectory = $row[0];
            }
        }
        else {
            $this->continentImageDirectory = "world";
        }
        
        $_SESSION['userSession']->logger->writeLog('Continent::setContinentDirectoryImage', 'continentImageDirectory is ' . $this->continentImageDirectory);
    }
    public function getContinentDirectoryImage() {
        
        return $this->continentImageDirectory;
    }
    
    public function setContinentPolygonsAndCountries() {
    
        
        $query = 'SELECT ContinentDirectoryName, PolygonName, Country, Capital FROM Countries WHERE IsEnabled ORDER BY IsSurrounded DESC, PolygonName';
    
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $this->continent_countries_array = array();
            for($i = 0; $i < mysql_num_rows($result); $i++) {
                $row = mysql_fetch_row($result);
        
                /* you haven't added it yet, add the primary continent's country with the continent name too */
                $primary_continent = $row[0];
                $primary_country = self::getPrimary($row[1]);
                $iter_continent = $row[1];
                $display_country = $row[2];
                $display_capital = $row[3];
                $this->continent_countries_array[$i] = array($primary_continent, $primary_country, $iter_continent, $display_country, $display_capital);
            }
        }
	
        /**
            you're done getting the country names
            now you need to get polygon coordindates for each of those country names
        **/
    }
    
	
}