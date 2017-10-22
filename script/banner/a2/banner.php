<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<LINK href="script/banner/a2/css/lanrentuku.css" type=text/css rel=stylesheet>
<div id="img_heightnum" style="display:none; width:0px; height:0px;"><?php echo $img_height;?></div>
<div id="img_widthnum" style="display:none; width:0px; height:0px;"><?php echo $img_width;?></div>

<SCRIPT type=text/javascript>
var img_heightnum;
img_heightnum=0;

img_heightnum=document.getElementById('img_heightnum').innerHTML*1;


var img_widthnum;
img_widthnum=0;

img_widthnum=document.getElementById('img_widthnum').innerHTML*1;
$(document).ready(function(){$('a[href="#"]').each(function(){$(this).attr('href','javascript:void(0)')});$('.perform li').each(function(){var o=$(this);$(this).find('.s').click(function(){var j=$(this).index();o.find('.s').removeClass('on').eq(j).addClass('on');o.find('.info').hide().eq(j).fadeIn(500)})});$('.artist_l li').each(function(m){$(this).find('a').css('top',-$(this).height());$(this).hover(function(){$(this).find('a').animate({'top':'0'},200)},function(){$(this).find('a').animate({'top':$(this).height()},{duration:200,complete:function(){$(this).css('top',-$(this).parent('li').height())}})})});$('#calendar td').live('mouseover',function(){$('#calendar td').removeClass('hover');$(this).addClass('hover')});$('.category_list .item').each(function(i){$(this).hover(function(){$('.category_list .item').removeClass('on').eq(i).addClass('on');$('.category_list ol').hide().eq(i).show()},function(){$(this).find('ol').hide();$(this).removeClass('on')})});$('.u_city_a li').each(function(i){$(this).click(function(){if(i==10)return false;$('.u_city_nav li').removeClass('on').eq(i).addClass('on');$('.u_city_nav p').hide().eq(i).show()})});var intIndexCity=0;var intHoverCity=0;$('.u_city_nav .more').click(function(){if(intIndexCity==1){$(this).removeClass('on');$('.s_citys').hide(200);intIndexCity=0}else{$(this).addClass('on');$('.s_citys').show(200);intIndexCity=1}return false});$('.s_citys').hover(function(){intHoverCity=1},function(){intHoverCity=0});$('body').bind('click',function(){if(intIndexCity==1&&intHoverCity==0){$('.s_citys').hide(200);$('.u_city_nav .more').removeClass('on');intIndexCity=0}});function scrollList(){if($('.scroll_txt li').length<=1)return;var temp=$('.scroll_txt li:last');temp.hide();$('.scroll_txt li:last').remove();$('.scroll_txt li:first').before(temp);temp.slideDown(500)}window.setInterval(scrollList,<?php echo $play_speed?$play_speed:5000;?>);$('.live_top li').each(function(i){$(this).hover(function(){$('.live_top li').removeClass('on').eq(i).addClass('on')})});$('.list_1 li').each(function(i){$(this).hover(function(){$('.list_1 li').removeClass('on').eq(i).addClass('on')})});$('.vote_m dd').each(function(i){$(this).click(function(){$('.vote_m dd').removeClass('on').eq(i).addClass('on')})});$('.tr_commend dl').each(function(i){$(this).hover(function(){$('.tr_commend dl').removeClass('on').eq(i).addClass('on')})});$('.ticketInfo .help').hover(function(){$('.minTips').fadeIn('fast')},function(){$('.minTips').fadeOut('fast')});$('.videoList li').hover(function(){$(this).addClass('on')},function(){$(this).removeClass('on')});$('.min_tip .tab_min_b a').each(function(i){$(this).click(function(){$('.tab_min_b a').removeClass('on').eq(i).addClass('on')})});$('.news_list li').hover(function(){$(this).addClass('on')},function(){$(this).removeClass('on')});$('.tr_pic_list li').hover(function(){$(this).addClass('on')},function(){$(this).removeClass('on')});$('.sift .expand').toggle(function(){$('#city').height(24);$(this).html('\u5c55\u5f00')},function(){$('#city').height('auto');$(this).html('\u6536\u7f29')});$('.buy_caption .tab_t a').each(function(i){$(this).click(function(){$('.buy_caption .tab_t a').removeClass('on').eq(i).addClass('on');$('.buy_caption dl').hide().eq(i).show()})});$('.vocal_list li .t .c7').click(function(){$(this).parent().parent().find('.t').show();$(this).parent().hide()})});$(document).ready(function(){var t=false;var str='';var speed=500;var w=img_widthnum;var n=$('#actor li').length;var numWidth=n*18;var _left=(w-(numWidth+26))/2;var c=0;$('#actor').width(w*n);$('#actor li').each(function(i){str+='<span></span>'});$('#numInner').width(numWidth).html(str);$('#imgPlay_cndns2 .mc').width(numWidth);$('#imgPlay_cndns2 .num').css('left',_left);$('#numInner').css('left',_left+13);$('#numInner span:first').addClass('on');function cur(ele,currentClass){ele=$(ele)?$(ele):ele;ele.addClass(currentClass).siblings().removeClass(currentClass)}$('#imgPlay_cndns2 .next').click(function(){slide(1)});$('#imgPlay_cndns2 .prev').click(function(){slide(-1)});function slide(j){if($('#actor').is(':animated')==false){c+=j;if(c!=-1&&c!=n){$('#actor').animate({'marginLeft':-c*w+'px'},speed)}else if(c==-1){c=n-1;$("#actor").css({"marginLeft":-(w*(c-1))+"px"});$("#actor").animate({"marginLeft":-(w*c)+"px"},speed)}else if(c==n){c=0;$("#actor").css({"marginLeft":-w+"px"});$("#actor").animate({"marginLeft":0+"px"},speed)}cur($('#numInner span').eq(c),'on')}}$('#numInner span').click(function(){c=$(this).index();fade(c);cur($('#numInner span').eq(c),'on')});function fade(i){if($('#actor').css('marginLeft')!=-i*w+'px'){$('#actor').css('marginLeft',-i*w+'px');$('#actor').fadeOut(0,function(){$('#actor').fadeIn(500)})}}function start(){t=setInterval(function(){slide(1)},<?php echo $play_speed?$play_speed:5000;?>)}function stopt(){if(t)clearInterval(t)}$("#imgPlay_cndns2").hover(function(){stopt()},function(){start()});start()});$(document).ready(function(){var isshowcity=false;var ishovercitys=false;$('.s_city .s').click(function(){if(isshowcity==false){$('.s_c_links').show(200);$(this).addClass('on');isshowcity=true}else{$('.s_c_links').hide(200);$(this).removeClass('on');isshowcity=false}return false});$('.s_c_links').hover(function(){ishovercitys=true},function(){ishovercitys=false});$('body').bind('click',function(){if(isshowcity==true&&ishovercitys==false){$('.s_c_links').hide(200);$('.s_city .s').removeClass('on');isshowcity=false}})});$(document).ready(function(){$('.sd').each(function(i){$(this).find('.hztitle').click(function(){$('.sd').eq(i).find('p').toggle()})});$(".hztitle").toggle(function(){$(this).addClass("hztitle-2")},function(){$(this).removeClass("hztitle-2")})});function artHeight(){var rh=$('.artists_r').height();var lh=$('.artists_l').height();var list=$('.artists_l .tab_min_in').height();var dh=rh-lh;if(dh>0){var h=lh+dh-12;$('.artists_l').height(h)}if(rh-list<90){$('.artists_l').height('auto')}}


