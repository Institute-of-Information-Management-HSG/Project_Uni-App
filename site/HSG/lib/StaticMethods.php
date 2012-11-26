<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class StaticMethods {

    public static function getLinkForRoom($roomnumber) {
        $roomString = $roomnumber;
        $buildingString = substr($roomString, 0, 2);
        
        if(strpos($roomnumber, 'U') !== false){
            $floorString = substr($roomString, 3, 2);
        }else{
            $floorString = substr($roomString, 3, 1);
        }
        
        $parameter = array("title" => $buildingString, "floorid" => $floorString);
        $roomLink = WebModule::buildURLForModule("map", "floor", $parameter);
        
        // zum exkludieren von externen Räumen
        $pattern = '/^[0-9]{2}-[U]{0,1}[0-9]{3,4}$/';
        if (preg_match($pattern, $roomString)) {
            $linkString = "<a href=" . $roomLink . ">" . $roomString . " (Raumplan)</a>";
        } else {
            $linkString = $roomString;
        }

        return $linkString;
    }

}

?>
