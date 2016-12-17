(function($) {
	"use strict";

	$('.alertAjaxForm').submit(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this));
	});

	$('.alertAjaxLink').click(function(event) {
		event.preventDefault();
		bel_cms_alert_box($(this));
	});

	$.fn.alrt_bel_cms = function(type, text) {
		var current = $(this);

		$("#alrt_bel_cms").animate({
			top: "35px"
		});

		bel_cms_alert_box_end(1);
		return false;
	};

	$.fn.popuser = function() {
		var current = $(this);
		var title = current.attr('title');
		var width = current.width();
		$('#belcms_popup_user').css('left', width / 2);
		current.mouseover(function() {
			$('#belcms_popup_user').fadeIn(600,function(){
				$('#belcms_popup_user').css('display', 'block');
			});
		});
		$('#belcms_popup_user').mouseout(function() {
			$('#belcms_popup_user').fadeOut(600,function() {
				$('#belcms_popup_user').css('display', 'none');
			});
		});
    };

	// Tooltips
	$("[data-toggle='tooltip']").tooltip({container:"body"});

	// Popovers
	$("[data-toggle='popover']").popover();

	// PopUser

	$("[data-toggle='popuser']").popuser();

	// Alert Bel-CMS Top

	$("[data-toggle='alrt_bel_cms']").alrt_bel_cms();

	// Fixed Navigation
	$(window).scroll(function(){
        if ($(this).scrollTop() > 40) {
            $('body').addClass('header-scroll');
        } else {
			$('body').removeClass('header-scroll');
        }
    });


	// Search Modal
	$("[data-toggle='modal-search']").click(function () {
		$('body').toggleClass('search-open');
		return false;
	});

	$(".modal-search .close").click(function () {
		$('body').removeClass('search-open');
		return false;
	});


	// header option
	$("[data-toggle='fixed-header']").click(function () {
		$('body').toggleClass('fixed-header');
		return false;
	});


	// Progress Bars
	setTimeout(function(){
		$('.progress-animation .progress-bar').each(function() {
			var me = $(this);
			var perc = me.attr("aria-valuenow");
			var current_perc = 0;
			var progress = setInterval(function() {
				if (current_perc>=perc) {
					clearInterval(progress);
				} else {
					current_perc +=1;
					me.css('width', (current_perc)+'%');
				}
			}, 0);
		});
	},0);


	// Modal Effects
	$('.modal').on('shown.bs.modal', function (e) {
		var effect  = $(this).data("effect");
		if(effect) {
			$(this).find('.modal').addClass('animated ' + effect + '');
		}
	});

	$('.modal').on('hidden.bs.modal', function (e) {
		var effect  = $(this).data("effect");
		if(effect) {
			$(this).find('.modal').removeClass('animated ' + effect + '');
		}
	});


	// Responsive Nav
	$('.bar').click(function() {
		$('body').toggleClass('nav-open');

		$('#wrapper').click(function() {
			$('body').removeClass('nav-open');
		});
	});


	// Background Resize
	$('section.background-image.full-height').each(function () {
        $(this).css('height', $(window).height());
    });

	$(window).resize(function () {
        $('section.background-image.full-height').each(function () {
			$(this).css('height', $(window).height());
        });
    });


	// Parallax
	$(document).ready(function(){
		var $window = $(window);
		$('.parallax').each(function(){
			var $bgobj = $(this); // assigning the object

			$(window).scroll(function() {
				var yPos = -($window.scrollTop() / '3');

				// Put together our final background position
				var coords = '50% '+ yPos + 'px';

				// Move the background
				$bgobj.css({ backgroundPosition: coords });
			});
		});
	});


	// Nav
	$('nav .dropdown > a').click(function() {
		return false;
	});

	$('nav .dropdown-submenu > a').click(function() {
		return false;
	});

	$('nav ul li.dropdown').hover(function() {
		$(this).addClass('open');
		var effect = $(this).data("effect");
		if(effect) {
			$(this).find('.dropdown-menu').addClass('animated ' + effect + '');
		} else {
			$(this).find('.dropdown-menu').addClass("animated fast fadeIn");
		}
	}, function() {
		$(this).removeClass('open');
		var effect = $(this).data("effect");
		if(effect) {
			$(this).find('.dropdown-menu').removeClass('animated ' + effect + '');
		} else {
			$(this).find('.dropdown-menu').removeClass("animated fast fadeIn");
		}
	});

	$('nav .dropdown-submenu').hover(function() {
		$(this).addClass('open');
	}, function() {
		$(this).removeClass('open');
	});


	// Carousel
	function slideranimation( elems ) {
		var animEndEv = 'webkitAnimationEnd animationend';
		elems.each(function () {
			var $this = $(this),
			$animationType = $this.data('animation');
			$this.addClass($animationType).one(animEndEv, function () {
				$this.removeClass($animationType);
			});
		});
	}
	var $fullCarousel = $('#full-carousel'),
	$firstAnimatingElems = $fullCarousel.find('.item:first').find("[data-animation ^= 'animated']");
	slideranimation($firstAnimatingElems);
	$fullCarousel.carousel('pause');

	$fullCarousel.on('slide.bs.carousel', function (e) {
		var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
		slideranimation($animatingElems);
	});

	$('.full-carousel .item').each(function () {
		$(this).css('height', $(window).height()  - $('header').outerHeight() );
    });

	$(window).resize(function () {
        $('.full-carousel .item').each(function () {
			$(this).css('height', $(window).height()  - $('header').outerHeight() );
        });
    });

	$('.inactiveUntilOnLoad').removeClass('inactiveUntilOnLoad'); // for carousel kenburns effect

	$('.full-height').each(function () {
		$(this).css('height', $(window).height()  - $('header').outerHeight() );
    });

	$(window).resize(function () {
        $('.full-height').each(function () {
			$(this).css('height', $(window).height()  - $('header').outerHeight() );
        });
    });


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
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		datepicker.setDefaults(datepicker.regional['fr']);

		return datepicker.regional['fr'];

		}));
	}

	if ($("#bel_cms_copyleft").size() == 0) {
		$('body').append('<a id="bel_cms_copyleft" style="display:none" href="https://bel-cms.be" title="BEL-CMS">Powered by Bel-CMS</a>');
	}

})(jQuery);

/*###################################
# Function Alert box
###################################*/
function bel_cms_alert_box (objet) {
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
		type: 'POST',
		url: url,
		data: dataValue,
		success: function(data) {
			var data = $.parseJSON(data);
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
			//$('body').append('<div id="alrt_bel_cms">Loading...</div>');
			//$('#alrt_bel_cms').animate({ top: 0 }, 500);
		},
		complete: function() {
			$('textarea').val('');
			$("input:text").val('');
			bel_cms_alert_box_end(3);
		}
	});
}

/*###################################
# Function end Alert box
###################################*/
function bel_cms_alert_box_end (time) {
	parseInt(time);
	if (time <= 3) {
		setTimeout(function() {
			bel_cms_alert_box_end(time+1);
		}, 1000);
	} else {
		$('#alrt_bel_cms').animate({ top: '-35px' }, 300, function() {
			$(this).remove();
		});
	}
}
