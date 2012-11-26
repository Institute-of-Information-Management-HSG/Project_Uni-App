<?php
/**
 * Description of BibWebModule
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-05-29
 */
 
class BibWebModule extends WebModule
{
	protected $id			= 'bib';
	protected $moduleName	= 'Bib';
	

	protected function initializeForPage() {
		//instantiate controller
    	$controller = DataController::factory('BibDataController');
		
		//instantiate User Controller
    	$User = DataController::factory('BibUserDataController');
		
		// Switch für Footereinbindung
		if ($User->isAuth())
			$this->assign("isAuth", 1);
		else
			$this->assign("isAuth", 0);
			
		// Javascript einbinden
		$this->addJQuery();
		
		switch ($this->page) {
			// Pre Modul//
			case 'index':
				// get information from ini-file
				$categories = $this->getModuleArray('categories');
				// assign the array to the tpl-files
				$this->assign('categories', $categories);
				break;
				
			  case 'openinghours':
				$url = $this->getModuleVar('openingUrl');
				$urlClosings = $this->getModuleVar('closingUrl');
		
				// business hours from webpage
				if (function_exists('mb_convert_encoding')) {
				  $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
				  $urlClosings = mb_convert_encoding($urlClosings, 'HTML-ENTITIES', 'UTF-8');
				}
				// create new DomDocument
				$dom = new DOMDocument();
				$domClosings = new DOMDocument();
				@$dom->loadHTMLFile($url);
				@$domClosings->loadHTMLFile($urlClosings);
						
				$tables = $dom->getElementsByTagName("table");
				$tablesClosing = $domClosings->getElementsByTagName("table");
		   
				//openinghours
				$openinghoursArray = $this->getOpeningHours($tables, 0);
		
				//closings
				$closingsTyArray = $this->getClosingHours($tablesClosing, 0);
				$closingsNyArray = $this->getClosingHours($tablesClosing, 1);
						
				$this->assign("openinghours", $openinghoursArray);
				$this->assign("closingsTy", $closingsTyArray);
				$this->assign("closingsNy", $closingsNyArray);
				break;
			  case 'contact':
				$officeBuilding = $this->getModuleVar('officeBuilding');
				$officeAddress =$this->getModuleVar('officeAddress');
				$officeAddressUrl=$this->getModuleVar('officeAddressUrl');
				$officePhone = $this->getModuleVar('officePhone');
				$officeFax = $this->getModuleVar('officeFax');
				$officeMail = $this->getModuleVar('officeMail');
				$officeUrl = $this->getModuleVar('officeUrl');
				$officeFacebook = $this->getModuleVar('officeFacebook');
				$officeLocation = $this->getModuleVar('officeLocation');
						
				// Address arrays
				$contactArray = array();
				array_push($contactArray, array('title' => "Kartenansicht", 'subtitle' => $officeBuilding . ", " . $officeAddress, 'url' => $officeAddressUrl));
				array_push($contactArray, array('title' => "Telefon", 'subtitle' => $officePhone, 'url' => "tel: " . $officePhone, 'class' => 'phone'));
				array_push($contactArray, array('title' => "Fax", 'subtitle' => $officeFax, 'url' => "fax: " . $officeFax, 'class' => 'phone'));
				array_push($contactArray, array('title' => "E-Mail", 'subtitle' => $officeMail, 'url' => "mailto: " . $officeMail, 'class' => 'email'));
				array_push($contactArray, array('title' => "Webseite", 'subtitle' => $officeUrl, 'url' => $officeUrl, 'class' => 'web'));
				array_push($contactArray, array('title' => "Facebook-Seite", 'subtitle' => $officeFacebook, 'url' => $officeFacebook, 'class' => 'web'));
				//array_push($contactArray, array('title' => "Kartenansicht", 'subtitle' => "Bibliotheksgeb&auml;ude 09", 'url' => $officeLocation, 'class' => 'web'));
				
				//$this->assign("address", $officeBuilding);
				$this->assign("contact", $contactArray);
				break;
			  case 'links':
				$librarylinks = $this->getModuleArray('librarylinks');
				$this->assign('librarylinks', $librarylinks);
				break;
			  case 'guidance':
				$url = $this->getModuleVar('guidanceUrl');
		
				// guidance hours from webpage
				if (function_exists('mb_convert_encoding')) {
				  $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
				}
				// create new DomDocument
				$dom = new DOMDocument();
				@$dom->loadHTMLFile($url);
						
				$tables = $dom->getElementsByTagName("table");
						
				//guidancehours
				$guidancehoursArray = $this->getGuidanceHours($tables, 0);
						
				$this->assign("guidance", $guidancehoursArray);
				$guidancelinks = $this->getModuleArray('guidancelinks');
				$this->assign('guidancelinks', $guidancelinks);
				break;
			  case 'events':
				$url = $this->getModuleVar('eventsUrl');
				if (function_exists('mb_convert_encoding')) {
				  $url = mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8');
				}
				$dom = new DOMDocument();
				@$dom->loadHTMLFile($url);
		
				//use DOMXpath to navigate the html with the DOM
				$events = $dom->saveHTML();
				$dom_xpath = new DOMXpath($dom);
		
				//delete the remain
				$startpos = strpos($events,"<p>Kommende");
				$endpos = strpos($events,"nscht.<br><br></p>");
				$endpos = $endpos - $startpos + 18;
				$events = substr($events,$startpos,$endpos);
				$events = preg_replace("/<a[^>]+>/i", "", $events);
				$events = preg_replace("/<\/a>/i", "", $events);
				
		
				$this->assign("events", $events);
				break;
				
			case 'apps':
				$appList = $this->getModuleArray('appList');
				$this->assign('appList', $appList);
				break;
			
			// Suchfeld
			case 'search':
				$url = $this->getModuleVar('searchURL');
				$this->assign('titel', "Suche im Katalog");
				
				$error	= '';
				
				//instantiate Suchcontroller
    			$EDS = DataController::factory('BibSearchDataController');
				try {
					$EDS->getSessionToken();
					
					// Suche darstellen
					if (isset($_GET['q'])) {
						try {
							$results	= $EDS->search($_GET['q'], $_GET['scope']);
						}
						catch (Exception $e) {
							unset($_SESSION['eds']);
							Kurogo::log(LOG_WARNING, 'Error 400 thrown by EDS API', 'Exceptions');
							$error	= $e->getMessage();
						}
						
						$this->assign("error", $error);
						
						// An Template übergeben
						$this->assign('showTotal', '1');
						$this->assign('titel', 'Resultate');
						$this->assign('q', $_GET['q']);
						$this->assign('total', $results['totalItems']);
						$this->assign('hsg', $results['hsgItems']);
						$this->assign('results', $results['items']);
						$this->assign('pageLoad', '1');
						$this->assign('scope', $_GET['scope']);
						$this->assign('query', $_GET['q']);
					}
				}
				catch (Exception $e) {
					unset($_SESSION['eds']);
					Kurogo::log(LOG_WARNING, 'Error 400 thrown by EDS API', 'Exceptions');
					$error	= $e->getMessage();
				}
				
				$this->assign("error", $error);
				
				/*echo '<pre>';
				print_r($EDS->getSessionToken());
				echo '</pre>';*/
				
				break;
				
			// Suchresultate
			case 'results':
				//instantiate Suchcontroller
    			$EDS = DataController::factory('BibSearchDataController');
					
				$error	= '';
				//$results	= $EDS->search($_GET['q'], $_GET['scope']);
				try {
					$results	= $EDS->search($_GET['q'], $_GET['scope']);
				}
				catch (Exception $e) {
					unset($_SESSION['eds']);
					$error	= $e->getMessage();
				}
				
				$this->assign("error", $error);
				
				// An Template übergeben
				$this->assign('q', $_GET['q']);
				$this->assign('total', $results['totalItems']);
				$this->assign('hsg', $results['hsgItems']);
				$this->assign('results', $results['items']);
				$this->assign('pageLoad', '1');
				$this->assign('scope', $_GET['scope']);
				$this->assign('query', $_GET['q']);
				
				break;
				
			// Suchfeld
			case 'search2':
				$url = $this->getModuleVar('searchURL');
				$url	= $this->buildBreadcrumbURL($url, array('doc_nr'	=> ''), true);
				$this->assign("url", $url);
				break;
				
			// Action auf Bestellbuttons
			case 'detail' :
				// GET Arguments
				$docNr = $this->getArg('doc_nr');
				
				// Items Query
				$items	= $controller->search($docNr);
				
				// Variablen übergeben an Template
				$checkComplete	= (string) $controller->getInfo('author');
				if (!empty($checkComplete)) {
					$this->assign('author', $controller->getInfo('author'));
					$this->assign('title', $controller->getInfo('title'));
					$this->assign('impressum', $controller->getInfo('imprint') . ', ' . $controller->getInfo('year'));
					
					// ISBN
					$isbn	= $controller->getInfo('isbn-issn');
					$isbn	= preg_replace("/\s.*$/", "", $isbn);
					$this->assign('isbn', $isbn);
					if ($controller->getInfo('isbn-issn-code') == "020")
						$labelISBN	= 'ISBN';
					else
						$labelISBN	= 'ISSN';
						
					$this->assign('labelISBN', $labelISBN);
					
					// Exemplarquery
					// Mehrere Bände
					$ifMult	= $controller->isMultiple();
					if ($ifMult == 'band') {
						$this->assign('multiples', '1');
						
						$multiples	= $controller->getMultiples();
						$i	= 0;
						foreach ($multiples as $band => $items) {
							$bandLinks[]	= array('title'	=> $band,
													'url'	=> 'javascript:show(\'v' . $i . '\')',
													'volumeID'	=> 'v' . $i,
													'books'	=> $items);
							$i++;
						}
						$this->assign('bandLinks', $bandLinks);
					}
					// Online
					else if ($ifMult == 'online') 
						$this->assign('showAllItems', 'no');
					
					// else normal anzeigen
					else {
						$allItems	= $controller->getAllItems();
						if ($ifMult == 'zeitschrift') // Zeitschrift, Infofeld liefern
							$this->assign('showInfo', '1');
							
						$this->assign('allItems', $allItems);
					}
					
					// Metaquery
					$controller->searchMeta($docNr);
					$meta	= $controller->getMetaXML();
					
					$amount	= $controller->getTag('metaXML', "datafield[@tag='300']/subfield", "code='a'");
					$this->assign('amount', $amount[0]);
					
					// Schlagworte
					$tagO	= '';
					if ($tags = $controller->getTag('metaXML', "/datafield", "tag='650'", true)) {
						$labelTags	= '';
						foreach ($tags as $node => $tag) {
							$tag	= (array) $tag->subfield;
							$labelTags	.= $tag[0] . ', ';
						}
						$tagO	= substr($labelTags, 0, -2);
					}
					$this->assign('tags', $tagO);
					
					// Volltextlinks
					$bibLinks	= array();
					
					// Inhaltsverzeichnis
					// ind2 A = Volltext
					// ind2 C = Inhaltsverzeichnis
					// TODO: Refaktorisieren
					if ($contentLink	= $controller->getTag('metaXML', "datafield", "tag='856'", true)) {
						//print_r($contentLink);
						foreach ($contentLink as $id => $label) {
							$ind	= $contentLink[$id]->attributes()->ind2;
							if ($ind == "A") {
								if (preg_match('/^http:\/\/(.*)/i', trim($contentLink[$id]->subfield[0])))
									$link	= $contentLink[$id]->subfield[0];
								else
									$link	= $contentLink[$id]->subfield[1];

									
								$bibLinks[]	= array('title' => 'Volltext',
													'url'	=> $link);
							}
							else if ($ind == "C") {
								$bibLinks[]	= array('title' => 'Inhaltsverzeichnis',
													'url'	=> $contentLink[$id]->subfield[0]);
							}
						}
					}
					$this->assign('bibLinks', $bibLinks);
					
					// Abstract
					$abstract	= false;
					if ($abstract	= $controller->getTag('metaXML', "datafield[@tag='520']/subfield", "code='a'", true)) {
						$abstractO	= '';
						foreach ($abstract as $id => $abstract) {
							$abstractO	.= $abstract[0] . '<br />';
						}
						$abstract	= $abstractO;
					}
					$this->assign('abstract', $abstract);
					
					$this->assign('docNr', $docNr);
					
					$url = $this->getModuleVar('actionURL');
					$this->assign("url", $url);
				}
				else {
					$this->assign("notComplete", "1");
				}
				
				break;
			
			
			// Actions ausführen (order, copy)
			// Login erforderlich
			case 'action' :
				// GET Arguments
				$action1	= $this->getArg('a');
				$docNr		= $this->getArg('doc_nr');
				$seq		= $this->getArg('seq');
				
				// eingeloggt
				if ($User->isAuth()) { 
					$this->assign('action', $action1);
					$this->assign('fees', $this->getModuleArray('feeLinks'));
					
					switch ($action1) {
						case 'copy' :
							$this->assign('h1', 'Kopierauftrag');
							$getArray	= $action1 . 'form';
							$formArray	= $this->getModuleArray($getArray);
							$this->assign('form', $formArray);
							$this->assign('seq', $seq);
							break;
							
						case 'order' :
							$this->assign('h1', 'Bestellen/Reservieren');
							// Abholorte bestimmen
							$pickup	= $User->getPickupLocations($_SESSION['user']['uid'], $docNr, $seq);
							$this->assign('pickup', $pickup);
							break;
					}
				}
				else 
					$this->redirectTo('login');
					
				break;
				
			// Zwischenseite für PUT Requests, wird nicht angezeigt
			case 'proceed' :
				// GET Arguments
				$action	= $this->getArg('a');
				$docNr	= $this->getArg('doc_nr');
				$seq	= $this->getArg('seq');
				$code	= $this->getArg('pick');
				$userID	= $_SESSION['user']['uid'];
				
				if ($User->isAuth()) {
					// bestellen
					if ($action == 'order') {
						$data	= 'post_xml=<hold-request-parameters><pickup-location>' . $code . '</pickup-location></hold-request-parameters>';
						$response	= $User->putOrder($userID, $docNr, $seq, $data);
						$anker		= '1';
					}
					
					// Kopierauftrag
					else if ($action == 'copy') {
						$_POST['userid']	= $userID;
						unset($_POST['submit']);
						$response	= $User->postCopy($_POST);
						$anker		= '2';
					}
					
					// Verarbeitung
					if ($response === true)
						$this->redirectTo('account#v' . $anker, array()); // direkt in Useraccount weiterleiten, photocopies öffnen
					else {
						$this->assign('errorTitle', $response['title']);
						$this->assign('errorMessage', $response['message']);
						$this->assign('backLink', 'detail?doc_nr=' . $docNr);
					}
				}
				else 
					$this->redirectTo('login', array('ref'		=> 'action',
													 'docNr'	=> $docNr));
					
				break;
				
			// Loginpage
			case 'login' :
				$action2	= $this->getArg('a');
				$docNr		= $this->getArg('doc_nr');
				$ref		= $this->getArg('ref');
				$seq		= $this->getArg('seq');
				$this->assign('action', $action2);
				$this->assign('seq', $seq);
				$this->assign('docNr', $docNr);
				$this->assign('ref', $ref);
				break;
				
			// Benutzerkonto
			case 'account' :
				// eingeloggt
				if ($User->isAuth()) { 
					$this->setCacheMaxAge(0);
					
					$userID	= $_SESSION['user']['uid'];
					$this->assign('username', $User->getUsername($userID));
					
					$items	= $this->getModuleArray('account');
					foreach ($items as $i => $item) {
						
						$array	= $this->getAccountCallback($User, $i, $item['callback'], $item['type'], $item['tag'], $item['detail']);
						$userLinks[]	= array('title'	=> $item['title'] . ': ' . $array['anzahl'],
												'url'	=> $array['link'],
												'volumeID'	=> 'v' . $i,
												'account'	=> $item['type'],
												'books'	=> $array['books']);
					}
					
					$this->assign('userLinks', $userLinks);
				}
				else 
					$this->redirectTo('login?ref=account');
				break;
				
			// Logout
			case 'logout' :
				$User->logout();
				$this->redirectTo('index');
				break;
		}
	}
	
