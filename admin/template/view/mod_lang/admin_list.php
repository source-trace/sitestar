<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '400px',height:'53px'});
});
$(function(){
	var k = 0;
	var el = document.getElementById('ckselect');
	var arr = document.getElementsByName("lang");
	for (i=0; i<arr.length; i++) {
		if (arr[i].checked) k++;
	}
	if (k == arr.length) el.checked = true;
});
	
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminlanglst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    //stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    alert("<?php _e('Delete language successful!'); ?>");
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminlanglst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    reloadParent();
    return false;
}

function delete_lang(lang_id) {
	if (confirm("<?php _e('Delete the selected language?'); ?>")) {
		// 禁止重复提交
		$('.delang,.cplang').attr('onclick','return false');
	    var stat = document.getElementById("adminlanglst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected language...'); ?>";
		_ajax_request("mod_lang", 
			"admin_delete", 
	        {
	            l_id:lang_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}

function on_copy_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_copy_failure(response);
    }
    
    var stat = document.getElementById("adminlanglst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    //stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    alert("<?php _e('Copy data successful!'); ?>");
	    reloadPage();
    } else {
        return on_copy_failure(response);
    }
}

function on_copy_failure(response) {
    document.getElementById("adminlanglst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    reloadParent();
    return false;
}

function copy_data(lang_id, del_tag) {
	var stat = document.getElementById("adminlanglst_stat");
    stat.style.display = "block";
    stat.innerHTML = "<?php _e('Copying data...'); ?>";
	_ajax_request("mod_lang", 
		"admin_copydata", 
        {
            l_id:lang_id,
            t_del:del_tag
        }, 
		on_copy_success, 
		on_copy_failure);
}

function copy_data_existed(lang_id) {
	_ajax_request("mod_lang", 
		"admin_copydata_existed", 
        {
            l_id:lang_id
        }, 
		on_copy_existed_success, 
		on_copy_existed_failure);
}

function on_copy_existed_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_copy_existed_failure(response);
    }
    
    if (o_result.result == "ERROR") {
        return false;
    } else if (o_result.result == "OK") {
    	if (o_result.self_tag == 1) {
			alert("<?php _e('Can not copy the data itself!');?>");
			return false;
		} else {
			if (parseInt(o_result.rows) > 0) {
	    		if (confirm("<?php _e('Sure to do this? Again after copying the data will lead to data loss!');?>")) {
	    			copy_data(o_result.lang_id, 'yes');
	    		}
	    	} else {
	    		copy_data(o_result.lang_id, 'no');
	    	}
		}
    } else {
        return on_copy_existed_failure(response);
    }
}

function on_copy_existed_failure(response) {
    return false;
}

/**/function on_tog_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminlanglst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        alert("<?php _e('Install finished!'); ?>");
 	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadParent();
    } else {
        return on_failure(response);
    }
}

function on_tog_successset(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminlanglst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        alert("<?php _e('Operate successful!'); ?>");
 	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadParent();
    } else {
        return on_failure(response);
    }
}

function on_tog_failure(response) {
    on_del_failure(response);
}

function toggle_default(lang_id) {
    var stat = document.getElementById("adminlanglst_stat");
    stat.style.display = "block";
    stat.innerHTML = "<?php _e('Setting default language...'); ?>";
    _ajax_request("mod_lang", 
    	"admin_make_default", 
    	{
    	    l_id:lang_id
    	}, 
    	on_tog_success, 
    	on_tog_failure);
}

function toggle_default_set(lang_id,lang_name) {
    var stat = document.getElementById("adminlanglst_stat");
    stat.style.display = "block";
    stat.innerHTML = "<?php _e('Setting default language...'); ?>";
    _ajax_request("mod_lang", 
    	"admin_make_default_set", 
    	{
			 l_id:lang_id,
    	    lang_name:lang_name
    	}, 
    	on_tog_successset, 
    	on_tog_failure);
}


function ck_select() {
	try {
		var el = document.getElementById('ckselect');
		var arr = document.getElementsByName("lang");
		if (el.checked) {
			for(i=0;i<arr.length;i++) {
				toggle_lang(arr[i].value, true, false);
				arr[i].checked = true;
			}
		} else {
			for(i=0;i<arr.length;i++) {
				if(arr[i].disabled ==  true){ 
					arr[i].checked = true;
					toggle_lang(arr[i].value, true, false);
				}else{
					arr[i].checked = false ;
					toggle_lang(arr[i].value, false, false);
				}
			}
		}
		alert("<?php _e('Operate successful!'); ?>");
	} catch(e) {
		return false;
	}
}

function toggle_lang(lang_id, checked, tag) {
	var published = checked ? 1 : 0;
	
	_ajax_request("mod_lang", "tog_lang", {'l_id': lang_id, '_p': published}, on_toggle_lang_success, on_toggle_lang_failure);
	if (tag == true) {
		alert("<?php _e('Operate successful!'); ?>");
		//reloadPage();
	}
}

