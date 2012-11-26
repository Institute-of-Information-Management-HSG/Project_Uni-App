<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteMapModule
 *
 * @author flo
 */
class CampusmapWebModule extends WebModule {

    protected $id = 'campusmap';
    protected $moduleName = 'Campus Map';
    protected $hasFeeds = false;

    protected function initializeForPage() {
        // initialize the data controller
        //$controller = GoogleMapDataController::factory();

        switch ($this->page) {

            case 'index':

                // Test der Google Api
               // $items = $controller->searchPlace('Universität St.Gallen, Holzstrasse 15, 9010 St. Gallen', 'false');
                // get arraylist from ini-file 
                $categories = $this->getModuleArray('categories');

                //assign variables
                //$this->assign('display_type', $this->getModuleVar('display_type'));
                //$this->assign('description',  $this->getModuleVar('description'));
                $this->assign('categories', $categories);

                //$this->assign('status', $items);
                break;
            case 'staticmap':
                //nothing to show here
                break;
            case 'buildings':
                $categorie = 'buildings';
                // get building information
                // should normally be done with an external interface
                $buildingnumbers = $this->getModuleArray($categorie);

                // search the array and building breadcrumbsURL
                foreach ($buildingnumbers as $number) {
                    $buildings[] = array(
                        'title' => $number['title'],
                        'url' => $this->buildBreadcrumbURL('buildingdetail', array(
                            'categoryid' => $categorie,
                            'buildingid' => $number['title']
                                )
                        )
                    );
                }

                $this->assign('buildingnumbers', $buildings);
                break;
            case 'buildingdetail':
                // create Google Map
                $map = $this->createGoogleMap();

                // get id from breadcrumb
                $buildingId = $this->getArg('buildingid');
                $categoryId = $this->getArg('categoryid');
                
                // set page title with the building id
                $this->setPageTitle($buildingId);
                // get specific information of all buildings in dependence of the categorie
                if ($categoryId == 'buildings') {    
                    $allBuildings[] = $this->getModuleArray('buildings');
                } else if ($categoryId == 'places') {
                    $allBuildings[] = $this->getModuleArray('places');
                }
                //var_dump($allBuildings);

                // set parameters of the map
                $map->setWidth('100%');
                $map->setHeight('250');
                $map->setZoomLevel(17);
                $map->setMapType('satellite');
                $map->disableStreetViewControls();
                $map->disableMapControls();


                //dieses sollte schon bald generisch erfolgen
                //$map->addMarkerByCoords('9.374641', '47.431665', 'Gebäudenummer: ' . $buildingid, 'Kursräume, Stilllernmöglichkeit, InfoB-Service');
                // boolean variable for error checking
                $isAvailable = false;

                // get building information from the in an assign different values to the template
                foreach ($allBuildings as $singleBuilding) {
                    foreach ($singleBuilding as $buildingInfo) {

                        if ($buildingId == $buildingInfo['title']) {
                            $isAvailable = true;
                            $map->addMarkerByCoords($buildingInfo['long'], $buildingInfo['lat'], $buildingInfo['name']);
                            $buildingName = $buildingId . " " . $buildingInfo['name'];
                            // read floors
                            $floorString = $buildingInfo['floors'];
                            $hasFloors = false;
                            // check if floors is empty
                            if (strlen($floorString) > 0) {
                                $hasFloors = true;
                                $floors[] = array();
                                //read single floors from string
                                $floors = explode(',', $floorString);
                                $hasFloors = true;
                            }

                            /*
                              $this->assign('title', $buildingInfo['title']);
                              $this->assign('long', $buildingInfo['long']);
                              $this->assign('lat', $buildingInfo['lat']);
                              $this->assign('name', $buildingInfo['name']);
                             * 
                             */
                        }
                        // just in case that an error occurs, stay on the side TODO breadcrumb
                        //if (!$isAvailable) {
                        //    $this->redirectTo('buildings');
                        //}
                    }
                }
                
                // look up if floors exist
                if ($hasFloors) {
                    foreach ($floors as $floor) {

                        //echo $floor;
                        $allFloors[] = array(
                            'title' => $floor,
                            'url' => $this->buildBreadcrumbURL('buildinginfo', array(
                                'buildingid' => $buildingId, 'floorid' => $floor
                                    )
                            )
                        );
                    }
                    $this->assign('floors', $allFloors);
                }
                
                $this->assign('buildingid', $buildingName);
                $this->assign('hasFloors', $hasFloors);
                $this->assign('map', $map);
                break;
            case 'buildinginfo':
                // get id from breadcrumb
                $floorId = $this->getArg('floorid');
                $buildingId = $this->getArg('buildingid');
                $this->setPageTitle($buildingId . '-' . $floorId);

                // set image directory
                $imageDirectory = "/modules/map/images/";

                // set file ending 
                $png = '.png';


                // file location
                $image = $imageDirectory . $buildingId . $floorId . $png;

                $this->assign('buildingid', $buildingId);
                $this->assign('floorid', $floorId);
                $this->assign('image', $image);


                break;
            case 'places':
                $categorie = 'places';
                // get building information
                // should normally be done with an external interface
                $places = $this->getModuleArray($categorie);

                // search the array and building breadcrumbsURL
                foreach ($places as $number) {
                    $buildings[] = array(
                        'title' => $number['title'],
                        'url' => $this->buildBreadcrumbURL('buildingdetail', array(
                            'categoryid' => $categorie,
                            'buildingid' => $number['title']
                                )
                        )
                    );
                }

                $this->assign('places', $buildings);
                break;
            case 'fullscreen':
                // create Google Map
                $map = $this->createGoogleMap();
                // set parameters
                $map->setWidth('100%');
                $map->setHeight('100%');
                $map->setZoomLevel(17);
                $map->setMapType('satellite');
                $map->disableStreetViewControls();
                $map->disableMapControls();

                // add markers TODO should be generic
                $map->addMarkerByCoords('9.374641', '47.431665', '(01) Hauptgebäude<br>', 'Kursräume, Stilllernmöglichkeit, InfoB-Service');

                // assign map to template
                $this->assign('map', $map);

                break;
            case 'search':

                break;
        }
    }

    private function createGoogleMap() {
        // include Google API
        include_once SITE_LIB_DIR . '/GoogleMap.php';
        include_once SITE_LIB_DIR . '/JSMin.php';

        // get and API key
        $mapsapikey = $this->getModuleVar('googleapikey');
        //var_dump($mapsapikey);
        $map = new GoogleMapAPI($mapsapikey);
        $map->_minify_js = isset($_REQUEST["min"]) ? FALSE : TRUE;

        return $map;
    }

}

?>
