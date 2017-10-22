<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>

<?php if (Notice::get('mod_navigation/msg')) { ?>
	<span id="adminnavfrm_stat" class="status"><?php echo Notice::get('mod_navigation/msg'); ?></span>
<?php } ?>
<!--table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td width="15%"><b><?php _e('Upload Navigation File'); ?></b></td>
  <td><a href="<?php echo Html::uriquery('mod_navigation', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></td>
</tr>
</table-->

<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#warn').cluetip({splitTitle: '|',width: '300px',height:'35px'});
});
//-->
</script>

<?php
$nav_form = new Form('index.php', 'navform', 'check_nav_info');
$nav_form->setEncType('multipart/form-data');
$nav_form->p_open('mod_navigation', 'admin_create');
?>
<table id="navform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;margin-top:15px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
    		echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.location.href=\''.Html::uriquery('mod_navigation', 'admin_list').'\'"');
            echo Html::input('submit', 'submit', __('Upload'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td style="text-align:center;"><?php _e('Navigation File'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'nav_file', '', 
                '', $nav_form, 'RequiredTextbox', 
                __('Please select a navigation file to upload!'));
            ?>
            <BR />
			<?php _e('Supported file format'); ?>:<?php echo 'zip';?>
			<img id="warn" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Navigation file must contain \'index.html\'');?>" />
            </td>
        </tr>
    </tbody>
</table>
<?php
$nav_form->close();
$nav_form->writeValidateJs();
?>