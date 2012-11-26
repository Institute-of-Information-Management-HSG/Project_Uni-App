<?php

/**
 * DataController für Bib
 * Anbindung an ALEPH XML Schnittstelle
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-05-29
 * @todo Konsistenz des Objekts! Immer auf gleiche Instanz beziehen -> docNr, userID, etc. nur einmal übergeben -> eine Instanz davon ableiten, mit dieser arbeiten
 */

// Basis URL -> switchen Test/Live
define('BASE_URL', 'ALEPH');

class BibDataController extends XMLParseController
{
	protected $cacheFolder	= "Bib";
	protected $DEFAULT_PARSER_CLASS	= 'JSONDataParser';
	protected $cacheLifetime	= 1; // No Cache, da ansonsten Verzögerung bei Benutzungskonto
	
	private $xml; // Object
	private $metaXML; // Object
	private $loginXML; // Object
	private $docNr; // Int
	private $data	= array(); // Array für overload
	private $userID; // String
	
	/**
	* Hauptbuchinfos holen
	* @param String q Suchstring
	* @void
	*/
	public function search($q)
	{
		$this->docNr	= $q;
		
		$this->setBaseUrl(BASE_URL . 'x' . $q . '/items');
		$this->addFilter('view', 'full');
		$this->addFilter('lang', 'fre');
		
		$data = $this->getData();
		$this->xml	= $this->parseData($data);

	}
	
	/**
	* Zugriff auf Meta Objekt
	* @return Object
	*/
	public function getXML()
	{
		return $this->xml;
	}
	
	/**
	* Zugriff auf Status Process Code
	*/
	public function getProcessStatusCode($docNr)
	{
		$this->search($docNr);
		return $this->xml->items->item[0]->{'z30-item-process-status-code'};
	}
	
	/**
	* Inhalt eines Tags erhalten
	* Metainformationen des Buchs (Stati etc), innerhalb z30
	* @param String tag XML Tag, dessen Inhalt gesucht ist
	* @return String Resultat
	*/
	public function getItem($tag) 
	{
		return $this->xml->items->item[0]->z30->{'z30-' . $tag}[0];
	}
	
	/**
	* Liefert die Exemplare gruppiert nach Bänden zurück
	* @return array
	* @todo Exception abfangen bei false
	*/
	public function getMultiples() 
	{
		$return	= array();
		$array	= (array) $this->xml->items;
		foreach ($array['item'] as $id => $item) {
			$desc	= (string) $item->z30->{'z30-description'};
			$enum	= (string) $item->z30->{'z30-enumeration-a'};
			$chron	= (string) $item->z30->{'z30-chronological-i'};
			$test	= preg_replace("/\s/", "", $desc);
			
			if (empty($test)) {
				if (!empty($enum))
					$desc	= $enum;		   
				else if (empty($test) & !empty($chron))
					$desc	= $chron;
				else
					$desc	= 'ohne Beschreibung';
			}
			
			if (!array_key_exists($desc, $return))
				$return[$desc]	= array();
			
			$return[$desc][]	= $this->putAllTogether($item);
		}
		return $return;
	}
	
	/**
	* Check, ob mehrere Bände. Gruppieren nach Bänden
	* @param int docNr
	* @return enum
	*/
	public function isMultiple() 
	{
		$this->setBaseUrl(BASE_URL . 'x' . $this->docNr . '/filters');
		$this->addFilter('view', 'full');
		$this->addFilter('lang', 'ger');
		
		$data = $this->getData();
		$vXML	= $this->parseData($data);
		
		$years	= (array) $vXML->{'record-filters'}->years;
		$volumes	= (array) $vXML->{'record-filters'}->volumes;
		
		$array	= (array) $this->xml->items->item[1];
		$string	= (string) $this->xml->items->item[0]->z30->{'z30-collection'};
		
		// Online
		if (count($array) < 1 & $string == "Online")
			return 'online';
			
		// Zeitschrift
		if (!empty($years) & !empty($volumes))
			return 'zeitschrift';
			
		// mehrbändig
		if (!empty($volumes) & empty($years))
			return 'band';
			
		return false;
	}
	
	/**
	* Inhalt eines Tags erhalten
	* Hauptinfos, innerhalb z13
	* @param String tag XML Tag, dessen Inhalt gesucht ist
	* @return String Resultat
	*/
	public function getInfo($tag) 
	{
		return $this->xml->items->item[0]->z13->{'z13-' . $tag}[0];
	}
	
