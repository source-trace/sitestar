<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$listbj=0;
$currentlanguage=SessionHolder::get('_LOCALE');



if(($show_name) != 1){

if (sizeof($qqs) > 0) {
$listbj++;
?>

<div class="qq_list_con">
<ul>
<?php

 foreach ($qqs as $qq) {
	
    if($qq->category == 1){
?>
	<li><a href="msnim:chat?contact=<?php echo $qq->account; ?>"><img src="<?php echo P_TPL_WEB; ?>/images/MSN.jpg" alt="MSN"><?php if($show_acct) echo $qq->account; ?></a></li>

<?php } else if ($qq->category == 2) { ?>
	<li><a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" target=_blank><img alt="" src="http://amos.im.alisoft.com/online.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" border=0><?php if($show_acct) echo $qq->account; ?></a></li>

<?php } else if ($qq->category == 3) { ?>
	<li><a href="skype:<?php echo $qq->account; ?>?chat" target="_blank"><img border="0" src="./images/skypelogo.gif" /><?php if($show_acct) echo $qq->account; ?></a></li>

<?php }  else if ($qq->category == 4) { ?>
	<li><a href="icq:account?email=<?php echo $qq->account; ?>" target="_blank"><img border="0" src="images/icq.jpeg" height="25" width="25" /><?php if($show_acct){echo $qq->qqname; echo "(".$qq->account.")";} ?></a></li>

<?php }else if ($qq->category == 5) { 
				if($_SITE->s_locale=='zh_CN') $l_local = 'cn'; else $l_local = 'en';
?>
	<li>
	<a href="ymsgr:sendIM?<?php echo $qq->account; ?>" target="_blank"><img border=0 src="http://opi.yahoo.com/online?u=linwang31@yahoo.com&m=g&t=1&l=<?php echo $l_local;?>"><?php if($show_acct){echo $qq->qqname; echo "(".$qq->account.")";} ?></a>
	</li>

<?php } else { ?>
	<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq->account; ?>&site=qq&amp;Menu=yes" target=blank><img src="http://wpa.qq.com/pa?p=4:<?php echo $qq->account; ?>:4" align=absMiddle border=0><?php if($show_acct) echo $qq->account; ?></a></li>

<?php } } ?>

</ul>
</div>
<div class="list_bot"></div>

<div class="blankbar"></div>
<?php } ?>

<script>
<?php if($show_acct){ ?>
$(".qq_list_con>a").width($(".qq_list_con").width()/2);
<?php }
else{?>
$(".qq_list_con>a").width($(".qq_list_con").width()/6);
<?php }?>
</script>
<?php }?>




<?php
if(($show_acct != 1) && ($show_name) == 1){

if (sizeof($qqs) > 0) {
$listbj++;
?>

<div class="qq_list_con">
<ul>
<?php foreach ($qqs as $qq) {

    if($qq->category == 1){
?>
	<li><a href="msnim:chat?contact=<?php echo $qq->account; ?>"><img src="<?php echo P_TPL_WEB; ?>/images/MSN.jpg" alt="MSN"><?php if($show_name) echo $qq->qqname; ?></a></li>
    
<?php } else if ($qq->category == 2) { ?>
	<li><a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" target=_blank><img alt="" src="http://amos.im.alisoft.com/online.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" border=0><?php if($show_name) echo $qq->qqname; ?></a></li>

<?php } else if ($qq->category == 3) { ?>
	<li><a href="skype:<?php echo $qq->account; ?>?chat" target="_blank"><img border="0" src="./images/skypelogo.gif" /><?php if($show_name) echo $qq->qqname; ?></a></li>
  
<?php }  else if ($qq->category == 4) { ?>
	<li><a href="icq:account?email=<?php echo $qq->account; ?>" target="_blank"><img border="0" src="images/icq.jpeg" height="25" width="25" /><?php if($show_acct){var_dump($qq->qqname); echo "(".$qq->account.")";} ?></a></li>

<?php }else if ($qq->category == 5) { ?>
	<li>
	<a href="ymsgr:sendIM?<?php echo $qq->account; ?>" target="_blank"><img border=0 src="http://opi.yahoo.com/online?u=<?php echo $qq->account; ?>&m=g&t=1&l=cn"><?php if($show_acct) echo $qq->account; ?></a>
	</li>

<?php } else { ?>
	<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq->account; ?>&site=qq&amp;Menu=yes" target=blank><img src="http://wpa.qq.com/pa?p=4:<?php echo $qq->account; ?>:4" align=absMiddle border=0><?php if($show_name) echo $qq->qqname; ?></a></li>
   
<?php } } ?>
</ul>
	</div>
<div class="list_bot"></div>
<div class="blankbar"></div>
<?php } ?>

<script>
<?php if($show_name){ ?>
$(".qq_list_con>a").width($(".qq_list_con").width()/2);
<?php }
else{?>
$(".qq_list_con>a").width($(".qq_list_con").width()/6);
<?php }?>
</script>
<?php }?>




<?php
if(($show_acct == 1) && ($show_name) == 1){

if (sizeof($qqs) > 0) {
$listbj++;
?>

<div class="qq_list_con">
<ul>
<?php foreach ($qqs as $qq) {
	
    if($qq->category == 1){
?>
	<li><a href="msnim:chat?contact=<?php echo $qq->account; ?>"><img src="<?php echo P_TPL_WEB; ?>/images/MSN.jpg" alt="MSN"><?php echo $qq->qqname.'('.$qq->account.')'; ?></a></li>

<?php } else if ($qq->category == 2) { ?>
	<li><a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" target=_blank><img alt="" src="http://amos.im.alisoft.com/online.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" border=0><?php echo $qq->qqname.'('.$qq->account.')'; ?></a></li>

<?php } else if ($qq->category == 3) { ?>
	<li><a href="skype:<?php echo $qq->account; ?>?chat" target="_blank"><img border="0" src="./images/skypelogo.gif" /><?php echo $qq->qqname.'('.$qq->account.')'; ?></a></li>

<?php }  else if ($qq->category == 4) { ?>
	<li><a href="icq:account?email=<?php echo $qq->account; ?>" target="_blank"><img border="0" src="images/icq.jpeg" height="25" width="25" /><?php if($show_acct){ echo $qq->qqname; echo "(".$qq->account.")";} ?></a></li>

<?php }else if ($qq->category == 5) { 
				if($_SITE->s_locale=='zh_CN') $l_local = 'cn'; else $l_local = 'en';
?>
	<li>
	<a href="ymsgr:sendIM?<?php echo $qq->account; ?>" target="_blank"><img border=0 src="http://opi.yahoo.com/online?u=linwang31@yahoo.com&m=g&t=1&l=<?php echo $l_local;?>"><?php if($show_acct) echo $qq->account; ?></a>
	</li>

<?php } else { ?>
	<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq->account; ?>&site=qq&amp;Menu=yes" target=blank><img src="http://wpa.qq.com/pa?p=4:<?php echo $qq->account; ?>:4" align=absMiddle border=0><?php echo $qq->qqname.'('.$qq->account.')'; ?></a></li>

<?php } } ?>
</ul>
</div>
<div class="list_bot"></div>
<div class="blankbar"></div>
<?php } ?>

<?php }?>
<?php

if(!$listbj&&SessionHolder::get('user/s_role')=='{admin}'){
?>
<div class="qq_list_con">
<ul><li></li>
</ul>
</div>
<div class="list_bot"></div>
<div class="blankbar"></div>
<?php
}
$listbj=0;

?>


