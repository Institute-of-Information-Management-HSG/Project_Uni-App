<?php

/**
 * DataController für Bib User
 * Anbindung an ALEPH XML Schnittstelle
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-07-04
 * @todo Konsistenz des Objekts! Immer auf gleiche Instanz beziehen -> docNr, userID, etc. nur einmal übergeben -> eine Instanz davon ableiten, mit dieser arbeiten
 */
 
define('APPLICATION_ID', 'ID');



class BibUserDataController extends XMLParseController
{
	protected $cacheFolder	= "BibUser";
	protected $DEFAULT_PARSER_CLASS	= 'JSONDataParser';
	protected $cacheLifetime	= 1; // No Cache, da ansonsten Verzögerung bei Benutzungskonto
	
	private $loginXML; // Object
	private $docNr; // Int
	private $data	= array(); // Array für overload
	private $userID; // String
	//instantiate User Controller
	private $Rest;
	
	public function __construct()
	{
		$this->Rest	= DataController::factory('RESTController');
		$this->Rest->setParser('XML');
		//parent::construct();
	}
	
	public function getItem($id)
	{
		
	}
	
	/**
	* Session prüfen
	* @return boolean
	*/
	public function isAuth()
	{
		if (isset($_SESSION['user']['key'])) {
			if (crypt($_SERVER['HTTP_USER_AGENT'], APPLICATION_ID) === $_SESSION['user']['key'])
				return true;
		}
			
		return false;
	}
	
	/**
	* User ausloggen
	* @void
	*/
	public function logout()
	{
		$_SESSION['user']['key']	= '';
		$_SESSION['user']['uid']	= '';
		unset($_SESSION['user']);
	}
	
	/**
	* Session vergeben
	* @param int UserID
	* @return boolean
	*/
	public function setSession()
	{
		$_SESSION['user']	= array();
		$_SESSION['user']['key']	= crypt($_SERVER['HTTP_USER_AGENT'], APPLICATION_ID);
		$_SESSION['user']['uid']	= $this->userID;
		
		return true;
	}
	
	/**
	* Logindaten abfragen per POST
	* 
	* @param String username
	* @param String password
	* @return int status
	*/
	public function isUser($username, $password)
	{
		$response = $this->Rest->postRequest(BASE_URL . 'auth.pl', 
											 array('user'	=> $username,
												   'pw'	=> $password));
		
		$this->loginXML	= $response;
		$status		= (array) $this->loginXML->status;

		return $status[0];		
	}
	
	/**
	* UserID abfragen
	*
	* @return String UserID
	*/
	public function getUserID()
	{
		$patron	= (array) $this->loginXML;
		if (!empty($patron)) {
			$this->userID	= $patron['patron-id'];
			
			return $patron['patron-id'];
		}
			
		return false;
	}
	
	/**
	* Username holen
	*
	* @param String userID
	* @return String Username
	*/
	public function getUsername($userID)
	{
		$this->setBaseUrl(BASE_URL . 'x' . $userID . 'x');
		$data	= $this->parseData($this->getData());
		
		return (string) $data->{'address-information'}->{'z304-address-1'};
	}
	
	/**
	* Anzahl Ausleihen zurückliefern
	*
	* @param String userID
	* @return int totalLoans
	*/
	public function getLoansAmount($userID)
	{
		$this->getLoans($userID);
		
		$totalLoans	= $this->getTag('loans', "total", "type='Loans'");

		return (int) $totalLoans[0];
	}
	
	/**
	* Alle ausgeliehenen Bücher holen
	*
	* @param int anzahl Anzahl Ausleihen, die durchlaufen werden müssen
	* @param String detail für URL
	* @param String type für XML Path
	* @return array
	* @todo Fallback mit Exceptionbehandlung
	*/
	public function getAllLoans($anzahl, $detail, $type)
	{
		$this->setBaseUrl(BASE_URL . ':x' . $this->userID . 'x' . $detail);
		$this->addFilter('lang', 'ger');
		$data	= $this->parseData($this->getData());
				
		$return	= array();
		
		// umformen, damit Abfrage stimmt 
		$type	= strtolower($type);
		$type	= str_replace("request", "-request", $type);
		$callback	= $type;
		
		if (substr($type, -1) != "s")
			$callback	= $type . 's';
		else
			$type		= substr($type, 0, -1);
		
		$loan	= $data->$callback->institution->$type;
		for ($i = 0; $i < $anzahl; $i++) {
			$href	= (string) $loan[$i]['href'];
			$return[$i]	= $this->getLoanDetails($href, $type);
		}
		return $return;
	}
	
