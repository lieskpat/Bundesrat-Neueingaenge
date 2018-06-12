<?php

require_once 'StreamOperation.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileOperation
 *
 * @author Lieske
 */
class FileOperation implements StreamOperation {
    
    private $fileConnection;
    
    /**
     * 
     * @param FileConnection $fileConnection
     */
    function __construct($fileConnection) {
        $this->fileConnection = $fileConnection;
        $this->fileConnection->openConnection();
    }
    
    /**
     * 
     * @return FileConnection $fileConnection
     */
    function getFileConnection() {
        return $this->fileConnection;
    }

    public function read() {
        
    }

    /**
     * 
     * @param String $string
     */
    public function write($string) {
        fwrite($this->getFileConnection()->getFileStream(), $string);
    }

}
