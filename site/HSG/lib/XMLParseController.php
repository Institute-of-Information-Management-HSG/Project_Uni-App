<?php

/**
 * DataController für die Bib Suche
 * Anbindung an EDS API
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-07-04
 */

class XMLParseController extends DataController
{
	protected $cacheFolder	= "XMLParse";
	protected $DEFAULT_PARSER_CLASS	= 'XMLDataParser';
	//protected $cacheLifetime	= 1; // No Cache, da ansonsten Verzögerung bei Benutzungskonto
	
	public function getItem($id)
	{
		
	}
	
	/**
	* parse Data
	* @param String data
	* @return array 
	*/
	protected function parseData($data) {
		$xml	= simplexml_load_string($data);
		
		return $xml;
	}
	
	/**
	* Zugriff auf Tags mit spezifischen Attributen
	*
	* @param String obj auf welches XML Objekt zugegriffen werden soll
	* @param String tagName
	* @param String attrName
	* @param bool all Alle Ergebnisse zeigen (true) oder nur erstes (false)
	* @return array | false
	*/
	public function getTag($obj, $tagName, $attrName = '', $all = false)
	{
		$xml	= $this->$obj;
		if (!empty($attrName))
			$attrName	= "[@" . $attrName . "]";
				   
		if ($return	= $xml->xpath("//" . $tagName . $attrName)) {
			if (!$all)
				return (array) $return[0];
			else
				return (array) $return;
		}
		else
			return false;
	}
}