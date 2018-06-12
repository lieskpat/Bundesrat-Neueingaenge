<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Connection.php';

/**
 * Description of FileOperation
 *
 * @author Lieske
 */
class FileConnection implements Connection {

    private $fileStream;
    private $fileNameToOpenStream;
    private $fileAccessMode;

    /**
     * 
     * @param type $fileStream
     * @param type $fileNameToOpenStream
     * @param type $fileAccessMode
     */
    function __construct($fileNameToOpenStream, $fileAccessMode) {
        $this->fileNameToOpenStream = $fileNameToOpenStream;
        $this->fileAccessMode = $fileAccessMode;
    }

    /**
     * 
     * @return stream the open file stream
     */
    function getFileStream() {
        return $this->fileStream;
    }

    /**
     * 
     * @return resource the file name from file stream
     */
    function getFileNameToOpenStream() {
        return $this->fileNameToOpenStream;
    }

    /**
     * 
     * @return 
     */
    function getFileAccessMode() {
        return $this->fileAccessMode;
    }

    /**
     * 
     */
    public function openConnection() {
        try {
            $this->fileStream = fopen($this->fileNameToOpenStream
                    , $this->fileAccessMode);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * close the open file stream
     */
    public function closeConnection() {
        if (!is_null($this->fileStream)) {
            return fclose($this->fileStream);
        }
    }

}