	/**
	* Detail einer Ausleihe holen
	*
	* @param String url
	* @param String shortCall für XML Aufruf
	* @return array
	* @todo Datum formatieren
	*/
	private function getLoanDetails($url, $shortCall)
	{		
		$return	= array();
		$this->setBaseUrl($url);
		$this->addFilter('lang', 'ger');
		$data	= $this->parseData($this->getData());
		
		$titel		= (string) $data->$shortCall->{'z13'}->{'z13-title'}[0];
		$autor		= '';
		$pickUp		= '';
		$dueDate	= '';
		$delete		= '';
		
		if ($data->$shortCall->attributes()->delete == "Y") {
			$aDel	= explode("/", $url);
			$delete	= $aDel[count($aDel) - 1];
		}
		
		// Ausleihen
		if ($data->xpath('//z36')) {
			$loanDate	= (string) $data->$shortCall->{'z36'}->{'z36-loan-date'}[0];
			$loanDate	= $this->formatLoanDate($loanDate);
			$dueDate	= (string) $data->$shortCall->{'z36'}->{'z36-due-date'}[0];
			$pickUp		= '';
		}
		// Bestellungen
		if ($data->xpath('//z37')) {
			$loanDate	= (string) $data->$shortCall->{'z37'}->{'z37-status'}[0];
			$pickUp		= (string) $data->$shortCall->{'z37'}->{'z37-pickup-location'}[0];
		}
		// Kopierauftrag
		if ($data->xpath('//z38')) {
			$loanDate	= (string) $data->$shortCall->{'z38'}->{'z38-open-date'}[0];
			$autor		= (string) $data->$shortCall->{'z38'}->{'z38-author'}[0];
			$temptitel	= (string) $data->$shortCall->{'z38'}->{'z38-title'}[0];
			if (!empty($temptitel))
				$titel		= $temptitel;
		}
		
		
		$return	= array('titel'		=> $titel,
						'signatur'	=> (string) $data->$shortCall->{'z30'}->{'z30-call-no'}[0],
						'loanDate'	=> $loanDate,
						'dueDate'	=> $this->formatDueDate($dueDate),
						'pickUp'	=> $pickUp,
						'autor'		=> $autor,
						'delete'	=> $delete);
		
		return $return;
	}
	
	/**
	* Datum korrekt formatieren
	* nur brauchbar via getLoanDetails
	* 
	* @param int date Datum im Format YYYYMMDD
	* @return String 
	*/
	private function formatLoanDate($date)
	{
		if (preg_match("/[0-9]{8}/", $date)) {
			$today	= date("Ymd");
			$diff	= (strtotime($today) - strtotime($date)) / (60*60*24);
			
			if ($diff == 0)
				return 'heute';
			else if ($diff == 1)
				return 'gestern';
			else if ($diff < 14)
				return $diff . ' Tagen';
			else if ($diff >= 14)
				return ceil($diff / 7) . ' Wochen';
		}
		else
			return 'Kein Datum';
	}
	
	/**
	* Formatiert Enddatum
	*
	* @param int date Datum im Format YYYYMMDD
	* @return String Datum im Format dd.mm.YYYY
	*/
	private function formatDueDate($date)
	{
		if (preg_match("/[0-9]{8}/", $date))
			return substr($date, 6, 2) . '.' . substr($date, 4, 2) . '.' . substr($date, 0, 4);
	}
	
	/**
	* Anzahl holen
	*
	* @param String userID
	* @param String callback
	* @param String type
	* @param String tag
	* @return int TotalLoans
	*/
	public function getAmount($userID, $callback, $type, $tag)
	{
		if (empty($this->userID))
			$this->userID	= $userID;
				  
		$this->getLoans($tag, $callback);
		$totalLoans	= $this->getTag($tag, "total", "type='" . $type . "'");

		return (int) $totalLoans[0];
	}
	
