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
class AlumniWebModule extends WebModule {

    protected $id = 'alumni';

    protected function getModuleDefaultData() {
        return array_merge(parent::getModuleDefaultData(), array(
            'url' => ''
                )
        );
    }

    protected function initializeForPage() {
        if ($url = $this->getModuleVar('url')) {
            header("Location: $url");
            die();
        } else {
            throw new Exception("URL not specified");
        }
    }

    //put your code here
}

?>
