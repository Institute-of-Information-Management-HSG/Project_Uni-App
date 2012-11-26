<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteAlumniModule
 *
 * @author flo
 */
class MailWebModule extends WebModule {

    protected $id = 'mail';


    protected function initializeForPage() {


        switch ($this->page) {
            case 'index':
                $links = $this->getModuleArray('links');
                $this->assign('links', $links);

                break;

            default:
                if ($url = $this->getModuleVar('url')) {
                    header("Location: $url");
                    die();
                } else {
                    throw new Exception("URL not specified");
                }
        }
    }

    //put your code here
}

?>
