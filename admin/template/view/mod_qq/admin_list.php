<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!--
<style type="text/css">
#onlineform{width:430px;float:left;}
</style>
-->
<script type="text/javascript" language="javascript">
<!--
function changeFont() {
	$('#param_service_').html('');
}
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminqqlst_stat");
    if (o_result.result == "ERROR") {
        document.forms["contactusform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["contactusform"].reset();
    
    document.getElementById("adminqqlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminqqlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminqqlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_qq(q_id) {
	if (confirm("<?php _e('Delete the selected IM account?'); ?>")) {
	    var stat = document.getElementById("adminqqlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected IM account...'); ?>";
		_ajax_request("mod_qq", 
			"admin_delete", 
	        {
	            q_id:q_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_qqs(){
	var arr = document.getElementsByName("qq");
	var str="";
	for (var i = 0; i < arr.length; i++){
		var e = arr[i];
		if (e.checked){
			str = e.value + "_" + str;
		}
	}
	if(str.length < 1) {
		alert("<?php _e('Please select items to be deleted!'); ?>");
	} else {
		delete_qq(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("qq");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}
function setFloat(obj) {
	if (obj.checked == true) {
		$('#online_input').css('display','inline-block');
	} else {
		$('#online_input').css('display','none');
		//$('#online_input').find('input:first').val("<?php _e('You can input online customer service title here');?>");
		$('#hidqq_online').val('none');
		$('#onlineform').submit();
	}
}
window.onload = function() {
	var status = "<?php echo QQ_ONLINE;?>";
	if (status == '1') document.getElementById('online_input').style.display = 'inline-block';
	
	// for sitestarv1.3 online service
	if ($('#sparam_QQ_ONLINE_TITLE_').val() == '') {
		$('#sparam_QQ_ONLINE_TITLE_').css('color','#C6C6C6');
		$('#sparam_QQ_ONLINE_TITLE_').val("<?php _e('You can input online customer service title here');?>");
	}
	if ($('#sparam_QQ_ONLINE_TITLE_').val() == "<?php _e('You can input online customer service title here');?>") {
		$('#sparam_QQ_ONLINE_TITLE_').css('color','#C6C6C6');
	}
	$('#sparam_QQ_ONLINE_TITLE_').focus(function(){
		if ($('#sparam_QQ_ONLINE_TITLE_').val() == "<?php _e('You can input online customer service title here');?>") {
			$('#sparam_QQ_ONLINE_TITLE_').val("");
		}
		$('#sparam_QQ_ONLINE_TITLE_').css('color','#444444');
	});
	$('#sparam_QQ_ONLINE_TITLE_').blur(function(){
		if ($('#sparam_QQ_ONLINE_TITLE_').val() == '') {
			$('#sparam_QQ_ONLINE_TITLE_').css('color','#C6C6C6');
			$('#sparam_QQ_ONLINE_TITLE_').val("<?php _e('You can input online customer service title here');?>");
		} else {
			$('#sparam_QQ_ONLINE_TITLE_').css('color','#444444');
		}
	});
}
//-->
</script>
<ul style="margin-left:1px;height:51px;">
	<li><a class="icontj" href="<?php echo Html::uriquery('mod_qq', 'admin_add'); ?>" title=""><?php _e('Add IM account'); ?></a></li>
	<li><a class="iconsc" href="javascript:void(0)" onclick="delete_qqs();"><?php _e('Delete Selected'); ?></a></li>
</ul>
<div class="status_bar">
	<span id="adminqqlst_stat" class="status" style="display:none;"></span>
</div>

<?php
$sinfo_form = new Form('index.php', 'contactusform', 'check_sinfo_info');
$sinfo_form->p_open('mod_static', $next_action, '_ajax');
?>
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-left:15px;width:98%;">
    <tfoot>
        <tr>
            <td colspan="2" style="background:none;">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
		<tr>
            <td class="label" style="width:20%;"><?php _e('service'); ?></td>
            <td class="entry"><span><?php _e("Input 53 service code please!"); ?></span>
            <?php
            if(!empty($service)) {
            	echo Html::textarea('param[service]', $service, 'rows="8" cols="108" class="textinput" style="width:450px;"');
            }else {
            	echo Html::textarea('param[service]', '', 'rows="8" cols="108" class="textinput" style="width:400px;" onclick="changeFont();"');
            }
            ?>
            </td>
        </tr>
		<!--tr>
        	<td class="label" style="width:20%;"><?php _e('Memo'); ?></td>
        	<td><span style="line-height:22px;width:450px;display:inline-block;"><?php _e('53kf_remark');?></span></td>
        </tr>
		<tr>
        	<td class="label" style="width:20%;" rowspan="4"><?php _e('service Provider'); ?></td>
        	<td><a href="http://www.53kf.com" target="_blank">http://www.53kf.com</a></td>
        </tr-->
        
    </tbody>
</table>
<?php
$sinfo_form->close();
$running_msg = __('Saving content...');
$custom_js = <<<JS
$("#adminqqlst_stat").css({"display":"block"});
$("#adminqqlst_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sinfo_form->addCustValidationJs($custom_js);
$sinfo_form->writeValidateJs();
?>
<div style="height:28px;line-height:28px;font-weight:bold;font-size:larger;padding-left:20px;color:#4372B0;margin-left:2px;"><?php _e('Instant Message');?></div>

<div style="padding-left:20px;line-height:36px;height:36px;background:#F6F6F4;margin-left:2px;">

<?php
$online_form = new Form('index.php', 'onlineform');
$online_form->p_open('mod_qq', 'admin_list');
echo Html::input('hidden','hidqq_online','');
echo Html::input('checkbox','sparam[QQ_ONLINE]','1',Toolkit::switchText(QQ_ONLINE,array('0'=>'','1'=>'checked="checked"')).' onclick="setFloat(this)"'); ?>&nbsp;<?php _e('Enabled floating style');
echo '&nbsp;&nbsp;<img class="title" src="template/images/answer1.gif" alt="help" title="'.__('You can see the floating style in the preview mode').'" />&nbsp;&nbsp;';

?>
<input type="hidden" name="tjok" value="1" /><span id="online_input" style="display:none;">
<?php
$options = array('left' => __('left'), 'right' => __('right'));
echo Html::select('sparam[QQ_ONLINE_POS]', $options, QQ_ONLINE_POS);
echo '<img class="title" src="template/images/answer1.gif" alt="help" title="'.__('Floating position').'" />&nbsp;&nbsp;&nbsp;&nbsp;';
echo Html::input('checkbox','sparam[QQ_ONLINE_SHOW]','1',Toolkit::switchText(QQ_ONLINE_SHOW,array('0'=>'','1'=>'checked="checked"')).' onclick="setShow(this)"'); ?>&nbsp;<?php _e('Always display');
echo '&nbsp;&nbsp;<img class="title" src="template/images/answer1.gif" alt="help" title="'.__('Floating window show or display').'" />&nbsp;&nbsp;';

echo Html::input('text','sparam[QQ_ONLINE_TITLE]',$qqstitle,'style="width:180px;" autocomplete="off"');
echo Html::input('submit', 'onlinesubmit', __('Save'));?></span>
<?php $online_form->close();?>
</div>
<form id="articleorderform" method="post" action="index.php" name="articleorderform">



<input id="_m" type="hidden" value="mod_qq" name="_m">
<input id="_a" type="hidden" value="admin_order" name="_a">
<input id="_r" type="hidden" value="_ajax" name="_r">
<table class="form_table_list" id="admin_qq_list" border="0" cellspacing="1" cellpadding="0" style="margin-top:0;line-height:24px;">
	<thead>
		<tr>
		    <th width="13%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Account'); ?></td>
            <th width="23%"><?php _e('Preview'); ?></th>
            <th width="13%"><?php _e('Order');?></th>
			<th width="13%"><?php _e('Publish'); ?></th>
			
            <th width="13%"><?php _e('Edit'); ?></th>
            <th width="13%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($qqs) > 0) {
        $row_idx = 0;
		$currentlanguage=SessionHolder::get('_LOCALE');
		if($currentlanguage=='zh_CN') $l_local = 'cn'; else $l_local = 'en';
        foreach ($qqs as $qq) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'qq', $qq->id); ?></td>
        	<td><?php echo $qq->account; ?></td>
        	<td>
			<?php
				if($qq->category == 1){
			?>
			<a href="msnim:chat?contact=<?php echo $qq->account; ?>"><img src="<?php echo P_TPL_WEB?>/images/msn.gif" alt="MSN" border="0"><?php _e('Contact Me'); ?></a>
			<?php }else if($qq->category == 2){?>
				<a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" target=_blank><IMG alt="" src="http://amos.im.alisoft.com/online.aw?v=2&amp;uid=<?php echo $qq->account; ?>&amp;site=cntaobao&amp;s=1&amp;charset=utf-8" border=0></a>
			<?php }else if($qq->category == 3){?>
				<a href="skype:<?php echo $qq->account; ?>?chat" target="_blank"><img border="0" src="../images/skypelogo.gif" /></a>
			<?php }else if($qq->category == 4){?>
					<a href="icq:account?email=<?php echo $qq->account; ?>" target="_blank"><img border="0" src="../images/icq.jpeg" height="25" width="25" /><?php echo $qq->account; ?></a>
			<?php }else if ($qq->category == 5) { ?>
	<li>
	<a href="ymsgr:sendIM?<?php echo $qq->account; ?>" target="_blank"><img border=0 src="http://opi.yahoo.com/online?u=<?php echo $qq->account; ?>&m=g&t=1&l=<?php echo $l_local;?>"><?php echo $qq->account; ?></a>
	</li>

<?php } else{?>
				<IMG src="http://wpa.qq.com/pa?p=4:<?php echo $qq->account; ?>:4" align=absMiddle border=0><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq->account; ?>&site=qq&amp;Menu=yes" target=blank><?php echo $qq->account; ?></A>
			<?php } ?>
			
			</td>
			<td><?php echo Html::input('text', 'i_order['.$qq->id.']', $qq->i_order, 'class="textinput" style="width:20px;"'); ?></td>

        	<td><?php echo Toolkit::validateYesOrNo($qq->published,$qq->id,Html::uriquery('mod_qq', 'admin_pic', array('_id' => $qq->id)));?></td>
        	
        	<td>
        		<a href="<?php echo Html::uriquery('mod_qq', 'admin_edit', array('q_id' => $qq->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
        	</td>
        	<td>
        		<a href="#" onclick="delete_qq(<?php echo $qq->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
		
		?><tr class="row_style_0">
    		<td colspan="7" align="center"><input style="_margin-top:10px;margin-top:10px;" type="submit" value="<?php _e('Save Order');?>" id="submit" name="submit"/></td>
    	</tr>
		<?php
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="7"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
</form>