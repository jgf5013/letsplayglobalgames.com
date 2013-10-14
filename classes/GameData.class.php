<?php
class GameData {

    public static function loadAllCategories() {
    
        
        echo '{';
        if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
            echo '"is_country_names_selected": "yes", '
                . '"is_capital_names_selected": "yes", '
                . '"is_country_flags_selected": "yes"';
        }
        else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
            
            if ($_SESSION['userSession']->getPlayType() === "country names") {
                echo '"is_country_names_selected": "yes", '
                    . '"is_capital_names_selected": "no", '
                    . '"is_country_flags_selected": "no"';
            }
            else if ($_SESSION['userSession']->getPlayType() === "capital names") {
                echo '"is_country_names_selected": "no", '
                    . '"is_capital_names_selected": "yes", '
                    . '"is_country_flags_selected": "no"';
            }
            else if ($_SESSION['userSession']->getPlayType() === "country flags") {
                echo '"is_country_names_selected": "no", '
                    . '"is_capital_names_selected": "no", '
                    . '"is_country_flags_selected": "yes"';
            }
        }
        else {
            /* throw an exception here */
        }
        echo '}';
    }


}