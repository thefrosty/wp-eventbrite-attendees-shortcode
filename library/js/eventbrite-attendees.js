jQuery(document).ready(
	function($) {
	
	$('#eas-toggle').css('cursor','pointer');
	$('#eas-toggle em').toggle();
	$('#eas-toggle').click(function(e) {
		$(this).find('em').toggle();
		$('#eventbrite-attendees-list').slideToggle();
		e.preventDefault();
	});	
			
	// Tabs
	$('div.tabbed div').hide();
	$('div.t1').show();
	$('div.tabbed ul.tabs li.t1 a').addClass('tab-current');
	$('div.tabbed ul li a').css('cursor','pointer');

	$('div.tabbed ul li a').click(function(){
		var thisClass = this.className.slice(0,2);
		$('div.tabbed div').hide();
		$('div.' + thisClass).show();
		$('div.tabbed ul.tabs li a').removeClass('tab-current');
		$(this).addClass('tab-current');
	});
	
	// #left a.question toggle span.hide
	$('#left  span.hide').hide();
	$('#left a.question').click(function() {
		$(this).next().next().toggleClass('hide').toggleClass('show').toggle(380);
	});
	
	
});