</SCRIPT>
<style type="text/css">
#imgPlay_cndns2 UL{PADDING: 0px; MARGIN: 0px;}
#imgPlay_cndns2 li{ list-style:none;}
#imgPlay_cndns2 P{PADDING: 0px; MARGIN: 0px;}
#imgPlay_cndns2 A {COLOR: #333}
#imgPlay_cndns2 A:hover {COLOR: #e51a45; TEXT-DECORATION: none}

#imgPlay_cndns2 {
	OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; ZOOM: 1; POSITION: relative; HEIGHT: <?php echo $img_height;?>px;margin:0 auto; padding:0; text-align:center; 
}
#imgPlay_cndns2 .imgs IMG {
	BORDER-RIGHT: #dbdbdb 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: #dbdbdb 0px solid; PADDING-LEFT: 0px; PADDING-BOTTOM: 1px; BORDER-LEFT: #dbdbdb 0px solid; WIDTH: <?php echo $img_width;?>px; PADDING-TOP: 1px; BORDER-BOTTOM: #dbdbdb 0px solid
}
#imgPlay_cndns2 .imgs LI {
	FLOAT: left; POSITION: relative
}
#imgPlay_cndns2 .imgs {
	WIDTH: 5760px
}
#imgPlay_cndns2 .btn {
	RIGHT: 12px; OVERFLOW: hidden; WIDTH: 112px; BOTTOM: 12px; TEXT-INDENT: -9999px; POSITION: absolute; HEIGHT: 29px;
}
#imgPlay_cndns2 .btn A {
/*	BACKGROUND: url(images/bg.png) no-repeat;BACKGROUND-POSITION: 0px 0px; DISPLAY: block; WIDTH: 112px; HEIGHT: 29px;*/
}
#imgPlay_cndns2 .btn A:hover {
/*	BACKGROUND: url(images/bg.png) no-repeat;BACKGROUND-POSITION: 0px -30px;*/
}
#imgPlay_cndns2 .prev {
	LEFT: 1px; WIDTH: 46px; CURSOR: pointer; TEXT-INDENT: -9999px; POSITION: absolute; TOP: 110px; HEIGHT: 81px
}
#imgPlay_cndns2 .next {
	LEFT: 1px; WIDTH: 46px; CURSOR: pointer; TEXT-INDENT: -9999px; POSITION: absolute; TOP: 110px; HEIGHT: 81px
}
#imgPlay_cndns2 .next {
	BACKGROUND-POSITION: right 0px; RIGHT: 1px; LEFT: auto
}
#imgPlay_cndns2 .numcndns2 {
	DISPLAY: inline; LEFT: 400px; POSITION: absolute; TOP: <?php echo $img_height;?>px; HEIGHT: 19px
}
#imgPlay_cndns2 .numcndns2 SPAN {
	DISPLAY: inline-block; MARGIN: 0px 2px; OVERFLOW: hidden; WIDTH: 14px; CURSOR: pointer; LINE-HEIGHT: 0; HEIGHT: 13px
}
#imgPlay_cndns2 .numcndns2 SPAN.on {
	BACKGROUND-POSITION: 1px -83px
}
#imgPlay_cndns2 .numcndns2 .lc {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px;  FLOAT: left; PADDING-BOTTOM: 0px; WIDTH: 13px; PADDING-TOP: 3px; HEIGHT: 16px
}
#imgPlay_cndns2 .numcndns2 .mc {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FLOAT: left; PADDING-BOTTOM: 0px; WIDTH: 13px; PADDING-TOP: 3px; HEIGHT: 16px
}
#imgPlay_cndns2 .numcndns2 .rc {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FLOAT: left; PADDING-BOTTOM: 0px; WIDTH: 13px; PADDING-TOP: 3px; HEIGHT: 16px
}
#imgPlay_cndns2 .numcndns2 .mc {

}
#imgPlay_cndns2 .numcndns2 .rc {

}
#numInner {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; BACKGROUND: none transparent scroll repeat 0% 0%; PADDING-BOTTOM: 0px; PADDING-TOP: 3px; POSITION: absolute; TOP: <?php echo $img_height;?>px; TEXT-ALIGN: center
}

</style>

<div id=imgPlay_cndns2>
  <ul class=imgs id=actor>
     <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){
				if($urlhttp=='http://'){$urlhttp='';}
		?>

             <li><a href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>><img title="<?php echo $sp_title[$k];?>" alt="<?php echo $sp_title[$k];?>" src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>"  /></a>    </li>
               <?php
			}else{
			?>
           <li><a href="#" ><img title="<?php echo $sp_title[$k];?>" src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>"  /></a>    </li>
          <?php
			}
		
		$kkk++;
		}
		?>
   
  </ul>
 
  <div class=numcndns2>
    <p class=lc></p>
    <p class=mc></p>
    <p class=rc></p>
  </div>
  <div class=numcndns2 id=numInner></div>
  <div class=prev>上一张</div>
  <div class=next>下一张</div>
   <!---->
</div>
