<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author Lieske
 */
class Util {

    const INSERT_COMMAND = 'Insert into tx_delegates_domain_model_topdok (pid'
        . ', wp'
        . ', fileDate'
        . ', filename'
        . ', filenamedoc'
        . ', betreff'
        . ', kurzbez'
        . ', dokNumber'
        . ', dokNumberExtr'
        . ', dokType'
        . ', dokArt'
        . ', einreicher'
        . ', drucksachenArt'
        . ', color'
        . ', pages'
        . ', ffAusschuss'
        . ', mitAusschuss'
        . ', wahlkreis'
        . ', verfuegung'
        . ', rankDokType'
        . ', importid'
        . ', baseImportId'
        . ') '
        . 'values (:pid, :wp, :fileDate, :filename, :filenamedoc, :betreff, '
        . ':kurzbez'
        . ', :dockNumber, :dockNumberExtr, :dockType, :dockArt, :einreicher, '
        . ':drucksachenArt'
        . ', :color, :pages, :ffAusschuss, :mitAusschuss, :wahlkreis, '
        . ':verfuegung, :rankDokType, :importid, :baseImportId)';
    const PREPARED_STATEMENT_ARRAY = array(
        ':pid' => '0',
        ':wp' => '7',
        ':fileDate' => '',
        ':filename' => '',
        ':filenamedoc' => '',
        ':betreff' => '',
        ':kurzbez' => '',
        ':dockNumber' => '',
        ':dockNumberExtr' => '0',
        ':dockType' => '6_0_BR_Neueingaenge',
        ':dockArt' => 'Informationen der Landesregierung',
        ':einreicher' => 'Staatskanzlei',
        ':drucksachenArt' => '',
        ':color' => '0',
        ':pages' => '0',
        ':ffAusschuss' => '',
        ':mitAusschuss' => '',
        ':wahlkreis' => '',
        ':verfuegung' => '',
        ':rankDokType' => '0',
        ':importid' => '',
        ':baseImportId' => '0',
    );

    /**
     * build objects from xml array.
     * 
     * @param type $xmlArray
     * @return \BRNeueingang
     */
    public static function xmlToBRNeueingangObject($xmlArray) {
        foreach ($xmlArray as $item) {
            $BRNeueingangObjectArray[] = BRNeueingang::create()
                ->setTitle($item->title)
                ->setLink($item->link)
                ->setCreationDateToDateTime($item->pubDate)
                ->setAuthor($item->author)
                ->setDrsNumberFromTitle()
                ->setPubDateFromTitle()
                ->setHashValue();
        }
        return $BRNeueingangObjectArray;
    }

    /**
     * get array with drsNumber from BRNeueingang object array.
     * 
     * @param array $brNeueingangArray
     * @return array
     */
    public static function getArrayWithDrsNumber($brNeueingangArray) {
        $drsNumberArray = array();
        foreach ($brNeueingangArray as $brNeueingang) {
            $drsNumberArray[] = $brNeueingang->getDrsNumber();
        }
        return $drsNumberArray;
    }

    /**
     * get a BRNeueingang object array of given drsNumbers.
     * 
     * @param array $drsNumberArray
     * @param array $RSSObjectArray
     * @return array $newRSSObjects with \BRNeueiengang objects.
     */
    public static function drsNumberToObject($drsNumberArray, $RSSObjectArray) {
        $newRSSObjects = array();
        foreach ($drsNumberArray as $drsNumber) {
            $newRSSObjects[] = Util::findObjectFromDrsNumber($drsNumber, $RSSObjectArray);
        }
        return $newRSSObjects;
    }

    /**
     * find BRNeueingang object of given Drsnumber.
     * 
     * @param String $drsNumber
     * @param array $objectArray
     * @return \BRNeueingang object
     */
    public static function findObjectFromDrsNumber($drsNumber, $objectArray) {
        foreach ($objectArray as $object) {
            if ($object->isEqualDrsNumber($drsNumber)) {
                return $object;
            }
        }
    }

    /**
     * update DB entry from liv mail box.
     * 
     * @param array $brNeueingangObjectArray
     * @param String $drsNumberArray
     * @return array
     */
    public static function updateObjectsFromLivList($brNeueingangObjectArray, $drsNumberArray) {
        $equalDrsNumberArray = array();
        foreach ($brNeueingangObjectArray as $brNeueingang) {
            foreach ($drsNumberArray as $drsNumber) {
                if ($brNeueingang->isEqualDrsNumber($drsNumber)) {
                    $brNeueingang->setLsaRelevant('Sachsen-Anhalt');
                    $equalDrsNumberArray[] = $brNeueingang;
                }
            }
        }
        return $equalDrsNumberArray;
    }

    /**
     * 
     * @param array $array
     */
    public static function echoArrayValues($array) {
        foreach ($array as $value) {
            echo $value . "\n";
        }
    }

    /**
     * show object properties. 
     * 
     * @param array $brNeueingangObjectArray
     */
    public static function echoBRNeueingangArray($brNeueingangObjectArray) {
        foreach ($brNeueingangObjectArray as $value) {
            $value->toString();
            echo '----------------------------------------------------------------' . "\n";
        }
    }

}
