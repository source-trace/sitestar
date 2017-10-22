<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$arr_params = array();
if (strlen(trim($curr_mblock->s_param)) > 0) {
	$arr_params = unserialize($curr_mblock->s_param);
}
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
        return on_failure(response);
    }
}

function on_failure(response) {
    alert("<?php _e('Update failed!'); ?>");
}

// 28/4/2010 Add >>
$(function(){
	if( $('#cids').length > 0 ) {
		var cids = $('#cids').val();
		var len = cids.length;
		
		if ( (cids == '0') || (cids == '') ) {
			$("select").find("option").attr("selected", true);
		} else {
			var cida = cids.split(',');
			for( var i=0; i<len; i++ ) {
				$("select").find("option").each(function(){
			    	if( $(this).val() == cida[i] ) $(this).attr("selected", true);
			    });
			}
		}
	}
	/*
	if( $('#cids2').length > 0 ) {
		var cids2 = $('#cids2').val();
		var len = cids2.length;
		
		if ( (cids2 == '0') || (cids2 == '') ) {
			$("select").find("option").attr("selected", true);
		} else {
			var cida = cids2.split(',');
			for( var i=0; i<len; i++ ) {
				$("select").find("option").each(function(){
			    	if( $(this).val() == cida[i] ) $(this).attr("selected", true);
			    });
			}
		}
	}
	*/
});
// 28/4/2010 Add <<
//-->

function chgmultiple(val) {
	var mar_data2 = document.getElementById("mar_data2");
	document.getElementById("ex_params_mar_direc_id_").options.length=0;
	if(val=="text"){
		//隐藏左右
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Right');?>","right"));
		document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Left');?>","left"));
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
function reupload(id){
	var img_id = document.getElementById("reupload"+id);
	img_id.innerHTML = "<input type='file' name='prd_extpic[]' size='15' />";
}
<?php
if(isset($arr_params['marquee_class']) && $arr_params['marquee_class']=="text"){

?>
$(function(){});
document.getElementById("ex_params_mar_direc_id_").options.length=0;
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Right');?>","right"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Left');?>","left"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Top');?>","top"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Down');?>","down"));

<?php }?>
	
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
        htmltr += '<input type="text" class="txtinput" size="4" value="" id="ex_params_slide_img_order'+newtempnum+'_" name="ex_params[slide_img_order'+newtempnum+']"></td></tr>';
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
	if($('#mar_prd_idtr').nextAll("tr").attr("id")!=='prd_list'){
		$('#mar_prd_idtr').after('<tr id="prd_list"><td class="label"><?php _e("Product"); ?></td><td class="entry"><div id="getPrdList"></div></td></tr>');
		$('#mar_prd_idtr').hide();
	}else{
		$('#mar_prd_idtr').hide();
		$('#prd_list').show();
	}
}
function prd_remove(e){
	var prd_id = $(e).prev("input").val();
	$.post("index.php?_m=mod_marquee&_a=del_prd",{"prd_id":prd_id});
	$(e).parent("li").remove();
}
function show_type(num){
	$(".qqType").hide();
	$("#aa"+num).css("display","block");
}
</script>
<?php



