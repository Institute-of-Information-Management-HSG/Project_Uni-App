{extends file="findExtends:modules/info/templates/index.tpl"}

{block name="pageTitle"}Universität St. Gallen - Die Uni-App der Uni{/block}

{block name='description'}{/block}

{block name="header"}
  	<div id="utility_nav">
    	<a href="http://www.unisg.ch" target="_blank">unisg.ch</a>
        &nbsp;|&nbsp;
        <a href="mailto:mobileuniapp@unisg.ch">Kontakt</a>
        &nbsp;|&nbsp;
		<a href="http://twitter.com/#!/mobileuniapp" title="Twitter" target="_blank">Share &nbsp;</a>
    </div><!--/utility_nav-->
    
    <div id="logo">
    	<img src="/modules/info/images/site_logo.png" alt="Universitas Mobile Internet" border="0" />
    </div>
    
    <h1>app.unisg.ch</h1>
    <p>
        Die App der Universität St.Gallen! Mit der App der Universität St. Gallen erhalten Sie mobil aktuelle Informationen zu Kursen, den Mitarbeitenden und dem Campus der Universität St.Gallen.</b>
        
    </p>
{/block}
    
{block name="content"}
    {$faq}

    
    <div class="leftcol">
    	<h2>Die App der Universität St.Gallen</h2>
        <p>
            Die App der Universität St.Gallen ist als mobile Webseite umgesetzt und bietet Studierenden, Mitarbeitenden und Besuchern der Universität St.Gallen nützliche Informationen rund um Kurse, Mitarbeitende und dem Campus der Universität.
			Über den Link <a href="http://app.unisg.ch/home">app.unisg.ch/home</a> können Sie auch ohne mobiles Gerät auf die App zugreifen.
