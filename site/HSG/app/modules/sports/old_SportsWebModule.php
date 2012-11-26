<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteDirectoryModule
 *
 * @author flo
 */
class SportsWebModule extends WebModule {

    protected $id = 'sports';
    protected $moduleName = 'Sports';

    protected function initializeForPage() {


        switch ($this->page) {
            case 'index':
                // get information from ini-file
                $categories = $this->getModuleArray('categories');
                // assign the array to the tpl-files
                $this->assign('categories', $categories);


                break;
            case 'sports':
                $title = $this->getPageTitle();

                //look up for private methods
                $elements = $this->getDataViaDataFolder();
                //$elements = $this->getDataViaUnisg();
                //$elements = $this->setDataInDataFolder();

                $this->assign('elements', $elements);
                $this->assign('title', $title);
                break;

            case 'office':
                $officeBuilding = $this->getModuleVar('officeBuilding');
                $officePhone = $this->getModuleVar('officePhone');
                $officeMail = $this->getModuleVar('officeMail');
                $url = $this->getModuleVar('officeUrl');

                // Address arrays
                $contactArray = array();
                array_push($contactArray, array('title' => "Telefon", 'subtitle' => $officePhone, 'url' => "tel: " . $officePhone, 'class' => 'phone'));
                array_push($contactArray, array('title' => "E-Mail", 'subtitle' => $officeMail, 'url' => "mailto: " . $officeMail, 'class' => 'email'));

                // business hours from webpage
                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $tables = $dom->getElementsByTagName("table");

                //Semester
                $semesterArray = $this->getBusinessHours($tables, 0);
                $nonSemesterArray = $this->getBusinessHours($tables, 1);


                $this->assign("address", $officeBuilding);
                $this->assign("contact", $contactArray);
                $this->assign("semester", $semesterArray);
                $this->assign("nosemester", $nonSemesterArray);

                break;
            case 'events':
                $url = $this->getModuleVar('eventsUrl');

                // business hours from webpage
                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }

                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $tables = $dom->getElementsByTagName("table");

                $results = array();

                foreach ($tables as $table) {
                    $caption = $table->getElementsByTagName("caption");
                    $entry = $caption->item(0)->nodeValue;

                    $results[] = array(
                        'title' => $entry,
                        'url' => $this->buildBreadcrumbURL('detail-events', array(
                            'title' => $entry,
                            'url' => $url
                        ))
                    );
                }

                $this->assign("results", $results);
                break;
            case 'detail-events':
                $title = $this->getArg('title');
                $url = $this->getArg('url');

                // business hours from webpage
                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }

                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $tables = $dom->getElementsByTagName("table");

                $events = array();

                foreach ($tables as $table) {
                    $caption = $table->getElementsByTagName("caption");
                    $entry = $caption->item(0)->nodeValue;

                    if ($entry == $title) {
                        $allRows = $table->getElementsByTagName("tr");

                        foreach ($allRows as $row) {
                            $allCells = $row->getElementsByTagName("td");

                            $events[] = array(
                                'title' => $allCells->item(0)->nodeValue,
                                'subtitle' => $allCells->item(1)->nodeValue . " - " . $allCells->item(2)->nodeValue
                            );
                        }
                    }
                }

                $this->assign('title', $title);
                $this->assign('events', $events);




            case 'detail':
                
                $name = $this->getArg('name');
                $url = $this->getArg('url');
                
                $url = rtrim($url);

                //url für dom nutzen
                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $allRows = $dom->getElementsByTagName("tr");
                //var_dump($allRows->length);

                $table = array(); //array to read 
                $details = array(); // array to read the node-values
                $elementNumber = 0;

                //reduced number of entries
                $showedDetails = array("Voraussetzung", "Ausrüstung", "Kosten", "Ort", "Anmeldung");
                $times = "Zeiten";
                $goal = "Lernziel";
                $goalDescription = "";

                foreach ($allRows as $row) {
                    if ($elementNumber >= 3) {

                        //normal values
                        $allColumns = $row->getElementsByTagName("td");
                        $key = $allColumns->item(0)->nodeValue;
                        if ($key == $goal) {
                            $goalDescription = $allColumns->item(1)->nodeValue;
                        }
                        if (in_array($key, $showedDetails)) {
                            $value = $allColumns->item(1)->nodeValue;
                            $details[$key] = $value;
                        }




                        //var_dump($allColumns->item(1)->nodeValue);
                    }
                    $elementNumber++;
                }

