<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>

<div class="space"></div>
<div class="status_bar">
<?php if (Notice::get('mod_static/msg')) { ?>
	<span id="adminflfrm_stat" class="status"><?php echo Notice::get('mod_static/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$mod_form = new Form('index.php', 'modform', 'check_mod_info');
$mod_form->setEncType('multipart/form-data');
$mod_form->p_open('mod_static', 'admin_mod');
?>
<table id="friendlinkform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'logo[id]', $curr_logo->id);
            echo Html::input('hidden', 'param[logo_img]', $p_logo['img_src']);
            echo Html::input('hidden', 'banner[id]', $curr_banner->id);
			echo Html::input('hidden', 'param[banner_img]', $p_banner['img_src']);
            echo Html::input('hidden', 'foot[id]', $curr_foot->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Logo'); ?></td>
            <td class="entry">
			<?php if($p_logo['img_src']){?>
			<img src="../<?php echo $p_logo['img_src']?>" ><br />
			<?php }?>
            <?php
            echo Html::input('file', 'logo_file', '', 
                '', $mod_form);
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
		 <tr>
            <td class="label"><?php _e('Size'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'param[logo_width]', $p_logo['img_width'], 
                'size=2', $mod_form);
            ?>
            &times;
            <?php
            echo Html::input('text', 'param[logo_height]', $p_logo['img_height'], 
                'size=2', $mod_form);
            ?>
            </td>
        </tr>
		 
		<tr>
            <td class="label"><?php _e('Banner'); ?></td>
            <td class="entry">
			<?php //if($p_banner['img_src']){?>
				<?php if(strpos($p_banner['flv_src'],'swf')){?>
			
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="<?php echo $p_banner['img_width'];?>" height="<?php echo $p_banner['img_height'];?>">
			<param name="movie" value="../<?php echo $p_banner['flv_src'];?>" />
			<param name="quality" value="high" />
			<embed src="../<?php echo $p_banner['flv_src'];?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="1004" height="256"></embed>
			</object>
			<br/>
				<?php }else{?>
					<img src="../<?php echo $p_banner['img_src'];?>"/>
				<?php }?>
				<br/>
			<?php //}?>
            <?php
            echo Html::input('file', 'banner_file', '', 
                '', $mod_form);
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT.'|swf';?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Size'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'param[banner_width]', $p_banner['img_width'], 
                'size=2', $mod_form);
            ?>
            &times;
            <?php
            echo Html::input('text', 'param[banner_height]', $p_banner['img_height'], 
                'size=2', $mod_form);
            ?>
            </td>
            </td>
        </tr>
		
		<tr>
            <td class="label"><?php _e('Footer'); ?></td>
            <td class="entry">
			<?php
            echo Html::textarea('param[html]', $p_foot['html'], 'rows="8" cols="108"')
            ?>
            </td>
        </tr>
		
		<tr>
            <td class="label"><?php _e('Background music'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'music_file', '', '', $mod_form);
            ?>
            <?php 
            $o_bgmusic = new BackgroundMusic();
            $bgmusic_items = $o_bgmusic->findAll();
            $once_play_checked = ($bgmusic_items[0]->play == 1) ? 'checked="checked"' : '';
            $loop_play_checked = ($bgmusic_items[0]->play == 2) ? 'checked="checked"' : '';
            $stop_play_checked = ($bgmusic_items[0]->play == 3) ? 'checked="checked"' : '';
            if(empty($once_play_checked) && empty($loop_play_checked) && empty($stop_play_checked))
            {
            	$once_play_checked = 'checked="checked"';
            }
            ?>
            <input type="radio" value="1" id="play_type" name="radio[play_type]" <?php echo $once_play_checked;?>><?php _e('once play');?>&nbsp;<input type="radio" value="2" id="play_type" name="radio[play_type]" <?php echo $loop_play_checked;?>><?php _e('loop play');?>
            &nbsp;<input type="radio" value="3" id="play_type" name="radio[play_type]" <?php echo $stop_play_checked;?>><?php _e('play sotp');?>
			<br />
			<?php _e('Supported file format'); ?>:<?php echo MUSIC_ALLOW_EXT;?>
			<br />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
			<br />
			<?php _e('Current music file');?>:<?php echo $bgmusic_items[0]->music_name;?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$mod_form->close();
$mod_form->writeValidateJs();
?>