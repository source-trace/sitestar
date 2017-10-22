<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_media', 'save_logo');
?>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
	<tfoot>
		<tr>
            <td colspan="2">
            <?php
			$curr_siteinfo_id='';
			if(isset($curr_siteinfo->id)){
				$curr_siteinfo_id=$curr_siteinfo->id;
			}
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'si[id]', $curr_siteinfo_id);
            echo Html::input('hidden', 'si[s_locale]', $lang_sw);
            ?>
            </td>
        </tr>
	</tfoot>
	<tbody>
		<?php 
			echo Html::input('hidden', 'logo[id]', $curr_logo->id);
            echo Html::input('hidden', 'param[logo_img]', $p_logo['img_src']);
		?>
		<tr>
			<td class="label"><?php _e('Logo'); ?></td>
			<td class="entry">
			<?php 

			if($p_logo['img_src']){
				if(preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $img_src)){
			?>
			<img src="../<?php echo $p_logo['img_src']?>" ><br />
			<?php 
			}else{
			?>
			<object <?php echo $str_img_width;?> <?php echo $str_img_height;?> codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
<param value="../<?php echo $p_logo['img_src']; ?>" name="movie">
<param value="high" name="quality">
<param value="transparent" name="wmode">
<embed <?php echo $str_img_width;?> <?php echo $str_img_height;?> wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" quality="high" src="../<?php echo $p_logo['img_src']; ?>">
</object>
<BR />
			<?php 
			}
			}
			?>
            <?php
            echo Html::input('file', 'logo_file', '','', $mod_form);
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT."|swf";?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
		</tr>
		<tr>
			<td class="label"><?php _e('Size'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'param[logo_width]', $p_logo['img_width'], 
                'class="textinput" style="width:40px;"', $mod_form);
            ?>
            &times;
            <?php
            echo Html::input('text', 'param[logo_height]', $p_logo['img_height'], 
                'class="textinput" style="width:40px;"', $mod_form);
            ?>
            </td>
		</tr>
	</tbody>
</table>
</div>
<?php
$sinfo_form->close();
$sinfo_form->writeValidateJs();
?>