                //times
                $allTables = $dom->getElementsByTagName("table");

                $timesTable = $allTables->item(2)->getElementsByTagName("tr");

                $timesCells = $timesTable->item(1)->getElementsByTagName("td");

                $timesArray = array();

                foreach ($timesCells as $timeCell) {
                    $innerHTML = '';
                    $fontTag = $timeCell->getElementsByTagName("font");
                    $children = $fontTag->item(0)->childNodes;

                    foreach ($children as $child) {
                        $tmp_doc = new DOMDocument();
                        $tmp_doc->appendChild($tmp_doc->importNode($child, true));
                        $innerHTML .= $tmp_doc->saveHTML();
                    }

                    $timesArray[] = $innerHTML;
                }

                // loop through all childNodes, getting html       
                $resultTempArray = array();

                foreach ($timesArray as $entry) {
                    $entry = explode("<br>", $entry);
                    $resultTempArray[] = $entry;
                }

                $timesArray = $resultTempArray;
                $resultTempArray = array();

                for ($i = 0; $i < count($timesArray); $i++) {
                    for ($j = 0; $j < count($timesArray[$i]); $j++) {
                        $resultTempArray[$j][$i] = $timesArray[$i][$j];
                    }
                }

                $results = array();

                for ($z = 0; $z < (count($resultTempArray) - 1); $z++) {
                    $results[] = array(
                        'title' => $resultTempArray[$z][0] . "/ " . $resultTempArray[$z][1],
                        'subtitle' => "Verantwortlich: " . $resultTempArray[$z][2] . "<br />Bemerkung: " . $resultTempArray[$z][3]
                    );
                }


