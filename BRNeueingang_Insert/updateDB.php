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

//************************************************************************
//Hole alle 6_0_BR_Neueingaenge aus DB und erstelle Objekte aus result row
//************************************************************************

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
Util::echoBRNeueingangArray($brNeueingangObjectArrayFromDB);

//****************************************************************************************************
//Lese rss xml http://www.bundesrat.de/SiteGlobals/Functions/RSSFeed/RSSGenerator_Announcement.xml ein
//und erstelle Objekte (NEU)
//****************************************************************************************************

$simpleXmlElementUpdate = simplexml_load_file('RSSGenerator_Announcement.xml');
$brNeueingangObjectArrayFromRss = Util::xmlToBRNeueingangObject(
        $simpleXmlElementUpdate->channel->item
);

echo 'Neueingang from RSS' . "\n";
echo '----------------------------------------------------------------------' . "\n";
echo count($brNeueingangObjectArrayFromRss) . "\n";
Util::echoBRNeueingangArray($brNeueingangObjectArrayFromRss);

//********************************************
//Vergleiche Objekte (NEU) mit Objekten aus DB
//********************************************

$newBRNeueingangDrsNumber = array_diff(Util::getArrayWithDrsNumber(
        $brNeueingangObjectArrayFromRss)
    , Util::getArrayWithDrsNumber($brNeueingangObjectArrayFromDB)
);

$newRSSObjectArray = Util::drsNumberToObject($newBRNeueingangDrsNumber, $brNeueingangObjectArrayFromRss);

Util::echoArrayValues($newBRNeueingangDrsNumber);
echo count($newBRNeueingangDrsNumber) . "\n";
echo '-------------------------------------------------------------' . "\n";

Util::echoBRNeueingangArray($newRSSObjectArray);
echo count($newRSSObjectArray);

//******************************
//Persistiere neue Objekte in DB
//******************************

$simpleOrm->persistBrNeueingangObjectIntoDB($newRSSObjectArray
    , Util::PREPARED_STATEMENT_ARRAY
    , $sqlHelper
    , Util::INSERT_COMMAND
    , array("SimpleORM", 'fillValuesWithBRNeueingangObjectValues')
);

//Falls Eigenschaften von Objekten in DB sich geändert haben -> update DB


