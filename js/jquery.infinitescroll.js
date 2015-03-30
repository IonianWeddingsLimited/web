$(function() {
	$("[id^=gallery]:not(:first)").hide();
	scrollalert(1);
	
});

function scrollalert($c){		
	var timg = $('#currentimg').attr("data-totimg");
	var nearToBottom = 100;
	if ($c <= timg) {
		if ($(window).scrollTop() + $(window).height() > $(document).height() - nearToBottom) { 
			//fetch new items
			$("#gallery"+$c).show(200);
			$c++;
			$("#load-more").remove();
			$('#dragable').append('<div id="load-more" class="load-more">Loading More...</div>');
		}
		var scrolling = setTimeout("scrollalert("+$c+")",2000);
	} 
	else {
	$("#load-more").remove();
	//$('#dragable').append('<div id="load-more" class="load-more"><a href="#top" nofollow>Top</a></div>');
	}
};
