if (typeof jQuery === 'undefined') {
	throw new Error('BEL-CMS requires jQuery')
}

(function($) {
	"use strict";

	_initFacebook();

	$('.alertAjaxForm').submit(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this), 'POST');
	});

	$('.alertAjaxLink').click(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this), 'GET');
	});

	// tooltip
	$('[data-toggle="tooltip"]').tooltip();
	// datepicker
	$(".datepicker" ).datepicker();

	if (typeof datepicker !== "undefined") {
		(function( factory ) {
			if ( typeof define === "function" && define.amd ) {

				// AMD. Register as an anonymous module.
				define([ "../datepicker" ], factory );
			} else {

				// Browser globals
				factory( jQuery.datepicker );
			}
		}(function( datepicker ) {

		datepicker.regional['fr'] = {
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin',
				'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
			monthNamesShort: ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin',
				'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'],
			dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
			dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
			dayNamesMin: ['D','L','M','M','J','V','S'],
			weekHeader: 'Sem.',
			dateFormat: 'dd-mm-yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		datepicker.setDefaults(datepicker.regional['fr']);

		return datepicker.regional['fr'];

		}));
	}

	if ($("#bel_cms_copyleft").length) {
		$('body').append('<a id="bel_cms_copyleft" style="display:none" href="https://bel-cms.be" title="BEL-CMS">Powered by Bel-CMS</a>');
	}

	if ($("textarea").hasClass("bel_cms_textarea_simple")) {
		_initTinymceSimple();
	}

	if ($("textarea").hasClass("bel_cms_textarea_full")) {
		_initTinymceFull();
	}

})(jQuery);

function _initTinymceSimple () {
	tinymce.init({
		selector: 'textarea.bel_cms_textarea_simple',
		browser_spellcheck: true,
		language: 'fr_FR',
		theme: 'modern',
		menubar: true,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table contextmenu paste code'
		],
		toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		content_css: '//www.tinymce.com/css/codepen.min.css'
	});
}
function _initTinymceFull () {
	tinymce.init({
		selector: 'textarea.bel_cms_textarea_full',
		browser_spellcheck: true,
		height: 300,
		language: 'fr_FR',
		theme: 'modern',
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
		],
		toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
		image_advtab: true,
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		]
	});
}

/*###################################
# Function Alert box
###################################*/
function bel_cms_alert_box (objet, type) {
	/* Get Url */
	if (objet.attr('href')) {
		var url = objet.attr('href');
	} else if (objet.attr('action')) {
		var url = objet.attr('action');
	} else if (objet.data('url')) {
		var url = objet.data('url');
	} else {
		alert('No link sets');
	}
	/* serialize data */
	if ($(objet).is('form')) {
		var dataValue  = $(objet).serialize();
	} else if (objet.data('data') == 'undefined'){
		var dataValue  = objet.data('data');
	}
	/* remove div#alrt_bel_cms is exists */
	if ($('#alrt_bel_cms').height()) {
		$('#alrt_bel_cms').remove();
	}
	$('body').append('<div id="alrt_bel_cms">Loading...</div>');
	$('#alrt_bel_cms').animate({ top: 0 }, 500);
	/* start ajax */
	$.ajax({
		type: type,
		url: url,
		data: dataValue,
		success: function(data) {
			var data = $.parseJSON(data);
			console.log(data);
			/* refresh page */
			if (data.redirect == undefined) {
				var redirect = false;
			} else {
				var redirect = true;
			}
			/* type color */
			if (data.type == undefined) {
				var type = 'blue';
			} else {
				var type = data.type;
			}
			/* link return */
			if (redirect) {
				setTimeout(function() {
					document.location.href=data.redirect;
				}, 3250);
			}
			/* add text */
			$('#alrt_bel_cms').addClass(type).empty().append(data.text);
		},
		error: function() {
			alert('Error function ajax');
		},
		beforeSend:function() {
			$('body').append('<div id="alrt_bel_cms">Loading...</div>');
			$('#alrt_bel_cms').animate({ top: 0 }, 500);
		},
		complete: function() {
			$('textarea').val('');
			$('input:text').val('');
			bel_cms_alert_box_end(3);
		}
	});
}

/*###################################
# Function end Alert box
###################################*/
function bel_cms_alert_box_end (time) {
	parseInt(time);

	var time = time * 1000;

	setTimeout(function() {
		$('#alrt_bel_cms').animate({ top: '-35px' }, 300, function() {
			$(this).remove();
		});
	}, time);
}
function _initFacebook() {
	var $body = $('body');

	var self = this;

	$body.append('<div id="fb-root"></div>');

	(function (d, s, id) {
		if (location.protocol === 'file:') {
			return;
		}
		var js = void 0,
			fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {
			return;
		}
		js = d.createElement(s);js.id = id;
		js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8";
		fjs.parentNode.insertBefore(js, fjs);
	})(document, 'script', 'facebook-jssdk');

	// resize on facebook widget loaded
	window.fbAsyncInit = function () {
		FB.Event.subscribe('xfbml.render', function () {
		   // self.debounceResize(); annuler manque jQuery ui
		});
	};
}
