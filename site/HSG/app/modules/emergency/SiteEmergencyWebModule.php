<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteEmergencyModule
 *
 * @author flo
 */
class SiteEmergencyWebModule extends EmergencyWebModule {

    protected $id = 'emergency';
    protected $moduleName = 'Emergency';
    
    protected function initializeForPage() {
        switch ($this->page) {
            case 'index':
              
                $contactNavListItems = array();
                if($this->contactsController !== NULL) {
                    foreach($this->contactsController->getPrimaryContacts() as $contact) {
                        $contactNavListItems[] = self::contactNavListItem($contact);
                    }

                    if($this->contactsController->hasSecondaryContacts()) {
                        $contactNavListItems[] = array(
                            'title' => $this->getModuleVar('MORE_CONTACTS'),
                            'url' => $this->buildBreadcrumbURL('contacts', array()),
                        );
                    }
                    $this->assign('contactNavListItems', $contactNavListItems);
                }
                $this->assign('hasContacts', (count($contactNavListItems) > 0));

              
              
                $categories = $this->getModuleArray('categories');
                $this->assign('categories', $categories);
                break;
                
        
          default:
                parent::initializeForPage();
        }
    }
    
    
    // this method overwrites the parent method in order to prevent NA-numbers
    protected static function contactNavListItem($contact) {
        return array(
            'title' => $contact->getTitle(),
            'subtitle' => $contact->getSubtitle() . ' (' . $contact->getPhone() . ')',
            'url' => 'tel:' . $contact->getPhone(),
            'class' => 'phone',
        );
    }

}

?>
