<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteTransportModule
 *
 * @author flo
 */
class TransitWebModule extends WebModule {

    protected $id = 'transit';
    protected $moduleName = 'Transit';
    protected $contactsController;
    
    /*
    protected function initialize() {
        $config = $this->loadFeedData();
        
        //require_once LIB_DIR . "/Emergency/INIFileContactsListDataController.php";
        if(isset($config['contacts'])) {
         // $this->contactsController = DataController::factory($config['contacts']['CONTROLLER_CLASS'], $config['contacts']);
        }
        
    }
    */

    protected function initializeForPage() {
        
        	//Instantiate Controller
    	$controller = DataController::factory('ScheduleDataController');

        switch ($this->page) {
           
            case 'index':
                // Nächste Verbindungen anzeigen				
				$buslinien	= $this->getModuleArray('linien');
				$this->assign('linien', $buslinien);
				
				foreach ($buslinien as $id => $linien) {
					$to	= $this->getModuleArray($linien['title']);
					$this->assign('to', $to);
					
					foreach ($to as $id => $go) {
						$controller->getConnection($linien['from'], $go['to'], 4);
						$con[$linien['title']][$id]	= $controller->getFormatedData();
					}
				}
				$this->assign('entries', $con);
                
                // get information from ini-file
                $entries = $this->getModuleArray('categories');

                // assign the array to the tpl-files
                $this->assign('categories', $entries);
                break;
             case 'taxi':
                $entries = $this->getModuleArray('primary');
                /*
                $contactNavListItems = array();
                if($this->contactsController !== NULL) {
                    foreach($this->contactsController->getPrimaryContacts() as $contact) {
                        $contactNavListItems[] = self::contactNavListItem($contact);
                    }

                    $this->assign('contactNavListItems', $contactNavListItems);
                }
                $this->assign('hasContacts', (count($contactNavListItems) > 0));
                 * 
                 */
                $this->assign('categories', $entries);
                break;
            case 'fromto':
                $fromto = $this->getModuleArray('fromto');
                
                

                foreach ($fromto as $entryFT) {
                    $entries[] = array(
                        'title' => $entryFT['title'],
                        'subtitle' => $entryFT['subtitle'],
                        'url' => $this->buildBreadcrumbURL('form', array(
                            'fromto' => $entryFT['title'],
                            'university' => $entryFT['subtitle']
                        ))
                    );
                }
                
                
                $this->assign('fromto', $entries);
                break;
            case 'form':
                $fromtoModuleArray = $this->getModuleArray('fromto');
                $arrivalModuleArray = $this->getModuleArray('arrival');
                
                $this->setPageTitle('HSG');
                
                $fromto = $this->getArg('fromto');
                $university =  $this->getArg('university');
                
                $fromtoOptions = array();
                $arrival = array();
                $arrivalOptions = array();
                $counter = 0;

                foreach ($arrivalModuleArray as $entryA) {
                    $arrival[] = $entryA['arrival'];
                }
                
                $this->assign('fromto', $fromto);
                $this->assign('university', $university);
                $this->assign('currentDate', date('d.m.Y'));
                $this->assign('currentTime', date('H:i'));
                $this->assign('fromtoDefault', $fromto[0]);
                $this->assign('arrivalDefault', $arrival[0]);
                $this->assign('fromto', $fromto);
                $this->assign('arrival', $arrival);
                break;

            case 'busschedule':
                //$postdata="st.gallen,universitaet";
                // initialize curl-request with appropriate urls

                $url_bus5 = "http://fahrplan.search.ch/st.gallen,universitaet";
                $url_bus9 = "http://fahrplan.search.ch/st.gallen,gatterstr.uni";

                $data_bus5 = $this->getBusContentFromDom($url_bus5);
                $data_bus9 = $this->getBusContentFromDom($url_bus9);

                $this->assign('bus_5', $data_bus5);
                $this->assign('bus_9', $data_bus9);

                break;

            case 'transport':
                $university = "st.gallen,dufourstr.50";
                $baseUrl = "http://timetable.search.ch/";

                // all user entries
                $fromtoPost = $_POST['fromto'];
                $placePost = $_POST['place'];
                $datePost = $_POST['date'];
                $timePost = $_POST['time'];
                $arrivalPost = $_POST['arrival'];

                if (!(isset($placePost) and isset($datePost) and isset($timePost))
                        or strlen($placePost) == 0) {
                    $this->assign('error', 'Bitte einen Ort eingeben.');
                    $this->assign('transport', NULL);
                    break;
                } else if (!(preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $datePost, $hits)
                        || checkdate($hits[2], $hits[1], $hits[3]))) {
                    $this->assign('error', 'Datum muss im Format dd.mm.yyyy angegeben werden.');
                    $this->assign('transport', NULL);
                    break;
                }else if(!(preg_match('/^(\d{2})[\.:](\d{2})$/', $timePost, $hits))){
                    $this->assign('error', 'Zeit muss im Format hh.mm oder hh:mm angegeben werden.');
                    $this->assign('transport', NULL);
                    break;
                }

                /* Fehlerabfangen... später :)
                 * if (!isset($fruit)) {

                  header("Location: http://www.fictionalwebsite.com/error.html");
                  echo "Please choose a fruit<p>";
                  echo "Click on your browswer back button to return to form";
                  }

                 * 
                 */
                if ($fromtoPost == "Nach") {
                    $url = $baseUrl . $this->replaceSpecialChar($placePost) . "/" . $university . "?";
                } else if ($fromtoPost == "Von") {
                    $url = $baseUrl . $university . "/" . $this->replaceSpecialChar($placePost) . "?";
                } else {
                    //fatal error
                }

                $url = $url . "time=" . $timePost . "&date=" . $datePost;

                if ($arrivalPost[0] == "1") {
                    $url = $url . "&" . "mode=arrival";
                }

                // URl will be divided into single parameters
                //$url = "http://timetable.search.ch/st.gallen,dufourstr.50/geneve,pont-d-arve?time=19.00&date=14.03.2011&mode=arrival";
                // for detailed view, will be transformed
                $urlDetail = $url . "#openall";

                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                $dom->loadHTMLFile($url);
                
                // look for the page title including all searched info
                $titleTag = $dom->getElementsByTagName("title");
                $title = $titleTag->item(0)->nodeValue;
                
                // eliminate fahrplan.search hint
                $title = str_replace (" - search.ch" , "" , $title);
                
                // search all table tags
                $allTables = $dom->getElementsByTagName("table");

                // array with all data
                $fullScheduleData = array();
                $entries = array();
                $types = array();

                // boolean variables for array complete
                $isset_time = false;
                $isset_duration = false;
                $isset_transport = false;
                $isset_detail = false;


                // lookup all table tags
                foreach ($allTables as $table) {

                    $classMainTable = $table->getAttribute("class");
                    // find the oev_compact table
                    if ($classMainTable == "oev_compact") {
                        // lookup for all rows
                        $tableRows = $table->getElementsByTagName("tr");
                        // initial Variables to cumulate changes and walks
                        $walk = 0;
                        $change = 0;
                        
                        foreach ($tableRows as $tableRow) {
                            // lookup for all columns
                            $tableColumns = $tableRow->getElementsByTagName("td");


                            foreach ($tableColumns as $tableColumn) {
                                $class = $tableColumn->getAttribute("class");
                                
                                // class-attribute contains tripoverview
                                if (strlen(strstr($class, 'oev_tripoverview')) > 0) {
                                    // for a certain case there are 2 identical node values -> get first link
                                    //$node = $allColumns->item(1)->nodeValue;
                                    $anchors=$tableColumn->getElementsByTagName("a");
                                    $timeMainInfo = $anchors->item(0)->nodeValue;            
                                    $isset_time = true;
                                    
                                    
                                    
                                } else if (strlen(strstr($class, 'oev_duration')) > 0) {
                                    $durationMainInfo = $tableColumn->nodeValue;
                                    $fullScheduleData = array(
                                        'duration' => $durationMainInfo);
                                    $isset_duration = true;
                                } else {
                                    // get subtables of td tag
                                    $subTables = $tableColumn->getElementsByTagName("table");

                                    foreach ($subTables as $subTable) {
                                        $subClass = $subTable->getAttribute("class");


                                        // class-attribute contains types
                                        if (strlen(strstr($subClass, 'oev_types')) > 0) {
                                            $types = array();
                                            $subTableColumns = $subTable->getElementsByTagName("td");

                                            foreach ($subTableColumns as $subTableColumn) {
                                                $subDivs = $subTableColumn->getElementsByTagName("div");

                                                foreach ($subDivs as $div) {
                                                    $divClass = $div->getAttribute("class");

                                                    if ($divClass == "oev_traintype") {
                                                        $type = $div->nodeValue;

                                                        if (strlen($type) > 0) {
                                                            if (strlen(strstr($type, "'"))) {
                                                                $walk = $walk + intval(substr($type, 0, 1));
                                                                //$types[] = array_push($types, "zu Fuss (" . $type . ")");
                                                            }/* else if (strlen(strstr($type, "Bus"))) {

                                                              //$types[] = array_push($types, "Bus");
                                                              } else if (strlen(strstr($type, "Tram"))) {
                                                              $types[] = array_push($types, "Tram");
                                                              } else {
                                                              $types[] = array_push($types, $type);
                                                              } */ else {
                                                                $change++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            /*
                                              $fullScheduleData = array(
                                              'transport' => array('walk' => $walk, 'change' => ($change - 1))
                                              );
                                             * 
                                             */
                                            $isset_transport = true;


                                            // class-attribute contains types contains detail
                                        } else if (strlen(strstr($subClass, 'oev_detail')) > 0) {
                                            $fullScheduleData = array(
                                                'detail' => array()
                                            );
                                            $isset_detail = true;
                                        }
                                    }
                                }
                            }



                            if ($isset_detail && $isset_duration && $isset_time && $isset_transport) {
                                //$fullSchedule_result = $fullSchedule_result . '<br>' . $timeMainInfo . ' ' . $durationMainInfo;
                                $entries[] = array(
                                    'title' => $timeMainInfo . " (" . $durationMainInfo . ")",
                                    'subtitle' => "Umsteigen: " . $change . " / Fussweg: " . $walk . "'",
                                    'url' => $urlDetail
                                );

                                $isset_time = false;
                                $isset_duration = false;
                                $isset_transport = false;
                                $isset_detail = false;
                                $walk = 0;
                                $change = 0;
                            }
                        }



                        /*
                          // get all rows of the table
                          $tableRows = $compactTable->getElementsByTagName("tr");

                          foreach ($tableRows as $row) {

                          // get all columns of the row
                          $tableColumns = $row->getElementsByTagName("td");


                         * 
                         */
                    }


                    // look for the right table which is called oev_compact
                    //  }
                }
                
                $this->assign('title', $title);
                $this->assign('transport', $entries);
                break;
        }
    }

    private function getBusContentFromDom($url) {

        if (function_exists('mb_convert_encoding')) {
            $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
        }
        // create new DomDocument
        $dom = new DOMDocument();
        $dom->loadHTMLFile($url);

        $allTables = $dom->getElementsByTagName("table");
        $entries = array();

        foreach ($allTables as $table) {

            $classMainTable = $table->getAttribute("class");

            // find the ovt table
            if ($classMainTable == "ovt") {


                $allTableRows = $table->getElementsByTagName("tr");


                foreach ($allTableRows as $tableRow) {
                    $allTableColumns = $tableRow->getElementsByTagName("td");

                    $issetTransport = false;
                    $issetSchedule = false;
                    $issetDirection = false;


                    foreach ($allTableColumns as $tableColumn) {
                        $classColumn = $tableColumn->getAttribute("class");

                        switch ($classColumn) {
                            //$entry = array();
                            // transport
                            case 'tt1':
                                $transportValue = $tableColumn->nodeValue;

                                //getting Number
                                preg_match('/[0-9]/', $transportValue, $result);

                                $transport = str_replace($result[0], " " . $result[0], $transportValue);
                                $issetTransport = true;

                                break;

                            // schedule
                            case 'tt3':
                                //TODO
                                $schedule = $tableColumn->nodeValue;
                                $issetSchedule = true;
                                break;

                            //direction
                            case 'tt4':
                                $direction = $tableColumn->nodeValue;
                                $issetDirection = true;
                                break;
                        }
                    }

                    if ($issetTransport && $issetSchedule && $issetDirection) {
                        $entries[] = array(
                            'transport' => $transport,
                            'schedule' => $schedule,
                            'direction' => $direction
                        );
                    }
                }
            }
        }

        return $entries;
    }
    
    protected static function contactNavListItem($contact) {
        return array(
            'title' => $contact->getTitle(),
            'subtitle' => $contact->getSubtitle() . ' (' . $contact->getPhoneDelimitedByPeriods() . ')',
            'url' => 'tel:' . $contact->getPhoneDialable(),
            'class' => 'phone',
        );

    }
    
     private function replaceSpecialChar($place){
        
        $replace = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
            'è' => 'e', 'é' => 'e', 'â' => 'a' ,
            'à' => 'a' ,'ô' => 'o', 'ç' => 'c');
        $place = strtr( strtolower($place),$replace);        
        return $place;
        
    }
}

?>
