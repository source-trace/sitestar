<div id="demo<?php echo $randstr;?>" class="marquee">
<div id="demo1<?php echo $randstr;?>">
<?php if($marquee_class=="pic"){?>
<?php foreach($curr_marquee as $marquee){
	// 21/09/2010
if (!empty($marquee->pic)) {
	$uri = Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link));
} else {
	$uri = Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link));
}
?>
<a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><img src="<?php echo $marquee->pic?>" border=0 width="160" height="120"  name="picautozoom" alt="<?php echo $marquee->title; ?>" ></a>
<?php }?>
<?php } elseif($marquee_class=='text') {?>
<?php foreach($curr_marquee as $marquee){
// 21/09/2010
if (!empty($marquee->pic)) {
	$uri = Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link));
} else {
	$uri = Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link));
}	
?>
<p><a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><?php echo Toolkit::substr_MB($marquee->title, 0, 15).((Toolkit::strlen_MB($marquee->title) > 15)?'...':'');?></a></p>
<?php }?>
<?php }else{?>
<?php foreach($curr_marquee as $marquee){
// 21/09/2010
if (!empty($marquee->pic)) {
	$uri = Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link));
} else {
	$uri = Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link));
}
?>
<a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><img src="<?php echo $marquee->pic?>" border=0 width="160" height="120" name="picautozoom" alt="<?php echo $marquee->title; ?>" ></a>
<p><a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><?php echo Toolkit::substr_MB($marquee->title, 0, 15).((Toolkit::strlen_MB($marquee->title) > 15)?'...':'');?></a></p>
<?php }?>
<?php }?>

</div>
<div id="demo2<?php echo $randstr;?>"></div>
</div>
<div class="list_bot"></div>
<script>
<!--
var speed=<?php echo $marquee_speed;?>;
var tab<?php echo $randstr;?>=document.getElementById("demo<?php echo $randstr;?>");
var tab1<?php echo $randstr;?>=document.getElementById("demo1<?php echo $randstr;?>");
var tab2<?php echo $randstr;?>=document.getElementById("demo2<?php echo $randstr;?>");

var showheight<?php echo $randstr;?>;
var loopheight<?php echo $randstr;?>;
var i<?php echo $randstr;?>;
var m<?php echo $randstr;?>,n<?php echo $randstr;?>;

showheight<?php echo $randstr;?>=tab<?php echo $randstr;?>.offsetHeight*1;//可视高度
loopheight<?php echo $randstr;?>=tab1<?php echo $randstr;?>.offsetHeight*1;//循环高度

if(loopheight<?php echo $randstr;?><=1){loopheight<?php echo $randstr;?>=1;}

m<?php echo $randstr;?>=Math.ceil(showheight<?php echo $randstr;?>/loopheight<?php echo $randstr;?>);

for(n<?php echo $randstr;?>=0;n<?php echo $randstr;?><m<?php echo $randstr;?>;n<?php echo $randstr;?>++){
tab2<?php echo $randstr;?>.innerHTML+=tab1<?php echo $randstr;?>.innerHTML;
}

tab<?php echo $randstr;?>.scrollTop=loopheight<?php echo $randstr;?>;
i<?php echo $randstr;?>=0;


function Marquee<?php echo $randstr;?>(){
if(i<?php echo $randstr;?>>=loopheight<?php echo $randstr;?>){
 i<?php echo $randstr;?>=0;
 }else{
 i<?php echo $randstr;?>++;
}
tab<?php echo $randstr;?>.scrollTop=loopheight<?php echo $randstr;?>-i<?php echo $randstr;?>;

}
var MyMar=setInterval(Marquee<?php echo $randstr;?>,speed);
tab<?php echo $randstr;?>.onmouseover=function() {clearInterval(MyMar)};//鼠标移上时清除定时器达到滚动停止的目的
tab<?php echo $randstr;?>.onmouseout=function() {MyMar=setInterval(Marquee<?php echo $randstr;?>,speed)};//鼠标移开时重设定时器
-->
</script>
