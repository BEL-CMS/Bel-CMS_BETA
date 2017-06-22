(function($) {

	var width = $('#bel_cms_widgets_user_log').width();

	if (width < 200) {
		$('#bel_cms_widgets_user_log a').css({
			display: 'block',
			width: '100%',
			float: 'none'
		});
	}

})(jQuery);