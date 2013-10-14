<?php
class UserSession {


    /* public */
    public $logger;
    public $dataAccessor;
    public $navigator;
    public $highScores;
    public $gameData;
    public $browser;
    
    /* private */
    private $learn_or_play;
    private $continent_choice;
    private $playType;
    private $highScoreGameType;
    
    private $difficulty_level_bottom;
    private $difficulty_level_top;
    
    private $userName;
    private $id;
    
    function __construct() {
        
        $this->logger = new Logger();
        $this->dataAccessor = new DataAccessor();
        $this->dataAccessor->setDatabaseConnections();
        $this->navigator = new Navigator();
        $this->highScores = new HighScores();
        $this->gameData = new GameData();

        $this->browser = $this->getBrowser();
        $this->id = session_id();
    }
    
    public function getId() {
        return $this->id;
    }
    
    
    public function setUser() {
        
        $this->logger->writeLog('UserSession::setUser', 'entering');
    
        /* userName clicked the logout button */
        if( isset($_POST['logout_submit']) ) {
    
            unset($_POST['logout_submit']);
            $this->unsetUser();
        }
    
        /* 			post: set					session: not set 	--> userName provided log in info but isn't logged in yet, so try login */
        else if( isset($_POST['userName']) && !isset($this->userName) ) {
            /* get encrypted version of userName input password */
            $this->logger->writeLog('UserSession::setUser', 'getting userName input password');
            $this->dataAccessor->setAESKey();
            
            if( isset($_POST['password']) ) {
                $unencrypted_password_user_input = $_POST['password'];
            }
            else {
                $unencrypted_password_user_input = "";
                $this->logger->writeError('UserSession::setUser', 'userName input password not set');
            }
            $this->logger->writeLog('UserSession::setUser', 'unencrypted version of userName input password received');
            $this->logger->writeLog('UserSession::setUser', 'setting encrypted version of userName input password');
            $encrypted_password_user_input = hash($_SESSION['userSession']->dataAccessor->getAESKey(), $unencrypted_password_user_input);
            $this->logger->writeLog('UserSession::setUser', 'encrypted version of userName input password set');
        
            /* get encrypted version of database password */
            $encrypted_password_db;
            $query = 'SELECT Password FROM Users Where User = "' . $_POST['userName'] . '"';
            
            $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
            if($result) {
                $row = mysql_fetch_row($result);
                $encrypted_password_db = $row[0];
            }
            else {
                $this->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
            }
        
            /* verify password */
            if($encrypted_password_user_input === $encrypted_password_db) {
                $this->userName = $_POST['userName'];
                $this->logger->writeLog('UserSession::setUser', 'login successful');
                $this->logger->writeLog('UserSession::setUser', 'userName: ' . $this->userName);
            }
            else {
                $this->logger->writeLog('UserSession::setUser', 'invalid login attempt');
            }
        
            unset($_POST['userName']);
        }
    
        /* 			post: not set				session: set 		--> session is already set, post isn't AND this wasn't caught
                                                                        by the logout form... don't do anything
                                                                        happens when redirected to index */
        elseif (!isset($_POST['userName']) && isset($this->userName) ) {
        }
    
        /* 			post: set					session: set 		--> session and post are both set... shouldn't ever happen */
        elseif (isset($this->userName) && isset($_POST['userName'])) {
        }
    
        /* 			post: not set				session: not set 	--> neither session nor post are set, the first-access case and also
                                                                        a general logout case, so don't do anything */
        elseif (!isset($_POST['userName']) && !isset($this->userName) ) {
        }

    }
    private function unsetUser() {
    
        if(isset($this->userName)) {
            unset($this->userName);
            $this->logger->writeLog('UserSession::unsetUser', 'unset complete');
        }
        else {
            $this->logger->writeLog('UserSession::unsetUser', 'unset not needed');
        }
    }
    public function getUser() {
        
        return $this->userName;
        
    }
    public function writeUser() {
        
        if(isset($this->userName)) {
            echo '"' . $this->userName . '"';
        }
        else {
            echo '""';
        }
    }
    
    public function getDifficultyLevel() {
    
        return array("bottom"=>$this->difficulty_level_bottom, "top"=>$this->difficulty_level_top);
    }