<table width="100%" style="border:hidden"><tr><td>
		<p>
          <a id="feedback" href="http://app.unisg.ch/home">
            <strong>Zugriff auf die App</strong>
            <br />
            
            <span class="address">http://app.unisg.ch/home</span>.
          </a>
        </p>
	</td><td>
		<p>
          <a id="feedback" href="mailto:mobileuniapp@unisg.ch">
            <strong>Kontakt</strong>
            <br />
            
            <span class="address">mobileuniapp@unisg.ch</span>.
          </a>
        </p>
	</td></tr></table>	
	
    </p>
    	<h2>Funktionen der App</h2>
        
    	<table cellpadding="0" cellspacing="0" id="features">
          <tr>
            <td>
              <img src="/modules/home/images/news.png" alt="News" width="40" height="40"/>
            </td>
            <td>
            <h2>News</h2>
            <p>
            Hier erhalten Sie die neuesten Mitteilungen der Universität und der Forschungsplattform Alexandria.
            </p>
            </td>
          </tr>
          <tr>
            <td>
              <img src="/modules/home/images/classes.png" alt="Kalender" width="40" height="50"/>
            </td>
            <td>
            <h2>Kalender</h2>
            <p>
            Erfahren Sie, welche Kurse und öffentlichen Veranstaltungen aktuell in diesem Moment an der HSG stattfinden. Studierende können zusätzlich auf einen persönlichen Terminkalender mit den zugeteilten Kursen zugreifen.
            </p>
            </td>
          </tr>

          <tr>
            <td>
              <img src="/modules/home/images/directory.png" alt="Verzeichnis" width="40" height="40"/>
            </td>
            <td>
            <h2>Verzeichnis</h2>
            <p>
            Über den Punkt Verzeichnis erhalten Sie Zugriff auf die Kontaktdaten der HSG-Mitarbeitenden sowie Telefonnummern von Seminarräumen. Sie können mit einem Klick auf die Telefonnummer direkt einen Anruf starten.
			</p>
            </td>
          </tr>

          <tr>
            <td>
              <img src="/modules/home/images/map.png" alt="Map" width="40" height="40"/>
            </td>
            <td>
            <h2>Map</h2>
            Über das Map-Modul können Sie Gebäude inkl. Etagenpläne und wichtige Orte wie Bushaltestellen finden und diese auf einer Karte anzeigen lassen. Per Google Maps können Sie weiter eine Navigationshilfe zum jeweiligen Gebäude bzw. Ort starten.
            <p>
            </p>
            </td>
          </tr>
          
          <tr>
            <td>
              
			  <img src="/modules/home/images/transit.png" alt="Transport" width="40" height="40"/>
            </td>
            <td>
            <h2>Transport</h2>
            <p>
            Dieser Punkt beinhaltet die nächsten Abfahrtszeiten von Bussen vom Campus, Rufnummern von Taxiunternehmen und einen Zugriff auf den SBB-Reiseplaner (öffentliche Verkehrsmittel).
            </p>
            </td>
          </tr>
		  
		  <tr>
            <td>
			  <img src="/modules/home/images/library.png" alt="Biblio" width="40" height="40"/>
            </td>
            <td>
            <h2>Bibliothek</h2>
            <p>
            Hier erhalten Sie nützliche Informationen zur Bibliothek der Universität St.Gallen. Zusätzlich bietet das Modul eine mobile Literaturrecherche, und mobilen Zugriff auf die Funktionen des Bibliothekkatalogs und des Benutzerkontos.
            </p>
            </td>
          </tr>		  
		  
		  <tr>
            <td>
			  <img src="/modules/home/images/sports.png" alt="Sport" width="40" height="40"/>
            </td>
            <td>
            <h2>Unisport</h2>
            <p>
            Das Unisport Modul beinhaltet aktuelle Informationen zu Veranstaltungen, Infos zum Sportbüro und die Möglichkeit zur mobilen Trainingsanmeldung.
            </p>
            </td>
          </tr>
		  
		  <tr>
            <td>
			  <img src="/modules/home/images/mensa.png" alt="Mensa" width="40" height="40"/>
            </td>
            <td>
            <h2>Mensa</h2>
            <p>
            Hier finden Sie die aktuellen Wochenmenüpläne der A-Mensa.
            </p>
            </td>
          </tr>
          
          <tr>
            <td>
              <img src="/modules/home/images/alumni.png" alt="Alumni" width="40" height="40"/>
            </td>
            <td>
            <h2>Alumni</h2>
            <p>
            Dieser Punkt ist eine Weiterleitung auf die mobile Webseite von HSG-Alumni.
            </p>
            </td>
          </tr>
		  
		  <tr>
            <td>
              <img src="/modules/home/images/emergency.png" alt="Notfallinfos" width="40" height="40"/>
            </td>
            <td>
            <h2>Notfallinfos</h2>
            <p>
            Hier finden Sie Informationen zum Verhalten im Notfall und Schnellzugriff zu den wichtigsten Notfallnummern.
            </p>
            </td>
          </tr>
		  
		  <tr>
            <td>
              <img src="/modules/home/images/mail.png" alt="Mail" width="40" height="40"/>
            </td>
            <td>
            <h2>Mail</h2>
            <p>
            Hier werden Sie zum Webinterface für den E-Mailzugriff von Universitätsangehörigen weitergeleitet.
            </p>
            </td>
          </tr>
		  
		  <tr>
            <td>
              <img src="/common/images/search-button.png" alt="Suche" width="40" height="40"/>
            </td>
            <td>
            <h2>Suche</h2>
            <p>
            Suchen Sie Gebäude und News direkt über die Suchfeldeingabe.
            </p>
            </td>
          </tr>          
        </table>
		
		<h2>Multi Device Support</h2>
        <p>
            Da die App der Universität St.Gallen als Web-App umgesetzt ist, wird eine breite Palette an Geräten unterstützt. Um die App immer direkt von Ihrem Homescreen aus starten zu können und somit den gleichen Komfort zu erhalten wie bei einer nativen App, empfehlen wir das Anlegen eines Bookmarks am Homescreen. Sie können die App nun jederzeit über Ihre Datenverbindung aufrufen. Für den Zugriff aus dem Campus Wifi-Netz der HSG ist der Zugriff auch ohne das Anmelden über die Landing-Page möglich. Prinzipiell sind eine Vielzahl von mobilen Betriebssystemen kompatibel, die Uni-App wurde jedoch für folgende Betriebssysteme in den angegebenen Versionen optimiert: iOS (Versionen 3 & 4), Android (Versionen 2.2.1 und 2.2.2) und Windows Phone (Version 7.10 Mango).
Für Support- und alle weiteren Anfragen bezüglich der App, kontaktieren Sie uns bitte per <a href="mailto:mobileuniapp@unisg.ch">E-Mail</a>.
Über den Link <a href="http://app.unisg.ch/home">app.unisg.ch/home</a> können Sie auch ohne mobiles Gerät auf die App zugreifen.
	
    </p>

    </div><!--/leftcol-->


	<div class="clr"></div>
{/block}
		
        
{block name="footer"}
  
{/block}

{block name="footerJavascript"}
  {literal}
    var _sf_async_config={uid:2327,domain:"m.universitas.edu"};
    (function(){
      function loadChartbeat() {
        window._sf_endpt=(new Date()).getTime();
        var e = document.createElement('script');
        e.setAttribute('language', 'javascript');
        e.setAttribute('type', 'text/javascript');
        e.setAttribute('src',
           (("https:" == document.location.protocol) ? "https://s3.amazonaws.com/" : "http://") +
           "static.chartbeat.com/js/chartbeat.js");
        document.body.appendChild(e);
      }
      var oldonload = window.onload;
      window.onload = (typeof window.onload != 'function') ?
         loadChartbeat : function() { oldonload(); loadChartbeat(); };
    })();
    
      // Google master profile start
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' 				type='text/javascript'%3E%3C/script%3E"));
    
      try {
      var pageTracker = _gat._getTracker("UA-2923555-18");
      pageTracker._trackPageview();
      } catch(err) {}
      // Google master profile end
  {/literal}
{/block}
