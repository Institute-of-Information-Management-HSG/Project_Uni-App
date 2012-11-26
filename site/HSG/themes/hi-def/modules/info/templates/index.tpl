{extends file="findExtends:modules/info/templates/index.tpl"}

{block name="pageTitle"}Universität St. Gallen - Switch Projekt: Mobile Uni App{/block}

{block name='description'}Erweiterte Mobilität, allerorts verfügbares Internet und zusätzliche Funktionen durch Smart-Phones schaffen neue Möglichkeiten für den Einsatz mobiler Endgeräte. Lehre und Lernprozesse werden künftig vermehrt durch mobile Applikationen unterstützt und Prozesse effizienter gestaltet werden können. Ein entsprechendes Konzept und Portfolio für Hochschulen wird in diesem Projekt erarbeitet und getestet.{/block}

{block name="header"}
  	<div id="utility_nav">
    	<a href="http://www.unisg.ch" target="_blank">unisg.ch</a>
        &nbsp;|&nbsp;
        <a href="mailto:thomas.sammer@unisg.ch">Contact</a>
        &nbsp;|&nbsp;
        Share &nbsp;
		<a href="http://twitter.com/#!/mobileuniapp" title="Twitter" target="_blank"><img src="/modules/info/images/twitter.png" alt="twitter"></a>
    </div><!--/utility_nav-->
    
    <div id="logo">
    	<img src="/modules/info/images/site_logo.png" alt="Universitas Mobile Internet" border="0" />
    </div>
    
    <h1>mobileuniapp.net</h1>
    <p>
        provides mobile access to various IT-services offered by the University of St. Gallen. <b>This is an early beta pre-release and still in development.</b>
        
    </p>
{/block}
    
{block name="content"}
  	<div class="leftcol">
  	  	
		<h2>Project Uni-App</h2>
        <p>
        The aim of project Uni-App is to develop a generic framework that can be used to provide mobile access to IT-services offered by educational institutes from different mobile devices. 
		The framework is designed to serve various smartphones and tablet computers and will be accessible by native apps as well as by optimized websites. 
		Within the 2nd phase of our project we want to invite other educational institutes from Switzerland to join our project and use the framework to offer mobile access on their IT-services.
		<a href="http://www.switch.ch/de/aaa/projects/detail/UNISG.3" target="_blank">Click here for in detail project description (SWITCH Project UNISG.3).</a>
		<br>
		<br>
		Project management: <br><a href="mailto:thomas.sammer@unisg.ch">Thomas Sammer</a>, <a href="http://www.twitter.com/thfs" target="_blank">@thfs</a> (UNISG), <br><a href="mailto:andrea.back@unisg.ch">Prof. Andrea Back</a>, <a href="http://www.twitter.com/ABack" target="_blank">@ABack</a> (UNISG)	
        <br>
		</p>
        
		<h2>News-Ticker</h2>
        <p>
            
            <script src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			version: 2,
			type: 'profile',
			rpp: 4,
			interval: 6000,
			width: 'auto',
			height: 160,
			theme: {
			shell: {
			  background: '#007F2E',
			  color: '#ffffff'
			},
			tweets: {
			  background: '#F0F0F0',
			  color: '#000000',
			  links: '#007F2E'
			}
			},
			features: {
			scrollbar: false,
			loop: false,
			live: false,
			hashtags: true,
			timestamp: false,
			avatars: false,
			behavior: 'all'
			}
			}).render().setUser('MobileUniApp').start();
			</script>
            <br>
			
			<a href="http://www.flickr.com/photos/mobileuniapp" target="_blank">MobileUniApp on Flickr</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="http://twitter.com/#!/MobileUniApp" target="_blank">MobileUniApp on Twitter</a>
		</p>
		
		<h2>Funded by SWITCH and HSG InfoB</h2>
        <p>
        This project has been carried out as part of the "AAA/SWITCH – e-infrastructure for escience” programme under the leadership of SWITCH, the Swiss National Research and 
