$(document).ready(function(){
	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});

	$('.btn-toggle').click(function() {
		$(this).find('.btn').toggleClass('active').toggleClass('btn-default').toggleClass('btn-primary');
	});

	var i = 1;
	var tables 	=	new Array(
						'comments',
						'config',
						'config_pages',
						'groups',
						'mails_blacklist',
						'links',
						'page',
						'page_blog',
						'page_blog_cat',
						'page_forum',
						'page_forum_post',
						'page_forum_posts',
						'page_forum_threads',
						'page_shoutbox',
						'page_users',
						'page_users_profils',
						'page_users_social',
						'stats',
						'visitors',
						'widgets'
					);
	var nbTables = tables.length;

	$("#submit_bdd").click( function() {	// à la soumission du formulaire	
		var error = false;
		$(tables).each(function(i, e) {

			$.ajax({
				type: "POST",
				url: "?page=CreateSQL",
				async: false,
				data: "table="+e,
				success: function(m) {
					if (m == '<span class="glyphicon glyphicon-ok"></span>') {
						$('#' + e).empty().append(m);
					} else {
						$('#error_bdd').append(m);
						error = true;
					}
					i = i+1;
					if (error === false) {
						if (i == nbTables) {
							setTimeout(function() {
								window.location.href = "?page=user"; 
							}, 2000);
						}
					}
				},
				beforeSend:function() {
					$('#' + e).empty().append('<span class="glyphicon glyphicon-refresh spin"></span>');
				}
			});

		});

		return false;
	});

});