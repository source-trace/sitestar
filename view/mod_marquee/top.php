<div id="marquee_demo<?php echo $randstr;?>" class="marquee">
<div id="marquee_product1<?php echo $randstr;?>">
<?php if($marquee_class=="pic"){?>
<?php foreach($curr_marquee as $marquee){
	// 21/09/2010
if (!empty($marquee->pic)) {
	$uri = Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link));
} else {
	$uri = Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link));
}
?>
<a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><img src="<?php echo $marquee->pic?>" border=0 width="160" height="120" name="picautozoom" alt="<?php echo $marquee->title; ?>" ></a>
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

<?php  foreach($curr_marquee as $marquee){
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
<div id="marquee_product2<?php echo $randstr;?>"></div>
</div>
<div class="list_bot"></div>
<script>
<!--
var speed=<?php echo $marquee_speed;?>;
var marquee_product2<?php echo $randstr;?> = document.getElementById("marquee_product2<?php echo $randstr;?>");
var marquee_product1<?php echo $randstr;?> = document.getElementById("marquee_product1<?php echo $randstr;?>");
var marquee_demo<?php echo $randstr;?> = document.getElementById("marquee_demo<?php echo $randstr;?>");

var showheight<?php echo $randstr;?>;
var loopheight<?php echo $randstr;?>;
var i<?php echo $randstr;?>;
var m<?php echo $randstr;?>,n<?php echo $randstr;?>;

showheight<?php echo $randstr;?>=marquee_demo<?php echo $randstr;?>.offsetHeight*1;//可视高度
loopheight<?php echo $randstr;?>=marquee_product1<?php echo $randstr;?>.offsetHeight*1;//循环高度
if(loopheight<?php echo $randstr;?><=1){loopheight<?php echo $randstr;?>=1;}

m<?php echo $randstr;?>=Math.ceil(showheight<?php echo $randstr;?>/loopheight<?php echo $randstr;?>);

for(n<?php echo $randstr;?>=0;n<?php echo $randstr;?><m<?php echo $randstr;?>;n<?php echo $randstr;?>++){
marquee_product2<?php echo $randstr;?>.innerHTML+=marquee_product1<?php echo $randstr;?>.innerHTML; //克隆demo1为demo2
}

marquee_demo<?php echo $randstr;?>.scrollTop=0;
i<?php echo $randstr;?>=0;

function Marquee<?php echo $randstr;?>(){
if(i<?php echo $randstr;?>>=loopheight<?php echo $randstr;?>){
 i<?php echo $randstr;?>=0;
 }else{
 i<?php echo $randstr;?>++;
}
marquee_demo<?php echo $randstr;?>.scrollTop=i<?php echo $randstr;?>;
	
}
var MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed);
marquee_demo<?php echo $randstr;?>.onmouseover=function() {clearInterval(MyMar<?php echo $randstr;?>)};//鼠标移上时清除定时器达到滚动停止的目的
marquee_demo<?php echo $randstr;?>.onmouseout=function() {MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed)};//鼠标移开时重设定时器
-->
</script>
