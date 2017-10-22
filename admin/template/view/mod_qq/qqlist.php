<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($qqs) > 0) {
?>
<div class="blockwrapper">
<?php foreach ($qqs as $qq) {
    if($qq->category == 1){
?>
	<a href="msnim:chat?contact=<?php echo $qq->account; ?>"><img src="<?php echo P_TPL?>/images/MSN.jpg" alt="MSN"><?php _e('Contact Me'); ?></a>
    <div class="space4"></div>
<?php } else if ($qq->category == 2) { ?>
	<a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" target=_blank><img alt="" src="http://amos.im.alisoft.com/online.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" border=0></a>
    <div class="space4"></div>
<?php } else if ($qq->category == 3) { ?>
	<a href="skype:<?php echo $qq->account; ?>?chat" target="_blank"><img border="0" src="./images/skypelogo.gif" /></a>
	<div class="space4"></div>	
<?php } else if ($qq->category == 4) { ?>
	<li><a href="icq:account?email=<?php echo $qq->account; ?>" target="_blank"><img border="0" src="images/icq.jpeg" height="25" width="25" /><?php if($show_acct) echo $qq->account; ?></a></li>

<?php }else if ($qq->category == 5) { ?>
	<li>
	<a href="ymsgr:sendIM?<?php echo $qq->account; ?>" target="_blank"><img border=0 src="http://opi.yahoo.com/online?u=<?php echo $qq->account; ?>&m=g&t=1&l=cn"><?php if($show_acct) echo $qq->account; ?></a>
	</li>

<?php } else { ?>
	<img src="http://wpa.qq.com/pa?p=4:<?php echo $qq->account; ?>:4" align=absMiddle border=0><a href="tencent://message/?uin=<?php echo $qq->account; ?>&amp;Menu=yes" target=blank><?php echo $qq->account; ?></a>
    <div class="space4"></div>
<?php } } } ?>
</div>