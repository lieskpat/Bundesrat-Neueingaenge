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
                    ->setCreationDate($value['fileDate'])
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
            $sqlHelper->beginTransaction();
            foreach ($objectArray as $brNeueingang) {
                $this->fillValuesWithBRNeueingangObjectValues($brNeueingang
                        , $paramPreparedStatementArray);
                $sqlHelper->execute($sqlCommand, $paramPreparedStatementArray, 1);
            }
            $sqlHelper->commit();
        } catch (Exception $exc) {
            $sqlHelper->rollback();
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
        foreach ($paramPreparedStatementArray as $key => $value) {
            switch ($key) {
                case ':fileDate':
                    $paramPreparedStatementArray[$key] = $brNeueingang
                            ->getCreationDate();
                //$value = $brNeueingang->getCreationDate();
                case ':fileName':
                    $paramPreparedStatementArray[$key] = $brNeueingang
                            ->getLink();
                //$value = $brNeueingang->getLink();
                case ':betreff':
                    $paramPreparedStatementArray[$key] = $brNeueingang
                            ->getTitle();
                //$value = $brNeueingang->getTitle();
                case ':dockNumber':
                    $paramPreparedStatementArray[$key] = $brNeueingang
                            ->getDrsNumber();
                //$value = $brNeueingang->getDrsNumber();
                default:
                    break;
            }
        }
    }

}
