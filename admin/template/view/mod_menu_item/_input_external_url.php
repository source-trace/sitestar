<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
function set_url() {
select_for_menu_item('<?php echo $type_text.' - '; ?>' + $("#weburl").attr("value"), $("#weburl").attr("value"));
}
//-->
</script>
<style type="text/css">
@import "template/css/popup.css";
</style>
<!--div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Please input URL'); ?></div></td>
			</tr>
		</tbody>
	</table>
</div-->
<div class="space"></div>
<table cellspacing="0" class="form_table">
    <tbody>
        <tr>
            <td class="label"><?php _e('Web URL'); ?></td>
            <td class="entry" width="140"><?php echo Html::input('text', 'weburl', 'http://', 'size="64"'); ?></td>
            <td class="entry" width="50"><a href="#" onclick="set_url(); return false;"><?php _e('OK'); ?></a></td>
            <td class="entry" width="50"><a href="#" title="" onclick="parent.$('#showContents').show();parent.$('#showContents1').remove();" style="color:#4372b0;"><?php _e('Back'); ?></a></td>
        </tr>
    </tbody>
</table>
