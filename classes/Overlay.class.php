<?php
class Overlay {

    
    /* private */
    private static $instructionNumber = 1;
    
    
    public static function writeInstructionNumber() {
    
        echo self::$instructionNumber . '. ';
        self::$instructionNumber++;
    }    

    public static function writeTabText() {
        $_SESSION['userSession']->logger->writeLog('Overlay::writeTabText', 'entering');
    
    
        if($_SESSION['userSession']->getContinentChoice() === "World") {

            $_SESSION['userSession']->logger->writeLog('Overlay::writeTabText', 'User is not playing World');
            
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                echo '<h3 class="overlay_instruction_step">1. </h3><p class="overlay_instruction_text">Click on the tabs to select a continent</p>';
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '<h3 class="overlay_instruction_step">2. </h3><p class="overlay_instruction_text">Click on the tabs to select a continent</p>';
            }
        }
    
        $_SESSION['userSession']->logger->writeLog('Overlay::writeTabText', 'exiting');

    }
    
    
    public static function writeMapText() {
        $_SESSION['userSession']->logger->writeLog('Overlay::writeMapText', 'entering');
    
    
        if($_SESSION['userSession']->getContinentChoice() !== "World") {

            $_SESSION['userSession']->logger->writeLog('Overlay::writeMapText', 'User is not playing World');
        
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                echo '<h3 class="overlay_instruction_step">1. </h3><p class="overlay_instruction_text">Move your mouse around to highlight different countries</p>';
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '<h3 class="overlay_instruction_step">2. </h3><p class="overlay_instruction_text">Click on the corresponding country. But be careful, you only get three incorrect guesses!</p>';
            }
        }
        else {
    
            $_SESSION['userSession']->logger->writeLog('Overlay::writeMapText', 'User is playing World');
    
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                echo '<h3 class="overlay_instruction_step">2. </h3><p class="overlay_instruction_text">Move your mouse around to highlight different countries</p>';
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '<h3 class="overlay_instruction_step">3. </h3><p class="overlay_instruction_text">Click on the corresponding country. But be careful, you only get three incorrect guesses!</p>';
            }
        }
    
        $_SESSION['userSession']->logger->writeLog('Overlay::writeMapText', 'exiting');

    }


    public static function writeGamePanelText() {
        $_SESSION['userSession']->logger->writeLog('Overlay::writeGamePanelText', 'entering');
    
    
        if($_SESSION['userSession']->getContinentChoice() !== "World") {

            $_SESSION['userSession']->logger->writeLog('Overlay::writeGamePanelText', 'User is not playing World');
        
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                echo '<h3 class="overlay_instruction_step">2. </h3><p class="overlay_instruction_text">Learn about the highlighted country!</p>';
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '<h3 class="overlay_instruction_step">1. </h3><p class="overlay_instruction_text">Look at the ' . $_SESSION['userSession']->getPlayType() . '</p>';
            }
        }
        else {
    
            $_SESSION['userSession']->logger->writeLog('Overlay::writeGamePanelText', 'User is playing World');
    
            if($_SESSION['userSession']->getLearnOrPlay() === "learn") {
                echo '<h3 class="overlay_instruction_step">3. </h3><p class="overlay_instruction_text">Learn about the highlighted country!</p>';
            }
            else if($_SESSION['userSession']->getLearnOrPlay() === "play") {
                echo '<h3 class="overlay_instruction_step">1. </h3><p class="overlay_instruction_text">Look at the ' . $_SESSION['userSession']->getPlayType() . '</p>';
            }
        }
    
        $_SESSION['userSession']->logger->writeLog('Overlay::writeGamePanelText', 'exiting');

    }
    
    public static function writeGameDataText() {
        
        $_SESSION['userSession']->logger->writeLog('Overlay::writeGameDataText', 'entering');
    
        if($_SESSION['userSession']->getContinentChoice() !== "World") {
            echo '<h3 class="overlay_instruction_step">3. </h3><p class="overlay_instruction_text">High Score??</p>';
        }
        else {
            echo '<h3 class="overlay_instruction_step">4. </h3><p class="overlay_instruction_text">High Score??</p>';
        }

        $_SESSION['userSession']->logger->writeLog('Overlay::writeGameDataText', 'exiting');

    }
	
}