Education Network, and has been supported by funds from State Secretariat for Education and Research (SER).<br><br>
		<a href="http://www.switch.ch" target="_blank"><img src="http://www.switch.ch/export/system/modules/ch.SWITCH.ocms.www/resources/images/logo.gif" width="120"/></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="http://www.unisg.ch" target="_blank"><img src="/modules/info/images/unisg_logo Kopie.gif" width="180"/></a>
        <br>
		
		</p>
        
        <h2>Supported by</h2>
        <p>
        <a href="http://www.samsung.ch" target="_blank"><img src="/modules/info/images/samsung.png" width="150"/></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="http://www.atizo.com" target="_blank"><img src="/modules/info/images/atizo.png" width="100"/></a><br>
        </p>
		
		
        <div class="clr"></div>
        
        
        
        
    </div><!--/leftcol-->

    
    <div class="rightcol">
    	<h2>Multi Device Support</h2>
        <p>
            <a href="/">mobileuniapp.net</a> provides mobile access to various IT-services offered by the University of St. Gallen. 
			At the moment we support the following features accessible via the mobile browser of your smartphone or tablet computer. 
			Our solutions is based on the opensource framework developed by the <a href="http://itunes.apple.com/us/app/mit-mobile/id353590319?mt=8" target="_blank">MIT</a> and 
			provided by <a href="http://imobileu.org/" target="_blank">iMobileU</a> respectively <a href="http://modolabs.com/solutions-campus.php" target="_blank">modo labs</a>.
            In the upcoming weeks we will release a native app for Android and iOS and extend the features supported by our app.
        </p>
    	
    	<h2>Features</h2>
        
    	<table cellpadding="0" cellspacing="0" id="features">
          <tr>
            <td>
              <img src="/modules/info/images/icons/map.gif" alt="Map" width="40" height="40"/>
            </td>
            <td>
            <h2>Campus Map and Floor Plans</h2>
            <p>
            Not sure how to get to the room of your next lecture? You can use this feature to find almost any room of the University of St. Gallen using Google Maps and floor plans.
            </p>
            </td>
          </tr>
          <tr>
            <td>
              <img src="/modules/info/images/icons/transport.gif" alt="Transport" width="40" height="40"/>
            </td>
            <td>
            <h2>Public Transportation</h2>
            <p>
            Get departure times of public transportation to and from the University in any direction.
            </p>
            </td>
          </tr>

          <tr>
            <td>
              <img src="/modules/info/images/icons/news.gif" alt="News" width="40" height="50"/>
            </td>
            <td>
            <h2>News</h2>
            <p>
            Get the latest news from the University of St. Gallen (nws from the university, research topics and the student body).  </p>
            </td>
          </tr>

          <tr>
            <td>
              <img src="/modules/info/images/icons/mensa.gif" alt="Mensa" width="40" height="40"/>
            </td>
            <td>
            <h2>Cafeteria</h2>
            Want to know what`s on today`s cafeteria menu? Get this week`s menu.
            <p>
            </p>
            </td>
          </tr>
          
          <tr>
            <td>
              <img src="/modules/info/images/icons/veranstaltungen.gif" alt="Events" width="40" height="50"/>
            </td>
            <td>
            <h2>Events</h2>
            <p>
            Provides a complete list of all courses hold at the moment on the University of St. Gallen. Including time schedule, room and lecturer (similar to the HSG infoscreens).
            </p>
            </td>
          </tr>
          
          <tr>
            <td>
              <img src="/modules/info/images/icons/alumni.gif" alt="Alumni" width="50" height="30"/>
            </td>
            <td>
            <h2>Alumni</h2>
            <p>
            Get access to the HSG alumni mobile website.
            </p>
            </td>
          </tr>
          
        </table>
		
		<h2>Get Involved</h2>
        <p>
        <table border=0 style="padding: 5px;" cellspacing=0>
		<tr><td style="padding-left: 5px">
		<b>Subscribe to Mobile Uni-App</b>
		</td></tr>
		<form action="http://groups.google.com/group/mobile-uni-app/boxsubscribe">
		<input type=hidden name="hl" value="en">
		<tr><td style="padding-left: 5px;">
		Email: <input type=text name=email>
		<input type=submit name="sub" value="Subscribe">
		</td></tr>
		</form>
		<tr><td align=right>
		<a href="http://groups.google.com/group/mobile-uni-app?hl=en">Visit this group</a>
		</td></tr>
		</table>
        </p>
		
        <p>
          <a id="feedback" href="mailto:thomas.sammer@unisg.ch">
            <strong>Feedback and Cooperations</strong>
            <br />
            
            <span class="address">thomas.sammer@unisg.ch</span>.
          </a>
        </p>
        
        <p>
          <a id="feedback" href="mailto:florian.ickelsheimer@student.unisg.ch">
            <strong>Technical Questions</strong>
            <br />
            
            <span class="address">florian.ickelsheimer@student.unisg.ch</span>.
          </a>
        </p>
		

    </div><!--/rightcol-->


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
