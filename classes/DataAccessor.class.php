<?php
class DataAccessor {


    const HOSTNAME = 'mysql.letsplayglobalgames.com';
    const USERNAME = 'lpgg01';
    const PASS = 'Iatp.*11';
    const DB_NAME_BASE = 'mysql_lpggdb01';
    const DB_NAME_POLYGON = 'mysql_polygonsdb01';
    const TABLE_DONORS = 'Donors';
    const TABLE_CHARITIES = 'Charities';
    
    
    private $base_connection;
    private $polygon_connection;
    private $aes_key;
    

    /* connection functions */
    public function setDatabaseConnections() {
    
        if(!is_resource($this->base_connection)) {
            $this->base_connection = mysql_pconnect(self::HOSTNAME, self::USERNAME, self::PASS);
            if($this->base_connection) {
                $this->setDatabase(self::DB_NAME_BASE, $this->base_connection);
            }
            else {
                $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), "issue setting base database connection");
            }
        }
        else {
            $_SESSION['userSession']->logger->writeLog("setDatabaseConnections", "base connection is already a resource");
        }
    
        if(!is_resource($this->polygon_connection)) {
            $this->polygon_connection = mysql_pconnect(self::HOSTNAME, self::USERNAME, self::PASS, true);
            if($this->polygon_connection) {
                $this->setDatabase(self::DB_NAME_POLYGON, $this->polygon_connection);
            }
            else {
                $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), "issue setting polygon database connection");
            }
        }
        else {
            $_SESSION['userSession']->logger->writeLog("setDatabaseConnections", "polygon connection is already a resource");
        }
        
    }
    public function getBaseConnection() {
        
        return $this->base_connection;
    }
    public function getPolygonConnection() {
        
        return $this->polygon_connection;
    }
    private function setDatabase($_db_name, $_connection) {

        $select = mysql_select_db($_db_name, $_connection);
        if(!$select) {
            $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), "issue selecting database " . $_db_name . "with connection " . $_connection);
        }
    }
    public function setAESKey() {
    
        if(!isset($this->aes_key)) {
            
            /* get the encryption key from db and store it in global variable*/
            $query = 'SELECT `Key` FROM General';
            $result = mysql_query($query, $this->base_connection);
            if($result) {
                $row = mysql_fetch_row($result);
                $this->aes_key = $row[0];
                $_SESSION['userSession']->logger->writeLog("setAESKey", "successfully set the aes key");
            }
            else {
                $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
            }
        }
    }
    public function getAESKey() {
    /* only used during user login password encryption */
    
        return $this->aes_key;
    }

    /* general sql functions */
    public static function tableExists($connection, $tablename, $database = false) {
    //http://www.electrictoolbox.com/check-if-mysql-table-exists/php-function/

        if(!$database) {
            $query = "SELECT DATABASE()";
            $result = mysql_query($query, $connection);
            $database = mysql_result($result, 0);
        }

        $query = "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$tablename'";
        $result = mysql_query($query, $connection);

        return mysql_result($result, 0) == 1;

    }
    
    public function insertPolygon() {
    
        if(isset($_POST['edit_polygon_country_name'])) {
            if($_SESSION['userSession']->getUser() === "admin") {
    
                if(!self::tableExists($this->polygon_connection, $_POST['edit_polygon_country_name'])) {
                    //table not created yet, just create it fresh
                    $query = 'CREATE TABLE `polygons`.`' . $_POST['edit_polygon_country_name'] . '` (`x` SMALLINT NOT NULL ,`y` SMALLINT NOT NULL) ENGINE = MYISAM';
                    $result = mysql_query($query, $this->polygon_connection);
                    if(!$result) {
                        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
                    }
                }
                else {
                    //table already exists, just delete the existing coordinates
                    $query = 'DELETE FROM ' . $_POST['edit_polygon_country_name'];
                    $result = mysql_query($query, $this->polygon_connection);
                    if(!$result) {
                        $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
                    }
                }
        
                //update the empty table with new/fresh coordinates
                $query = 'INSERT INTO ' . $_POST['edit_polygon_country_name'] . '(x, y) VALUES ' . $_POST['polygon_coords'];
                $result = mysql_query($query, $this->polygon_connection);
                if(!$result) {
                    $_SESSION['userSession']->logger->writeSQLError(mysql_errno(), mysql_error(), $query);
                }
            }
            else {
                $_SESSION['userSession']->logger->writeError('DataAccessor::insertPolygon', 'user is not admin');
            }
        }
    }
    
}