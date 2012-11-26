<?php

/**
 * Description of TimetableWebModule
 *
 * @author fabio
 */
class TimetableWebModule extends WebModule {

    protected $id = 'timetable';
    protected $moduleName = 'Timetable';
    private $salt = 'salt';
    private $secretKey = 'secretKey';
    //url Parameters

    private $stagServer = 'url';
    private $prodServer = 'url';
    private $stagTokenizer = 'url';
    private $prodTokenizer = 'url';
    private $isProd = true;
    private $prodUrl = "http://app.unisg.ch/home";
    private $stagUrl = "http://testsystem.app.unisg.ch/home";
    //session parameter
    private $securityToken = '';
    private $timestamp = '';
    private $language = '';
    private $hash = '';
    private $result = '';
    private $tmpDate;
    private $isAuth = 0;
    private $isErrorDate;

    protected function initialize() {
        // beim Start des Moduls immer den aktuellen Tag setzen
        $this->tmpDate = time();
        $this->isErrorDate = false;
        parent::initialize();
    }

    private function convertLanguage($lang) {
        if ($lang == 'ger') {
            return 'de';
        } else {
            return 'en';
        }
    }

    private function loginProc() {
        if (isset($_SESSION['user']['key'])) {
            if (crypt($_SERVER['HTTP_USER_AGENT'], $this->salt) === $_SESSION['user']['key']) {
                $this->result = 'Congratulation: Logged in through Kurogo-Session';
                $this->isAuth = $_SESSION['user']['isAuth'];
                $this->language = $_SESSION['user']['language'];
                $this->securityToken = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), base64_decode($_SESSION['user']['securityToken']), MCRYPT_MODE_CBC, md5(md5($this->salt))), "\0");
            }
        } else {
            $this->securityToken = $_GET['param1'];
            $this->timestamp = $_GET['param2'];
            $this->language = $_GET['param3'];
            $this->hash = $_GET['param4'];

            //check integrety of the hash
            $checkstring = $this->securityToken . $this->timestamp . $this->language . $this->secretKey;
            $ownhash = md5($checkstring);
            if ($this->securityToken and $this->timestamp and $this->language and ($ownhash === $this->hash)) {
                $_SESSION['user'] = array();
                $_SESSION['user']['key'] = crypt($_SERVER['HTTP_USER_AGENT'], $this->salt);
                $_SESSION['user']['language'] = $this->language;
                $_SESSION['user']['validity'] = $this->timestamp;
                $_SESSION['user']['isAuth'] = "1";
                $_SESSION['user']['securityToken'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), $this->securityToken, MCRYPT_MODE_CBC, md5(md5($this->salt))));
                $this->isAuth = "1";
                $this->result = 'DEBUG: ' . $this->securityToken . ' - ' . $this->timestamp . ' - ' . $this->language . ' - ' . $this->hash . ' - ' . $_SESSION['user']['key'];
                //$this->redirectTo('index');
            } else {
                // back to login-site
                session_destroy();
                if ($this->isProd) {
                    header('Location: ' . $this->prodTokenizer);
                } else {
                    header('Location: ' . $this->stagTokenizer);
                }
            }
        }
    }

    /**
     *
     * @param type $currentDate as YYYY-MM-DD
     * @return type 
     */
    private function getCurrentDayDates($currentDate) {
        $dates = array();
        $dates[0] = $currentDate . "T00:00:00";
        $dates[1] = $currentDate . "T23:59:59";
        return $dates;
    }

    private function getTestDayDates($testDate) {
        $dates = array();
        $dates[0] = $testDate . "T00:00:00";
        $dates[1] = $testDate . "T23:59:59";
        return $dates;
    }

    /**
     * Function returning the from and the until date of a given week
     *
     * @param $currentWeek the desired week, should normally be the current week
     * @return type array with two dates of a week, start date (monday) in array[0] and end date (sunday 0:00) in array[1]
     */
    private function getCurrentWeekDates($currentWeek) {

        $dates = array();
        $dates[0] = substr(date('c', strtotime(date('Y', $this->tmpDate) . "-W" . $currentWeek . "-1")), 0, 19);
        $dates[1] = substr(date('c', strtotime(date('Y', $this->tmpDate) . "-W" . $currentWeek . "-7")), 0, 19);

        return $dates;
    }

    // only for own tests since i do not have entries in my current timetable
    private function getTestWeekDates($testWeek) {
        $dates = array();
        $dates[0] = substr(date('c', strtotime("2011-W" . $testWeek . "-1")), 0, 19);
        $dates[1] = substr(date('c', strtotime("2011-W" . $testWeek . "-7")), 0, 19);

        return $dates;
    }

    private function getGermanDays($weekday) {
        $day[0] = "Sonntag";
        $day[1] = "Montag";
        $day[2] = "Dienstag";
        $day[3] = "Mittwoch";
        $day[4] = "Donnerstag";
        $day[5] = "Freitag";
        $day[6] = "Samstag";

        return $day[$weekday];
    }

    private function getICalFeedLink() {
        
    }

    protected function initializeForPage() {


        switch ($this->page) {
            case 'index':
                $this->isErrorDate = $this->getArg('errorDate');
                $this->loginProc();

                $personals = $this->getModuleArray('personals');

                $soap_interface = new SOAPTimetableInterface($this->isProd, $this->securityToken);
                $iCalFeedLink = $soap_interface->getICalFeedLink();

                $iCalFeedLink = str_replace("http", "webcal", $iCalFeedLink);

                $ical = array(array(
                        'title' => 'Export in Kalender',
                        'url' => $iCalFeedLink
                        ));
                
                // initial input for the search form
                $this->assign('currentDate', date("d.m.Y"));
                $this->assign('isErrorDate', $this->isErrorDate);
                $this->assign('ical', $ical);
                $this->assign('personals', $personals);
                $this->assign('isAuth', $this->isAuth);
                break;

                break;
            case 'today':
                // in case this is from the search form
                $timePost = $_POST['time'];
                // try to convert the given date
                // getting the current time and creating next and prev Urls for Links
                $timeFromUrl = $this->getArg('time');
                if ($timeFromUrl != null) {
                    if (isset($timePost)) {
                        try {
                            // actually, i read that this version is not always secure
                            $timePost = strtotime($timePost);
                            if($timePost != 0){
                                $this->tmpDate = intval($timePost);
                            }else{
                                $this->isErrorDate = true;
                                $this->redirectTo('index?errorDate=true');
                            }
                        } catch (Exception $e) {
                            $this->redirectTo('error');
                        }
                    }else{
                        $this->tmpDate = intval($timeFromUrl);
                    }
                } 
                
                $nextUrl = $this->buildURL('today', array(
                    'time' => $this->tmpDate + (24 * 60 * 60)
                        ));
                $prevUrl = $this->buildURL('today', array(
                    'time' => $this->tmpDate - (24 * 60 * 60)
                        ));

                //doing the login procedure
                $result = $this->loginProc();

                $date_array = $this->getCurrentDayDates(date('Y-m-d', $this->tmpDate));
                //$date_array = $this->getTestDayDates("2011-02-22");
                try {
                    $soap_interface = new SOAPTimetableInterface($this->isProd, $this->securityToken);
                    $result_array = $soap_interface->getCalendarEntries($date_array[0], $date_array[1], $this->convertLanguage($this->language));
                } catch (Exception $e) {
                    Kurogo::log(LOG_CRIT, $e->getTrace(), 'SOAP_timetable');
                    $this->redirectTo('error');
                }
                //in case there no events on that day
                // adding current day and date
                $currentDay = $this->getGermanDays(date('w', $this->tmpDate)) . ", " . date("d.m.Y", $this->tmpDate);
                //in case there no events on that day
                if (count($result_array) == 0) {
                    $result_array = array(
                        $currentDay => array()
                    );
                }

                // offering two links, one back to the current day, another to jump to week view
                $additionalLinks = array();
                $additionalLinks[] = array(
                    'title' => 'Zurück zu heutigen Veranstaltungen',
                    'url' => $this->buildURL('today')
                );
                $additionalLinks[] = array(
                    'title' => 'Zur Wochenansicht',
                    'url' => $this->buildURL('week', array('time' => $this->tmpDate))
                );
                
                $this->assign('additionalLinks', $additionalLinks);
                $this->assign('currentDay', $currentDay);
                $this->assign('results', $result_array);
                $this->assign('nextUrl', $nextUrl);
                $this->assign('prevUrl', $prevUrl);
                $this->assign('isAuth', $this->isAuth);
                break;
            case 'week':
                // getting the current time and creating next and prev Urls for Links
                $timeFromUrl = $this->getArg('time');
                if (strlen($timeFromUrl) != 0) {
                    $this->tmpDate = intval($timeFromUrl);
                }
                $nextUrl = $this->buildURL('week', array(
                    'time' => $this->tmpDate + (7 * 24 * 60 * 60)
                        ));
                $prevUrl = $this->buildURL('week', array(
                    'time' => $this->tmpDate - (7 * 24 * 60 * 60)
                        ));


                // doing the Login procedure
                $result = $this->loginProc();

                // create appropriate timestamp format YYYY-MM-DDTHH:mm:ss with
                // substr(date('c'), 0, 19)                
                $date_array = $this->getCurrentWeekDates(date('W', $this->tmpDate));
                //$date_array = $this->getTestWeekDates(date('W', $this->tmpDate));
                try {
                    $soap_interface = new SOAPTimetableInterface($this->isProd, $this->securityToken);
                    $result_array = $soap_interface->getCalendarEntries($date_array[0], $date_array[1], $this->convertLanguage($this->language));
                } catch (Exception $e) {
                    Kurogo::log(LOG_CRIT, $e->getTrace(), 'SOAP_timetable');
                    $this->redirectTo('error');
                }


                // TODO muss noch angepasst werden
                $currentWeekNumber = date('W', $this->tmpDate);
                $currentYearNumber = date("Y", $this->tmpDate);
                $addedWeekYearNumber = $currentYearNumber . "-W" . $currentWeekNumber;
                $currentWeek = date("d.m.Y", strtotime($addedWeekYearNumber . "-1")) . " - " . date("d.m.Y", strtotime($addedWeekYearNumber . "-7"));
                //in case there no events on that day
                if (count($result_array) == 0) {
                    $result_array = array(
                        $currentWeek => array()
                    );
                }

                // offering two links, one back to the current day, another to jump to week view
                $additionalLinks = array();
                $additionalLinks[] = array(
                    'title' => 'Zurück zu aktuelle Woche',
                    'url' => $this->buildURL('week')
                );

                $this->assign('additionalLinks', $additionalLinks);
                $this->assign('results', $result_array);
                $this->assign('nextUrl', $nextUrl);
                $this->assign('prevUrl', $prevUrl);
                $this->assign('isAuth', $this->isAuth);
                break;
            case 'error':
                // User sicherheitsbedingt ausloggen
                $_SESSION['user']['key'] = '';
                $_SESSION['user']['language'] = '';
                $_SESSION['user']['validity'] = '';
                $_SESSION['user']['securityToken'] = '';
                $_SESSION['user']['isAuth'] = '';
                unset($_SESSION['user']);
                
                $this->securityToken = '';
                $this->timestamp = '';
                $this->hash = '';
                
                // array mit navigationselementen kreiieren
                $navlist = array();

                // Login
                $navlist[] = array(
                    'title' => 'Login',
                    'url' => 'index'
                );

                // Feedback
                $navlist[] = array(
                    'title' => 'Fehler melden',
                    'url' => 'mailto:' . Kurogo::getSiteString('FEEDBACK_EMAIL'),
                    'class' => 'email'
                );

                // Feedback
                $navlist[] = array(
                    'title' => 'Zurück zur Startseite',
                    'url' => $this->prodUrl
                );

                $this->assign('navlist', $navlist);
                break;
            case 'logout':
                $_SESSION['user']['key'] = '';
                $_SESSION['user']['language'] = '';
                $_SESSION['user']['validity'] = '';
                $_SESSION['user']['securityToken'] = '';
                $_SESSION['user']['isAuth'] = '';
                unset($_SESSION['user']);
                $this->redirectTo('back');
                break;
            case 'back':
                if ($this->isProd) {
                    header('Location: ' . $this->prodUrl);
                } else {
                    header('Location: ' . $this->stagUrl);
                }

                break;
        }
    }

}