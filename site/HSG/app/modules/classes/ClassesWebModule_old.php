<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteClassesModule
 *
 * @author flo
 */
class ClassesWebModule extends WebModule {

    protected $id = 'classes';
    protected $moduleName = 'Classes';
    protected $hasFeeds = false;

    protected function initializeForPage() {
        switch ($this->page) {
            case 'index':
                $categories = $this->getModuleArray('categories');
                $dates = $this->getModuleArray('dates');
                
                $this->assign('dates', $dates);
                $this->assign('categories', $categories);
                break;
            case 'current':
                // Using DOM/HTML-Pager
                $url = $this->getModuleVar('urlCourses');
                $htmlPage = new HTMLPager($url, "UTF-8", 1);

                $dom = new DOMDocument();
                $dom->loadHTMLFile(mb_convert_encoding($url, 'HTML-ENTITIES', "UTF-8"));
                $divs = $dom->getElementsByTagName("div");
                $zahl = '';

                // array full of entries
                $entries = array();
                // array with all info for one entry
                $entry = array();
                   
                // to delete 
                //$counter = '';
                //$counterf = 0;

                $time = false;
                $room = false;
                $title = false;
                $lecturer = false;
                $level = false;

                // set values for variables
                foreach ($divs as $div) {
                    //attribut-wert von class ermitteln
                    $category = $div->getAttribute("class");
                    switch ($category) {
                        case 'zeit':
                            $zeit = $div->nodeValue;
                            $time = true;
                            break;
                        case 'raum':
                            $raum = $div->nodeValue;
                            $room = true;
                            break;
                        case 'titel':
                            $titel = $div->nodeValue;
                            $title = true;
                            break;
                        case 'dozent':
                            $dozent = $div->nodeValue;
                            $lecturer = true;
                            break;
                        /* case 'stufe':
                          $stufe = $div->nodeValue;
                          $level = true;
                          break;
                          default:
                          break;
                         * 
                         */
                    }

                    // check if all flags are true, means all data per course is shown
                    if ($time and $room and $title and $lecturer) {

                        $entry = array(
                            'zeit' => $zeit,
                            'raum' => $raum,
                            'titel' => $titel,
                            'dozent' => $dozent,
                            'stufe' => $stufe
                        );
                        
                        // add entries into the result structure
                        $entries[] = array(
                            'title' => $entry['titel'],
                            'subtitle' => $entry['zeit'] . " / " . $entry['raum'] . " / " . $entry['dozent']
                        );

                        // inistial value setting and restart the loop
                        $time = false;
                        $room = false;
                        $title = false;
                        $lecturer = false;
                        $level = false;
                    }
                }

                $this->assign('all', $entries);

                break;
            case 'public':
                // Using DOM/HTML-Pager
                $searchedDate = date("Y") . "-" . date("m") . "-" .date("d");
                
                //get url
                $url = $this->getModuleVar('urlPublic') 
                    . $searchedDate . $this->getModuleVar('urlPublicStartDate')
                    . $searchedDate . $this->getModuleVar('urlPublicEndDate');
                
                $dom = new DOMDocument();
                $dom->load(mb_convert_encoding($url, 'HTML-ENTITIES', "UTF-8"));
                
                // get elements with name "Event"
                $events = $dom->getElementsByTagName("Event");
                
                
                if($events->length == 0){
                    $this->assign('error', 'Aktuell keine öffentlichen Veranstaltungen.');
                    break;
                }
                
                $entries = array();
                
                foreach($events as $event){
                    // get German description
                    $contents = $event->getElementsByTagName("GermanContent");
                    
                    // get all information
                    foreach($contents as $content){
                        $title = $content->getElementsByTagName("Subject")->item(0)->nodeValue;
                        $date = $content->getElementsByTagName("DateFrom")->item(0)->nodeValue;
                        $start = $content->getElementsByTagName("TimeFrom")->item(0)->nodeValue;
                        $end = $content->getElementsByTagName("TimeUntil")->item(0)->nodeValue;
                        $room = $content->getElementsByTagName("Room")->item(0)->nodeValue;
                        $speaker = $content->getElementsByTagName("Speaker")->item(0)->nodeValue;
                        
                        $entries[] = array(
                            'title' => $title,
                            'subtitle' => $start . "-" . $end . " / " . $room . " / " . $speaker
                        );
                    }
                }
                
                $this->assign('all', $entries);
                
                break;
        }
    }

}

?>