    private static function getBrowser() {
    
        
        /* set the browser name */
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            if(preg_match('/Chrome/i',$u_agent))
            {
                return "Google Chrome";
            }
            elseif(preg_match('/Firefox/i',$u_agent))
            {
                return "Mozilla Firefox";
            }
            elseif(preg_match('/MSIE/i',$u_agent))
            {
                return "Internet Explorer";
            }
            elseif(preg_match('/Opera/i',$u_agent))
            {
                return "Opera";
            }
            elseif(preg_match('/Netscape/i',$u_agent))
            {
                return "Netscape";
            }
            else {
                return "Other";
            }
        }
        else {
            return "Undefined";
        }
    }
    public function writeBrowser() {

        echo '"' . $this->browser . '"';
    }
    
    public function setRank($_userRank) {
        
        $this->userRank = $_userRank;
    }
    public function getRank() {
        
        if(isset($this->userRank)) {
            $this->logger->writeLog('UserSession.php: getRank', 'userRank is ' . $this->userRank);
            return $this->userRank;
        }
        else {
            $this->logger->writeLog('UserSession: getRank', 'userRank is returning 0');
            return 0;
        }
    }
    public function unsetRank() {

        unset($this->userRank);
    }
    public function writeRank() {
        
        if(isset($this->userRank)) {
            $this->logger->writeLog('UserSession: writeRank', 'userRank is ' . $this->userRank);
            echo $this->userRank;
        }
        else {
            $this->logger->writeLog('UserSession: writeRank', 'userRank is returning 0');
            echo '0';
        }
    }
    
    public static function getCurrentURL() {
    /* source: stackoverflow.com */
    
        $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
        $host     = $_SERVER['HTTP_HOST'];
        $script   = $_SERVER['SCRIPT_NAME'];
        $params   = $_SERVER['QUERY_STRING'];
 
        $currentUrl = $protocol . '://' . $host . $script;
        
        return $currentUrl;
    }
    
    
    public function setHomePanelChoices() {
    
        $this->logger->writeLog('UserSession: setHomePanelChoices', 'entering');
        
        /* set session & userName info */
        if( isset($_POST['learn_continent']) ) {
            $this->logger->writeLog('UserSession: setHomePanelChoices', 'game type is learn');
            $this->continent_choice = $_POST['learn_continent'];
            /*$this->is_country_names_selected = $_POST['is_learn_country_names_selected'];
            $this->is_capital_names_selected = $_POST['is_learn_capital_names_selected'];
            $this->is_country_flags_selected = $_POST['is_learn_country_flags_selected'] ;*/
            $this->learn_or_play = "learn";
        }
        else if( isset($_POST['play_continent']) ) {
            $this->logger->writeLog('UserSession: setHomePanelChoices', 'game type is play');
            $this->continent_choice = $_POST['play_continent'];
            $this->difficulty_level_bottom = $_POST['difficulty_level_bottom'] ;
            $this->difficulty_level_top = $_POST['difficulty_level_top'];
            $this->playType = $_POST['game_option'];
            $this->learn_or_play = "play";
        }
            
        $_SESSION['userSession']->logger->writeLog('UserSession::setHomePanelChoices', 'home panel choices are set');
        
        
    }
    
    public function getLearnOrPlay() {
        
        return $this->learn_or_play;
    }
    public function writeLearnOrPlay() {
        echo '"' . $this->learn_or_play . '"';
    }
    public function getPlayType() {
        
        return strtolower($this->playType);
    }
    public function getContinentChoice() {
    
        return $this->continent_choice;
    }
    
    public function setHighScoreGameType() {
    
        $this->logger->writeLog('high_scores', 'entering setHighScoreGameType');
        $this->unsetRank();
        
        if(isset($_POST['high_score_play_game_type'])) {
            $this->logger->writeLog('high_scores', 'entering setHighScoreGameType from play page');
            $this->highScoreGameType = $_POST['high_score_play_game_type'];
            $this->highScores->executeSQLAddHighScore();
        }
        else if(isset($_POST['high_score_nav_game_type'])) {
        $this->logger->writeLog('high_scores', 'entering setHighScoreGameType from navigation bar');
            $this->highScoreGameType = $_POST['high_score_nav_game_type'];
        }
        else {
            $this->logger->writeError('high_scores', 'neither high_score_nav_game_type nor high_score_play_game_type set');
        }
        $this->logger->writeLog('high_scores', 'exiting setHighScoreGameType');
    }
    public function getHighScoreGameType() {
    
        return $this->highScoreGameType;
    }
    public function writeHighScoreGameType() {
    
        echo '"' . $this->highScoreGameType . '"';
    }

}