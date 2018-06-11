<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BRNeueingang
 *
 * @author Lieske
 */
class BRNeueingang {
    
    /*
     * 
     */
    private $title;
    /*
     * 
     */
    private $link;
    /*
     * 
     */
    private $creationDate;
    /*
     * 
     */
    private $pubDate;
    /*
     * 
     */
    private $author;
    /*
     * 
     */
    private $drsNumber;
    /*
     * 
     */
    private $hashValue;

    /**
     * Constructor
     */
    private function __construct() {
        // allocate your stuff
    }

    /**
     * Static constructor(factory)
     * @return \self
     */
    public static function create() {
        $instance = new self();
        return $instance;
    }

    /**
     * 
     * @return type
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * 
     * @return type
     */
    public function getCreationDate() {
        return $this->creationDate->format('Y-m-d H:i:s');
    }
    
    /**
     * 
     */
    public function getCreationDateTime() {
        return $this->creationDate;
    }

    /**
     * 
     * @return type
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * 
     * @return type
     */
    public function getPubDate() {
        return $this->pubDate;
    }

    /**
     * 
     * @return type
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * 
     * @return type
     */
    public function getDrsNumber() {
        return $this->drsNumber;
    }

    /**
     * 
     * @return type
     */
    public function getHashValue() {
        return $this->hashValue;
    }

    /**
     * 
     * @param type $title
     */
    public function setTitle($title) {

        $this->title = trim($title);
        return $this;
    }

    /**
     * 
     * @param type $link
     */
    public function setLink($link) {
        $this->link = trim($link);
        return $this;
    }

    /**
     * 
     * @param type $creationDate
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * 
     * @param String $creationDate
     * @return $this
     */
    public function setCreationDateToDateTime($creationDate) {
        $this->creationDate = DateTime::createFromFormat(DateTime::RSS
                , $creationDate
        );
        return $this;
    }

    /**
     * 
     * @param type $pubDate
     */
    public function setPubDate($pubDate) {
        $this->pubDate = $pubDate;
        return $this;
    }

    /**
     * 
     * @param type $author
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    /**
     * 
     * @param type $drsNumber
     */
    public function setDrsNumber($drsNumber) {
        $this->drsNumber = $drsNumber;
        return $this;
    }

    /**
     * 
     * @return $this
     */
    public function setDrsNumberFromTitle() {
        if (!is_null($this->getTitle())) {
            return $this->setDrsNumber($this->getStringFromSubject('/[0-9]{1,4}\/[0-9]{1,3}\(?[a-zA-Z]*\)?/'
                        , $this->getTitle()
            ));
        }
    }

    /**
     * 
     * @return $this
     */
    public function setPubDateFromTitle() {
        if (!is_null($this->getTitle())) {
            return $this->setPubDate(str_replace('|', '', $this->getStringFromSubject(
                            '/\| [0-9]{1,2}\. [a-zA-ZäÄöÖüÜß]* [0-9]{4}/'
                            , $this->getTitle())));
        }
    }

    /**
     * set a hashValue from drsNumber and pubDate
     * @return $this
     */
    public function setHashValue() {
        $array = array($this->getDrsNumber(), $this->getPubDate());
        $this->hashValue = $this->hash($array);
        return $this;
    }

    /**
     * 
     * @param String $pattern
     * @param String $subject
     * @return String $match[0]
     */
    private function getStringFromSubject($pattern, $subject) {
        preg_match($pattern, $subject, $match);
        return $match[0];
    }

    /**
     * 
     * @param array $stringArrayToHash with strings to implode
     * @return string
     */
    private function setHashString($stringArrayToHash) {
        return implode("", $stringArrayToHash);
    }

    /**
     * 
     */
    public function toString() {
        echo 'Titel: ' . ' ' . $this->getTitle() . "\n";
        echo 'Link: ' . ' ' . $this->getLink() . "\n";
        echo 'CreationDate: ' . ' ' . $this->getCreationDate() . "\n";
        //echo 'CreationDate: ' . ' ' . $this->getCreationDateTime() . "\n";
        echo 'PubDate: ' . ' ' . $this->getPubDate() . "\n";
        echo 'Author: ' . ' ' . $this->getAuthor() . "\n";
        echo 'DRS: ' . ' ' . $this->getDrsNumber() . "\n";
        echo 'Hash: ' . ' ' . $this->getHashValue() . "\n";
    }

    /**
     * 
     * @param BRNeueingang $object to compare two objects
     * @return boolean
     */
    public function equals($object) {
        if (is_null($object)) {
            return FALSE;
        }
        if ($object == $this) {
            return TRUE;
        }
        if ($object instanceof BRNeueingang) {
            if ($this->getHashValue() == $object->getHashValue()) {
                if ($this->title == $object->getTitle() &&
                    $this->link == $object->getLink() &&
                    $this->drsNumber == $object->getDrsNumber) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param array $stringArrayToHash
     * @return hash
     */
    public function hash($stringArrayToHash) {
        return hash('md5', $this->setHashString($stringArrayToHash));
    }

}
