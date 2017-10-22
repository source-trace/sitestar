<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript">
function aaa()
{
	parent.$("#loading_overlay").css("display", "block");
    parent.$("#loading_anim").css("display", "block");
	window.location.href="index.php?_m=mod_template&_a=reset_tpl_data&_r=_ajax";
}
function bbb()
{
	parent.tb_remove();
}
</script>
<div style="text-align:left;font-size:12px;background-color:#FDEBD9;color:#FF6600;font-weight:700;height:50px;line-height:25px;margin:0 auto;width:400px;"><?php _e('Continue resetting will erase all your data! Are you SURE?'); ?></div>
<div style="width: 320px; height: 80px; margin: 0px auto;">
        <table style="display:inline;text-align:left;height:60px;margin:10px 20px;width:185px;" cellspacing="0" cellpadding="0" border="0" id="loginbox">
            <tbody>
        	</tbody>
        </table>

        <div style="height:25px;line-height:25px;margin:0 auto;text-align:center;width:200px;">
        	<span style="float:left"><input type="button" onclick="aaa();" value="<?php _e('Ok');?>"/></span>
        	<span style="float:right"><input type="button" onclick="bbb();" value="<?php _e('Cancel');?>"/></span>
        </div>
</div>	

