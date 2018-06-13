<?php

require_once 'Classes_liv\FileLogger.php';
require_once 'Classes_liv\FileConnection.php';
require_once 'Classes_liv\FileOperation.php';
require_once 'Classes_liv\ExchangeConnection.php';
require_once 'Classes_liv\ExchangeMailBox.php';
require_once 'Classes_liv\SearchDrsNumberStrategy.php';
require_once 'Classes_liv\ConfigLiv.php';
require_once 'Classes_BR\Config.php';
require_once 'Classes_BR\SqlHelper.php';
require_once 'Classes_BR\SimpleORM.php';
require_once 'Classes_BR\Util.php';

$fileConnection = new FileConnection('liv.log', 'a');
$logger = new FileLogger(new FileOperation($fileConnection));
$exchangeConnection = new ExchangeConnection(
    ConfigLiv::EXCHANGE_SOURCE
    , ConfigLiv::USERNAME
    , ConfigLiv::PASSWORD
);
$exchangeConnection->addObserver($logger);
$exchangeMailBox = new ExchangeMailBox($exchangeConnection, new SearchDrsNumberStrategy());
$exchangeMailBox->addObserver($logger);
$drsNumberArray = array_unique($exchangeMailBox->getAllSearchStringsFromAllMailBodyText(
        '/[0-9]{1,4}\/[0-9]{1,3}\(?[a-zA-Z]*\)?/'
        , 1514761200)
);

Util::echoArrayValues($drsNumberArray);
echo count($drsNumberArray) . "\n";

$exchangeConnection->closeConnection();
$fileConnection->closeConnection();

$sqlHelper = new SqlHelper(Config::DBSOURCE, Config::USERNAME, Config::PASSWORD);
$simpleOrm = new SimpleORM();
$sqlCommand = 'select betreff, fileDate, fileName, dokNumber, kurzbez '
    . 'from tx_delegates_domain_model_topdok where dokType = :dokType';

$brNeueingangObjectArrayFromDB = $simpleOrm->topDokTableToBRNeueingangObject(
    $sqlHelper
    , $sqlCommand
    , array(':dokType' => '6_0_BR_Neueingaenge')
);

Util::echoBRNeueingangArray($brNeueingangObjectArrayFromDB);
echo count($brNeueingangObjectArrayFromDB) . "\n";

$equalDrsNumberArray = Util::updateObjectsFromLivList($brNeueingangObjectArrayFromDB, $drsNumberArray);

echo '********************************************************************' . "\n";
Util::echoBRNeueingangArray($equalDrsNumberArray);
echo count($equalDrsNumberArray);

$sqlUpdateCommand = "update tx_delegates_domain_model_topdok set kurzbez = :kurzbez  where dokNumber = :dokNumber";
$simpleOrm->persistBrNeueingangObjectIntoDB(
    $equalDrsNumberArray
    , array(':kurzbez' => '', ':dokNumber' => '')
    , $sqlHelper, $sqlUpdateCommand
    , array("SimpleORM", 'fillValueUpdateBRNeueingangObjectValue')
);




