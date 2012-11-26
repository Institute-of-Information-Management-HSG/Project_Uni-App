<?php

/**
 * Description of LibraryWebModule
 *
 * @author fabio
 */
class LibraryWebModule extends WebModule
{
  protected $id='library';
  protected $moduleName = 'Library';

  protected function initializeForPage() {
    switch ($this->page) {
      case 'index':
        // get information from ini-file
        $categories = $this->getModuleArray('categories');
        // assign the array to the tpl-files
        $this->assign('categories', $categories);
        break;
      case 'openinghours':
        $url = $this->getModuleVar('openingUrl');
        $urlClosings = $this->getModuleVar('closingUrl');

        // business hours from webpage
        if (function_exists('mb_convert_encoding')) {
          $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
          $urlClosings = mb_convert_encoding($urlClosings, 'HTML-ENTITIES', 'UTF-8');
        }
        // create new DomDocument
        $dom = new DOMDocument();
        $domClosings = new DOMDocument();
        @$dom->loadHTMLFile($url);
        @$domClosings->loadHTMLFile($urlClosings);
                
        $tables = $dom->getElementsByTagName("table");
        $tablesClosing = $domClosings->getElementsByTagName("table");
   
        //openinghours
        $openinghoursArray = $this->getOpeningHours($tables, 0);

        //closings
        $closingsTyArray = $this->getClosingHours($tablesClosing, 0);
        $closingsNyArray = $this->getClosingHours($tablesClosing, 1);
                
        $this->assign("openinghours", $openinghoursArray);
        $this->assign("closingsTy", $closingsTyArray);
        $this->assign("closingsNy", $closingsNyArray);
        break;
      case 'contact':
        $officeBuilding = $this->getModuleVar('officeBuilding');
        $officeAddress =$this->getModuleVar('officeAddress');
        $officeAddressUrl=$this->getModuleVar('officeAddressUrl');
        $officePhone = $this->getModuleVar('officePhone');
        $officeFax = $this->getModuleVar('officeFax');
        $officeMail = $this->getModuleVar('officeMail');
        $officeUrl = $this->getModuleVar('officeUrl');
		$officeFacebook = $this->getModuleVar('officeFacebook');
        $officeLocation = $this->getModuleVar('officeLocation');
                
        // Address arrays
        $contactArray = array();
        array_push($contactArray, array('title' => "Kartenansicht", 'subtitle' => $officeBuilding . ", " . $officeAddress, 'url' => $officeAddressUrl));
        array_push($contactArray, array('title' => "Telefon", 'subtitle' => $officePhone, 'url' => "tel: " . $officePhone, 'class' => 'phone'));
        array_push($contactArray, array('title' => "Fax", 'subtitle' => $officeFax, 'url' => "fax: " . $officeFax, 'class' => 'phone'));
        array_push($contactArray, array('title' => "E-Mail", 'subtitle' => $officeMail, 'url' => "mailto: " . $officeMail, 'class' => 'email'));
        array_push($contactArray, array('title' => "Webseite", 'subtitle' => $officeUrl, 'url' => $officeUrl, 'class' => 'web'));
		array_push($contactArray, array('title' => "Facebook-Seite", 'subtitle' => $officeFacebook, 'url' => $officeFacebook, 'class' => 'web'));
        //array_push($contactArray, array('title' => "Kartenansicht", 'subtitle' => "Bibliotheksgeb&auml;ude 09", 'url' => $officeLocation, 'class' => 'web'));
        
        //$this->assign("address", $officeBuilding);
        $this->assign("contact", $contactArray);
        break;
      case 'links':
        $librarylinks = $this->getModuleArray('librarylinks');
        $this->assign('librarylinks', $librarylinks);
        break;
      case 'guidance':
        $url = $this->getModuleVar('guidanceUrl');

        // guidance hours from webpage
        if (function_exists('mb_convert_encoding')) {
          $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
        }
        // create new DomDocument
        $dom = new DOMDocument();
        @$dom->loadHTMLFile($url);
                
        $tables = $dom->getElementsByTagName("table");
                
        //guidancehours
        $guidancehoursArray = $this->getGuidanceHours($tables, 0);
                
        $this->assign("guidance", $guidancehoursArray);
        $guidancelinks = $this->getModuleArray('guidancelinks');
        $this->assign('guidancelinks', $guidancelinks);
        break;
      case 'events':
        $url = $this->getModuleVar('eventsUrl');
        if (function_exists('mb_convert_encoding')) {
          $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
        }
        $dom = new DOMDocument();
        @$dom->loadHTMLFile($url);

        //use DOMXpath to navigate the html with the DOM
        $events = $dom->saveHTML();
        $dom_xpath = new DOMXpath($dom);

        //delete the remain
        $startpos = strpos($events,"<p>Kommende");
        $endpos = strpos($events,"nscht.<br><br></p>");
        $endpos = $endpos - $startpos + 18;
        $events = substr($events,$startpos,$endpos);
        $events = preg_replace("/<a[^>]+>/i", "", $events);
        $events = preg_replace("/<\/a>/i", "", $events);
        

        $this->assign("events", $events);
        break;
      case 'search':
        $appList = $this->getModuleArray('appList');
        $this->assign('appList', $appList);
        break;
    }
  }

