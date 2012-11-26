<?php

/**
 * @author Florian Ickelsheimer (florian.ickelsheimer@gmail.com)
 */
include(SITE_DIR . '/lib/nusoap/nusoap.php');

class SOAPTimetableInterface {

    private $webservice_url; // url zum webservice 
    //private $auth_url; // Link zum SecToken erzeuger
    private $prod_environment = false; // false = dev und true = prod
    private static $APPLICATION_ID = 'id'; // App ID
    private $securityToken = "";
    private $client;

    // Konstruktor
    /**
     *
     * @param type $prod_environment true für produktiv umgebung, false für staging umgebung
     */
    public function __construct($prod_environment, $securityToken) {
        if ($prod_environment) {
            $this->webservice_url = "url";
            $this->prod_environment = true;
            $this->securityToken = $securityToken;
        } else {
            $this->webservice_url = 'url';
            //$this->auth_url = ''; //Link zum Security Token
            $this->prod_environment = false;
            $this->securityToken = $securityToken;
        }
    }

    private function openConnection() {
        $this->client = new nusoap_client($this->webservice_url, $this->securityToken);
        $this->client->soap_defencoding = 'UTF-8';
    }

    private function sortEntries($inputArray) {
        $sortedArray = array();

        foreach ($inputArray as $entry) {
            ////$date = new DateTime("2012-10-25T12:15:00"); to convert to timestamp

            $tmpTime = new DateTime($entry["StartTime"]);

            $tmpWeekday = date('l', $tmpTime->getTimestamp());

            $dateFormat = 'd.m.Y';
            $timeFormat = 'H:i';

            $lectureTime = date($timeFormat, $tmpTime->getTimestamp()) . " - " . date($timeFormat, $tmpTime->getTimestamp() + 60 * $entry["Time"]);

            // link zum raum
            $linkString = StaticMethods::getLinkForRoom($entry['Location']);
 
            switch ($tmpWeekday) {
                case 'Monday':
                    $sortedArray['Montag' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );
                    break;
                case 'Tuesday':
                    $sortedArray['Dienstag' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );
                    break;

                case 'Wednesday':
                    $sortedArray['Mittwoch' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );

                    break;

                case 'Thursday':
                    $sortedArray['Donnerstag' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );

                    break;
                case 'Friday':
                    $sortedArray['Freitag' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );

                    break;
                case 'Saturday':
                    $sortedArray['Samstag' . ', ' . date($dateFormat, $tmpTime->getTimestamp())][] = array(
                        "title" => $entry["Title"],
                        "subtitle" => $lectureTime . ' / ' . $entry["Lecturer"] . '<br />'. $linkString
                    );

                    break;
            }
        }
        return $sortedArray;
    }

    public function getSecurityToken() {
        return '';
    }

    public function setSecurityToken($username, $passwort) {
        
    }

    public function getICalFeedLink($language) {
        $this->openConnection();
        $action = 'http://tempuri.org/ICalendarService/GetICalFeedLink';
        $xml_request = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                            <s:Body>
                                <GetICalFeedLink xmlns="http://tempuri.org/">
                                     <calendarRequest xmlns:a="http://www.unisg.ch/Base/v20120101/" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                                        <a:DefaultRequestHeader>
                                            <a:Application>' . self::$APPLICATION_ID . '</a:Application>
                                            <a:RequestedLanguage>' . $language. '</a:RequestedLanguage>
                                            <a:SecurityToken>' . $this->securityToken . '</a:SecurityToken>
                                        </a:DefaultRequestHeader>
                                     </calendarRequest>
                                </GetICalFeedLink>
                            </s:Body>
                        </s:Envelope>';
         // is necessary, but not sure why it is needed
        if (empty($this->client->operation)) {
            $this->client->operation = "";
        }
        
        $answer = array();
        $answer = $this->client->send($xml_request, $action, '');
        
        if (array_key_exists('faultcode', $answer) || $this->client->getError()) {
            return "error";
        } else {
            $iCalFeedLink = $answer["GetICalFeedLinkResult"]["Item"];
        }
        return $iCalFeedLink;
    }

