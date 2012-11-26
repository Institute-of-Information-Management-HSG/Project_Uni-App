<?php

/**
 * Description of BibWebModule
 *
 * @author Daniel
 * @version 2012-04-03
 */
 
class ScheduleDataController extends DataController
{
	protected $cacheFolder	= "transit";
	protected $DEFAULT_PARSER_CLASS	= 'JSONDataParser';
	
	private $jsonData;	// Speicher für Daten
	
	/**
	* URL setzen und Daten holen
	*
	* @param String from
	* @param String to
	* @return array
	*/
	public function getConnection($from, $to, $limit = false, $date = false, $time = false)
	{
		$this->setBaseUrl('http://transport.opendata.ch/v1/connections');
		$this->addFilter('from', $from);
		$this->addFilter('to', $to);
		
		if ($date)
			$this->addFilter('date', $date);
			
		if ($time)
			$this->addFilter('time', $time);
		
		if ($limit)
			$this->addFilter('limit', $limit);

		$data	= $this->getParsedData();
		
		/*echo '<pre>';
		print_r($data);
		echo '</pre>';*/
		$this->jsonData	= $data['connections'];
	}
	
	/**
	* Nur benötigte und formatierte Daten zurückliefern
	*
	* @param int count Anzahl Einträge, die geliefert werden sollen
	* @param enum departure|arrival
	* @param boolean date falls true wird Datum auch ausgegeben
	* @return array (departure, change, duration[, datum], from, to)
	*/
	public function getFormatedData($departure = 'departure', $date = false)
	{
		$aReturn	= array();
		
		foreach ($this->jsonData as $id	=> $items) {
			if ($departure == 'departure')
				$from	= 'from';
			else
				$from	= 'to';
			
			$aReturn['items'][$id][$departure]	= date('H:i', strtotime($this->jsonData[$id][$from][$departure]));
			$aReturn['items'][$id]['change']	= count($items['sections']) - 1;
			$aReturn['items'][$id]['duration']	= $this->getTimeDiff(strtotime($items['to']['arrival']), strtotime($items['from']['departure']));
			
			if ($date)
				$aReturn['items'][$id]['datum']	= date('d.m.Y', strtotime($this->jsonData[$id][$from][$departure]));
		}
		
		$aReturn['meta']	= array('from'	=> $this->jsonData[0]['from']['station']['name'],
									'to'	=> $this->jsonData[0]['to']['station']['name']);
		
		return $aReturn;
	}
	
	/**
	* Errechnet den Zeitunterschied zwischen zwei Zeiten (time1 muss höher sein)
	*
	* @param timestamp
	* @param timestamp
	* @return String
	*/
	private function getTimeDiff($time1, $time2)
	{
		$timestamp	= $time1 - $time2;
		$hours	= floor($timestamp / 60 / 60);
		$min	= floor(($timestamp - ($hours * 60 * 60)) / 60);
		if (strlen($hours) < 2)
			$hours	= 0 . $hours;
		if (strlen($min) < 2)
			$min	= 0 . $min;
		
		return $hours . ':' . $min;
	}
		
	/**
	* Daten ausgeben
	* (Methodenname, damit parent::getData nicht überschrieben wird)
	* 
	* @return array
	*/
	public function getJsonData()
	{
		return $this->jsonData;
	}
	
	/**
	* (vorgegeben durch abstrakte Parent Klasse)
	* Liefert gewünschtes Item aus dem JSON Array
	*
	* @todo Suchparameter ergänzen, um innerhalb von Arrayrumpf Ergebnisse zu finden
	* @param int ID
	* @return array
	*/
	public function getItem($id) 
	{
		
	}
}