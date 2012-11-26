<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: flo
 */


class SiteAboutWebModule extends AboutWebModule{
    
     protected function initializeForPage() {

        switch ($this->page) {
            case 'faq':
                $locationFAQ = DATA_DIR . "/info/faq.tpl";
                
                $fileAsString = file_get_contents($locationFAQ);
                
                $fileAsString = str_replace("&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;", "<br>", $fileAsString);
                
                $this->assign('faq', $fileAsString);
                
                break;
            default:
                parent::initializeForPage();
        }
     }

}
?>
