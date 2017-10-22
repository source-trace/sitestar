<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>
<script type="text/javascript" language="javascript">
<!--
var popup_win = false;

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    if (o_result.result == "OK") {
        reloadParent();
    } else {
		if (o_result.result == "ERROR") {
			alert(o_result.errmsg);
			return false;
    	}else{
        	return on_failure(response);
		}
    }
}

function on_failure(response) {
	if(response!=''){
		alert(response);
	}else{
    	alert("<?php _e('Update failed!'); ?>");
	}
}

function chgmultiple(val) {
	var mar_data2 = document.getElementById("mar_data2");
	document.getElementById("ex_params_mar_direc_id_").options.length=0;
	if(val=="text"){
		//隐藏左右
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Top');?>","top"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Down');?>","down"));
	}else if(val=="pic"){
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Top');?>","top"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Down');?>","down"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Right');?>","right"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Left');?>","left"));
	}else{
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Top');?>","top"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Down');?>","down"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Right');?>","right"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Left');?>","left"));
	}
}
function changeprd(val1,val2){
	var text = document.getElementById("ex_params_marquee_class_").value;
	var addRow=document.getElementById("row_id").getAttribute("rowspan");
	if(text=="text" && val1=="prd_info"){
		var val1="article_info";
	}
	document.getElementById("prd_info").style.display="none";
	document.getElementById("article_info").style.display="none";
	var mar_prd_id = document.getElementById(val1);
	var mar_data1 = document.getElementById(val2);
	if(mar_data1.checked){
		mar_prd_id.style.display="";
		if(val2 !="ex_params_marquee_data3_")
		document.getElementById("row_id").setAttribute("rowspan",parseInt(addRow)+1);
	}else {
		mar_prd_id.style.display="none";
		if(val2 !="ex_params_marquee_data3_")
		document.getElementById("row_id").setAttribute("rowspan",parseInt(addRow)-1);
	}
	var che_val = $("#ex_params_marquee_data1_").attr("checked");
	
	if($('#mar_prd_idtr').next("tr").attr("id")=='prd_list'){
		if(che_val){
			$('#prd_list').hide();
			$('#prd_list_tag').val("0")
		}else{
			$('#prd_list').show();
			$('#prd_list_tag').val("1")

		}
	}
}
// 添加flash幻灯项
function AddSlideTr() {
	var curnum = $('#ex_params_slide_num_').val();
	var tempnum = $('#ex_params_slide_temp_num_').val();
	var newnum = parseInt(curnum) + 1;
	var newtempnum = parseInt(tempnum) + 1;
	
	if (newnum > 6) {
		alert("<?php _e('Add up to 6');?>");
		$('#slide_img_srtr').find('a:last').hide();
		return false;
	} else {
		var htmltr = '<tr id="slide_img_sctr'+newtempnum+'"><td class="label">'+"<?php _e('Select Image');?>"+'</td><td colspan="2" class="entry" style="border-top:1px dashed #F35D02">';
		htmltr += '<input type="text" class="txtinput" value="" id="ex_params_slide_img_src'+newtempnum+'_" name="ex_params[slide_img_src'+newtempnum+']">&nbsp;';
		htmltr += '<a onclick="popup_win=show_imgpicker(\'ex_params[slide_img_src'+newtempnum+']\');return false;" href="#">'+"<?php _e('Select Image');?>"+'</a>';
		htmltr += '&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="DelSlideTr('+newtempnum+');return false;" href="javascript:void(0);">'+"<?php _e('Delete the flash slide items');?>"+'</a></td></tr>';
		htmltr += '<tr id="slide_img_ordetr'+newtempnum+'"><td class="label">'+"<?php _e('Display order');?>"+'</td><td colspan="2" class="entry">';
        htmltr += '<input type="text" class="txtinput" size="4" value="0" id="ex_params_slide_img_order'+newtempnum+'_" name="ex_params[slide_img_order'+newtempnum+']"></td></tr>';
		htmltr += '<tr id="slide_img_urtr'+newtempnum+'"><td class="label">'+"<?php _e('Image Url');?>"+'</td><td colspan="2" class="entry">';
        htmltr += '<input type="text" class="txtinput" size="28" value="http://" onblur="if(this.value==\'\') this.value=\'http:\/\/\'" onfocus="if(this.value==\'http:\/\/\') this.value=\'\'" id="ex_params_slide_img_uri'+newtempnum+'_" name="ex_params[slide_img_uri'+newtempnum+']">';
        htmltr += '&nbsp;&nbsp;<img class="title" src="admin/template/images/answer1.gif" alt="help" title="'+"<?php _e('Click the picture after the jump to the web site');?>"+'" /></td></tr>';
		htmltr += '<tr id="slide_img_destr'+newtempnum+'"><td class="label">'+"<?php _e('Description');?>"+'</td><td colspan="2" class="entry">';
	    htmltr += '<input type="text" class="txtinput" size="28" value="" id="ex_params_slide_img_desc'+newtempnum+'_" name="ex_params[slide_img_desc'+newtempnum+']"></td></tr>';
		
		$('#slide_img_destr').after(htmltr);
		// ++
		$('#ex_params_slide_num_').val(newnum);
		$('#ex_params_slide_temp_num_').val(newtempnum);
	}
}
// 删除flash幻灯项
function DelSlideTr(trid) {
	$('#slide_img_srtr').find('a:last').show();
	var curnum = $('#ex_params_slide_num_').val();
	var newnum = parseInt(curnum) - 1;
	
	var imgsrc = $('#slide_img_sctr'+trid+' > .entry').find('input[type=text]').val();
	if (imgsrc.length) {
		_ajax_request('mod_media', 'del_image', {'img': encodeURI(imgsrc)}, slide_onsuccess, slide_onfailed);	
	}
	
	$('#slide_img_sctr'+trid).remove();
	$('#slide_img_ordetr'+trid).remove();
	$('#slide_img_urtr'+trid).remove();
	$('#slide_img_destr'+trid).remove();
	// --
	$('#ex_params_slide_num_').val(newnum);
}
function slide_onsuccess(response) {}
function slide_onfailed(response) {}
function prd_list(){
	var che_val = $("#ex_params_marquee_data1_").attr("checked");
	if(che_val){
		$('#ex_params_marquee_data1_').trigger('click');	
	}
	if($('#mar_prd_idtr').next("tr").attr("id")!=='prd_list'){
		$('#mar_prd_idtr').after('<tr id="prd_list"><td class="label"><?php _e("Product"); ?></td><td class="entry"><div id="getPrdList"></div></td></tr>');
		$('#mar_prd_idtr').hide();
	}else{
		$('#mar_prd_idtr').hide();
		$('#prd_list').show();
	}
}
function prd_remove(e){
	$(e).parent("li").remove();
}

