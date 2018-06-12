<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'AbstractObservable.php';
require_once 'ExchangeEmail.php';

/**
 * Description of ExchangeMailBox
 *
 * @author lies
 */
class ExchangeMailBox extends AbstractObservable {

    private $exchangeConnection;
    private $searchBehavior;
    private $imapStream;

    /**
     * 
     * @param type $exchangeConnection
     * @param type $searchBehavior
     */
    function __construct($exchangeConnection, $searchBehavior) {
        $this->exchangeConnection = $exchangeConnection;
        $this->searchBehavior = $searchBehavior;
        $this->exchangeConnection->openConnection();
        $this->imapStream = $this->exchangeConnection->getImapStream();
    }

    /**
     * 
     * @return type
     */
    function getExchangeConnection() {
        return $this->exchangeConnection;
    }

    /**
     * 
     * @return type
     */
    function getSearchBehavior() {
        return $this->searchBehavior;
    }

    /**
     * 
     * @param type $exchangeConnection
     */
    function setExchangeConnection($exchangeConnection) {
        $this->exchangeConnection = $exchangeConnection;
    }

    /**
     * 
     * @param type $searchBehavior
     */
    function setSearchBehavior($searchBehavior) {
        $this->searchBehavior = $searchBehavior;
    }

    /**
     * 
     * @param type $imapStream
     * @param type $messageNumber
     * @return imap_header object
     */
    function getMailHeaderObjectFromMail($imapStream, $messageNumber) {
        return imap_header($imapStream, $messageNumber);
    }

    /**
     * 
     * @return array
     */
    function getAllMailBodysFromMailBox() {
        $mailBodyArray = array();
        for ($index = 0; $index < imap_num_msg($this->imapStream); $index++) {
            $mailBodyArray[] = imap_body($this->imapStream, $index);
        }
        return $mailBodyArray;
    }

    /**
     * 
     * @param string $fromDate
     */
    function getAllMailBodysFromMailBoxFromMailSendingDate($fromDate) {
        $exchangeEmailArray = array();
        for ($index = 1; $index <= $this->getAmountOfAllMessagesFromMailBox()
        ; $index++) {
            $headerObject = $this->getMailHeaderObjectFromMail(
                    $this->imapStream, $index);
            if ($headerObject && (strtotime($headerObject->date) > $fromDate)) {
                //$mailBodyArray[] = imap_body($this->imapStream, $index);
                $exchangeEmailArray[] = new ExchangeEmail(
                        imap_body($this->imapStream, $index)
                        , $headerObject->fromaddress
                        , $headerObject->udate);
            }
        }
        return $exchangeEmailArray;
    }

    /**
     * 
     * @param timestamp $fromDate
     */
    function printAllMailBodysFromMailBox($fromDate) {
        foreach ($this->getAllMailBodysFromMailBoxFromMailSendingDate($fromDate) as $exchangeEmail) {
            echo $exchangeEmail->getMailBody();
        }
    }

    /**
     * 
     * @return type
     */
    function getAmountOfAllMessagesFromMailBox() {
        return imap_num_msg($this->imapStream);
    }

    /**
     * 
     * @param string $pattern
     * @param string $fromDate
     * @return array
     */
    function getAllSearchStringsFromAllMailBodyText($pattern, $fromDate) {
        $searchResult = array();
        foreach ($this->getAllMailBodysFromMailBoxFromMailSendingDate($fromDate)
        as $exchangeEmail) {
            if ($this->getSearchString($pattern
                            , $exchangeEmail->getMailBody()) != "") {
                $drsNumberResult = $this->getSearchString(
                        $pattern, $exchangeEmail->getMailBody());
                $searchResult[] = $drsNumberResult;
                //notify the Observer for logging
                $this->setData($exchangeEmail->getSenderAddress()
                        . ' ' . date("d.m.Y - H:i:s"
                                , $exchangeEmail->getSendingDate()) . ' '
                        . $drsNumberResult);
            }
        }
        return $searchResult;
    }

    /**
     * 
     * @param string $pattern
     * @param string $subject
     * @return string the string to search
     */
    function getSearchString($pattern, $subject) {
        return $this->searchBehavior->searchStrategy($pattern, $subject);
    }

}
