<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'BRNeueingang.php';
require_once 'SqlHelper.php';
require_once 'Config.php';
require_once 'WorkOnDB.php';

$simpleXmlElementTotal = simplexml_load_file('br_neueingaenge.xml');
//$simpleXmlElementUpdate = simplexml_load_file(Config::BRRSSXML);
$brNeueingangTotalObjectArray = xmlToBRNeueingangObject($simpleXmlElementTotal->channel->item);
//$brNeieingangUpdateObjectArray = xmlToBRNeueingangObject($simpleXmlElementUpdate->channel->item);



$sqlHelper = new SqlHelper(ConfigLiv::DBSOURCE, ConfigLiv::USERNAME, ConfigLiv::PASSWORD);

//foreach ($brNeueingangTotalObjectArray as $brNeueingang) {
//    $sqlHelper->execute($sqlInsertCommand, [
//        ':pid' => '0',
//        ':wp' => '7',
//        ':fileDate' => $brNeueingang->getCreationDate(),
//        ':filename' => $brNeueingang->getLink(),
//        ':filenamedoc' => '',
//        ':betreff' => $brNeueingang->getTitle(),
//        ':kurzbez' => '',
//        ':dockNumber' => $brNeueingang->getDrsNumber(),
//        ':dockNumberExtr' => '0',
//        ':dockType' => '6_0_BR_Neueingaenge',
//        ':dockArt' => 'Informationen der Landesregierung',
//        ':einreicher' => 'Staatskanzlei',
//        ':drucksachenArt' => '',
//        ':color' => '0',
//        ':pages' => '0',
//        ':ffAusschuss' => '',
//        ':mitAusschuss' => '',
//        ':wahlkreis' => '',
//        ':verfuegung' => '',
//        ':rankDokType' => '0',
//        ':importid' => '',
//        ':baseImportId' => '0',
//            ]
//            , 1
//    );
//}

$paramPreparedStatementArray = array(
    ':pid' => '0',
    ':wp' => '7',
    ':fileDate' => $brNeueingang->getCreationDate(),
    ':filename' => $brNeueingang->getLink(),
    ':filenamedoc' => '',
    ':betreff' => $brNeueingang->getTitle(),
    ':kurzbez' => '',
    ':dockNumber' => $brNeueingang->getDrsNumber(),
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

persistBrNeueingangObjectIntoDB($brNeueingangTotalObjectArray
        , $paramPreparedStatementArray, $sqlHelper, $sqlInsertCommand);



/**
 * 
 * @param type $xmlArray
 * @return \BRNeueingang
 */
function xmlToBRNeueingangObject($xmlArray) {
    foreach ($xmlArray as $item) {
        $BRNeueingangObjectArray[] = BRNeueingang::create()
                ->setTitle($item->title)
                ->setLink($item->link)
                ->setCreationDateToDateTime($item->pubDate)
                ->setAuthor($item->author)
                ->setDrsNumberFromTitle()
                ->setPubDateFromTitle();
    }
    return $BRNeueingangObjectArray;
}



$sqlSelectCommand = 'select betreff, fileDate, fileName, dokNumber '
        . 'from tx_delegates_domain_model_topdok where dokType = :dokType';
$paramArray = array(':dokType' => '6_0_BR_Neueingaenge');

foreach (topDokTableToBRNeueingangObject($sqlHelper, $sqlSelectCommand
        , $paramArray) as $brNeueingang) {
    $brNeueingang->toString();
    echo '-------------------------------------------' . "\n";
}




