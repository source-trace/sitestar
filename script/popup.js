$(function(){
	var name = $('#pp');
	function showAd(time){
		setTimeout(function(){$(name).show();},time);
	}
	
	function hideAd(time){
		setTimeout(function(){$(name).hide();},time);
	}
	$('.close').click(function(){
		$(name).hide();
	});

	showAd(1000);
	//hideAd(31000);

	function scrollAd(){
		var offset = $(window).height() - $(name).height() + $(document).scrollTop();
		$(name).animate({top:offset},{duration:800,queue:false});
	}	
					
	scrollAd();

	$(window).scroll(scrollAd);
});