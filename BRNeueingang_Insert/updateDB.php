<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Hole alle 6_0_BR_Neueingaenge aus DB und erstelle Objekte jeder row

//Lese rss xml http://www.bundesrat.de/SiteGlobals/Functions/RSSFeed/RSSGenerator_Announcement.xml ein
//und erstelle Objekte (NEU)

//Vergleiche Objekte (NEU) mit Objekten aus DB
//Falls Eigenschaften von Objekten in DB sich geändert haben -> update DB
//alle Objekte die nicht in DB sind persistieren