	/**
	* Ausgabe für Benutzerkonto vorbereiten
	* @param Object obj Controllerobjekt
	* @param int i ID des Durchlaufs
	* @param String callback
	* @return array
	*/
	private function getAccountCallback($obj, $i, $callback, $type, $tag, $detail)
	{
		$userID	= $_SESSION['user']['uid'];
		$return	= array();
		$return['link']	= 'javascript:void(0);';
		
		$anzahl	= $obj->getAmount($userID, $callback, $type, $tag);
		$return['anzahl']	= $anzahl;
		$return['books']	= '';
		if ($anzahl > 0) {
			$books	= $obj->getAllLoans($anzahl, $detail, $type);
			$return['books']	= $books;
			$return['link']		= 'javascript:show(\'v' . $i . '\')';
		}
		
		return $return;
	}
	
	
	private function getOpeningHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $businessHours['caption'] = $caption->item(0)->nodeValue;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
              $time = $allCells->item(1)->nodeValue;

              $time = str_replace("bis", "-", $time);
              $time = str_replace(" Uhr", "", $time);
              $rowArray[] = array(
                  "day" => $allCells->item(0)->nodeValue,
                  "time"  => $time
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
    private function getClosingHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $closingCaption = str_replace("Bibliotheksschliessungen ", "", $caption->item(0)->nodeValue);
          $businessHours['caption'] = $closingCaption;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
			  $day = $allCells->item(0)->nodeValue;
              
			  //replace monthnames
			  $critmonth = array(" Oktober", " November", " Dezember", " Dez.", " Januar", " Februar", " M&auml;rz", " März", " April", " Mai", " Juni", " Juli", " August", "August", " September");
			  $replmonth = array('10.', '11.', '12.', '12.', '1.', '2.', '3.', '3.', '4.', '5.', '6.', '7.', '8.', '8.', '9.');
			  //For Debugging
			  //$day = utf8_encode($day);
			  //$day = preg_replace("/[^a-zA-Z0-9\s\D]/", "", $day);
			  $day = str_replace($critmonth, $replmonth, $day);
			  $day = str_replace("1. 20", "1.20", $day);
			  //$day = str_replace("27. 8", "27.8", $day);
			  $rowArray[] = array(
                  "day" => $day,
                  "description"  => $allCells->item(1)->nodeValue
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
    private function getGuidanceHours($domNode, $id){
          $businessHours = array();
          
          //Caption
          $caption = $domNode->item($id)->getElementsByTagName("caption");
          $businessHours['caption'] = $caption->item(0)->nodeValue;
          
          //Content
          $allRows = $domNode->item($id)->getElementsByTagName("tr");
          $hoursArray = array();
          
          foreach($allRows as $row){
              $allCells = $row->getElementsByTagName("td");
              $description = $allCells->item(0)->nodeValue;
              $day = $allCells->item(1)->nodeValue;
              $time = $allCells->item(2)->nodeValue;

              // delete strange characters
              $description = preg_replace("/[^a-zA-Z0-9\s]/", "", $description);
              $day = preg_replace("/[^a-zA-Z0-9\s]/", "", $day);
              $time = preg_replace("/[^a-zA-Z0-9\s]/", "", $time);

              // corrections
              $description = str_replace("ff", "&Ouml;ff", $description);
              $description = str_replace("inRechts", "in Rechts-", $description);
              $description = str_replace("inVWLBWL", "in VWL, BWL", $description);
              $day = str_replace("tagbis", "tag -", $day);
              $day = str_replace("ag Do", "ag, Do", $day);
              $time = str_replace("bis", "-", $time);
              $time = str_replace("93", "9:3", $time);
              $time = str_replace("73", "7:3", $time);
              $time = str_replace("40", "4:0", $time);
              $time = str_replace("60", "6:0", $time);
              $time = str_replace("20", "2:0", $time);
              $time = str_replace(" Uhr", "", $time);
              $time = str_replace("0-", "0 -", $time);

              $rowArray[] = array(
                  "description" => $description,
                  "day"  => $day,
                  "time"  => $time
              );
              $hoursArray['times'] = $rowArray;
          }
          
          $businessHours['hours'] = $hoursArray;
          
          return $businessHours;
          
    }
}