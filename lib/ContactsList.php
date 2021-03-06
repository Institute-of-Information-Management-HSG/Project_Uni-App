<?php

class ContactsListItem {

    protected $title;
    protected $subtitle;
    protected $phone;

    public function __construct($title, $subtitle, $phone) {
        $this->title = $title;
        $this->subtitle = !empty($subtitle) ? $subtitle : NULL;
        $this->phone = $phone;
    }

    public function getTitle() {
        return $this->title;
    } 

    public function getSubtitle() {
        return $this->subtitle;
    } 

    public function getPhone() {
        return $this->phone;
    } 

    /*
     * For now we only handle North American numbers
     * for the following two methods
     */
    public function getPhoneDelimitedByPeriods() {
        $phone = $this->phone;
        if(strlen($phone) == 10) {  // 10 digits in a north american number
            return substr($phone, 0, 3) . '.' . substr($phone, 3, 3) . '.' . substr($phone, 6, 4);
        } else {
            return $phone;
        }
    }

    public function getPhoneDialable() {
        if(strlen($this->phone) == 10) {  // 10 digits in a north american number
            //return $this->phone;
            return '1' . $this->phone;
        } else {
            return $this->phone;
        }
    }
}