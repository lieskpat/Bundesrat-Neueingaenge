<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SqlHelper
 *
 * @author Lieske
 */
class SqlHelper {

    /**
     *
     * @var \PDO 
     */
    private $pdo;

    /**
     * 
     * @param String $dsn
     * @param String $userName
     * @param String $password
     */
    public function __construct($dsn, $userName, $password) {
        $this->connect($dsn, $userName, $password);
    }

    /**
     * 
     * @return PDO
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * 
     * @param String $dsn
     * @param String $userName
     * @param String $password
     */
    private function createPDO($dsn, $userName, $password) {
        $this->pdo = new PDO($dsn, $userName, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * 
     * @param String $dsn
     * @param String $userName
     * @param String $password
     */
    private function connect($dsn, $userName, $password) {
        try {
            $this->createPDO($dsn, $userName, $password);
        } catch (PDOException $exc) {
            echo 'Connection Failed: ' . $exc->getMessage();
        }
    }

    /**
     * 
     * @param String $sqlCommand
     * @param array $params
     * @param int $flag when flag = 0 you can execute statements with return result, $flag = 1 for insert and update
     */
    public function execute($sqlCommand, array $params = [], $flag = 0) {
        try {
            if ($params == []) {
                return $this->queryWithFetchAllResult($sqlCommand);
            } else {
                return $this->prepare($sqlCommand, $params, $flag);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param String $sqlCommand
     * @param array $params
     * @param int $flag when flag = 0 you can execute statements with return result, $flag = 1 for insert and update
     */
    private function prepare($sqlCommand, array $params, $flag) {
        if ($flag == 0) {
            //$pdoQuery is a PDOStatement object
            $pdoQuery = $this->pdo->prepare($sqlCommand, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            //execute the PreparedStatement (PDOStatement)
            //return TRUE/FALSE
            $pdoQuery->execute($params);
            //returns an array containing rows in the result set
            return $pdoQuery->fetchAll();
        } else {
            $pdoQuery = $this->pdo->prepare($sqlCommand, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $pdoQuery->execute($params);
        }
    }

    /**
     * Don't use this method for insert and update queries
     * 
     * @param String $sqlCommand
     * @return array with result set of db rows
     */
    private function queryWithFetchAllResult($sqlCommand) {
        //executes an SQL statement as PDOStatement object
        $pdoQuery = $this->pdo->query($sqlCommand);
        return $pdoQuery->fetchAll();
    }

}
