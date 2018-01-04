<?php

/**
 * MysqliDb.php - a simple mysqli db class
 *
 * @author     Matthew Speicher <matthewbspeicher@gmail.com>
 */

class MysqliDb {
    /**
     * @var object $dbconn Stores the mysqli connection
     */
    private $dbconn;

    /**
     * MysqliDb constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     */
    public function __construct($host, $user, $password, $database) {
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;

        /**
         * Make db connection
         */
        $this->dbconn = new mysqli($this->host, $this->user, $this->password, $this->database);

        /**
         * Check db connection
         */
        if ($this->dbconn->connect_errno) {
            printf("Connect failed: %s\n", $this->dbconn->connect_error);
            exit();
        }

    }

    /**
     * Inserts new record in the db.
     * @param string $query
     * @return mixed|null
     */
    public function insert($query){
        $dbconn = $this->dbconn;
        if (!$result = $dbconn->query($query)) {
            printf("insert failed, error: ", $dbconn->error);
            return null;
        } else {
            return $dbconn->insert_id;
        }
    }

    /**
     * Called when object is deleted - close sql connection.
     */
    function __destruct(){
        $this->dbconn->close();
    }

    /**
     * Clean a string using the mysqli real escape string function.
     * @param string $string
     * @return string
     */
    public function cleanString($string){
        return mysqli_real_escape_string($this->dbconn, $string);
    }
}