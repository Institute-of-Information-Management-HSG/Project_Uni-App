<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteMensaModule
 *
 * @author flo
 */
class MensaWebModule extends WebModule{

    protected $id = 'mensa';
    protected $moduleName = 'Mensa';
    protected $controller;



    protected function initializeForPage() {
        
        $controller = DataController::factory('HTMLDataController');

        switch ($this->page) {
            case 'index':
                
                // get Url
                $pdfDirectory[] = $this->getModuleVar('directory');
                $mensa = $this->getModuleVar('mensa');
                
                $controller->setBaseURL($mensa);
                
                //Infos auslesen
                $openinghoursSection = $controller->getItem('ct_sub_main');
                
                $openinghours = $openinghoursSection->getElementsByTagName('ul');
                
                $semesterArray = $openinghours->item(0)->getElementsByTagName('li');
                
                foreach($semesterArray as $info){
                    $value = $info->nodeValue;
                    $value = str_replace('Mo', '<br/>Mo', $value);
                    $semester[] = $value;
                }
                
                $nonSemesterArray = $openinghours->item(1)->getElementsByTagName('li');
                
                foreach($nonSemesterArray as $info){
                    $value = $info->nodeValue;
                    $value = str_replace('Mo', '<br/>Mo', $value);
                    $nonsemester[] = $value;
                }
                
                $this->assign('semester',$semester);
                
                $this->assign('nonsemester', $nonsemester);
                
                //Wochenplan auslesen
                $menu = $controller->getItem('hsginMedia_ov');
                
                $date = $menu->getElementsByTagName('dt');
                
                $week = $date->item(1)->nodeValue;
                $week = str_replace("Menüplan: ", '', $week);
                
                $links = $menu->getElementsByTagName('a');
                $stringLink = $links->item(0)->getAttribute('href');
                $link[] = array(
                    'title' => $week,
                    'url' => 'http://www.unisg.ch' . $stringLink
                );
                
                $this->assign('menu', $link);
                
                //var_dump($link);
                
                
                // get current week
                $week= date('W');
                $week = (int) $week; 
                $year = date('Y');
                $entries = array();
                $currWeek = $week;
                
                // find all current menus, normally 4
                for($i=0; $i<4;$i++) {
                    // funny workaround to solve the new year problem
                    if(($currWeek+$i)>52){
                        $week = $currWeek+$i-52;
                        $year = date('Y') + 1;
                    }
                    
                    
                    
                    if($week > 9){
                        $entries[] = array(
                                'title' => "Kalenderwoche ".$week,
                                'url' => $pdfDirectory[0]."Menuplan_".$week."_".$year.".ashx?fl=de"
                                
                            );
                    } else{
                        $entries[] = array(
                                'title' => "Kalenderwoche ".$week,
                                'url' => $pdfDirectory[0]."Menuplan_0".$week."_".$year.".ashx?fl=de"
                            );
                    }
                    
                    $week++;
                }
                
                $this->assign('weeks', $entries);
                
                
                
                 // assign the array to the tpl-files
                

                break;
        }
    }

}

?>
