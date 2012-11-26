// URL für AJAX abfragen
var URL	= 'http://' + document.location.hostname;

/**
* Funktionen beim Seitenaufruf
* Globale Eventhandler
*/
$(document).ready(function() {
	var url	= document.location.href;
	var id	= url.split("#")[1];
	
	if ($('#' + id) != undefined)
		show(id);
		
	//=========================================
	
	/**
	* Löschlinks
	*/
	$('a.delete').click(function() {
		var aID		= this.id;
		
		var divID	= $('div.volumeList').prev('a');
		var url		= document.location.href.substr(-1, 1);
		zahl	= parseInt(divID[url].innerHTML.match(/[0-9]/));
		
		$.ajax({
			url: URL + "/rest/bib/delete?id=" + this.id + "&type=" + this.title,
		}).done(function (data) {
			var json	= jQuery.parseJSON(data);
			if (json.response == 1) {
				alert('Auftrag wurde gelöscht');
				divID[url].innerHTML	= divID[url].innerHTML.replace(/[0-9]/, (zahl - 1)); // Anzeige ändern
				$('#t' + aID).fadeOut('fast'); // Zeile rauslöschen
			}
			else
				alert(json.response['title']);
		});
		
		return false;
	});
	
	//=========================================
	
	/**
	* Formular Kopierauftrag überprüfen
	*/
	$("form[name=copy]").submit(function(){
		// Alle Labels durchgehen, span.mandatory vorhanden?
		var mandatory	= $('span.mandatory').next();
		
		for (var i = 0; i < mandatory.length; i++) {
			if (mandatory[i].type == "text" & mandatory[i].value.length == 0) { // Textfelder
				var error	= $(mandatory[i]).next();
				error.show();
				$(mandatory[i]).css('border-color', '#900');
				return false;
			}
		}
	});
	
	/**
	* Erstes Formularfeld aktivieren
	*/
	var inputs	= $('form input');
	if (inputs[0] != undefined)
		inputs[0].focus();

	/**
	* Loginformular absenden
	*/
	$("#login").submit(function(){
		$("#darkcloud").show();						
		url	= URL + "/rest/bib/check";
		
		$.post(url, $("#login").serialize(),
			function(data) {
				$("#darkcloud").hide();
				var json	= jQuery.parseJSON(data);
				if (json.response['status'] == '1')
					window.location	= json.response['url'];
				else {
					$('.error').text(json.response['error']);
				}
			}
		);
		return false;
	});


	/**
	* Select
	*/
	$("#select-filter").change(function() {
		var newText	= $("#filteroutput").text().split(":")[0] + ': ' + this.options[this.selectedIndex].text;
		$("#filteroutput").text(newText);
	});
	if ($('#select-filter').length > 0) {
		var newText	= $("#filteroutput").text().split(":")[0] + ': ';
		
		$("#select-filter :selected").each(function () {
			newText += $(this).text();
		});
		//var newText	= $("#filteroutput").text().split(":")[0] + ': ' + $('#select-filter option:selected')[0].text();
		$("#filteroutput").text(newText);
	}
	
	/**
	* Externe Links bei Suchresultaten, Hinweis
	*/
	$('a.extern').click(function(event) {
		event.preventDefault();
		var sHref	= $(this).attr('href');
		var goAhead	= false;
		 $.ajax({
			type: "get",
			url: URL + "/rest/bib/noShowConfirm",
			success: function(data) {
				var json	= jQuery.parseJSON(data);
				if (json.response['show'] == 0) {
					if (confirm('Externer Link. Sie verlassen die Uni App. Die nachfolgende Seite ist nicht für mobile Ansichten optimiert. Für vollständigen Zugriff benötigen Sie eine VPN Verbindung oder müssen im Universitäts-Netzwerk (WLAN) sein.'))
						goAhead = true;
				}
				else
					goAhead = true;
					
				if (goAhead === true) {
					window.location.href	= sHref;
				}
			}
			
		});
		
			
	});
	
	
	/**
	* Fancy LazyLoad Stuff
	*/
	if ($("#page").length > 0) {
		 $(window).scroll(function() {
			var minus	= $(window).height() + 2;
			//console.log(($(document).height() - minus) + ' < ' + $(window).scrollTop() + ' < ' + ($(document).height() - $(window).height()));
			
			if ($(window).scrollTop() > ($(document).height() - minus) && $(window).scrollTop() < ($(document).height() - $(window).height())) {
				loadNextPage();
			}
		});
	}
	
	$('#loadNext').click(function(event) {
		event.preventDefault();
		loadNextPage();
	});
});

function loadNextPage() {
	$("#darkcloud").show();
	var id = $("#page").val();
	var scope	= $("#select-filter").val();
	var query	= $("#query").val();
	$.ajax({
		type: "post",
		url: URL + "/rest/bib/getResults",
		data: {"page":id, "scope":scope, "query": query},
		success: function(data) {
			var json	= jQuery.parseJSON(data);
			if(json.response['error'] == 'none') {
				$("#results ul.nav").append('<li>Keine weiteren Ergebnisse mehr </li>');
				$("#loadNext").hide();
				//$("#loader").html("Keine Datensätze mehr vorhanden!");
			}
			else {
				$("#results ul.nav").append(json.response['content']);
				$("#page").val(json.response['id'])
				$("#darkcloud").hide();
			}
		}
	});
}


/*
* Exemplarliste bei mehrbändigen Werken ein-/ausblenden
* @todo Globale Eventhandler
*/
function show(id) {
	var allLists	= $('div.volumeList:visible');
	$.each(allLists, function(key, value) {
		if (value.id != id)
			$('#' + value.id).slideUp();
	});
	$('#' + id).slideToggle('fast', function() {
		var url	= document.location.href;
		var urlTrue	= url.split("#")[0];
		if ($(this).is(":hidden"))
			document.location.href	= urlTrue + "#";
		else
			document.location.href	= urlTrue + "#" + id;
	});
}