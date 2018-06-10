<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'BRNeueingang.php';
require_once 'Config.php';
require_once 'SqlHelper.php';
require_once 'SimpleORM.php';

$sqlInsertCommand = 'Insert into tx_delegates_domain_model_topdok (pid'
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

$paramPreparedStatementArray = array(
    ':pid' => '0',
    ':wp' => '7',
    ':fileDate' => $brNeueingang->getCreationDate(),
    ':filename' => '$brNeueingang->getLink()',
    ':filenamedoc' => '',
    ':betreff' => '$brNeueingang->getTitle()',
    ':kurzbez' => '',
    ':dockNumber' => '$brNeueingang->getDrsNumber()',
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

$sqlHelper = new SqlHelper(
        Config::DBSOURCE, Config::USERNAME, Config::PASSWORD
);
$simpleOrm = new SimpleORM();
$simpleXmlElementTotal = simplexml_load_file('br_neueingaenge.xml');
$brNeueingangTotalObjectArray = xmlToBRNeueingangObject(
        $simpleXmlElementTotal->channel->item
);

$simpleOrm->persistBrNeueingangObjectIntoDB($brNeueingangTotalObjectArray
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
                ->setPubDateFromTitle()
                ->setHashValue();
    }
    return $BRNeueingangObjectArray;
}

