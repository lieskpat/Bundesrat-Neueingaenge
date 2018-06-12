<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'ObservableInterface.php';

/**
 * Description of AbstractObservable
 *
 * @author lies
 */
class AbstractObservable implements ObservableInterface {

    private $data;
    private $observerArray = array();

    /**
     * 
     * @return type
     */
    public function getData() {
        return $this->data;
    }

    /**
     * 
     * @param type $data
     */
    public function setData($data) {
        $this->data = $data;
        $this->notify();
    }

    /**
     * 
     * @return type
     */
    public function getObserverArray() {
        return $this->observerArray;
    }

    /**
     * 
     * @param type $observer
     */
    public function addObserver($observer) {
        $this->observerArray[] = $observer;
    }

    /**
     * 
     * @param type $observer
     */
    public function deleteObserver($observer) {
        
    }

    /**
     * 
     */
    public function notify() {
        foreach ($this->observerArray as $observer) {
            $observer->update($this);
        }
    }

}
