<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'ObserverInterface.php';
require_once 'LoggerInterface.php';

/**
 * Description of YamlLogger
 *
 * @author lies
 */
class YamlLogger implements LoggerInterface, ObserverInterface {

    private $fileOperation;
    
    private $yamlArray = array();

    /**
     * 
     * @param type $fileOperation
     */
    function __construct($fileOperation) {
        $this->fileOperation = $fileOperation;
    }

    /**
     * 
     * @return type
     */
    function getFileOperation() {
        return $this->fileOperation;
    }

    /**
     * 
     * @param String $string
     */
    public function write($string) {
        
    }

    /**
     * 
     * @param Observable $observable
     */
    public function update($observable) {
        
    }

}
