<?php

class HighScores {

    private $high_scores_table='HighScores';
    
    
    public function executeSQLAddHighScore() {
    
        $difficulty = $_SESSION['userSession']->getDifficultyLevel();
        $_SESSION['userSession']->logger->writeLog('high_scores', 'entering function');
        $query = 'INSERT INTO ' . $this->high_scores_table . '(Name, Score, Time, Continent, DifficultyLevelLower, DifficultyLevelUpper, GameType, DateTime) VALUES ('
            . '"' . $_POST['high_score_name'] . '", '
            . $_POST['high_score_score'] . ', '
            . $_POST['high_score_time'] . ', ' 
            . '"' . $_SESSION['userSession']->getContinentChoice() . '", '
            . $difficulty['bottom'] . ', '
            . $difficulty['top'] . ', '
            . '"' . $_SESSION['userSession']->getHighScoreGameType() . '", '
            . ' CURRENT_TIMESTAMP)';
    
        $_SESSION['userSession']->logger->writeLog('high_scores', '$query is: ' . $query);
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if(!$result) {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
        }
        $query = 'SET @rownum := 0';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
            
        $query = 'SELECT rank FROM ( '
                    . 'SELECT @rownum := @rownum + 1 AS rank, Name, Score '
                    . 'FROM HighScores WHERE `GameType` = "' . $_SESSION['userSession']->getHighScoreGameType() . '" ORDER BY Score DESC '
                . ' ) as result WHERE `Name` = "' . $_POST['high_score_name'] . '" and Score = ' . $_POST['high_score_score'];
    
        $_SESSION['userSession']->logger->writeLog("high_scores", "$query is: " . $query);
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $row = mysql_fetch_row($result);
            $_SESSION['userSession']->logger->writeLog('high_scores: executeSQLAddHighScore', 'setting userRank to ' . $row[0]);
            $_SESSION['userSession']->setRank($row[0]);
        }
        else {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
        }
    
    }

    public function loadHighScores() {
    
        $difficulty = $_SESSION['userSession']->getDifficultyLevel();
        
        $query = 'SELECT * FROM ' . $this->high_scores_table
            . ' WHERE GameType = "' . $_SESSION['userSession']->getHighScoreGameType() . '"'
            . ' ORDER BY Score DESC, Time ASC';
    
        $_SESSION['userSession']->logger->writeLog('high_scores', '$query is: ' . $query);
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        if($result) {
            $j =  mysql_num_rows($result);
            echo '[';
            for($i = 0; $i < $j-1 ; $i++) {
                $row = mysql_fetch_row($result);
                echo '{"name": "' . $row[0] . '", '
                    . '"score": "' . $row[1] . '", '
                    . '"time": "' . $row[2] . '", '
                    . '"continent": "' . $row[3] . '", '
                    . '"difficulty": "' . $row[4] . ' - ' . $row[5] . '", ';
                
                
                if (isset($_POST['high_score_name']) && isset($_POST['high_score_score']) && isset($_POST['high_score_time'])) {
                    if (($row[0] === $_POST['high_score_name']) && ($row[1] === $_POST['high_score_score']) &&
                        ($row[2] === $_POST['high_score_time']) && ($row[3] === $_SESSION['userSession']->getContinentChoice()) &&
                        ($row[4] === $difficulty['bottom']) &&
                        ($row[5] === $difficulty['top'])) {
                        echo '"selected": "yes"';
                    }
                    else {
                        echo '"selected": "no"';
                    }
                }
                else {
                    echo '"selected": "no"';
                }
                echo '}, ';
            }
            /* the last entry */
            $_SESSION['userSession']->logger->writeLog('high_scores', 'getting last row of query: ' . $query);
            $row = mysql_fetch_row($result);
                echo '{"name": "' . $row[0] . '", '
                    . '"score": "' . $row[1] . '", '
                    . '"time": "' . $row[2] . '", '
                    . '"continent": "' . $row[3] . '", '
                    . '"difficulty": "' . $row[4] . ' - ' . $row[5] . '", ';
            
            if (isset($_POST['high_score_name']) && isset($_POST['high_score_score']) && isset($_POST['high_score_time'])) {
                if (($row[0] === $_POST['high_score_name']) && ($row[1] === $_POST['high_score_score']) &&
                    ($row[2] === $_POST['high_score_time']) && ($row[3] === $$_SESSION['userSession']->getContinentChoice()) &&
                    ($row[4] === $difficulty['bottom']) &&
                    ($row[5] === $difficulty['top'])) {
                    echo '"selected": "yes"';
                }
                else {
                    echo '"selected": "no"';
                }
            }
            else {
                echo '"selected": "no"';
            }
            echo '}]';
        }
        else {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
        }
        $_SESSION['userSession']->logger->writeLog('high_scores', 'exiting loadHighScores');
    }

    public function getHighlightedRow() {
        $difficulty = $_SESSION['userSession']->getDifficultyLevel();

        echo '{name:"' . $_POST['high_score_name']
            . " , score:" . $_POST['high_score_score']
            . " , time:" . $_POST['high_score_time']
            . " , continent:" . $_SESSION['userSession']->getContinentChoice()
            . " , lower_difficulty:" . $difficulty['bottom']
            . " , upper_difficulty:" . $difficulty['top'];
    
    }

    public static function loadLeaders() {

        $query = 'select '
            . 'b.Name, b.score as MaxScore, b.GameType from HighScores b '
            . 'inner join ( '
            . 'SELECT Name, max(Score) as MaxScore, GameType from HighScores '
            . 'group by GameType '
            . ') a on '
            . 'a.GameType = b.GameType '
            . 'and '
            . 'a.MaxScore = b.Score '
            . 'order by MaxScore desc';
        $result = mysql_query($query, $_SESSION['userSession']->dataAccessor->getBaseConnection());
        echo '[';
        if($result) {
            $j =  mysql_num_rows($result);
            for($i = 0; $i < $j-1 ; $i++) {
                $row = mysql_fetch_row($result);
                echo '{"name": "' . $row[0] . '", '
                    . '"score": "' . $row[1] . '", '
                    . '"game_type": "' . $row[2] . '"';
                echo '}, ';
            }
            /* the last entry */
            $row = mysql_fetch_row($result);
                echo '{"name": "' . $row[0] . '", '
                    . '"score": "' . $row[1] . '", '
                    . '"game_type": "' . $row[2] . '"';
            echo '}';
        }
        else {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
        }
        echo ']';
    }

}