	/**
	* Alle Exemplare holen
	* @return array
	*/
	public function getAllItems()
	{
		$items	= $this->xml->items->item;
		$allItems	= array();
		
		foreach ($items as $item => $content) {
			if ($this->putAllTogether($content) != false) 
				$allItems[]	= $this->putAllTogether($content);
			else
				continue;
		}
		return $allItems;
	}
	
	/**
	* Array zusammensetzen
	* verwendet von getAllItems und getMultiples
	* setzt Array für Output zusammen
	* @param object
	* @return array
	*/
	private function putAllTogether($content)
	{
		//Helper::pre($content);
		$status		= (string) $content->z30->{'z30-item-status'};
		$stao		= (string) $content->{'z30-collection-code'};
		$signatur	= (string) $content->z30->{'z30-call-no'};
		$info		= (string) $content->z30->{'z30-description'};
		
		$processStatus	= (string) $content->{'z30-item-process-status-code'};
		$itemStatus		= (string) $content->{'z30-item-status-code'};
		$order	= $this->getItemStatus($itemStatus, $processStatus, $status, $stao);
		
		// Exemplar nicht anzeigen
		if (empty($stao))
			$stao	= 'HSG';
		
		if (is_array($order) & $order['display'] == 'N')
			return false;
		
		// Datum korrigieren
		$datum	= '';
		$datum	= (string) $content->status;
		if (!empty($datum)) {
			$datum	= str_replace('/', '.', $datum);
			if (!preg_match('/^[0-9]{2}\./', $datum))
				$datum	= $order['desc'];
			else
				$status	= '00';
		}
				
		// Link setzen
		$media	= 'javascript:alert(\'Bitte wenden Sie sich an das Ausleihpersonal\')'; // falls kein normales Exemplar
		if (!empty($order['ebene'])) {
			$media	= 'http://mediascout.unisg.ch/Rauminfosystem.1.0.html?signatur=' . $stao . '%20' . $signatur . '&ebene=' . $order['ebene'];
		}
		
		// Exemplarschlüssel
		$href	= explode("/", $content->attributes()->href);
		$sequence	= $href[count($href) - 1];

		// Ausgabearray zusammenstellen	
		$return	= array('status'	=> $status,
						'order'		=> $order,
						'datum'		=> $datum,
						'standort'	=> $stao,
						'link'		=> $media,
						'signatur'	=> $signatur,
						'info'		=> $info,
						'sequence'	=> $sequence);
		
		return $return;
	}
	
	/**
	* Abfrage ob Exemplar ausgeliehen / kopiert werden darf
	* @param String z30-item-status-code
	* @param String z30-item-process-status-code
	* @param String z30-item-status
	* @param String z30-collection
	* @return array
	*/
	private function getItemStatus($itemStatus, $processStatus, $status, $coll)
	{
		$this->setBaseUrl(BASE_URL . 'x' . $status . '&status=' . $itemStatus . '&procstatus=' . $processStatus . '&coll=' . $coll);
		$data	= $this->parseData($this->getData());

		return array('display'	=> (string) $data->itemAction[4],
					 'hold'		=> (string) $data->itemAction[1],
					 'copy'		=> (string) $data->itemAction[2],
					 'desc'		=> (string) $data->itemDesc,
					 'ebene'	=> (string) $data->itemFloor);
	}
	
	// ==============================================================
	
	/**
	* Metainformationen holen
	* @void
	*/
	public function searchMeta($q)
	{
		if (empty($this->metaXML)) {
			$this->setBaseUrl(BASE_URL . 'x' . $q);	
			$this->addFilter('view', 'full');
			$this->addFilter('lang', 'ger');
			
			$data	= $this->getData();
			$this->metaXML	= $this->parseData($data);
		}
	}
	
	/**
	* Zugriff auf Meta Objekt
	* @return Object
	*/
	public function getMetaXML()
	{
		return $this->metaXML;
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
	
	/**
	* Klasse überladen mit eigenen Variabeln
	* 
	* @param String name
	* @param mixed value
	* @void
	*/
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}
	
	/**
	* Dynamisch erzeugte Variable holen
	* 
	* @param mixed name
	* @return mixed
	*/
	public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
	}
	
}