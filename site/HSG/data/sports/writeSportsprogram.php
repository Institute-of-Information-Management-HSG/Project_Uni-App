<?php

/*
 * 
  $url = "http://www1.unisg.ch/www/sport.nsf/SportprogramBySportart!OpenView&Start=1&Count=150&Expand=2";
  $baseUrl = $this->getModuleVar('baseUrl');
  $typeUrl = $this->getModuleVar('sports');
  $suffixUrl = $this->getModuleVar('suffixUrl');
  $expand = $this->getModuleVar('expand');
 */


$baseUrl = "http://www1.unisg.ch/www/sport.nsf/Sportprogram";
$typeUrl = "BySportart";
$suffixUrl = "!OpenView&Start=1&Count=150&Expand=";
$expand = "1";

$url = $baseUrl . $typeUrl . $suffixUrl . $expand;
var_dump($url);

if (function_exists('mb_convert_encoding')) {
    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
}
// create new DomDocument                
$dom = new DOMDocument();
@$dom->loadHTMLFile($url);

$allTables = $dom->getElementsByTagName("tr");

$sports = array();


$lastElement = $allTables->length;

//bad workaround, better ask for the concrete names
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

        //$allDetailRows = $dom->getElementsByTagName("tr");
        $allDetailRows = $dom->getElementsByTagName("tr");

        $detailElement = $allDetailRows->item($number + $firstElement + 1);
        $aLink = $detailElement->getElementsByTagName("a");
        $urlLink = $aLink->item(0)->getAttribute("href");
        $urlLinkPre = "http://www1.unisg.ch";

        $sports[] = array(
            'title' => $sport->nodeValue,           
            'url' => $urlLinkPre . $urlLink
            //'url' => $this->buildBreadcrumbURL('detail', array(
            //    'name' => $sport->nodeValue,
            //    'url' => $urlLinkPre . $urlLink
            //))
        );
        
        echo "read entry: " . $sport->nodeValue . "\n";
    }
    $currentElement++;
}

$myFile = "sportsprogram.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
foreach ($sports as $sport) {
    fwrite($fh, $sport['title'] . ",");
    fwrite($fh, $sport['url'] . "\n");
    echo "write entry: " . $sport['title'] . ", " . $sport['url'] . "\n";
}

fclose($fh);
?>
