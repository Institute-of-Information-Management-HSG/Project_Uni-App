<?php
/**
 * AJAX Komponenten für Bib Modul
 *
 * @author daniel.fiechter@student.unisg.ch
 * @version 2012-05-29
 */

class BibAPIModule extends APIModule {

    protected $id = 'bib';
    protected $vmin = 1;
    protected $vmax = 1;
    
    protected function initializeForCommand() {
		//instantiate controller
    	$controller = DataController::factory('BibDataController');
		
		//instantiate User controller
    	$User = DataController::factory('BibUserDataController');
		
        switch($this->command) {
			// Auftrag löschen (aus Benutzungskonto)
            case 'delete':
				$seq	= $this->getArg('id');
				$type	= $this->getArg('type');
				$userID	= $_SESSION['user']['uid'];
				
				$delete	= $User->deleteRequest($userID, $seq, $type);
				if ($delete === true) {
					$this->setResponse(1);
					$this->setResponseVersion(1);
				}
				else {
					$this->setResponse($delete);
					$this->setResponseVersion(1);
				}
				break;
				
			// Authentifizierung bei Login
			case 'check' :
				$status	= $User->isUser($_POST['username'], $_POST['passwort']);
				$url	= '';
				$error	= '';
						
				if ($status == '1') {
					// UserID holen
					$userID	= $User->getUserID();
					$User->setSession($userID);
					$a	= $_POST['a'];
					$docNr	= $_POST['docNr'];
					$seq	= $_POST['seq'];
					if (!empty($_POST['ref'])) $ref = $_POST['ref']; else $ref = 'action';
					
					$url	= $ref . '?a=' . $a . '&doc_nr=' . $docNr . '&seq=' . $seq;
				}
				else if ($status == '-1')
					$error	= 'Kein User gefunden';
				else if ($status == '0')
					$error	= 'Falsches Passwort';
					
				$this->setResponse(array('status'	=> $status,
										 'error'	=> $error,
										 'url'		=> $url));
								   
				$this->setResponseVersion(1);
				
				break;
				
			// Resultate nachladen
			case 'getResults' : 
				$id	= (int) $_POST['page'];
				$newID	= $id + 1;
				
				$EDS = DataController::factory('BibSearchDataController');
				$results	= $EDS->search($_POST['query'], $_POST['scope'], $id);
				//print_r($results);
				
				if (is_array($results['items'])) {
					$content	= '';
					foreach ($results['items'] as $id => $cont) {
						$content	.= '<li><a class="' . $cont['class'] . '" href="' . $cont['url'] . '">' . $cont['title'] . '<span class="smallprint">' . $cont['subtitle'] . '</span></a></li>';
					}
					
					$this->setResponse(array('content'	=> $content,
											 'id'		=> $newID));
				}
				else
					$this->setResponse(array('error'	=> 'none'));
					
				$this->setResponseVersion(1);
				
				break;
				
			// Hinweis ausblenden für Session
			case 'noShowConfirm' :
				if (empty($_SESSION['user']['showConfirm']) || empty($_COOKIE["showConfirm"])) {
					setcookie("showConfirm", 1, time()+(3600*24));  /* verfällt in 1 Stunde */
					$_SESSION['user']['showConfirm']	= 1;
					$this->setResponse(array('show'	=> 0));
				}
				else
					$this->setResponse(array('show'	=> 1));
					
				$this->setResponseVersion(1);	
				break;
        }
    }
	
	/**
	* Session vergeben
	* @param int UserID
	* @return boolean
	*/
	private function setSession($userID)
	{
		$_SESSION['user']	= array();
		$_SESSION['user']['key']	= crypt($_SERVER['HTTP_USER_AGENT'], APPLICATION_ID);
		$_SESSION['user']['uid']	= $userID;
		
		return true;
	}
}
?>