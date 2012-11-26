<?php

/**
 * DataController für die Bib Suche
 * Anbindung an EDS API
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-08-30
 */

class BibSearchDataController extends DataController
{
	protected $cacheFolder	= "BibSearch";
	protected $DEFAULT_PARSER_CLASS	= 'JSONDataParser';
	//protected $cacheLifetime	= 0; // Wenig Cache, ansonsten falsche Ergebnisse
	private	$Rest;
	
	// Authtoken speichern
	private $authToken;
	
	// Sessiontoken speichern
	private $sessionToken;
	
	public function getItem($id) {}
	
	/**
	* Konstruktor
	*/
	public function __construct()
	{
		$this->Rest	= DataController::factory('RESTController');
		$this->Rest->addHeader("Content-Type: application/json");
		$this->Rest->addHeader("Accept: application/json");
	}
	
	/**
	* AuthToken holen
	* @return String authtoken
	* @todo Überprüfen, ob Token noch gültig
	*/
	public function getAuthToken()
	{
		$url	= 'https://eds-api.ebscohost.com/Authservice/rest/ipauth';
		
		if ($response = $this->Rest->postRequest($url, array(), false)) {		
			$token	= json_decode($response);
			if (json_last_error() != JSON_ERROR_NONE)
				$token	= simplexml_load_string($response);
			
			$this->authToken	= $token->AuthToken;
			$_SESSION['eds']['authtoken']	= $token->AuthToken;
			
			return $token->AuthToken;
		}
		return false;
	}
	
	/**
	* Authtoken prüfen ob gesetzt
	* @param boolean getNew,optional, anzeigen ob neues holen oder aus Session laden
	* @private
	* @void
	*/
	private function checkAuthToken($getNew = false)
	{
		if (empty($this->authToken) || $getNew == true) {
			unset($_SESSION['eds']);
			if (empty($_SESSION['eds']['authtoken']) || $getNew == true) {
				$authtoken	= $this->getAuthToken();
				$_SESSION['eds']['authtoken']	= $authtoken;
			}
			else {
				$authtoken	= $_SESSION['eds']['authtoken'];
				$this->authToken	= $authtoken;
			}
		}
		else
			$authtoken	= $this->authToken;
			
		$this->Rest->removeHeader();
		$this->Rest->addHeader("Content-Type: application/json");
		$this->Rest->addHeader("Accept: application/json");
		$this->Rest->addHeader("x-authenticationToken: " . $authtoken);
	}
	
	/**
	* SessionToken holen
	* fügt Token in Header des Requests ein
	* @param string authtoken
	* @return String sessiontoken
	* @todo Überprüfen, ob Token noch gültig
	*/
	public function getSessionToken($count = 0, $getNew = false)
	{
		// Header und Authtoken setzen
		$this->checkAuthToken($getNew);
		
		// Add the HTTP query parameters
        $params = array(
            'profile' => 'newapi',
            'org'     => 'HSG'
        );
		$url	= 'http://eds-api.ebscohost.com/edsapi/rest/createsession?' . http_build_query($params);
		
		$while = false;
		
		if ($response = $this->Rest->getRequest($url, false)) {	
			/*echo '<pre>';
			print_r($response);
			echo '</pre>';*/
			
			// Fehler wenn Token abgelaufen
			if (preg_match('/ErrorNumber/', $response['response'])) {
				if ($count <= 3)
					$this->getSessionToken($count + 1);
					
				else
					throw new Exception('Verbindungsprobleme. Bitte Seite neu laden');
			}
			else 
				$while	= true;
				
				
			if ($while) {	
				$token	= json_decode($response);
				$this->Rest->addHeader("x-sessionToken: " . $token->SessionToken);
				
				$while	= true;
				$this->sessionToken	= $token->SessionToken;
				$_SESSION['eds']['sessiontoken']	= $token->SessionToken;
				$_SESSION['eds']['timeout']			= time() + (60*10);
				
				return array($token->SessionToken, $_SESSION['eds']['authtoken']);
			}
		}
		
		return false;
	}
	
	/**
	* SessionToken holen
	* @param string authtoken
	* @param string sessiontoken
	* @return boolean
	*/
	public function endSession($authtoken, $sessiontoken)
	{
		// Add the HTTP query parameters
        $params = array(
            'SessionToken' => $sessiontoken,
        );
		
		$url	= 'http://eds-api.ebscohost.com/edsapi/rest/createsession?' . http_build_query($params);
		
		if ($response = $this->Rest->getRequest($url, false)) {
			$token	= $this->parseData($response);
			if ($token['IsSuccessful'] == "y")
				return true;
		}
		
	}
	
