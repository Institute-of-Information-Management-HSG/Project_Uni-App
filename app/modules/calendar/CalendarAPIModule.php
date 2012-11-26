<?php

Kurogo::includePackage('Calendar');

class CalendarAPIModule extends APIModule
{
    const ERROR_NO_SUCH_EVENT = 50;

    protected $id = 'calendar';
    protected $vmin = 1;
    protected $vmax = 1;

    protected $timezone;
    protected $fieldConfig;

    // modified from CalendarWebModule
    protected function getFeedsByType() {  
        $groupTitles = array(
            'user' => 'Users',
            'resource' => 'Resources',
            'static' => 'Other Calendars',
            );

        $feeds = array();
        foreach (array('user','resource','static') as $type) {
            $typeFeedData = $this->getFeeds($type);
            $typeFeeds = array();
            foreach ($typeFeedData as $feed => $feedData) {
                $typeFeeds[] = array(
                    'id' => $feed,
                    'title' => $feedData['TITLE'],
                    'type' => $type,
                    );
            }
            if ($typeFeeds) {
                $feeds[] = array(
                    'id' => $type,
                    'title' => $groupTitles[$type],
                    'calendars' => $typeFeeds,
                    );
            }
        }
        return $feeds;
    }

    // from CalendarWebModule

    protected function getFeeds($type) {
        if (isset($this->feeds[$type])) {
            return $this->feeds[$type];
        }

        $feeds = array();
        switch ($type) {
            case 'static':
                $feeds = $this->loadFeedData();
                break;

            case 'user':
                $sectionData = $this->getOptionalModuleSection('user_calendars');
                $listController = isset($sectionData['CONTROLLER_CLASS']) ? $sectionData['CONTROLLER_CLASS'] : '';
                if (strlen($listController)) {
                    $sectionData = array_merge($sectionData, array('SESSION'=>$this->getSession()));
                    $controller = CalendarListController::factory($listController, $sectionData);
                    $feeds = $controller->getUserCalendars();
                }
                break;

            case 'resource':
                $sectionData = $this->getOptionalModuleSection('resources');
                $listController = isset($sectionData['CONTROLLER_CLASS']) ? $sectionData['CONTROLLER_CLASS'] : '';
                if (strlen($listController)) {
                    $sectionData = array_merge($sectionData, array('SESSION'=>$this->getSession()));
                    $controller = CalendarListController::factory($listController, $sectionData);
                    $feeds = $controller->getResources();
                }
                break;
            default:
                throw new KurogoConfigurationException($this->getLocalizedString('ERROR_INVALID_FEED', $type));
        }

        if ($feeds) {
            foreach ($feeds as $id => &$feed) {
                $feed['type'] = $type;
            }

            $this->feeds[$type] = $feeds;
        }

        return $feeds;
    }

    public function getDefaultFeed($type) {
        $feeds = $this->getFeeds($type);
        if ($indexes = array_keys($feeds)) {
            return current($indexes);
        }
    }

    private function getFeedData($index, $type) {
        $feeds = $this->getFeeds($type);
        if (isset($feeds[$index])) {
            return $feeds[$index];
        }
    }
    
    public function getFeed($index, $type) {
        $controller = null;
        $feeds = $this->getFeeds($type);
        if (isset($feeds[$index])) {
            $feedData = $feeds[$index];
            if (!isset($feedData['CONTROLLER_CLASS'])) {
                $feedData['CONTROLLER_CLASS'] = 'CalendarDataController';
            }
            $controller = CalendarDataController::factory($feedData['CONTROLLER_CLASS'],$feedData);
        } else {
            throw new KurogoDataException($this->getLocalizedString('ERROR_NO_CALENDAR_FEED', $index));
        }
        return $controller;
    }

    private function apiArrayFromEvent(ICalEvent $event) {
        foreach ($this->fieldConfig as $aField => $fieldInfo) {
            $fieldName = isset($fieldInfo['label']) ? $fieldInfo['label'] : $aField;
            $attribute = $event->get_attribute($aField);

            if ($attribute) {
                if (isset($fieldInfo['section'])) {
                    $section = $fieldInfo['section'];
                    if (!isset($result[$section])) {
                        $result[$section] = array();
                    }
                    $result[$section][$fieldName] = $attribute;

                } else {
                    $result[$fieldName] = $attribute;
                }
            }
        }

        return $result;
    }

    private function getStartArg($currentTime) {
        $startTime = $this->getArg('start', null);
        if ($startTime) {
            $start = new DateTime(date('Y-m-d H:i:s', $startTime), $this->timezone);
        } else {
            $start = new DateTime(date('Y-m-d H:i:s', $currentTime), $this->timezone);
            $start->setTime(0, 0, 0);
        }
        return $start;
    }