function on_toggle_lang_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_toggle_lang_failure(response);
    }
    
    if (o_result.result == "ERROR") {
        return on_toggle_lang_failure(response);
    } else if (o_result.result == "OK") {
	    //stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    //alert("<?php _e('Operate successful!'); ?>");
    } else {
        return on_toggle_lang_failure(response);
    }
}

function on_toggle_lang_failure(response) {
    alert("<?php _e('Request failed!'); ?>");
    return false;
}
//-->
</script>
<div style="line-height:24px;width:96%;margin-left:15px;margin-top:5px;">
<a href="<?php echo Html::uriquery('mod_lang', 'admin_add'); ?>" title=""><?php _e('Add New Language'); ?></a><img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="tips" title="<?php _e('After adding a new language site, it is recommended to copy the data from the default language site, otherwise some of the functions might not be used.');?>" />
</div>
<div class="status_bar">
	<span id="adminlanglst_stat" class="status" style="display:none;"></span>
</div>

<table  class="form_table_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;" id="admin_lang_list">
	<thead>
		<tr>
		<th><?php _e('Name'); ?></th>
		<th><?php _e('Locale'); ?></th>
		<th><?php _e('editweb'); ?></th>
		    <th ><?php _e('Language_item'); ?><?php
	        $title = '';
			if (SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') {
				$title = __('Language_item_jstip');
			}
			$title = __('Language_item_jstip');
			echo Html::input('checkbox', 'ckselect','','onclick="ck_select()" title="'.$title.'"'); ?></th>
            
            
            <th><?php _e('Is Default'); ?> </th>
			 <th><?php _e('Edit_page_f'); ?> </th>
			 <th><?php _e('Edit_page_e'); ?> </th>
            <th><?php _e('Operation'); ?> </th>
        </tr>
    </thead>	
    <tbody>
    <?php
	/*
$filename = "../locale/language.txt";
$file = fopen($filename, "w");      //以写模式打开文件
fwrite($file, "en");      //写入
fclose($file);         //关闭文件

$filename = "../locale/language.txt";
 $defaultlanguage = file_get_contents($filename);
*/
$defaultlanguage =DEFAULT_LOCALE;
    if (sizeof($langs) > 0) {
        $row_idx = 0;
        foreach ($langs as $lang) {
		$curentlug="";
		if ($lang->locale == SessionHolder::get('_LOCALE')) {$curentlug="#ff0000";}
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
		<td><font color="<?php echo $curentlug;?>"><?php echo $lang->name; ?></font></td>
		<td><?php echo $lang->locale; ?></td>
			
			<td><a href="javascript:;" onclick="parent.set_default_lang(<?php echo $lang->id;?>,'other',<?php echo MOD_REWRITE;?>)"><?php _e('editwebedit'); ?></a></td>
        	<td><?php
            $checked = ($lang->published == '1') ? 'checked="true"' : '';
        	$disabled='';
			if ($lang->locale == $defaultlanguage) {$disabled='disabled="true"'; $checked = 'checked="true"';}
        	echo Html::input('checkbox', 'lang', $lang->id, 'onclick="toggle_lang(\''.$lang->id.'\', this.checked, true)" title="'.$title.'" '.$checked.' '.$disabled.' ');?></td>
        
        	
        	<td>
        	<?php
        	//if ($lang->locale == SessionHolder::get('_LOCALE')) {
			if ($defaultlanguage == $lang->locale) {
        	    _e('Yes');
        	} else {
			    echo '<a href="#" onclick="toggle_default_set(\''.$lang->id.'\',\''.$lang->locale.'\');return false;" title="'.__('Set as default language').'">'.__('No').'</a>';

        	    //echo '<a href="#" onclick="toggle_default(\''.$lang->id.'\');return false;" title="'.__('Set as default language').'">'.__('No').'</a>';
        	}
        	?>
        	</td>
			<td><a href="index.php?_m=mod_lang&_a=modify&_f=<?php echo $lang->locale; ?>">locale/<?php echo $lang->locale; ?></a></td>
			<td><a href="index.php?_m=mod_lang&_a=modifya&_f=<?php echo $lang->locale; ?>">admin/locale/<?php echo $lang->locale; ?></a></td>
        	<td align="center" >
        		<?php //defaultlanguage
        		//if (SessionHolder::get('_LOCALE') == $lang->locale) {
				if ($defaultlanguage == $lang->locale) {
        			_e('Default LANG');
        		} else {
        		?>
                <?php 
				if (SessionHolder::get('_LOCALE') != $lang->locale) {
				?>
        		<a href="#" class="delang" onclick="delete_lang(<?php echo $lang->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
                <?php
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				}else{}
				?>
          
				<a href="#" class="cplang" onclick="copy_data_existed(<?php echo $lang->id; ?>);return false;" title="<?php _e('Copy current website language data'); ?>"><img style="border:none;" alt="<?php _e('Copy data');?>" src="<?php echo P_TPL_WEB; ?>/images/copy.gif"/></a><?php }?>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="5"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