  private function getOpeningHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $businessHours['caption'] = $caption->item(0)->nodeValue;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
              $time = $allCells->item(1)->nodeValue;

              $time = str_replace("bis", "-", $time);
              $time = str_replace(" Uhr", "", $time);
              $rowArray[] = array(
                  "day" => $allCells->item(0)->nodeValue,
                  "time"  => $time
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
    private function getClosingHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $closingCaption = str_replace("Bibliotheksschliessungen ", "", $caption->item(0)->nodeValue);
          $businessHours['caption'] = $closingCaption;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
			  $day = $allCells->item(0)->nodeValue;
              
			  //replace monthnames
			  $critmonth = array(" Oktober", " November", " Dezember", " Dez.", " Januar", " Februar", " M&auml;rz", " März", " April", " Mai", " Juni", " Juli", " August", "August", " September");
			  $replmonth = array('10.', '11.', '12.', '12.', '1.', '2.', '3.', '3.', '4.', '5.', '6.', '7.', '8.', '8.', '9.');
			  //For Debugging
			  //$day = utf8_encode($day);
			  //$day = preg_replace("/[^a-zA-Z0-9\s\D]/", "", $day);
			  $day = str_replace($critmonth, $replmonth, $day);
			  $day = str_replace("1. 20", "1.20", $day);
			  //$day = str_replace("27. 8", "27.8", $day);
			  $rowArray[] = array(
                  "day" => $day,
                  "description"  => $allCells->item(1)->nodeValue
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
    private function getGuidanceHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $businessHours['caption'] = $caption->item(0)->nodeValue;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
              $description = $allCells->item(0)->nodeValue;
              $day = $allCells->item(1)->nodeValue;
              $time = $allCells->item(2)->nodeValue;

              // delete strange characters
              $description = preg_replace("/[^a-zA-Z0-9\s]/", "", $description);
              $day = preg_replace("/[^a-zA-Z0-9\s]/", "", $day);
              $time = preg_replace("/[^a-zA-Z0-9\s]/", "", $time);

              // corrections
              $description = str_replace("ff", "&Ouml;ff", $description);
              $description = str_replace("inRechts", "in Rechts-", $description);
              $description = str_replace("inVWLBWL", "in VWL, BWL", $description);
              $day = str_replace("tagbis", "tag -", $day);
              $day = str_replace("ag Do", "ag, Do", $day);
              $time = str_replace("bis", "-", $time);
              $time = str_replace("93", "9:3", $time);
              $time = str_replace("73", "7:3", $time);
              $time = str_replace("40", "4:0", $time);
              $time = str_replace("60", "6:0", $time);
              $time = str_replace("20", "2:0", $time);
              $time = str_replace(" Uhr", "", $time);
              $time = str_replace("0-", "0 -", $time);

              $rowArray[] = array(
                  "description" => $description,
                  "day"  => $day,
                  "time"  => $time
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
}