if($_GET['mar']==1) {
	$_rowspan = 2;
	if(isset($arr_params['marquee_data1'])&&$arr_params['marquee_data1']==1){
		$_rowspan += 1;
	}
	if(isset($arr_params['marquee_data2'])&&$arr_params['marquee_data2']==1){
		$_rowspan += 1;
	}
}else{
	$_rowspan = 4;
}
$mb_form = new Form('index.php', 'mblockform', 'check_login_info');
$mb_form->setEncType('multipart/form-data');
$mb_form->p_open('mod_tool', 'save_prop', '_ajax');
?>
<table id="mblockform_table" class="form_table" cellspacing="0" cellpadding="0">
    <tfoot>
        <tr>
            <td colspan="3">
            <?php
			$arr_params_slide_num='';
			if(isset($arr_params['slide_num'])){
				$arr_params_slide_num=$arr_params['slide_num'];
			}
			$arr_params_slide_temp_num='';
			if(isset($arr_params['slide_temp_num'])){
				$arr_params_slide_temp_num=$arr_params['slide_temp_num'];
			}
    		echo Html::input('reset', 'reset', __('Reset'));
			echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mb[id]', $curr_mblock->id);
            echo Html::input('hidden', 'dispage', $_SERVER['HTTP_REFERER']);
            // for adding flash slide
            echo Html::input('hidden', 'ex_params[slide_num]', $arr_params_slide_num);
            // temp hidden
            echo Html::input('hidden', 'ex_params[slide_temp_num]', $arr_params_slide_temp_num);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" style="padding-top:10px;width:12%"><?php _e('Title'); ?></td>
            <td class="entry" style="padding-top:10px;width:60%">
            <?php
            echo Html::input('text', 'mb[title]', $curr_mblock->title, 'class="txtinput"');
            ?>
            </td>
			<?php
			if($_GET['mar']==1) {
				$pic_arr = array();
				$pic_remote_arr = array();
				$text_arr = array();
				foreach($marquees as $marquee){
					if($marquee->marquee_type=='pic' && $marquee->flag==3){
						$pic_remote_arr[] = $marquee;
					}else if($marquee->marquee_type=='pic' && $marquee->flag==1){
						$pic_arr[] = $marquee;
					}else if($marquee->marquee_type=='text' && $marquee->flag==1){
						$text_arr[] = $marquee;
					}
				}
			?>
			<td class="entry" id="row_id" rowspan="<?php echo sizeof($params)+$_rowspan;?>" style="text-align:left;vertical-align:top;">
			<div id="prd_info" style="display:<?php if(isset($arr_params['marquee_data3'])&&$arr_params['marquee_data3']==1 && $arr_params['marquee_class']=='pic'){echo '';}else{echo 'none';}?>">
				图片上传：<br>
				<?php
					$pic_arr_0_pic='';
					if(isset($pic_arr[0]->pic)){
						$pic_arr_0_pic=$pic_arr[0]->pic;
					}
					$pic_arr_0_link='';
					if(isset($pic_arr[0]->link)){
						$pic_arr_0_link=$pic_arr[0]->link;
					}
					$pic_arr_0_id='';
					if(isset($pic_arr[0]->id)){
						$pic_arr_0_id=$pic_arr[0]->id;
					}
				?>
				<input type="text" name="prd_extpic[]" id="prd_extpic1" value="<?php echo $pic_arr_0_pic;?>"  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic1');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" value="<?php echo $pic_arr_0_link;?>"><input type="hidden" name="prd_id[]" value="<?php echo $pic_arr_0_id;?>"><br />
				<?php
					$pic_arr_1_pic='';
					if(isset($pic_arr[1]->pic)){
						$pic_arr_1_pic=$pic_arr[1]->pic;
					}
					$pic_arr_1_link='';
					if(isset($pic_arr[1]->link)){
						$pic_arr_1_link=$pic_arr[1]->link;
					}
					$pic_arr_1_id='';
					if(isset($pic_arr[1]->id)){
						$pic_arr_1_id=$pic_arr[1]->id;
					}
				?>
				<input type="text" name="prd_extpic[]" id="prd_extpic2" value="<?php echo $pic_arr_1_pic;?>"  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic2');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" value="<?php echo $pic_arr_1_link;?>"><input type="hidden" name="prd_id[]" value="<?php echo $pic_arr_1_id;?>"><br />
				<?php
					$pic_arr_2_pic='';
					if(isset($pic_arr[2]->pic)){
						$pic_arr_2_pic=$pic_arr[2]->pic;
					}
					$pic_arr_2_link='';
					if(isset($pic_arr[2]->link)){
						$pic_arr_2_link=$pic_arr[2]->link;
					}
					$pic_arr_2_id='';
					if(isset($pic_arr[2]->id)){
						$pic_arr_2_id=$pic_arr[2]->id;
					}
				?>
				<input type="text" name="prd_extpic[]" id="prd_extpic3" value="<?php echo $pic_arr_2_pic;?>"  size=15/>&nbsp;<a href="#" onclick="popup_win=show_imgpicker('prd_extpic3');return false;" title="">选择图片</a>&nbsp;&nbsp;链接：<input type="text" name="prd_name[]" value="<?php echo $pic_arr_2_link;?>"><input type="hidden" name="prd_id[]" value="<?php echo $pic_arr_2_id;?>"><br />
				<br>
				网络图片：<br>
				<?php
					$pic_remote_arr_0_pic='';
					if(isset($pic_remote_arr[0]->pic)){
						$pic_remote_arr_0_pic=$pic_remote_arr[0]->pic;
					}
					$pic_remote_arr_0_link='';
					if(isset($pic_remote_arr[0]->link)){
						$pic_remote_arr_0_link=$pic_remote_arr[0]->link;
					}
					$pic_remote_arr_0_id='';
					if(isset($pic_remote_arr[0]->id)){
						$pic_remote_arr_0_id=$pic_remote_arr[0]->id;
					}

				?>
				图片地址：<input type="text" name="remote_pic[]" size='15' value="<?php echo $pic_remote_arr_0_pic;?>">&nbsp;链接：<input type="text" name="remote_name[]" value="<?php echo $pic_remote_arr_0_link;?>"><input type="hidden" name="remote_id[]" value="<?php echo $pic_remote_arr_0_id;?>"><br />
				<?php
					$pic_remote_arr_1_pic='';
					if(isset($pic_remote_arr[1]->pic)){
						$pic_remote_arr_1_pic=$pic_remote_arr[1]->pic;
					}
					$pic_remote_arr_1_link='';
					if(isset($pic_remote_arr[1]->link)){
						$pic_remote_arr_1_link=$pic_remote_arr[1]->link;
					}
					$pic_remote_arr_1_id='';
					if(isset($pic_remote_arr[1]->id)){
						$pic_remote_arr_1_id=$pic_remote_arr[1]->id;
					}

				?>
				图片地址：<input type="text" name="remote_pic[]" size='15' value="<?php echo $pic_remote_arr_1_pic;?>">&nbsp;链接：<input type="text" name="remote_name[]" value="<?php echo $pic_remote_arr_1_link;?>" ><input type="hidden" name="remote_id[]" value="<?php echo $pic_remote_arr_1_id;?>"><br />
				<?php
					$pic_remote_arr_2_pic='';
					if(isset($pic_remote_arr[2]->pic)){
						$pic_remote_arr_2_pic=$pic_remote_arr[2]->pic;
					}
					$pic_remote_arr_2_link='';
					if(isset($pic_remote_arr[2]->link)){
						$pic_remote_arr_2_link=$pic_remote_arr[2]->link;
					}
					$pic_remote_arr_2_id='';
					if(isset($pic_remote_arr[2]->id)){
						$pic_remote_arr_2_id=$pic_remote_arr[2]->id;
					}

				?>
				图片地址：<input type="text" name="remote_pic[]" size='15' value="<?php echo $pic_remote_arr_2_pic;?>">&nbsp;链接：<input type="text" name="remote_name[]" value="<?php echo $pic_remote_arr_2_link;?>"><input type="hidden" name="remote_id[]" value="<?php echo $pic_remote_arr_2_id;?>"><br />
			</div>
	
			<div id="article_info" style="display:<?php if(isset($arr_params['marquee_data3'])&&$arr_params['marquee_data3']==1 && $arr_params['marquee_class']=='text'){echo '';}else{echo 'none';}?>">
				自定义文本：<br>
				<?php
					$text_arr_0_title='';
					if(isset($text_arr[0]->title)){
						$text_arr_0_title=$text_arr[0]->title;
					}
					$text_arr_0_link='';
					if(isset($text_arr[0]->link)){
						$text_arr_0_link=$text_arr[0]->link;
					}
					$text_arr_0_id='';
					if(isset($text_arr[0]->id)){
						$text_arr_0_id=$text_arr[0]->id;
					}

				?>
				文本标题：<input type="text" name="def_text[]" size='15' value="<?php echo $text_arr_0_title;?>">&nbsp;链接：<input type="text" name="def_name[]" value="<?php echo $text_arr_0_link;?>"><input type="hidden" name="def_id[]" value="<?php echo $text_arr_0_id;?>"><br />
				<?php
					$text_arr_1_title='';
					if(isset($text_arr[1]->title)){
						$text_arr_1_title=$text_arr[1]->title;
					}
					$text_arr_1_link='';
					if(isset($text_arr[1]->link)){
						$text_arr_1_link=$text_arr[1]->link;
					}
					$text_arr_1_id='';
					if(isset($text_arr[1]->id)){
						$text_arr_1_id=$text_arr[1]->id;
					}

				?>
				文本标题：<input type="text" name="def_text[]" size='15' value="<?php echo $text_arr_1_title;?>">&nbsp;链接：<input type="text" name="def_name[]" value="<?php echo $text_arr_1_link;?>"><input type="hidden" name="def_id[]" value="<?php echo $text_arr_1_id;?>"><br />
				<?php
					$text_arr_2_title='';
					if(isset($text_arr[2]->title)){
						$text_arr_2_title=$text_arr[2]->title;
					}
					$text_arr_2_link='';
					if(isset($text_arr[2]->link)){
						$text_arr_2_link=$text_arr[2]->link;
					}
					$text_arr_2_id='';
					if(isset($text_arr[2]->id)){
						$text_arr_2_id=$text_arr[2]->id;
					}

				?>
				文本标题：<input type="text" name="def_text[]" size='15' value="<?php echo $text_arr_2_title;?>">&nbsp;链接：<input type="text" name="def_name[]" value="<?php echo $text_arr_2_link;?>"><input type="hidden" name="def_id[]" value="<?php echo $text_arr_2_id;?>"><br />
				
			</div>
			</td><?php }?>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            // Show title
            echo Html::input('checkbox', 'mb[show_title]', '1', 
                Toolkit::switchText($curr_mblock->show_title, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Show Title'); 
            // Display on all pages
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            $checked = '';
            if ($curr_mblock->s_query_hash == '_ALL') {
                $checked = 'checked="checked"';
            }
            echo Html::input('checkbox', 'dispallpg', '1', $checked);
            ?>
            &nbsp;<?php _e('Display on all pages');
            // 	Member only access
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_mblock->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
             &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
        <!-- [Disable publish status temporarily] tr>
            <td class="label"><?php _e('Publish'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[published]', '1', 
                Toolkit::switchText($curr_mblock->published, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr >
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            $checked = '';
            if ($curr_mblock->s_query_hash == '_ALL') {
                $checked = 'checked="checked"';
            }
            echo Html::input('checkbox', 'dispallpg', '1', $checked);
            ?>
            &nbsp;<?php _e('Display on all pages'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_mblock->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr -->
        <?php
        for ($i = 0; $i < sizeof($params); $i++) {
			if($_GET['mar']==1) {
				if(isset($arr_params['marquee_data2'])&&substr($params[$i]['id'],0,-2)=='mar_article_id' && $arr_params['marquee_data2']==1){
					$display = '';
				} else if(substr($params[$i]['id'],0,-2)=='mar_prd_id' && isset($arr_params['marquee_data1'])&& $arr_params['marquee_data1']==1){
					$display = '';
				}else if(in_array($params[$i]['id'],array('marquee_width','marquee_speed','mar_direc_id','marquee_class','marquee_data'))){
					$display = '';
				}else{
					$display = 'none';
				}
			}
			// for sitestarv1.3 slide_img_height/width start
			if (!in_array($params[$i]['id'], array('slide_img_width','slide_img_height','slide_img_open'))) {
        ?>
     
        <tr id=<?php echo substr($params[$i]['id'],0,-2).'tr'; ?> style="display:<?php echo isset($display)?$display:'';?>">
            <td class="label"><?php _e($params[$i]['label']); ?></td>
            <td class="entry" <?php if (in_array($params[$i]['tag'],array('slide_input','slide_imgpicker'))){?>colspan="2"<?php }?>>
        <?php
            switch ($params[$i]['tag']) {
                case 'input':
                	switch ($params[$i]['type']) {
                		case 'text':
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $arr_params[$params[$i]['id']], 
		                        $params[$i]['extra']);
								if($params[$i]['id']=='bulletin_speed'){
								 echo "&nbsp;&nbsp;"; _e("Larger value, the slower the speed,preferred values");echo 40;
							}
                			break;
                	    case 'checkbox':
                	    	$element_extra = $params[$i]['extra'];
                	    	if (intval($arr_params[$params[$i]['id']]) == 1) {
                	    	    $element_extra .= ' checked="checked"';
                	    	}
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], 
		                        $element_extra);
                	    	break;
                	    case 'img_open': 
                	    	
                	    	$image_data = unserialize($curr_mblock->s_param); 
                	    	$image_blank='';
                	    	if ($image_data['image_open']=='1') {
                	    		$image_self = "checked";
                	    	}else {
                	    		$image_blank = "checked";
                	    	}
                	    	
				            echo Html::input('radio', 'ex_params[image_open]', '1',$image_self)."&nbsp;&nbsp;&nbsp;";				         
				            echo _e('image self');				          
				            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				            echo Html::input('radio', 'ex_params[image_open]','2',$image_blank)."&nbsp;&nbsp;&nbsp;";				          
				            echo _e('image blank');  
                	    	break;
                	    case 'floatType':
                	    	$showType = unserialize($curr_mblock->s_param); 
                	    	$lang = $_SITE->s_locale=='zh_CN'?'c':'e';
                	    	$image_blank='';
                	    	if ($showType['qq_show_type']=='1') {
                	    		$image1 = "checked";
                	    		$display_qq1 = "block";
                	    		$display_qq2 = 'none';
                	    	}elseif ($showType['qq_show_type']=='2') {
                	    		$display_qq2 = "block";
                	    		$display_qq1 = "none";
                	    		$image2 = "checked";
                	    	}else{
                	    		$image3 = "checked";
							}
                	    	echo Html::input('radio', 'ex_params[qq_show_type]', '1',$image1." onclick=show_type('1');")."&nbsp;&nbsp;&nbsp;";			
				             _e('Effect1');				          
				            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				            echo Html::input('radio', 'ex_params[qq_show_type]','2',$image2." onclick=show_type('2');")."&nbsp;&nbsp;&nbsp;";			
				            _e('Effect2'); 
				             echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				             echo "<div id=\"aa1\" class=\"qqType\" style=\"display:{$display_qq1}\"><image src=\"images/head_1_{$lang}.gif\" /></div><div id=\"aa2\" class=\"qqType\" style=\"display:{$display_qq2}\"><image src=\"images/head_2_{$lang}.gif\" /></div>";
                	    	break;
						case 'checkbox2':
							$check1='';
							$check2='';
							$check3='';
							if(isset($arr_params['marquee_data1'])&&$arr_params['marquee_data1']==1) { $check1= 'checked="checked"';}
							if(isset($arr_params['marquee_data2'])&&$arr_params['marquee_data2']==1) { $check2= 'checked="checked"';}
							if(isset($arr_params['marquee_data3'])&&$arr_params['marquee_data3']==1) { $check3= 'checked="checked"';}
							//if($arr_params['marquee_class']=='pic') { $pic_none= 'none';}else{$pic_none= '';}
		                    echo '<span id="mar_data1">'.__("Product").':';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][0].']', 
		                        $params[$i]['value'], 
		                        'onclick=changeprd("mar_prd_idtr","ex_params_marquee_data1_") '.$check1);echo '</span>';
							
 							echo '&nbsp;&nbsp;&nbsp;&nbsp; 
 							<span id="mar_data12">'.__("Product List").':
 							<a href="admin/index.php?_m=mod_product&_a=prd_list&keepThis=true&TB_iframe=true&height=300&width=520" title="'.__("Product List").'" class="thickbox">';echo Html::input('button', 'ex_params['.$params[$i]['page'][0].'2]',  __("Click and get"), 'onclick="prd_list();"');echo '</a>
 							</span>' ;
 							
 							/*echo '<span id="mar_data2" style="display:none">'.__("Article").':';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][1].']', 
		                        $params[$i]['value'], 
		                        'onclick=changeprd("mar_article_idtr","ex_params_marquee_data2_") '.$check2);echo '</span>';*/
							/*echo '<span id="mar_data3">自定义:';echo Html::input('checkbox', 
		                        'ex_params['.$params[$i]['page'][2].']', 
		                        $params[$i]['value'], 
		                       'onclick=changeprd("prd_info","ex_params_marquee_data3_") '.$check3);echo '</span>';
                	    	break; */
                	}
                    break;
                case 'textarea':
                	if ($params[$i]['id'] == 'html') { // for fckeditor
                	?>
<script language="javascript">
function setCookie(name,value)
{var Days = 1;
var exp= new Date();
exp.setTime(exp.getTime() + Days*24*60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
setCookie("language_info",'<?php echo trim(SessionHolder::get('_LOCALE'));?>');		
</script>
 <?php
                    	RichTextbox::jsinclude('admin/richtexteditor');
                    	echo Html::textarea('ex_params['.$params[$i]['id'].']', $arr_params[$params[$i]['id']], $params[$i]['extra'])."\n";
			            $o_fck = new RichTextbox('ex_params['.$params[$i]['id'].']');
			            echo $o_fck->create();
                    } else {
	                    echo Html::textarea('ex_params['.$params[$i]['id'].']', 
	                        $arr_params[$params[$i]['id']], 
	                        $params[$i]['extra']);
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
                        $parmval="";
                        if(isset($arr_params[$params[$i]['id']])) $parmval=$arr_params[$params[$i]['id']];
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$hash_entry, $parmval, 
                	    		$params_1_extra);
                	    	break;
                	    // 28/4/2010 Add >>
                	    case 'multiple':
                	    	$key = str_replace('[]', '', $params[$i]['id']);
							$cids='';
							if(isset($arr_params[$key])){
				        		$cids = $arr_params[$key];
							}
							$params_cids='';
							if(isset($params[$i]['cids'])){
								$params_cids=$params[$i]['cids'];
							}
				        	echo '<input type="hidden" name="cids" id="cids'.$params_cids.'" value="'.$cids.'" />';
				        	
                	    	$obj_name = $params[$i]['obj_name'];
                	    	$obj = new $obj_name();
                	    	$func = $params[$i]['func_name'];
                	    	$hash_entry =& $obj->$func();
							if(	$obj_name=='ArticleCategory'){
								$all_categories =& ArticleCategory::listCategories(0, "s_locale=?",
								array(SessionHolder::get('_LOCALE')));
								$select_categories = array();
								ArticleCategory::toSelectArray($all_categories, $select_categories,
								0, array(0), array('0' => __('Uncategorised')));
								$hash_entry=$select_categories;
							}
							/*if(	$obj_name=='ProductCategory'){
							    $all_categories =& ProductCategory::listCategories(0, "s_locale=?",
       							array(SessionHolder::get('_LOCALE')));
								$select_categories = array();
        						ProductCategory::toSelectArray($all_categories, $select_categories,
	        					0, array(0), array('0' => __('Uncategorised')));
								$hash_entry=$select_categories;
							}*/
                	    	echo Html::select($params[$i]['id'], 
                	    		$hash_entry, '', $params[$i]['extra']);
                	        echo '&nbsp;&nbsp;<img id="answer" class="title" src="admin/template/images/answer1.gif" alt="help" title="'.__('Multi-select by Ctrl or Shift').'" />';
                	        break;
                	    // 28/4/2010 Add <<
                	    case 'array':
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$params[$i]['data'], $arr_params[$params[$i]['id']], 
                	    		$params[$i]['extra']);
                	    	break;
                	}
                	break;
                case 'imgpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                $arr_params[$params[$i]['id']], $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
                	break;
                // sitestarv1.3 09/09/2010 start
                case 'slide_imgpicker':
                	 echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                $arr_params[$params[$i]['id']], $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
		            echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="AddSlideTr();return false;">'.__('Add the flash slide items').'</a>';
                	break;
                case 'slide_input':
                	echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $arr_params[$params[$i]['id']], $params[$i]['extra']);
		        	if ($params[$i]['id'] == 'slide_img_uri1') {
		            	echo '&nbsp;&nbsp;<img class="title" src="admin/template/images/answer1.gif" alt="help" title="'.__('Click the picture after the jump to the web site').'" />';
		            }
                	break;
                // sitestarv1.3 09/09/2010 end 
                case 'flvpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                $arr_params[$params[$i]['id']], $params[$i]['extra']);
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
	                        $arr_params[$params[$i]['id']], $params[$i]['extra']);
					break;
            }
        ?>
            </td>
        </tr>
        <?php
        	}

        }
        //如果走马灯的样式是图片加文字，则显示具体的产品
        if (sizeof($prdListArr)>0 && $arr_params['marquee_data1']!=1) { 
    		echo '<tr id="prd_list"><td class="label">'. __("Product").'</td><td class="entry"><div id="getPrdList"> ';
    		foreach($prdListArr as $prdList) {
    			foreach ($prdList as $p_k=>$p_v){
        ?>
         
      <li><input type="hidden" name="ex_params[mar_prd_id2][]" value="<?php echo $p_k;?>" />
      <?php echo $p_v; ?>
      <font style='color:red;cursor:pointer;' onclick='prd_remove(this);'>&nbsp;&nbsp;&nbsp;删除</font></li>
       
       <?php
    			}
    		}
    		echo '</div></td></tr><input type="hidden" name="ex_params[prd_list_tag]" value="1" id="prd_list_tag" />';
       	}
       	?>
        <?php
        // for sitestarv1.3 adding flash-slide item(s)
        foreach ($arr_params as $ky => $val) {
    		$math = array();
    		if (preg_match("/^slide\_img\_src(\d+)$/i", $ky, $math)) {
    			$k = $math[1];
    			if ((intval($k) > 1) && !empty($arr_params['slide_img_src'.$k])) {
    				echo '<tr id="slide_img_sctr'.$k.'"><td class="label">'.__('Select Image').'</td><td colspan="2" class="entry" style="border-top:1px dashed #F35D02">';
    				echo Html::input('text', 'ex_params[slide_img_src'.$k.']', $arr_params['slide_img_src'.$k], 'class="txtinput"')."&nbsp;";
    				echo '<a onclick="popup_win=show_imgpicker(\'ex_params[slide_img_src'.$k.']\');return false;" href="#">'.__('Select Image').'</a>';
    				echo '&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="DelSlideTr('.$k.');return false;" href="javascript:void(0);">'.__('Delete the flash slide items').'</a></td></tr>';
					echo '<tr id="slide_img_ordetr'.$k.'"><td class="label">'.__('Display order').'</td><td colspan="2" class="entry">';
					echo Html::input('text', 'ex_params[slide_img_order'.$k.']', $arr_params['slide_img_order'.$k], 'class="txtinput" size="4"')."</td></tr>";
    				echo '<tr id="slide_img_urtr'.$k.'"><td class="label">'.__('Image Url').'</td><td colspan="2" class="entry">';
    				echo Html::input('text', 'ex_params[slide_img_uri'.$k.']', $arr_params['slide_img_uri'.$k], 'class="txtinput" size="28"');
    				echo '&nbsp;&nbsp;<img class="title" src="admin/template/images/answer1.gif" alt="help" title="'.__('Click the picture after the jump to the web site').'" /></td></tr>';
    				echo '<tr id="slide_img_destr'.$k.'"><td class="label">'.__('Description').'</td><td colspan="2" class="entry">';
    				echo Html::input('text', 'ex_params[slide_img_desc'.$k.']', $arr_params['slide_img_desc'.$k], 'class="txtinput" size="28"')."</td></tr>";
    			}
    		} else continue;
    	}
    	// slide_img_open
    	if (isset($arr_params['slide_img_open'])) {
    		$image_self = $image_blank = '';
    		switch ($arr_params['slide_img_open']) {
    			case '1':
    				$image_self = "checked";
    				break;
    			case '2':
    				$image_blank = "checked";
    				break;
    		}
    		echo '<tr id="slide_img_optr"><td class="label">'.__('open image in window from self or blank').'</td><td class="entry">';
            echo Html::input('radio', 'ex_params[slide_img_open]', '1',$image_self)."&nbsp;&nbsp;&nbsp;";				         
            echo _e('image self');				          
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('radio', 'ex_params[slide_img_open]', '2',$image_blank)."&nbsp;&nbsp;&nbsp;";				          
            echo _e('image blank')."</td></tr>"; 
    	}
    	// slide_img_height/width
    	if (isset($arr_params['slide_img_width'])) {
    		echo '<tr id="slide_img_widtr"><td class="label">'.__('Flash slide width').'</td><td colspan="2" class="entry">';
    		echo Html::input('text', 'ex_params[slide_img_width]', $arr_params['slide_img_width'], 'class="txtinput" size="4"')."</td></tr>";
    	}
    	if (isset($arr_params['slide_img_height'])) {
    		echo '<tr id="slide_img_heigtr"><td class="label">'.__('Flash slide height').'</td><td colspan="2" class="entry">';
    		echo Html::input('text', 'ex_params[slide_img_height]', $arr_params['slide_img_height'], 'class="txtinput" size="4"')."</td></tr>";
    	}
       	?>
    </tbody>
</table>
<script language="javascript">

var zmdtype;






var mmx=0;

 var optsx = document.getElementById('ex_params_marquee_class_').options;
  if (optsx.length > 0)
  {
   for (var i = 0; i < optsx.length; i++)
   {
   if( optsx[i].selected == true){mmx=i;}
	}
  }

if(mmx==0){
var mm=0;

 var opts = document.getElementById('ex_params_mar_direc_id_').options;
  if (opts.length > 0)
  {
   for (var i = 0; i < opts.length; i++)
   {
   if( opts[i].selected == true){mm=i;}
	}
  }

document.getElementById("ex_params_mar_direc_id_").options.length=0;
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Right');?>","right"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Left');?>","left"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Top');?>","top"));
document.getElementById("ex_params_mar_direc_id_").options.add(new Option("<?php echo __('Down');?>","down"));

if(mm==3){
document.getElementById("ex_params_mar_direc_id_").options[1].selected=true;
}
}
</script>
<?php
$mb_form->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$mb_form->addCustValidationJs($custom_js);
$mb_form->writeValidateJs();
?>