    private function getEndArg($startTime) {
        $endTime = $this->getArg('end', null);
        if ($endTime) {
            $end = new DateTime(date('Y-m-d H:i:s', $endTime), $this->timezone);
        } else {
            $end = new DateTime(date('Y-m-d H:i:s', $startTime), $this->timezone);
            $end->setTime(23, 59, 59);
        }
        return $end;
    }

    public function  initializeForCommand() {

        $this->timezone = Kurogo::siteTimezone();
        $this->fieldConfig = $this->getAPIConfigData('detail');

        switch ($this->command) {
            case 'index':
            case 'groups':

                $response = $this->getFeedsByType();

                $this->setResponse($response);
                $this->setResponseVersion(1);
                
                break;

            case 'events':
                $type     = $this->getArg('type', 'static');
                // the calendar argument needs to be urlencoded
                $calendar = $this->getArg('calendar', $this->getDefaultFeed($type));

                // default to the full day that includes current time
                $current = $this->getArg('time', time());
                $start   = $this->getStartArg($current);
                $end     = $this->getEndArg($start->getTimestamp());
                $feed    = $this->getFeed($calendar, $type);

                $feed->setStartDate($start);
                $feed->setEndDate($end);
                $iCalEvents = $feed->items();

                $events = array();
                $count  = 0;

                foreach ($iCalEvents as $iCalEvent) {
                    $events[] = $this->apiArrayFromEvent($iCalEvent);
                    $count++;
                }

                $response = array(
                    'total'        => $count,
                    'returned'     => $count,
                    'displayField' => 'title',
                    'results'      => $events,
                    );

                $this->setResponse($response);
                $this->setResponseVersion(1);

                break;

            case 'detail':
                $eventID = $this->getArg('id', null);
                if (!$eventID) {
                    $error = new KurogoError(
                            5,
                            'Invalid Request',
                            'Invalid id parameter supplied');
                    $this->throwError($error);
                }

                // default to the full day that includes current time
                $current  = $this->getArg('time', time());
                $start    = $this->getStartArg($current);
                $end      = $this->getEndArg($start->getTimestamp());
                $type     = $this->getArg('type', 'static');
                $calendar = $this->getArg('calendar', $this->getDefaultFeed($type));

                $feed = $this->getFeed($calendar, $type);
                $feed->setStartDate($start);
                $feed->setEndDate($end);

                if ($filter = $this->getArg('q')) {
                    $feed->addFilter('search', $filter);
                }

                if ($catid = $this->getArg('catid')) {
                    $feed->addFilter('category', $catid);
                }

                if ($event = $feed->getEvent($this->getArg('id'))) {
                    $eventArray = $this->apiArrayFromEvent($event);
                    $this->setResponse($eventArray);
                    $this->setResponseVersion(1);

                } else {
                    $error = new KurogoError(
                            self::ERROR_NO_SUCH_EVENT,
                            'Invalid Request',
                            "The event $eventID cannot be found");
                    $this->throwError($error);
                }

                break;

            case 'search':
                $filter = $this->getArg('q', null);
                if ($filter) {
                    $searchTerms = trim($filter);

                    $current  = $this->getArg('time', time());
                    $start    = $this->getStartArg($current);
                    $end      = $this->getEndArg($start->getTimestamp());
                    $type     = $this->getArg('type', 'static');
                    $calendar = $this->getArg('calendar', $this->getDefaultFeed($type));
			
                    $feed     = $this->getFeed($calendar, $type);

                    $feed->setStartDate($start);
                    $feed->setEndDate($end);
                    $feed->addFilter('search', $searchTerms);
                    $iCalEvents = $feed->items();
					
					$events = array();
                    $count = 0;
                    foreach ($iCalEvents as $iCalEvent) {
                        $events[] = $this->apiArrayFromEvent($iCalEvent);
                        $count++;
                    }

                    $titleField = 'summary';
                    if (isset($this->fieldConfig['summary'], $this->fieldConfig['summary']['label'])) {
                        $titleField = $this->fieldConfig['summary']['label'];
                    }

                    $response = array(
                        'total' => $count,
                        'returned' => $count,
                        'displayField' => $titleField,
                        'results' => $events,
                        );

                    $this->setResponse($response);
                    $this->setResponseVersion(1);

                } else {
                    $error = new KurogoError(
                            5,
                            'Invalid Request',
                            'Invalid search parameter');
                    $this->throwError($error);
                }
                break;

            case 'calendars':

                $group = $this->getArg('group');
                $response = array();
                foreach ($this->getFeeds($group) as $feedID => $feedData) {
                    $response[] = array(
                        'id' => $feedID,
                        'title' => $feedData['TITLE'],
                        );
                }

                $this->setResponse($response);
                $this->setResponseVersion(1);

                break;

            case 'resources':
                //break;

            case 'user':
                //break;

            case 'categories':
                //break;

            case 'category':
                //break;

            default:
                $this->invalidCommand();
                break;
        }
    }

}


