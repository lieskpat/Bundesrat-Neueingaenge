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

}
