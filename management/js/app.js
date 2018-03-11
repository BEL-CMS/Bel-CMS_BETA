$.navigation = $('nav > ul.nav');
$.panelIconOpened = 'icon-arrow-up';
$.panelIconClosed = 'icon-arrow-down';
'use strict';
$(document).ready(function($){

	$('.alertAjaxForm').submit(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this), 'POST');
	});

	$('.alertAjaxLink').click(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this), 'GET');
	});

	$.fn.alrt_bel_cms = function(type, text) {
		var current = $(this);

		$("#alrt_bel_cms").animate({
			top: "35px"
		});

		bel_cms_alert_box_end(1);
		return false;
	};

	$('.datepicker').datepicker({
		language: 'fr'
	});

	if ($("textarea").hasClass("bel_cms_textarea_simple")) {
		_initTinymceSimple();
	}

	if ($("textarea").hasClass("bel_cms_textarea_full")) {
		_initTinymceFull();
	}

  // Add class .active to current link
  $.navigation.find('a').each(function(){

	var cUrl = String(window.location).split('?')[0];

	if (cUrl.substr(cUrl.length - 1) == '#') {
	  cUrl = cUrl.slice(0,-1);
	}

	if ($($(this))[0].href==cUrl) {
	  $(this).addClass('active');

	  $(this).parents('ul').add(this).each(function(){
		$(this).parent().addClass('open');
	  });
	}
  });

  // Dropdown Menu
  $.navigation.on('click', 'a', function(e){

	if ($.ajaxLoad) {
	  e.preventDefault();
	}

	if ($(this).hasClass('nav-dropdown-toggle')) {
	  $(this).parent().toggleClass('open');
	  resizeBroadcast();
	}

  });

  function resizeBroadcast() {

	var timesRun = 0;
	var interval = setInterval(function(){
	  timesRun += 1;
	  if(timesRun === 5){
		clearInterval(interval);
	  }
	  window.dispatchEvent(new Event('resize'));
	}, 62.5);
  }

  /* ---------- Main Menu Open/Close, Min/Full ---------- */
  $('.sidebar-toggler').click(function(){
	$('body').toggleClass('sidebar-hidden');
	resizeBroadcast();
  });

  $('.sidebar-minimizer').click(function(){
	$('body').toggleClass('sidebar-minimized');
	resizeBroadcast();
  });

  $('.brand-minimizer').click(function(){
	$('body').toggleClass('brand-minimized');
  });

  $('.aside-menu-toggler').click(function(){
	$('body').toggleClass('aside-menu-hidden');
	resizeBroadcast();
  });

  $('.mobile-sidebar-toggler').click(function(){
	$('body').toggleClass('sidebar-mobile-show');
	resizeBroadcast();
  });

  $('.sidebar-close').click(function(){
	$('body').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
  });

  /* ---------- Disable moving to top ---------- */
  $('a[href="#"][data-top!=true]').click(function(e){
	e.preventDefault();
  });

});

/****
* CARDS ACTIONS
*/

$('.card-actions').on('click', 'a, button', function(e){
  e.preventDefault();

  if ($(this).hasClass('btn-close')) {
	$(this).parent().parent().parent().fadeOut();
  } else if ($(this).hasClass('btn-minimize')) {
	// var $target = $(this).parent().parent().next('.card-body').collapse({toggle: true});
	if ($(this).hasClass('collapsed')) {
	  $('i',$(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
	} else {
	  $('i',$(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
	}
  } else if ($(this).hasClass('btn-setting')) {
	$('#myModal').modal('show');
  }

});

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url) {

  /* ---------- Tooltip ---------- */
  $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

  /* ---------- Popover ---------- */
  $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}
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
	!function(a){a.fn.datepicker.dates.fr={days:["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"],daysShort:["dim.","lun.","mar.","mer.","jeu.","ven.","sam."],daysMin:["d","l","ma","me","j","v","s"],months:["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"],monthsShort:["janv.","févr.","mars","avril","mai","juin","juil.","août","sept.","oct.","nov.","déc."],today:"Aujourd'hui",monthsTitle:"Mois",clear:"Effacer",weekStart:1,format:"dd/mm/yyyy"}}(jQuery);