	/**
	* Anzahlrequest absetzen
	* speicher Ergebnis in eine zur Laufzeit erzeugte Variabel
	* @param String tag
	* @param String callback
	* @void
	*/
	private function getLoans($tag, $callback)
	{
		if (empty($this->$tag)) {
			$this->setBaseUrl(BASE_URL . 'x' . $this->userID . '/circulationActions/' . $callback);
			$this->addFilter('lang', 'ger');
			$data	= $this->parseData($this->getData());
			$this->$tag	= $data;
		}
	}
	
	/**
	* Liste aller möglichen Abholorte
	*
	* @param String UserID
	* @param int docNr
	* @param int seq
	* @return array
	*/
	public function getPickupLocations($userID, $docNr, $seq)
	{
		$url	= BASE_URL . 'x' . $userID . '/record/HSB01' . $docNr . '/holds/' . $seq;
		$this->setBaseUrl($url);
		$this->addFilter('lang', 'ger');
		
		$data	= $this->parseData($this->getData());
		$pickup	= $data->xpath('//pickup-location');
		$status	= $data->group->{'item-status-code'};
		
		// HSG erlaubt?
		$BibData	= DataController::factory('BibDataController');
		$collCode	= $BibData->getProcessStatusCode($docNr);
		
		$return	= array();
		foreach ($pickup as $id => $value) {
			$code	= (string) $pickup[$id]->attributes()->code;
			$url	= 'proceed?a=order&amp;doc_nr=' . $docNr . '&amp;seq=' . $seq . '&amp;pick=' . $code;
			
			if ($status == 15 && $pickup[$id][0] == 'HSG' && $collCode == '') // item process status == ''
				$url	= "javascript:alert('Dieses Dokument ist nicht ausgeliehen. Bitte holen Sie es selbst im Regal. Ihre Reservation wird nicht verarbeitet.');";
			
			$return[$id]	= array('title'	=> (string) str_replace('ue', 'ü', $pickup[$id][0]),
									'url'	=> $url);
		}
		return $return;
	}
	
	/**
	* Kopierauftrag absetzen
	*
	* @param array Post Variablen
	* @return boolean
	*/
	public function postCopy($array)
	{
		$url	= BASE_URL . 'x';
		
		$return	= $this->Rest->postRequest($url, $array);
		echo '<pre>';
		print_r($return);
		echo '</pre>';
		return $this->requestResponse($return);
		
		return false;
		
	}
	

	/**
	* Bestellung vornehmen
	*
	* @param String userID
	* @param int docNr
	* @param String Sequence
	* @param String Daten
	* @return boolean
	*/
	public function putOrder($userID, $docNr, $seq, $data)
	{
		$url	= BASE_URL . 'x' . $userID . '/record/HSB01' . $docNr . '/holds/' . $seq . '?lang=ger';
		$return	= $this->Rest->putRequest($url, $data);
		
		return $this->requestResponse($return);
		
		return false;
	}
	
	/** 
	* DELETE Request
	*
	* @param String userID
	* @param String DokID
	* @param String Type (photocopies|holds)
	* @return boolean
	*/
	public function deleteRequest($userID, $seq, $type)
	{
		$url	= BASE_URL . 'x' . $userID . '/circulationActions/requests/' . $type . '/' . $seq . '?lang=ger';
		$return	= $this->Rest->deleteRequest($url);
		
		return $this->requestResponse($return);
			
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
	
	/**
	* Response eines Requests auswerten und verwerten
	*
	* @param int HttpCode
	* @param Object XML Reponse
	* @return boolean | array
	*/
	public function requestResponse($responseXML)
	{
		$code	= $responseXML->{'reply-code'}[0];
	
		if (isset($code) && $code == '0000')
			return true;
		else
			return $this->throwError($code, $responseXML->{'reply-text'}[0], $responseXML->{'create-hold'}->{'note'}[0]);
		
		return false;
	}
	
	/**
	* Private Fehlerbehandlung
	* gibt Array mit code, title, message zurück
	*
	* @param int Code
	* @param String title
	* @param String message
	* @return array
	*/
	private function throwError($code, $title, $message)
	{
		return array('code'		=> (int) $code,
					 'title'	=> (string) $title,
					 'message'	=> (string) $message);
	}
}