<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'BRNeueingang.php';

/**
 * Description of SimpleORM
 *
 * @author lies
 */
class SimpleORM {

    /**
     * Constructor.
     * 
     */
    public function __construct() {
        
    }

    /**
     * 
     * @param \SqlHelper $sqlHelper
     * @param String $sqlCommand
     * @param array $paramForPrepareStatementArray
     * @return array
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
                ->setCreationDate(new DateTime($value['fileDate']))
                ->setLink($value['fileName'])
                ->setDrsNumber($value['dokNumber'])
                ->setLsaRelevant($value['kurzbez']);
        }
        return $BRNeueingangObjectArray;
    }

    /**
     * 
     * @param array $objectArray
     * @param array $paramPreparedStatementArray
     * @param \SqlHelper $sqlHelper
     * @param String $sqlCommand
     * @param array $callbackFunction 
     *
     */
    public function persistBrNeueingangObjectIntoDB($objectArray
    , $paramPreparedStatementArray, $sqlHelper, $sqlCommand, $callbackFunction) {

        try {
            $sqlHelper->getPdo()->beginTransaction();
            foreach ($objectArray as $brNeueingang) {
                $sqlHelper->execute(
                    $sqlCommand
                    //, $this->fillValuesWithBRNeueingangObjectValues(
                    , call_user_func($callbackFunction, $brNeueingang
                        , $paramPreparedStatementArray), 1
                );
            }
            $sqlHelper->getPdo()->commit();
        } catch (Exception $exc) {
            $sqlHelper->getPdo()->rollback();
            echo $exc->getTraceAsString();
        }
    }

    /**
     * 
     * @param \BRNeueingang $brNeueingang
     * @param array $paramPreparedStatementArray
     * @return array
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
        $paramPreparedStatementArray[':importid'] = $brNeueingang
            ->getHashValue();

        return $paramPreparedStatementArray;
    }

    /**
     * 
     * @param \BRNeueingang $brNeueingang
     * @param array $paramPreparedStatementArray
     * @return array Description
     */
    private function fillValueUpdateBRNeueingangObjectValue($brNeueingang, $paramPreparedStatementArray) {
        $paramPreparedStatementArray[':kurzbez'] = $brNeueingang->getLsaRelevant();
        $paramPreparedStatementArray[':dokNumber'] = $brNeueingang->getDrsNumber();
        return $paramPreparedStatementArray;
    }

}