    /**
     *
     * @param type $from startdatum der abfrage im format 2012-01-31T23:59:59
     * @param type $until endatum der abfrage im format 2012-01-31T23:59:59
     * @param type $language 'de' für deutsch, 'en' für englisch
     * @return type array mit allen Werten
     */
    public function getCalendarEntries($from, $until, $language) {

        $this->openConnection();
        // until format: 2012-01-31T23:59:59
        // language de oder en
        $action = 'http://tempuri.org/ICalendarService/GetCalendarEntries';
        $xml_request = '<?xml version="1.0" encoding="utf-8" ?>
            <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                <s:Body>
                    <GetCalendarEntries xmlns="http://tempuri.org/">
                        <calendarRequest xmlns:a="http://www.unisg.ch/Events/v20120101/" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                            <DefaultRequestHeader xmlns="http://www.unisg.ch/Base/v20120101/">
                                <Application>' . self::$APPLICATION_ID . '</Application>
                                <RequestedLanguage>' . $language . '</RequestedLanguage>
                                <SecurityToken>' . $this->securityToken . '</SecurityToken>
                            </DefaultRequestHeader>
                            <a:From>' . $from . '</a:From>
                            <a:Until>' . $until . '</a:Until>
                        </calendarRequest>
                    </GetCalendarEntries>
                </s:Body>
            </s:Envelope>';

        // is necessary, but not sure why it is needed
        if (empty($this->client->operation)) {
            $this->client->operation = "";
        }

        $answer = array();
        $answer = $this->client->send($xml_request, $action, '');
        //print_f($this->client->request);


        $result_array = array();

        // Fehlerbehandlung
        if (array_key_exists('faultcode', $answer) || $this->client->getError()) {
            throw new KurogoException("SOAP-Anfrage nicht korrekt");
        } else {
            $allItems_array = $answer["GetCalendarEntriesResult"]["Items"]["CalendarEntry"];
            /*

              // really bad workaround, since the array asociation runs apparently through all CalendarEntry* indexes, the if-check tries to exclude stupid entries
              if (count($allItems_array) == 19) {
              $result_array[] = $this->getArrayFromSOAPRequest($allItems_array);
              }else{
              foreach ($allItems_array as $entry) {
              $result_array[] = $this->getArrayFromSOAPRequest($entry);
              }
              }
             */
            if (array_key_exists("Id", $allItems_array)) {
                if ($allItems_array["Id"] != "00000000-0000-0000-0000-000000000000" and
                        $allItems_array["AdditionalText"] == null and
                        $allItems_array["CalendarEntryClassification"] == null and
                        $allItems_array["Location"] != null
                ) {
                    $result_array[0] = array(
                        "Lecturer" => utf8_encode($allItems_array["Description"]),
                        "StartTime" => $allItems_array["StartTime"],
                        "Time" => $allItems_array["DurationMinutes"],
                        "Title" => utf8_encode($allItems_array["Summary"]),
                        "Location" => $allItems_array["Location"]
                    );
                }
            } else {
                foreach ($allItems_array as $entry) {
                    if ($entry["Id"] != "00000000-0000-0000-0000-000000000000" and
                            $entry["AdditionalText"] == null and
                            $entry["CalendarEntryClassification"] == null and
                            $entry["Location"] != null
                    ) {
                        $result_array[] = array(
                            "Lecturer" => utf8_encode($entry["Description"]),
                            "StartTime" => $entry["StartTime"],
                            "Time" => $entry["DurationMinutes"],
                            "Title" => utf8_encode($entry["Summary"]),
                            "Location" => $entry["Location"]
                        );
                    }
                }
            }

            $sorted_array = $this->sortEntries($result_array);
            return $sorted_array;
        }
    }

    private function getArrayFromSOAPRequest(array $entry) {
        $tmpArray = array();
        if ($entry["Id"] != "00000000-0000-0000-0000-000000000000" and
                strlen($entry["AdditionalText"]) == 0 and
                strlen($entry["CalendarEntryClassification"]) == 0 and
                strlen($entry["Location"] != 0)
        ) {
            $tmpArray = array(
                "Lecturer" => utf8_encode($entry["Description"]),
                "StartTime" => $entry["StartTime"],
                "Time" => $entry["DurationMinutes"],
                "Title" => utf8_encode($entry["Summary"]),
                "Location" => $entry["Location"]
            );
        }
        return $tmpArray;
    }

}

?>
