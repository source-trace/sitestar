<?php
if($mar_direc_id=="right" || $mar_direc_id=="left"){
	?>
<style type="text/css">
#marquee_demo<?php echo $randstr;?> p{line_height:120px;*height:143px !important; margin:0;  padding-left:6px; padding-right:6px; float:left;}
</style>
<div id="marquee_demo<?php echo $randstr;?>" class="marquee_list">

<div id="mmmmddd<?php echo $randstr;?>" style=" float:left; width:32000px" >
<div id="marquee_product1<?php echo $randstr;?>"  style="float:left" >

<?php
foreach($curr_marquee as $marquee){
	if($marquee_class=="text"){
?>
<?php if($marquee->pic){?>
<p><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link)); ?>" title="<?php echo $marquee->title; ?>"><?php echo $marquee->title?></a></p>
<?php }else{?>
<p><a href="<?php echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link)); ?>" title="<?php echo $marquee->title; ?>"><?php echo Toolkit::substr_MB($marquee->title, 0, 15).((Toolkit::strlen_MB($marquee->title) > 15)?'...':''); ?></a></p>
<?php }?>
<?php }elseif($marquee_class=='pic') {?>
<p><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link)); ?>" title="<?php echo $marquee->title; ?>"><img src="<?php echo $marquee->pic?>" border=0 width="160" height="120"  name="picautozoom" alt="<?php echo $marquee->title; ?>"></a></p>
<?php }else{?>
<?php 
// 21/09/2010
if (!empty($marquee->pic)) {
	$uri = Html::uriquery2('mod_product', 'view', array('p_id' => $marquee->link));
} else {
	$uri = Html::uriquery2('mod_article', 'article_content', array('article_id' => $marquee->link));
}
?>
<p>
<font style="font-size:12px;"><a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><img src="<?php echo $marquee->pic?>" border=0 width="160" height="120" name="picautozoom" alt="<?php echo $marquee->title; ?>" ></a></font><br>
<font style="font-size:12px;"><a href="<?php echo $uri; ?>" title="<?php echo $marquee->title; ?>"><?php echo Toolkit::substr_MB($marquee->title, 0, 15).((Toolkit::strlen_MB($marquee->title) > 15)?'...':'');?></a></font></p>
<?php }?>
<?php }?>

</div>
<div id="marquee_product2<?php echo $randstr;?>"  style="float:left" ></div>
</div>
</div>
<div class="list_bot"></div>
<?php
	if($mar_direc_id=="right"){
?>

<script type="text/javascript">

var speed=<?php echo $marquee_speed;?>;
var marquee_product2<?php echo $randstr;?> = document.getElementById("marquee_product2<?php echo $randstr;?>");
var marquee_product1<?php echo $randstr;?> = document.getElementById("marquee_product1<?php echo $randstr;?>");
var marquee_demo<?php echo $randstr;?> = document.getElementById("marquee_demo<?php echo $randstr;?>");

var showwidth<?php echo $randstr;?>;
var loopwidth<?php echo $randstr;?>;
var i<?php echo $randstr;?>;
var m<?php echo $randstr;?>,n<?php echo $randstr;?>;

showwidth<?php echo $randstr;?>=marquee_demo<?php echo $randstr;?>.offsetWidth*1;
loopwidth<?php echo $randstr;?>=marquee_product1<?php echo $randstr;?>.offsetWidth*1;

if(loopwidth<?php echo $randstr;?><=1){loopwidth<?php echo $randstr;?>=1;}

m<?php echo $randstr;?>=Math.ceil(showwidth<?php echo $randstr;?>/loopwidth<?php echo $randstr;?>);

for(n<?php echo $randstr;?>=0;n<?php echo $randstr;?><m<?php echo $randstr;?>;n<?php echo $randstr;?>++){
marquee_product2<?php echo $randstr;?>.innerHTML+=marquee_product1<?php echo $randstr;?>.innerHTML;
}

marquee_demo<?php echo $randstr;?>.scrollLeft=loopwidth<?php echo $randstr;?>;
i<?php echo $randstr;?>=0;

function Marquee<?php echo $randstr;?>(){

if(i<?php echo $randstr;?>>=loopwidth<?php echo $randstr;?>){
 i<?php echo $randstr;?>=0;
 }else{
 i<?php echo $randstr;?>++;
}
marquee_demo<?php echo $randstr;?>.scrollLeft=loopwidth<?php echo $randstr;?>-i<?php echo $randstr;?>;

}
var MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed);
marquee_demo<?php echo $randstr;?>.onmouseover=function(){clearInterval(MyMar<?php echo $randstr;?>);}
marquee_demo<?php echo $randstr;?>.onmouseout=function(){MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed);}

</script>
<?php } else if($mar_direc_id=="left"){?>
<script type="text/javascript">


var speed=<?php echo $marquee_speed;?>;
var marquee_product2<?php echo $randstr;?> = document.getElementById("marquee_product2<?php echo $randstr;?>");
var marquee_product1<?php echo $randstr;?> = document.getElementById("marquee_product1<?php echo $randstr;?>");
var marquee_demo<?php echo $randstr;?> = document.getElementById("marquee_demo<?php echo $randstr;?>");

var showwidth<?php echo $randstr;?>;
var loopwidth<?php echo $randstr;?>;
var i<?php echo $randstr;?>;
var m<?php echo $randstr;?>,n<?php echo $randstr;?>;

showwidth<?php echo $randstr;?>=marquee_demo<?php echo $randstr;?>.offsetWidth*1;
loopwidth<?php echo $randstr;?>=marquee_product1<?php echo $randstr;?>.offsetWidth*1;

if(loopwidth<?php echo $randstr;?><=1){loopwidth<?php echo $randstr;?>=1;}

m<?php echo $randstr;?>=Math.ceil(showwidth<?php echo $randstr;?>/loopwidth<?php echo $randstr;?>);

for(n<?php echo $randstr;?>=0;n<?php echo $randstr;?><m<?php echo $randstr;?>;n<?php echo $randstr;?>++){
marquee_product2<?php echo $randstr;?>.innerHTML+=marquee_product1<?php echo $randstr;?>.innerHTML;
}

marquee_demo<?php echo $randstr;?>.scrollLeft=0;
i<?php echo $randstr;?>=0;

function Marquee<?php echo $randstr;?>(){

if(i<?php echo $randstr;?>>=loopwidth<?php echo $randstr;?>){
 i<?php echo $randstr;?>=0;
 }else{
 i<?php echo $randstr;?>++;
}
marquee_demo<?php echo $randstr;?>.scrollLeft=i<?php echo $randstr;?>;

}
var MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed);
marquee_demo<?php echo $randstr;?>.onmouseover=function(){clearInterval(MyMar<?php echo $randstr;?>);}
marquee_demo<?php echo $randstr;?>.onmouseout=function(){MyMar<?php echo $randstr;?>=setInterval(Marquee<?php echo $randstr;?>,speed);}

</script>

<?php }?>
<?php
}else if($mar_direc_id=="top"){
	include_once 'top.php';
}else if($mar_direc_id=="down"){
	include_once 'down.php';
}
?>