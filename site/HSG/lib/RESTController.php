<?php

/**
 * Rest Controller für HTTP Abfragen
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-07-009
 * @todo Refaktorisieren für andere Klassen als BibUserData...
 */

class RESTController extends DataController
{
	protected $cacheFolder	= "REST";
	protected $DEFAULT_PARSER_CLASS	= 'JSONDataParser';
	
	// XML Parsen oder nicht
	protected $parser	= 'XML';
	
	// Header
	protected $header	= array();
	
	/**
	* Parser ändern
	* @param string parser
	*/
	public function setParser($parser)
	{
		if ($this->parser = $parser)
			return true;
	}
	
	/**
	* Header hinzufügen
	* @param string
	*/
	public function removeHeader()
	{
		$this->header = array();
	}
	
	/**
	* Header löschen
	*/
	public function addHeader($string)
	{
		if (array_push($this->header, $string))
			return true;
	}
	
	
	public function getItem($id) {	}
	
	/**
	* Response zurückgeben
	* @param int httpCode
	* @param mixed response
	* @return mixed response
	*/
	private function response($httpCode, $response)
	{
		if ($httpCode == 200)
			return $response;
			
		else
			return array('code'		=> $httpCode,
						 'response'	=> $response);
	}
	
	/** 
	* DELETE Request
	*
	* @param String url
	* @return boolean
	*/
	public function deleteRequest($url, $parse = true)
	{
		$ch	= curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response	= curl_exec($ch);
		
		if ($parse)
			$response	= $this->parseData($response);
			
		$httpCode		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		return $this->response($httpCode, $response);
			
		return false;
	}
	
	/**
	* PUT Request absetzen
	*
	* @param String URL
	* @param String Daten
	* @return boolean
	*/
	public function putRequest($url, $data, $parse = true) 
	{
		// Tempräre Datei schreiben
		$length = strlen($data);
		$fh = fopen('php://memory', 'rw');
		fwrite($fh, $data);
		rewind($fh);
		
		$ch	= curl_init();
		curl_setopt($ch, CURLOPT_INFILE, $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE, $length);
		curl_setopt($ch, CURLOPT_PUT, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response	= curl_exec($ch);
		
		if ($parse)
			$response	= $this->parseData($response);
			
		$httpCode		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		return $this->response($httpCode, $response);
			
		return false;
	}
	
	/**
	* POST Request absetzen
	*
	* @param String URL
	* @param array Daten
	* @param boolean parsen oder nicht
	* @return response bei 200 | false
	*/
	public function postRequest($url, $array, $parse = true) 
	{
		$responseXML	= '';
		$url	= $url;
		$ch	= curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
		if (!empty($this->header))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
			
		$response		= curl_exec($ch);
		if ($parse)
			$response	= $this->parseData($response);
			
		$httpCode		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		return $this->response($httpCode, $response);
			
		return false;
	}
	
	/**
	* GET Request absetzen
	*
	* @param String URL
	* @param array Daten
	* @param boolean parsen oder nicht
	* @return response bei 200 | false
	*/
	public function getRequest($url, $parse = true) 
	{
		$responseXML	= '';
		$url	= $url;
		$ch	= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
		if (!empty($this->header))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		
		$response		= curl_exec($ch);
		
		if ($parse)
			$response	= $this->parseData($response);

		/*echo '<pre>';
		print_r($this->header);
		echo '</pre>';*/
		
		/*echo '<pre>';
		print_r($response);
		echo '</pre>';
*/
		$httpCode		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		return $this->response($httpCode, $response);
			
		return false;
	}

	
	/**
	* parse Data
	* @param String data
	* @return array 
	*/
	public function parseData($data) {
		if ($this->parser == 'XML')
			$xml	= simplexml_load_string($data);
		else
			$xml	= parent::parseData($data);
		
		return $xml;
	}
}