<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'SqlHelper.php';

/**
 * Description of WorkOnDB
 *
 * @author Lieske
 */
class WorkOnDB {
    
    private $sqlHelper;
    
    public function __construct($dbSource, $userName, $password) {
        $this->sqlHelper = new SqlHelper($dbSource, $userName, $password);
    }
    
    /**
     * 
     * @return SqlHelper
     */
    public function getSqlHelper() {
        return $this->sqlHelper;
    }

        
    public function insert($param) {
        
    }
    
    public function update($param) {
        
    }
    
    public function select($param) {
        
    }
}