function show_type(num){
	$(".qqType").hide();
	$("#aa"+num).css("display","block");
}
//-->
</script>
<?php
if($_GET['widget']=='mod_marquee-marquee') {
	$_rowspan = 2;
}else{
	$_rowspan = 4;
}
$new_mblock_form_s2 = new Form('index.php', 'newmblockform', 'check_mblock_info');
$new_mblock_form_s2->setEncType('multipart/form-data');
$new_mblock_form_s2->p_open('mod_tool', 'add_mblock', '_ajax');
?>
<table id="mblockform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="3">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mb[module]', $w_module);
            echo Html::input('hidden', 'mb[action]', $w_action);
            echo Html::input('hidden', 'mb[s_pos]', $currpos);
            echo Html::input('hidden', 'modblk', $modblk);
            echo Html::input('hidden', 'dispage', $_SERVER['HTTP_REFERER']);
            // for adding flash slide
            echo Html::input('hidden', 'ex_params[slide_num]', 1);
            // temp hidden
            echo Html::input('hidden', 'ex_params[slide_temp_num]', 1);
            ?>
            <script type="text/javascript">
            $(document).ready(function(){
            	var tmp = (parent.$('#getValues').attr('value'));
                $('#newmblockform').append("<input type='hidden' name='mb[s_token]' value='"+tmp+"'");
            });
            
            </script>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" style="padding-top:10px;width:12%"><?php _e('Title'); ?></td>
            <td class="entry" style="padding-top:10px;width:60%">
            <?php
            echo Html::input('text', 'mb[title]', '', 'class="txtinput"');
            ?>
            </td>
			<?php
			if($_GET['widget']=='mod_marquee-marquee') {
			?>
			<td class="entry" id="row_id" rowspan="<?php echo sizeof($params)+$_rowspan;?>" style="text-align:left;vertical-align:top;">
			<div id="prd_info" style="display:none">
				图片上传：<br>
				<input type="text" name="prd_extpic[]" id="prd_extpic1" value=""  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic1');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" size=15 ><br />
				<input type="text" name="prd_extpic[]" id="prd_extpic2" value=""  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic2');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" size=15><br />
				<input type="text" name="prd_extpic[]" id="prd_extpic3" value=""  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic3');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" size=15><br />
				<br>
				网络图片：<br>
				图片地址：<input type="text" name="remote_pic[]" size='15'>&nbsp;链接：<input type="text" name="remote_name[]"><br />
				图片地址：<input type="text" name="remote_pic[]" size='15'>&nbsp;链接：<input type="text" name="remote_name[]"><br />
				图片地址：<input type="text" name="remote_pic[]" size='15'>&nbsp;链接：<input type="text" name="remote_name[]"><br />
			</div>
			<div id="article_info" style="display:none">
				自定义文本：<br>
				文本标题：<input type="text" name="def_text[]" size='15'>&nbsp;链接：<input type="text" name="def_name[]"><br />
				文本标题：<input type="text" name="def_text[]" size='15'>&nbsp;链接：<input type="text" name="def_name[]"><br />
				文本标题：<input type="text" name="def_text[]" size='15'>&nbsp;链接：<input type="text" name="def_name[]"><br />
				
			</div>
			</td><?php }?>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[show_title]', '1');
            ?>
            &nbsp;<?php _e('Show Title');
            // Display on all pages
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('checkbox', 'dispallpg', '1');
            ?>
            &nbsp;<?php _e('Display on all pages');
            // 	Member only access
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('checkbox', 'ismemonly', '1');
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
        <!-- tr>
            <td class="label"><?php _e('Position'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('mb[s_pos]', $positions);
            ?>
            </td>
        </tr -->
        <!-- [Disable publish status temporarily] tr>
            <td class="label"><?php _e('Publish'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[published]', '1', 
                'checked="checked"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'dispallpg', '1');
            ?>
            &nbsp;<?php _e('Display on all pages'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1');
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr -->
        <?php
        for ($i = 0; $i < sizeof($params); $i++) {
        ?>
        <tr id=<?php echo substr($params[$i]['id'],0,-2).'tr'; ?> <?php if(substr($params[$i]['id'],0,-2)=='mar_article_id'||substr($params[$i]['id'],0,-2)=='mar_prd_id'){?>style="display:none;"<?php }?>>
            <td class="label"><?php echo $params[$i]['label']; ?></td>
            <td class="entry" <?php if (in_array($params[$i]['tag'],array('slide_input','slide_imgpicker'))){?>colspan="2"<?php }?>>
        <?php
            switch ($params[$i]['tag']) {
                case 'input':
                	switch ($params[$i]['type']) {
                		case 'text':
							 echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], $params[$i]['extra']);
							if($params[$i]['id']=='bulletin_speed'){
								echo "&nbsp;&nbsp;"; _e("Larger value, the slower the speed,preferred values");echo 40;
							}
                			break;
                	    case 'checkbox':
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], 
		                        $params[$i]['extra']);
                	    	break;
                	    case 'img_open':                	    	
				            echo Html::input('radio', 'ex_params[image_open]', '1','checked')."&nbsp;&nbsp;&nbsp;";				         
				            echo _e('image self');				          
				            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				            echo Html::input('radio', 'ex_params[image_open]', '2')."&nbsp;&nbsp;&nbsp;";				          
				            echo _e('image blank');  
                	    	break;
                	     case 'floatType':
                	    	
                	    	echo Html::input('radio', 'ex_params[qq_show_type]', '1','checked'." onclick=show_type('1');")."&nbsp;&nbsp;&nbsp;";			
				             _e('Effect1');				          
				            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				            echo Html::input('radio', 'ex_params[qq_show_type]','2'," onclick=show_type('2');")."&nbsp;&nbsp;&nbsp;";			
				            _e('Effect2'); 
				             echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				             echo '<div id="aa1" class="qqType" style="display:none"><image src="images/head_1_"'.$_SITE->s_locale.'".gif" /></div>';
				             echo '<div id="aa2" class="qqType" style="display:none"><image src="images/head_2_"'.$_SITE->s_locale.'".gif" /></div>';
                	    	break;
                	    case 'slide_img_open':                	    	
				            echo Html::input('radio', 'ex_params[slide_img_open]', '1','checked')."&nbsp;&nbsp;&nbsp;";				         
				            echo _e('image self');				          
				            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				            echo Html::input('radio', 'ex_params[slide_img_open]', '2')."&nbsp;&nbsp;&nbsp;";				          
				            echo _e('image blank');  
                	    	break;
						case 'checkbox2':
		                    echo '<span id="mar_data1">'.__("Product").':';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][0].']', 
		                        $params[$i]['value'], 
		                        'onclick=changeprd("mar_prd_idtr","ex_params_marquee_data1_")');echo '</span>';
							/*echo '<span id="mar_data2" style="display:none">文章:';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][1].']', 
		                        $params[$i]['value'], 
		                        'onclick=changeprd("mar_article_idtr","ex_params_marquee_data2_")');echo '</span>';*/
 							echo '&nbsp;&nbsp;&nbsp;&nbsp; 
 							<span id="mar_data2">'.__("Product List").':
 							<a href="admin/index.php?_m=mod_product&_a=prd_list&keepThis=true&TB_iframe=true&height=260&width=520" title="'.__("Product List").'" class="thickbox">';echo Html::input('button', 'ex_params['.$params[$i]['page'][0].'2]',  __("Click and get"), 'onclick="prd_list();"');echo '</a>
 							</span>' ;
							/*echo '<span id="mar_data3">自定义:';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][2].']', 
		                        $params[$i]['value'], 
		                       'onclick=changeprd("prd_info","ex_params_marquee_data3_")');echo '</span>';
                	    	break;*/
                	}
                    break;
                case 'textarea':
                    if ($params[$i]['id'] == 'html') { // for fckeditor
                    	RichTextbox::jsinclude('admin/richtexteditor');
                    	echo Html::textarea('ex_params['.$params[$i]['id'].']', '', $params[$i]['extra'])."\n";
			            $o_fck = new RichTextbox('ex_params['.$params[$i]['id'].']');
			            $o_fck->height = 320;
			            echo $o_fck->create();
                    } else {
                    	echo Html::textarea('ex_params['.$params[$i]['id'].']', 
                        '', $params[$i]['extra']);
                    }
                    break;
                case 'select':
                	switch ($params[$i]['fill_type']) {
                	    case 'objfunc':
							$params_1_extra='';
							if(isset($params[$i]['extra'])){
								$params_1_extra=$params[$i]['extra'];
							}
                	    	$obj_name = $params[$i]['obj_name'];
                	    	$obj = new $obj_name();
                	    	$func = $params[$i]['func_name'];
                	    	$hash_entry =& $obj->$func();
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$hash_entry, '', $params_1_extra);
                	    	break;
                	    // 28/4/2010 Add >>
                	    case 'multiple':
                	    	$obj_name = $params[$i]['obj_name'];
                	    	$obj = new $obj_name();
                	    	$func = $params[$i]['func_name'];
                	    	$hash_entry =& $obj->$func();/*
							if(	$obj_name=='ArticleCategory'){
								$all_categories =& ArticleCategory::listCategories(0, "s_locale=?",
								array(SessionHolder::get('_LOCALE')));
								$select_categories = array();
								ArticleCategory::toSelectArray($all_categories, $select_categories,
								0, array(0), array('0' => __('Uncategorised')));
								$hash_entry=$select_categories;
							}
							if(	$obj_name=='ProductCategory'){
							    $all_categories =& ProductCategory::listCategories(0, "s_locale=?",
       							array(SessionHolder::get('_LOCALE')));
								$select_categories = array();
        						ProductCategory::toSelectArray($all_categories, $select_categories,
	        					0, array(), array('0' => __('Uncategorised')));
								$hash_entry=$select_categories;
							}*/
                	    	// for published categories
                	    	$hash_published_entry =& $obj->getPublishedCategoryArray();
                	    	if (count($hash_entry) == count($hash_published_entry)+1) {
                	    		@array_push($hash_published_entry, '0');
                	    	}
                	    	echo Html::select($params[$i]['id'], 
                	    		$hash_entry, $hash_published_entry, $params[$i]['extra']);
                	        echo '&nbsp;&nbsp;<img id="answer" class="title" src="admin/template/images/answer1.gif" alt="help" title="'.__('Multi-select by Ctrl or Shift').'" />';
                	        break;
                	    // 28/4/2010 Add <<

                	    case 'array':
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$params[$i]['data'], '', $params[$i]['extra']);
                	    	break;
                	}
                	break;
                case 'imgpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                '', $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
                	break;
                // sitestarv1.3 09/09/2010 start
                case 'slide_imgpicker':
                	 echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                '', $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
		            echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="AddSlideTr();return false;">'.__('Add the flash slide items').'</a>';
                	break;
                case 'slide_input':
		            if ($params[$i]['id'] == 'slide_img_uri1') {
		            	echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        'http://', $params[$i]['extra'].' onblur="if(this.value==\'\') this.value=\'http:\/\/\'" onfocus="if(this.value==\'http:\/\/\') this.value=\'\'"');
		            	echo '&nbsp;&nbsp;<img class="title" src="admin/template/images/answer1.gif" alt="help" title="'.__('Click the picture after the jump to the web site').'" />';
		            } else {
		            	echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        '', $params[$i]['extra']);
		            }
                	break;
                // sitestarv1.3 09/09/2010 end 
                case 'flvpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                '', $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_flvpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Flash'));
                	break;
				case 'upload_img':
					$marquees = Marquee::getImg();
					echo "<a href='index.php?_m=mod_tool&_a=upload_img'>".__("Add Img")."</a>";
					echo '<br />';
					if(sizeof($marquees) > 0){
					foreach($marquees as $marquee){
						echo "<img src='".$marquee['img_name']."' width=100 height=100>";
						echo "<a href='index.php?_m=mod_tool&_a=img_delete&id=".$marquee['id']."'>".__("Delete")."</a>&nbsp;";
						echo "<a href='index.php?_m=mod_tool&_a=img_edit&id=".$marquee['id']."'>".__("Edit")."</a>";
					}
					}
					echo Html::input("hidden","ex_params[id_marquee]");
					break;
				case 'imgurl': //for image url
	            	echo Html::input($params[$i]['type'], 
	                        'ex_params['.$params[$i]['id'].']', 
	                        'http://', $params[$i]['extra'].' onblur="if(this.value==\'\') this.value=\'http:\/\/\'" onfocus="if(this.value==\'http:\/\/\') this.value=\'\'"');
					break;
            }
        ?>
            </td>
			
        </tr>
        <?php
        /*if ($params[$i]['id'] == 'bulletin_type') {
        ?>
        <tr>
            <td class="label"></td>
            <td class="entry" colspan="2" style="color:#F35D02">
			<?php _e("Notice: To edit bulletin,please login 'Admin - Contents - Bulletins'");?>
            </td>
        </tr>
        <?php
        }
        if ($params[$i]['id'] == 'cpy_intro_number') {
        ?>
        <tr>
            <td class="label"></td>
            <td class="entry" colspan="2" style="color:#F35D02">
			<?php _e("Notice: To edit about content,please login 'Admin - Settings - Web Settings - Company Introduction'");?>
            </td>
        </tr>
        <?php	
        }*/
        }
        ?>
    </tbody>
</table>
<?php
$new_mblock_form_s2->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;
JS;
$new_mblock_form_s2->addCustValidationJs($custom_js);
$new_mblock_form_s2->writeValidateJs();
?>
