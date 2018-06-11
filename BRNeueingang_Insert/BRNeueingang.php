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
     * @var String
     */
    private $title;
    /*
     * @var String
     */
    private $link;
    /*
     * @var \Datetime
     */
    private $creationDate;
    /*
     * 
     */
    private $pubDate;
    /*
     * @var String
     */
    private $author;
    /*
     * @var String
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
     * @return String
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * returns string representation of DateTime Object.
     * 
     * @return String
     */
    public function getCreationDate() {
        return $this->creationDate->format('Y-m-d H:i:s');
    }
    
    /**
     * returns the DateTime Object.
     * 
     * @return DateTime
     */
    public function getCreationDateTime() {
        return $this->creationDate;
    }

    /**
     * 
     * @return String
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
     * @return String
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * 
     * @return String
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
     * @param String $title
     */
    public function setTitle($title) {
        $this->title = trim($title);
        return $this;
    }

    /**
     * 
     * @param String $link
     */
    public function setLink($link) {
        $this->link = trim($link);
        return $this;
    }

    /**
     * 
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * creates a DateTime object and storage to instance variable creationDate 
     * 
     * @param String $creationDate
     * @return BRNeueingang
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
     * @param String $author
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    /**
     * 
     * @param String $drsNumber
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