	/**
	* Überprüft Tokens, falls nicht gesetzt
	* werden diese geholt.
	* Spätestens nach 30min wird neues geholt
	* @private
	* @void
	*/
	private function checkTokens()
	{
		if (empty($_SESSION['eds']['authtoken']) || empty($_SESSION['eds']['sessiontoken']) || time() > $_SESSION['eds']['timeout']) {
			$tokens	= $this->getSessionToken();
			$_SESSION['eds']['authtoken']	= $tokens[1];
			$_SESSION['eds']['sessiontoken']	= $tokens[0];
			$_SESSION['eds']['timeout']		= time() + (60*10);
		}
		
		$this->Rest->removeHeader();
		$this->Rest->addHeader("Content-Type: application/json");
		$this->Rest->addHeader("Accept: application/json");
		$this->Rest->addHeader("x-authenticationToken: " . $_SESSION['eds']['authtoken']);
		$this->Rest->addHeader("x-sessionToken: " . $_SESSION['eds']['sessiontoken']);
	}
	
	/**
	* Suche ausführen
	* @param string query Suchanfrage
	* @param string scope (books|articles|all)
	* @return array
	*/
	public function search($query, $scope = 'books', $page = 1)
	{
		$this->checkTokens();
		
		// Add the HTTP query parameters
        $params = array(
			'query'			=> $query,
			'resultsperpage'	=> 10,
			'highlight'		=> 'n',
			'pagenumber'	=> $page
        );
		
		// Filter hinzufügen
		switch ($scope) {
			case 'books'	: $params['limiter']	= 'FC:y';
				break;
				
			case 'articles'	: $params['limiter']	= 'RV:y';
				break;
		}
		
		// Volltext setzen bei Artikel oder Suche nach allem
		if ($scope == 'articles' || $scope == 'all')
			$params['expander']	= 'fulltext';
			
		
		$url	= 'http://eds-api.ebscohost.com/edsapi/rest/search?' . http_build_query($params);
		
		
		
		if ($response = $this->Rest->getRequest($url, false)) {
			/*echo '<pre>';
			print_r($response);
			echo '</pre>';*/
			
			if (is_array($response)) {
				if (preg_match('/ErrorNumber/', $response['response'])) {
					$this->checkTokens();
					$while	= false;
				}
			}
			
			else {
				$token	= $this->parseData($response);
				
				return array('totalItems'	=> $token['SearchResult']['Statistics']['TotalHits'],
							 'hsgItems'		=> $token['SearchResult']['Statistics']['Databases'][80]['Hits'],
							 'items'		=> $this->putAllTogether($token['SearchResult']['Data']['Records'])
							 );
			}
		}
		return false;
	}
	
	/**
	* Suchresulte aufbereiten für Template
	* @param array Results von API
	* @return array Formatiert
	*/
	private function putAllTogether($array)
	{
		if (is_array($array)) {
			$return	= array();
			foreach ($array as $id => $result) {
				// PH Bib auslassen
				if ($result['Header']['DbId'] == "cat00829a")
					continue;
				
				$subtitle	= '';
				if (isset($result['Items'][1]['Data'])) {
					//$subtitle	= $result['Items'][1]['Data'];
					$subtitle	= html_entity_decode($result['Items'][1]['Data']); 
					$subtitle	= str_replace('<br />', '; ', $subtitle);
					//$subtitle	= preg_replace('@(<superscript>|.*?<\/superscript>)@is', '', $subtitle);
					$subtitle	= utf8_encode($subtitle);
					
					$arrAuthors	= explode(";", $subtitle);
					$subtitle	= implode("; ", array_slice($arrAuthors, 0, 50));
				}
					
				$type	= '';
				if (isset($result['Items'][2]['Data']))
					$type	= $result['Items'][2]['Data'];
					
				
				$title		= strip_tags($result['Items'][0]['Data']);
				
				$class	= '';
				$url	= "detail?doc_nr=" . substr($result['Header']['An'], -9, 9);
				if ($result['Header']['DbId'] != 'cat00327a') {
					$url	= $result['PLink'];
					$class	= "extern";
				}
				
				$return[]	= array(
								'url'		=> $url,
								'class'		=> $class,
								'title'		=> html_entity_decode($title, ENT_COMPAT, 'UTF-8'),
								'subtitle'	=> strip_tags($subtitle),
								'type'		=> $type
							);
			}
			return $return;
		}
		else
			return false;
	}
}