                $this->assign('name', $name);
                $this->assign('descriptions', $table);
                $this->assign('attributes', $details);
                $this->assign('times', $times);
                $this->assign('goal', $goalDescription);
                $this->assign('results', $results);
        }
    }

    private function getBusinessHours($domNode, $id) {
        $businessHours = array();

        //Caption
        $caption = $domNode->item($id)->getElementsByTagName("caption");
        $businessHours['caption'] = $caption->item(0)->nodeValue;

        //Content
        $allRows = $domNode->item($id)->getElementsByTagName("tr");
        $hoursArray = array();

        foreach ($allRows as $row) {
            $allCells = $row->getElementsByTagName("td");
            $am = $allCells->item(1)->nodeValue;
            $pm = $allCells->item(2)->nodeValue;

            $am = str_replace(" Uhr", "", $am);
            $pm = str_replace(" Uhr", "", $pm);

            $rowArray[] = array(
                "day" => $allCells->item(0)->nodeValue,
                "am" => $am,
                "pm" => $pm
            );
            $hoursArray['times'] = $rowArray;
        }

        $businessHours['hours'] = $hoursArray;

        return $businessHours;
    }

    //very slow method because of the double-loop of doms
    private function getDataViaUnisg() {
        $baseUrl = $this->getModuleVar('baseUrl');
        $typeUrl = $this->getModuleVar('sports');
        $suffixUrl = $this->getModuleVar('suffixUrl');
        $expand = $this->getModuleVar('expand');

        $title = $this->getPageTitle();

        $url = $baseUrl . $typeUrl . $suffixUrl . $expand;

        if (function_exists('mb_convert_encoding')) {
            $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
        }
        // create new DomDocument
        $dom = new DOMDocument();
        @$dom->loadHTMLFile($url);

        $sports = array();

        $allTables = $dom->getElementsByTagName("tr");

        $lastElement = $allTables->length;
        //bad workarount, better ask for the concrete names
        //named: sportsprogramm, semester, [nach sportart]
        $firstElement = 3;
        $currentElement = 0;

        foreach ($allTables as $sport) {
            if ($currentElement > $firstElement && $currentElement != ($lastElement - 1)) {
                $allLinks = $sport->getElementsByTagName("a");
                $expand = $allLinks->item(0)->getAttribute("href");


                $expand = substr($expand, -4);
                $number = substr($expand, -2);
                if (substr_count($expand, "#") > 0) {
                    $expand = substr($expand, -3);
                    $number = substr($expand, -1);
                }

                $url = $baseUrl . $typeUrl . $suffixUrl . $expand;

                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $allDetailRows = $dom->getElementsByTagName("tr");
                $detailElement = $allDetailRows->item($number + $firstElement + 1);
                $aLink = $detailElement->getElementsByTagName("a");
                $urlLink = $aLink->item(0)->getAttribute("href");
                $urlLinkPre = "http://www1.unisg.ch";

                $sports[] = array(
                    'title' => $sport->nodeValue,
                    'url' => $this->buildBreadcrumbURL('detail', array(
                        'name' => $sport->nodeValue,
                        'url' => $urlLinkPre . $urlLink
                    ))
                );
            }
            $currentElement++;
        }

        return $sports;
    }

    // just reading the txt-file in the sports-folder
    private function getDataViaDataFolder() {
        
	//Übergangslösung mit PDF
	$file = DATA_DIR . "/sports/sportsprogram_pdf.txt";
	// $file = DATA_DIR . "/sports/sportsprogram.txt";
        $sports = array();
        $tmpArray = array();

        if (file_exists($file)) {
            $fh = fopen($file, 'r');
            while (($buffer = fgets($fh)) != false) {
                array_push($tmpArray, explode(",", $buffer));
            }
        } else {
            throw new KurogoException("txt-Datei für Unisportprogramm nicht gefunden.");
        }

        fclose($fh);

        /* Lösung mit DOM & Txt-Datei
	foreach ($tmpArray as $entry) {
            $sports[] = array(
                'title' => $entry[0],
                'url' => $this->buildBreadcrumbURL('detail', array(
                    'name' => $entry[0],
                    'url' => $entry[1]
                )));
        } */

	// Lösung mit PDF
	foreach ($tmpArray as $entry) {
            $sports[] = array(
                'title' => $entry[0],
                'url' => $entry[1]
                );
	}

        return $sports;
    }

    //only use this method when overwriting the entries.txt when unisportprogramm was updated    
    private function setDataInDataFolder() {

        $baseUrl = $this->getModuleVar('baseUrl');
        $typeUrl = $this->getModuleVar('sports');
        $suffixUrl = $this->getModuleVar('suffixUrl');
        $expand = $this->getModuleVar('expand');

        $title = $this->getPageTitle();

        $url = $baseUrl . $typeUrl . $suffixUrl . $expand;

        if (function_exists('mb_convert_encoding')) {
            $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
        }
        // create new DomDocument                
        $dom = new DOMDocument();
        @$dom->loadHTMLFile($url);

        $controller->setCacheLifetime(3600);
        $controller->setBaseURL($url);
        $allTables = $controller->getParsedData()->getElementsByTagName("tr");

        $sports = array();

        //$allTables = $dom->getElementsByTagName("tr");

        $lastElement = $allTables->length;
        //var_dump($lastElement);
        //bad workarount, better ask for the concrete names
        //named: sportsprogramm, semester, [nach sportart]
        $firstElement = 3;
        $currentElement = 0;


        foreach ($allTables as $sport) {
            if ($currentElement > $firstElement && $currentElement != ($lastElement - 1)) {
                // var_dump($sport);
                $allLinks = $sport->getElementsByTagName("a");
                $expand = $allLinks->item(0)->getAttribute("href");


                $expand = substr($expand, -4);
                $number = substr($expand, -2);
                if (substr_count($expand, "#") > 0) {
                    $expand = substr($expand, -3);
                    $number = substr($expand, -1);
                }

                $url = $baseUrl . $typeUrl . $suffixUrl . $expand;

                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $controller->setBaseURL($url);

                //$allDetailRows = $dom->getElementsByTagName("tr");
                $allDetailRows = $controller->getParsedData()->getElementsByTagName("tr");

                $detailElement = $allDetailRows->item($number + $firstElement + 1);
                $aLink = $detailElement->getElementsByTagName("a");
                $urlLink = $aLink->item(0)->getAttribute("href");
                $urlLinkPre = "http://www1.unisg.ch";

                $sports[] = array(
                    'title' => $sport->nodeValue,
                    'url' => $this->buildBreadcrumbURL('detail', array(
                        'name' => $sport->nodeValue,
                        'url' => $urlLinkPre . $urlLink
                    ))
                );
            }
            $currentElement++;
        }

        $myFile = DATA_DIR . "/sports/entries.txt";
        $fh = fopen($myFile, 'w') or die("can't open file");
        foreach ($sports as $sport) {
            fwrite($fh, $sport['title'] . ",");
            fwrite($fh, $sport['url'] . "\n");
        }

        fclose($fh);
    }

}

?>
