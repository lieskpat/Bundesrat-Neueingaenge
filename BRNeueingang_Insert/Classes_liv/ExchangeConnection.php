<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Connection.php';
require_once 'AbstractObservable.php';

/**
 * Description of ExchangeConnection
 *
 * @author lies
 */
class ExchangeConnection extends AbstractObservable implements Connection {

    private $imapStream;
    private $connectionAddress;
    private $userName;
    private $password;

    /**
     * 
     * @param type $connectionAddress
     * @param type $userName
     * @param type $password
     */
    function __construct($connectionAddress, $userName, $password) {
        $this->connectionAddress = $connectionAddress;
        $this->userName = $userName;
        $this->password = $password;
    }

    /**
     * 
     * @return type
     */
    function getImapStream() {
        return $this->imapStream;
    }

    /**
     * 
     * @return type
     */
    function getConnectionAddress() {
        return $this->connectionAddress;
    }

    /**
     * 
     * @return type
     */
    function getUserName() {
        return $this->userName;
    }

    /**
     * 
     * @return type
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @param type $connectionAddress
     */
    function setConnectionAddress($connectionAddress) {
        $this->connectionAddress = $connectionAddress;
    }

    /**
     * 
     * @param type $userName
     */
    function setUserName($userName) {
        $this->userName = $userName;
    }

    /**
     * 
     * @param type $password
     */
    function setPassword($password) {
        $this->password = $password;
    }

    /**
     * 
     * @throws Exception
     */
    private function getImapConnection() {
        $connection = imap_open($this->getConnectionAddress()
            , $this->getUserName()
            , $this->getPassword());
        if (!$connection) {
            throw new Exception('Connection not possible: check username/password');
        } else {
            $this->imapStream = $connection;
        }
    }

    /**
     * 
     */
    public function openConnection() {
        try {
            $this->getImapConnection();
            //über Observer in Logger schreiben 
            //Datum Uhrzeit Connection zum Postfach
            $this->setData('Connection successful ' .
                date("d.m.Y - H:i:s", time()));
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            //über Observer in Logger schreiben
            $this->setData('Connection not successful' . ' ' .
                date("d.m.Y - H:i:s", time()) . ' ' .
                //$exc->getTraceAsString());
                $exc->getMessage());
            exit();
        }
    }

    /**
     * 
     */
    public function closeConnection() {
        if (!is_null($this->imapStream)) {
            imap_close($this->getImapStream());
            //über Observer in Logger schreiben
            $this->setData('Connection closed' . ' ' .
                date("d.m.Y - H:i:s", time()));
        }
    }

}
