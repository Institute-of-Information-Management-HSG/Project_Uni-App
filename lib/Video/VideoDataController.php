<?php

includePackage('DataController');
class VideoDataController extends ItemsDataController
{
    protected $cacheFolder='Video';
    protected $tag;
    protected $author;

    public static function getVideoDataControllers() {
        return array(
            'BrightcoveVideoController'=>'Brightcove',
            'VimeoVideoController'=>'Vimeo',
            'YouTubeVideoController'=>'YouTube'
        );
    }
    
    public function getTag() {
        return $this->tag;
    }

    public function getAuthor() {
        return $this->author;
    }
    
    protected function init($args) {
        parent::init($args);

        if (isset($args['TAG']) && strlen($args['TAG'])) {
            $this->tag = $args['TAG'];
        }
        
        if (isset($args['AUTHOR']) && strlen($args['AUTHOR'])) {
            $this->author = $args['AUTHOR'];
        }
    }
}
