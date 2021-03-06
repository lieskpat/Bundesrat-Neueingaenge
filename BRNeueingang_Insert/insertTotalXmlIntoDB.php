<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Classes_BR\BRNeueingang.php';
require_once 'Classes_BR\Config.php';
require_once 'Classes_BR\SqlHelper.php';
require_once 'Classes_BR\SimpleORM.php';
require_once 'Classes_BR\Util.php';

$sqlHelper = new SqlHelper(
    Config::DBSOURCE, Config::USERNAME, Config::PASSWORD
);
$simpleOrm = new SimpleORM();
$simpleXmlElementTotal = simplexml_load_file('br_neueingaenge.xml');
$brNeueingangTotalObjectArray = Util::xmlToBRNeueingangObject(
        $simpleXmlElementTotal->channel->item
);

$simpleOrm->persistBrNeueingangObjectIntoDB($brNeueingangTotalObjectArray
    , Util::PREPARED_STATEMENT_ARRAY, $sqlHelper, Util::INSERT_COMMAND
    , array("SimpleORM", 'fillValuesWithBRNeueingangObjectValues')
);

Util::echoBRNeueingangArray($brNeueingangTotalObjectArray);
