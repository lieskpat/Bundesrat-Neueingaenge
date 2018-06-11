<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SimpleORM
 *
 * @author lies
 */
class SimpleORM {

    /**
     * 
     * @param type $sqlHelper
     */
    public function __construct() {
        
    }

    /**
     * 
     * @param type $sqlHelper
     * @param type $sqlCommand
     * @param type $paramForPrepareStatementArray
     * @return type
     */
    public function topDokTableToBRNeueingangObject($sqlHelper, $sqlCommand
    , $paramForPrepareStatementArray) {
        $BRNeueingangObjectArray = array();
        $resultArray = $sqlHelper->execute($sqlCommand
            , $paramForPrepareStatementArray
        );
        foreach ($resultArray as $value) {
            $BRNeueingangObjectArray[] = BRNeueingang::create()
                ->setTitle($value['betreff'])
                //->setCreationDate($value['fileDate'])
                ->setCreationDateToDateTime($value['fileDate'])
                ->setLink($value['fileName'])
                ->setDrsNumber($value['dokNumber']);
        }
        return $BRNeueingangObjectArray;
    }

    /**
     * 
     * @param type $objectArray
     * @param type $paramPreparedStatementArray
     * @param type $sqlHelper
     * @param type $sqlCommand
     * @param type $objectValueFromPreparedStatementArray
     */
    public function persistBrNeueingangObjectIntoDB($objectArray
    , $paramPreparedStatementArray, $sqlHelper, $sqlCommand) {

        try {
            $sqlHelper->getPdo()->beginTransaction();
            foreach ($objectArray as $brNeueingang) {
                $sqlHelper->execute($sqlCommand, $this->fillValuesWithBRNeueingangObjectValues($brNeueingang
                        , $paramPreparedStatementArray), 1);
            }
            $sqlHelper->getPdo()->commit();
        } catch (Exception $exc) {
            $sqlHelper->getPdo()->rollback();
            echo $exc->getTraceAsString();
        }
    }

    /**
     * 
     * @param type $brNeueingang
     * @param type $paramPreparedStatementArray
     */
    private function fillValuesWithBRNeueingangObjectValues($brNeueingang
    , $paramPreparedStatementArray) {
        $paramPreparedStatementArray[':fileDate'] = $brNeueingang
            ->getCreationDate();
        $paramPreparedStatementArray[':filename'] = $brNeueingang
            ->getLink();
        $paramPreparedStatementArray[':betreff'] = $brNeueingang
            ->getTitle();
        $paramPreparedStatementArray[':dockNumber'] = $brNeueingang
            ->getDrsNumber();

        return $paramPreparedStatementArray;
    }

}
