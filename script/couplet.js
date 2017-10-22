(function($) { 
    $.fn.jFloat = function(o) {
      o = $.extend({top:60,left:0,right:0,width:100,height:360,minScreenW:800,position:"left",allowClose:true}, o || {});
	  var h=o.height;
      var showAd=true;
      var fDiv=$(this);
      if(o.minScreenW>=$(window).width()){
      	  fDiv.hide();
      	  showAd=false;
      }else{
		   fDiv.css("display","block")
           var closeHtml='<span class="close">×</span>';
           switch(o.position){
               case "left":
                    if(o.allowClose){
                       fDiv.prepend(closeHtml);
					   $(".close",fDiv).click(function(){$(this).hide();fDiv.hide();showAd=false;})
					   h+=20;
					}
                    fDiv.css({position:"absolute",left:o.left+"px",top:o.top+"px",width:o.width+"px",height:h+"px",overflow:"hidden"});
                    break;
               case "right":
                    if(o.allowClose){
                       fDiv.prepend(closeHtml)
					   $(".close",fDiv).click(function(){$(this).hide();fDiv.hide();showAd=false;})
					   h+=20;
					}
                    fDiv.css({position:"absolute",left:"auto",right:o.right+"px",top:o.top+"px",width:o.width+"px",height:h+"px",overflow:"hidden"});
                    break;
            };
        };
        function ylFloat(){
            if(!showAd){return}
            var windowTop=$(window).scrollTop();
            if(fDiv.css("display")!="none")
                fDiv.css("top",o.top+windowTop+"px");
        };

      $(window).scroll(ylFloat);
      $(document).ready(ylFloat);
    }; 
})(jQuery);