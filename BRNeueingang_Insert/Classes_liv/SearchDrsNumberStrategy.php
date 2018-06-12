<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'SearchBehavior.php';

/**
 * Description of SearchDrsNumber
 *
 * @author lies
 */
class SearchDrsNumberStrategy implements SearchBehavior {

    /**
     * 
     * @param string $pattern to search a DRS-Number
     * @param string $subject
     * @return string
     */
    public function searchStrategy($pattern, $subject) {
        if (preg_match($pattern, $subject, $matches)) {
            return $matches[0];
        }
    }

}