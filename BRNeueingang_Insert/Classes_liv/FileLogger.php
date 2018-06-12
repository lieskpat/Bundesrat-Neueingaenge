<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'LoggerInterface.php';
require_once 'ObserverInterface.php';

/**
 * Description of Logger
 *
 * @author Lieske
 */
class FileLogger implements LoggerInterface, ObserverInterface{
    
    private $fileOperation;
    
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
    function write($string) {
        $this->writeLine($string);
        return $this;
    }
    
    /**
     * 
     * @param type $string
     */
    function writeLine($string) {
        $this->fileOperation->write($string . "\n");
    }

    /**
     * 
     * @param type $observable
     */
    public function update($observable) {
        $this->write($observable->getData());
    }

}
