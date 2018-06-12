<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExchangeEmail
 *
 * @author lies
 */
class ExchangeEmail {
    
    private $mailBody;
    
    private $senderAddress;
    
    private $sendingDate;
    
    /**
     * 
     * @param type $mailBody
     * @param type $senderAddress
     * @param type $sendingDate
     */
    function __construct($mailBody, $senderAddress, $sendingDate) {
        $this->mailBody = $mailBody;
        $this->senderAddress = $senderAddress;
        $this->sendingDate = $sendingDate;
    }

    /**
     * 
     * @return type
     */
    function getMailBody() {
        return $this->mailBody;
    }

    /**
     * 
     * @return type
     */
    function getSenderAddress() {
        return $this->senderAddress;
    }

    /**
     * 
     * @return type
     */
    function getSendingDate() {
        return $this->sendingDate;
    }

    /**
     * 
     * @param type $mailBody
     */
    function setMailBody($mailBody) {
        $this->mailBody = $mailBody;
    }

    /**
     * 
     * @param type $senderAddress
     */
    function setSenderAddress($senderAddress) {
        $this->senderAddress = $senderAddress;
    }

    /**
     * 
     * @param type $sendingDate
     */
    function setSendingDate($sendingDate) {
        $this->sendingDate = $sendingDate;
    }




}
