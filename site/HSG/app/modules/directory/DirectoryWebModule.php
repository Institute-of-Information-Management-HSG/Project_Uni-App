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
class DirectoryWebModule extends WebModule {

    protected $id = 'directory';
    protected $moduleName = 'Directory';

    protected function initializeForPage() {
        switch ($this->page) {
            case 'index':
                // get information from ini-file
                $categories = $this->getModuleArray('alphabetic');


                foreach ($categories as $categorie) {
                    $entries[] = array(
                        'title' => $categorie['title'],
                        'url' => $this->buildBreadcrumbURL('results', array(
                            'letter' => $categorie['title']
                        ))
                    );
                }

                // assign the array to the tpl-files
                $this->assign('categories', $entries);


                break;
            case 'results':
                $baseUrl = $this->getModuleVar('baseUrl');
                $letter = $this->getArg('letter');
                $suffixUrl = $this->getModuleVar('suffixUrl');
                $showMail = $this->getModuleVar('showMail');

                $letter = $this->getArg('letter');

                $this->setPageTitle($letter);
                $this->setBreadcrumbTitle($letter);


                $url = $baseUrl . $letter . $suffixUrl;

                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);


                $results = array();
                $names = array();

                $allNames = $dom->getElementsByTagName("a");


                foreach ($allNames as $name) {
                    if (strpos($name->nodeValue, "@")==false) {
					$names[] = array(
                        'title' => $name->nodeValue,
                        'url' => $this->buildBreadcrumbURL('detail', array(
                            'name' => $name->nodeValue,
                            'url' => $baseUrl . $name->getAttribute("href") . $showMail
                        ))
                    );
					}
                }


                $this->assign('results', $names);
                $this->assign('id', $letter);
                break;

            case 'alphabetic':

                $baseUrl = $this->getModuleVar('baseUrl');
                $suffixUrl = $this->getModuleVar('suffixUrl');
                $letter = $this->getArg('letter');
                $url = $baseUrl . $letter . $suffixUrl . $letter;
                $this->assign('url', $url);
                break;
            case 'detail':

                $name = $this->getArg('name');
                $url = $this->getArg('url');

                $preNumber = $this->getModuleVar('preNumber');

                // sometimes too long, therefore taking the default value 'Detail'
                //$this->setPageTitle($name);
                //url für dom nutzen
                if (function_exists('mb_convert_encoding')) {
                    $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
                }
                // create new DomDocument
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($url);

                $allRows = $dom->getElementsByTagName("tr");

                $name = $allRows->item(1)->nodeValue;
                $details = array(); // array to read the node-values
                $contacts = array(); // array for Options (Call, Mail)
                $hasContacts = false; // show Options or not
                $hasPhone = false; // avoids mixing of phone and fax numbers

                foreach ($allRows as $row) {
                    $allColumns = $row->getElementsByTagName("td");

                    $node = $allColumns->item(1)->nodeValue;

                    /* regex zum Auslesen der Adressszeile, um Strasse und PLZ zu trennen:
                     * /[0-9]{4}[[:blank:]][a-zA-Z]{2,}/
                     */
                    $regex = "/[0-9]{4}[[:blank:]][a-zA-Z]{2,}/";

                    // Telefon Regex, um Telefon (Direkt: externe Nummer) auszulesen
                    $telDirektPrenumberRegex = "/^[^0-9]*\s[0-9]{3}.*$/";
                    
                    // Telefon Regex, um Telefon (Direkt: interne Nummer) auszulesen
                    $telDirektRegex = "/^[^0-9]*\s[0-9]{4}$/";

                    // Telefon Regex, um Telefon (Direkt, Ressort) auszulesen
                    $telDirektRessortRegex = "/^[^0-9]*\s[0-9]{4}\s[^0-9]*\s[0-9]{4}$/";

                    // mail regex
                    $mailRegex = "/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";

                    //check if address
                    if (preg_match($regex, $node, $matches)) {
                        $position = strpos($node, $matches[0]);
                        $details[] = substr($node, 0, $position) . '<br>' . substr($node, $position);
                    }
                    //check if internal telephone number
                    else if (preg_match($telDirektRegex, $node)) {
                        $phonelink = str_replace("Direkt:", '' . $preNumber, $node); // replace direkt with prenumber
                        $phonelinkTel = "tel:" . preg_replace("/\s/", "", $phonelink); // create link variable
                        array_push($contacts, array('title' => "Anrufen", 'subtitle' => "Direkt: " . $phonelink, 'url' => $phonelinkTel, 'class' => 'phone')); //fill options array
                        $details[] = $node;
                        $hasPhone = true; // set phone value true
                    }
                    //check if external telephone number
                    else if (preg_match($telDirektPrenumberRegex, $node) && !$hasPhone) {
                        $phonelink = str_replace("Direkt:", '',$node); // delete direkt:
                        $phonelinkTel = "tel:" . preg_replace("/\s/", "", $phonelink); // create link variable
                        array_push($contacts, array('title' => "Anrufen", 'subtitle' => "Direkt: " . $phonelink, 'url' => $phonelinkTel, 'class' => 'phone')); //fill options array
                        $details[] = $node;
                        $hasPhone = true; // set phone value true
                    }
                    // check if two phone numbers are given
                    else if (preg_match($telDirektRessortRegex, $node)) {
                        // replace the direkt: then the ressort part
                        $phonelink = str_replace("Direkt:", '' . $preNumber, $node);
                        $phonelink = str_replace("Ressort/Institut:", '/' . $preNumber, $phonelink); // set delimiter
                        // delete all white spaces
                        //$phonelink = str_replace(" ", '', $phonelink);
                        $phonelinks[] = array(); // new array 
                        $phonelinks = explode('/', $phonelink); // separating the field values by delimiter
                        // add to options array
                        array_push($contacts, array('title' => "Anrufen", 'subtitle' => "Direkt: " . $phonelinks[0], 'url' => "tel:". $phonelinks[0], 'class' => 'phone'));
                        array_push($contacts, array('title' => "Anrufen", 'subtitle' => "Ressort/Institut: " . $phonelinks[1], 'url' => "tel:" . $phonelinks[1], 'class' => 'phone'));
                        $details[] = $node;
                        $hasPhone = true; // set phone value true
                    // check if mail address
                    } else if (preg_match($mailRegex, $node)) {
                        $maillink = "mailto:" . $node;
                        array_push($contacts, array('title' => "E-Mail", 'subtitle' => $node, 'url' => $maillink, 'class' => 'email'));
                        $details[] = $node;
                    }
                    // normal value
                    else {
                        $details[] = $node;
                    }
                }


                if (count($contacts) > 0) {
                    $hasContacts = true;
                }


                $this->assign('name', $name);
                $this->assign('function', $details[2]);
                $this->assign('institute', $details[3]);
                $this->assign('phone', $details[4]);
                $this->assign('fax', $details[5]);
                $this->assign('email', $details[6]);
                $this->assign('homepage', $details[7]);
                $this->assign('address', $details[8]);
                $this->assign('phonelink', $phonelink);
                $this->assign('maillink', $maillink);

                $this->assign('hasContacts', $hasContacts);
                $this->assign('contact', $contacts);
        }
    }

    //put your code here
}

?>
