<?php

class HTMLDataController extends DataController
{
    protected $DEFAULT_PARSER_CLASS='DOMDataParser';
    public function getItem($id)
    {
        return $this->getContentById($id);
    }

    public function getContentById($id)
    {
        $content = '';
        if ($dom = $this->getParsedData()) {
            if ($element = $dom->getElementById($id)) {
                $content = $dom->saveXML($element);
            }
            
        }
        
        //changed from content to element since it is easier to work on with dom nodes
        return $element;
    }
    
    public function getContentByIdAsString($id)
    {
        $content = '';
        if ($dom = $this->getParsedData()) {
            if ($element = $dom->getElementById($id)) {
                $content = $dom->saveXML($element);
            }
            
        }
        
        //changed from content to element since it is easier to work on with dom nodes
        return $element;
    }
    

    public function getContentByTag($tag) {
        $content = '';
        if ($dom = $this->getParsedData()) {
            $elements = $dom->getElementsByTagName($tag);
            for ($i=0; $i < $elements->length; $i++) {
                $element = $elements->item($i);
                $content .= $dom->saveXML($element);
            }
            if (strtolower($tag)=='body') {
                //strip body tag
                $content = preg_replace("#</?body.*?>#", "", $content);
            }
        }
        
        return $content;
    }

    public function getContent()
    {
        $content = '';
        if ($dom = $this->getParsedData()) {
            if ($element = $dom->getElementsByTagName('body')->item(0)) {
                $content = $dom->saveXML($element);
                $content = preg_replace("#</?body.*?>#", "", $content);
            } else {
                $content = $this->getData();
            }
        }
        
        return $content;
    }
    
    public function getAttribute($tag,$attribute){
        
    }
    
    public function getNode($tag,$id,$class){
        
    }
            
            
}
