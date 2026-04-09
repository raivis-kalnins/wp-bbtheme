document.addEventListener("DOMContentLoaded", function() {
	const $ = jQuery.noConflict(),
			win = $(window).width(),
			d = new Date(),
			n = d.getFullYear(),
			page = $('html, body'),
			pageUrl = window.location.href,
			i = 0;

	// WP Remove <p> tags
	$('div.row p:empty').hide();
	$('div.row p').each(function() {
		var $this = $(this);
		if ($this.html() === "&nbsp;") {
			$this.remove();
		}
	});

	/**
	* Menu Scroll
	*/
	$('.site-header').removeClass('sticky-header');
	const lastScrollTop = 320;
	function stickyHeader() { 
		const scroll = $(window).scrollTop();
		const st = $(this).scrollTop();
		lastScrollTop = st;
		if (scroll >= 30) {
			$('.wp-nav-menu__sticky-header').addClass('sticky-header');
		} else {
			$('.wp-nav-menu__sticky-header').removeClass('sticky-header');
		}
		if (scroll >= 200) {
			$('.scroll-up').removeClass('hidden');
		} else {			
			$('.scroll-up').addClass('hidden');
		}
	}
	$(window).on('scroll', stickyHeader);
	$(window).on('resize', stickyHeader);
	$(document).ready(stickyHeader);
});
