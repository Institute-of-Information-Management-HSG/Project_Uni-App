<?php
/**
  * @package Module
  * @subpackage Info
  */

/**
  * @package Module
  * @subpackage Info
  */
class InfoWebModule extends WebModule {
  protected $id = 'info';
  protected $moduleName = 'Info';
     
  protected function initializeForPage() {
        switch ($this->page) {
            case 'index':
                $locationFAQ = DATA_DIR . "/info/faq.tpl";
                
                $fileAsString = file_get_contents($locationFAQ);
                
                $this->assign('faq', $fileAsString);
        }
  }
}
