<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Classes_BR\SqlHelper.php';
require_once 'Classes_BR\SimpleORM.php';
require_once 'Classes_BR\Config.php';
require_once 'Classes_BR\BRNeueingang.php';
require_once 'Classes_BR\Util.php';

//Hole alle 6_0_BR_Neueingaenge aus DB und erstelle Objekte aus result row
$sqlHelper = new SqlHelper(
    Config::DBSOURCE, Config::USERNAME, Config::PASSWORD
);
$simpleOrm = new SimpleORM();
$sqlCommand = 'select betreff, fileDate, fileName, dokNumber, kurzbez '
    . 'from tx_delegates_domain_model_topdok where dokType = :dokType';

$brNeueingangObjectArrayFromDB = $simpleOrm->topDokTableToBRNeueingangObject(
    $sqlHelper
    , $sqlCommand
    , array(':dokType' => '6_0_BR_Neueingaenge')
);
echo 'Neueingang from DB' . "\n";
echo '----------------------------------------------------------------------' . "\n";
echo count($brNeueingangObjectArrayFromDB) . "\n";
foreach ($brNeueingangObjectArrayFromDB as $brNeueingang) {
    $brNeueingang->toString();
    echo '-------------------------------------------' . "\n";
}

//Lese rss xml http://www.bundesrat.de/SiteGlobals/Functions/RSSFeed/RSSGenerator_Announcement.xml ein
//und erstelle Objekte (NEU)

$simpleXmlElementUpdate = simplexml_load_file('RSSGenerator_Announcement.xml');
$brNeueingangObjectArrayFromRss = Util::xmlToBRNeueingangObject(
        $simpleXmlElementUpdate->channel->item
);

echo 'Neueingang from RSS' . "\n";
echo '----------------------------------------------------------------------' . "\n";
echo count($brNeueingangObjectArrayFromRss) . "\n";
foreach ($brNeueingangObjectArrayFromRss as $brNeueingang) {
    $brNeueingang->toString();
    echo '-------------------------------------------' . "\n";
}
//Vergleiche Objekte (NEU) mit Objekten aus DB
$newBRNeueingang = array();
foreach ($brNeueingangObjectArrayFromRss as $brNeueingangFromRss) {
    foreach ($brNeueingangObjectArrayFromDB as $brNeueingangFromDB) {
        if (!$brNeueingangFromRss->isEqualDrsNumber($brNeueingangFromDB->getDrsNumber())) {
            $newBRNeueingang[] = $brNeueingangFromRss;
        }
    }
}
echo 'Vergleich from RSS/DB' . "\n";
echo '----------------------------------------------------------------------' . "\n";
echo count($newBRNeueingang) . "\n";
foreach ($newBRNeueingang as $brNeueingang) {
    $brNeueingang->toString();
    echo '-------------------------------------------' . "\n";
}

//alle Objekte die nicht in DB sind persistieren
//$simpleOrm->persistBrNeueingangObjectIntoDB(
//    $newBRNeueingang
//    , Util::PREPARED_STATEMENT_ARRAY
//    , $sqlHelper
//    , Util::INSERT_COMMAND
//);


//Falls Eigenschaften von Objekten in DB sich geändert haben -> update DB


