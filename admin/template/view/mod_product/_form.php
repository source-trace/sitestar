<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$act_2='';
if(isset($act)){
	$act_2=$act;
}
$curr_product_is_seo='';
if(isset($curr_product->is_seo)){
	$curr_product_is_seo=$curr_product->is_seo;
}
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '165px',height:'33px'});
	$('#answer2').cluetip({splitTitle: '|',width: '235px',height:'33px'});
	$('#answer3').cluetip({splitTitle: '|',width: '235px',height:'33px'});
});
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '165px',height:'33px'});
});
function on_failure(response) {
    document.forms["productform"].reset();
    
    document.getElementById("adminprdfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function on_quick_add_cate_p_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminprdfrm_stat");
    if (o_result.result == "ERROR") {
        $("#new_cate_p").val("");
        
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var cate_select = document.getElementById("prd_product_category_id_");
        var after_idx = cate_select.selectedIndex;
        var new_id = o_result.id;
        var new_text = $("#new_cate_p").val();
        var parent_id = cate_select.options[after_idx].value;
        var level_count = cate_select.options[after_idx].text.count("--");

        for (var i = cate_select.length - 1; i > after_idx; i--) {
            cate_select.options[i + 1] = new Option();
            cate_select.options[i + 1].value = cate_select.options[i].value;
            cate_select.options[i + 1].text = cate_select.options[i].text;
        }
        if (typeof(cate_select.options[i + 1]) == "undefined") {
            cate_select.options[i + 1] = new Option();
        }
        cate_select.options[i + 1].value = new_id;
        if (parent_id == "0") {
            cate_select.options[i + 1].text = " " + new_text;
        } else {
            cate_select.options[i + 1].text = " " + "-- ".repeat(level_count + 1) + new_text;
        }
        cate_select.options[i + 1].selected = "selected";
    } else {
        return on_failure(response);
    }
}

function on_remove_extpic_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminprdfrm_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var img_id = "#exp_pic_" + o_result.id;
        $(img_id).hide();
    } else {
        return on_failure(response);
    }
}

function add_cate_p() {
    _ajax_request("mod_category_p", 
        "admin_quick_create", 
        {
            name: $("#new_cate_p").val(),
            parent: $("#prd_product_category_id_").val(),
            locale: $("#prd_s_locale_").val()
        }, 
        on_quick_add_cate_p_success, 
        on_failure);
}

function add_pic_upload() {
    $("#prd_gala_pic_uploader").append("<input type=\"file\" name=\"prd_extpic[]\" /><br />");
}

function remove_extpic(pic_id) {
    if (confirm("<?php _e('Delete selected picture?'); ?>")) {
        _ajax_request("mod_product", 
            "admin_delete_extpic", 
            { p_id: pic_id }, 
            on_remove_extpic_success, 
            on_failure);
    }
}

$(function(){
	var act = "<?php echo $act_2;?>";
	if ( act == 'add' ) {
		$('<option value="-1" selected=true><?php _e('Please add category');?></option>').prependTo('#prd_product_category_id_');
		opt = document.getElementById('prd_product_category_id_');
		if( opt.options[0].value == '-1' ) opt.options[0].selected = true;
	    $('#prd_product_category_id_').click(function(){
	    	opt = document.getElementById('prd_product_category_id_');
	    	if( opt.options[0].value == '-1' ) {
	    		opt.options[1].selected = true;
	    		opt.remove(0);
	    	}
	    });
	}
	var iseo = "<?php echo $curr_product_is_seo;?>";
	setSeo(iseo);
});

function addSort(obj) {
	$(obj).parent().find('span').css('display','inline-block');
	$(obj).css('display','none');
	$(obj).parent().find('span > input:first').focus();
}

function newDir(obj) {
	var pth = $(obj).prev().attr('value');
	var basepth = $('#gtcurdir option:selected').text();
	if (pth.replace(/^\s+|\s+$/g,'').length == 0) {
		alert('Please input directory name!');
		$(obj).prev().focus();
		return false;
	} else {
		var params = {basedir: basepth, newdir: pth};
		var url = "index.php?_m=mod_product&_a=mk_dir&_r=_ajax";
	    // Reform query string
	    for (key in params) {
	       url += "&" + key + "=" + params[key];
	    }
		$.ajax({
	        type    : "GET",
	        url     : url,
	        success : function(response) {
	        	var o_result = _eval_json(response);

			    if (!o_result) {
			        return onfailed(response);
			    }
			    
			    if (o_result.result == "ERROR") {
			        switch (o_result.errmsg) {
			    		case "-1":
			    			alert('The folder is exist!');
			    		    $(obj).prev().focus();
			    			break;
			    		case "-2":
			    			alert('Make directory failed!');
			    			break;
			    	}
			        return false;
			    } else if (o_result.result == "OK") {
			        $(obj).prev().val('');
				    $(obj).parent().css('display','none');
				    $(obj).parent().parent().find('a').css('display','inline-block');
				    $('<option value="'+basepth+pth+'/" selected="true">'+basepth+pth+'/</option>').appendTo('#gtcurdir');
			        //reloadPage();
			    } else {
			        return onfailed(response);
			    }
	        },
	        error   : function(response) {
	        	alert('Request failed!');
	    		return false;
	        }
	    });
	}
}
function setSeo(val) {
	$('.seoption').css('display','none');
	if (val == '1') {
		$('.seoption').css('display','');
	} else {
		$('.seoption input').val('');
	}
}
//-->
</script>
<script>   
  function   DrawImage(ImgD,wid_th,hei_th){   
        var   image=new   Image();   
        var   iwidth   =   wid_th;     //定义允许图片宽度   
        var   iheight   =   hei_th;     //定义允许图片高度   
        image.src=ImgD.src;   
        if(image.width>0   &&   image.height>0){   
          flag=true;   
          if(image.width/image.height>=   iwidth/iheight){   
            if(image.width>iwidth){       
            ImgD.width=iwidth;   
            ImgD.height=(image.height*iwidth)/image.width;   
            }else{   
            ImgD.width=image.width;       
            ImgD.height=image.height;   
            }   
           // ImgD.alt=image.width+"×"+image.height;   
            }   
          else{   
            if(image.height>iheight){       
            ImgD.height=iheight;   
            ImgD.width=(image.width*iheight)/image.height;             
            }else{   
            ImgD.width=image.width;       
            ImgD.height=image.height;   
            }   
          //  ImgD.alt=image.width+"×"+image.height;   
            }   
          }   
  }     
    
  </script> 

<div class="status_bar">
<?php if (Notice::get('mod_product/msg')) { ?>
	<span id="adminprdfrm_stat" class="status"><?php echo Notice::get('mod_product/msg'); ?></span>
<?php } else { ?>
    <span id="adminprdfrm_stat" class="status" style="display:none;"></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$prd_form = new Form('index.php', 'productform', 'check_product_info');
$prd_form->setEncType('multipart/form-data');
$prd_form->p_open('mod_product', $next_action);
?>
<table id="productform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_product_id='';
			if(isset($curr_product->id)){
				$curr_product_id=$curr_product->id;
			}
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'prd[id]', $curr_product_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="10%"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText((isset($curr_product->s_locale)&&$curr_product->s_locale)?$curr_product->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'prd[s_locale]', 
           		(isset($curr_product->s_locale)&&$curr_product->s_locale)?$curr_product->s_locale:$mod_locale);
            ?><script language="javascript">
function setCookie(name,value)
{
　　var Days = 1; 
　　var exp　= new Date();
　　exp.setTime(exp.getTime() + Days*24*60*60*1000);
　　document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
setCookie("language_info",'<?php echo $language_info;?>');

			
			</script>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Category'); ?></td>
            <td class="entry">
            <?php
			$curr_product_product_category_id='';
			if(isset($curr_product->product_category_id)){
				$curr_product_product_category_id=$curr_product->product_category_id;
			}
            echo Html::select('prd[product_category_id]', 
            	$select_categories, 
            	$curr_product_product_category_id, 'class="textselect"');
            ?>
            &nbsp;<a href="#" onclick="add_cate_p(); return false;"><?php _e('Add Category'); ?></a>
            &nbsp;<?php echo Html::input('text', 'new_cate_p', '', 'class="textinput" style="width:190px;"'); ?>
            &nbsp;<a href="<?php echo Html::uriquery('mod_category_p', 'admin_list'); ?>" title=""><?php _e('Manage Categories'); ?></a>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Name'); ?></td>
            <td class="entry">
            <?php
			$curr_product_name='';
			if(isset($curr_product->name)){
				$curr_product_name=$curr_product->name;
			}
            echo Html::input('text', 'prd[name]', $curr_product_name, 
                'class="textinput"', $prd_form, 'RequiredTextbox', 
                __('Please input name!'));
            ?>
            </td>
        </tr>
		        <tr>
            <td class="label"><?php _e('Set SEO'); ?></td>
            <td class="entry">
            <?php
            if (isset($curr_product->is_seo) && $curr_product->is_seo == '1') {
            	$checked01 = 'checked';
            	$checked02 = '';
            } else {
            	$checked01 = '';
            	$checked02 = 'checked';
            }
            echo Html::input('radio', 'prd[is_seo]', '1', 'onclick="setSeo(this.value)"'.$checked01).'&nbsp;&nbsp;'.__('Yes').'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('radio', 'prd[is_seo]', '0', 'onclick="setSeo(this.value)"'.$checked02).'&nbsp;&nbsp;'.__('No').'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo __("select 'yes' means separately setting SEO parameters for this page,  global parameters are invalid to this page").'&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?_m=mod_static&_a=seo" target="_blank">'.__('About SEO').'</a>';
            ?>
            </td>
        </tr>
        <tr class="seoption" style="display:none;">
            <td class="label"><?php _e('Tags');echo '(Keyword)'; ?></td>
            <td class="entry">
            <?php

			$curr_product_meta_key='';
			if(isset($curr_product->meta_key)){
				$curr_product_meta_key=$curr_product->meta_key;
			}
            echo Html::input('text', 'prd[meta_key]', $curr_product_meta_key, 'class="textinput"');
            ?>
			<?php 
			if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer2" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set key note');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr class="seoption" style="display:none;">
            <td class="label"><?php _e('Description');echo '(Description)'; ?></td>
            <td class="entry">
            <?php
			$curr_product_meta_desc='';
			if(isset($curr_product->meta_desc)){
				$curr_product_meta_desc=$curr_product->meta_desc;
			}
            echo Html::input('text', 'prd[meta_desc]', $curr_product_meta_desc, 'class="textinput"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer3" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set search note');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Publish Date'); ?></td>
            <td class="entry">
            <?php
            if( !isset($curr_product->create_time) || empty($curr_product->create_time)) {
            	$dateline = date('Y-m-d H:i:s');
            } else {
            	$dateline = date('Y-m-d H:i:s', $curr_product->create_time);
            }
            echo Html::input('text', 'prd[date]', $dateline, 'class="textinput" style="width:150px;"');
            ?>
            <img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set date note');?>"/>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Introduction'); ?></td>
            <td class="entry">
			<?php
			$curr_product_introduction='';
			if(isset($curr_product->introduction)){
				$curr_product_introduction=$curr_product->introduction;
			}
            echo Html::textarea('prd[introduction]', $curr_product_introduction, 'rows="8" cols="76" class="textinput" style="width:500px"', $prd_form)
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Product Description');?></td>
            <td class="entry">
            <?php
            $pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			$path = substr($_SERVER['PHP_SELF'],0,$pos);
			$curr_product_description='';
			if(isset($curr_product->description)){
				$curr_product_description=$curr_product->description;
			}
			if(strpos($curr_product_description,$path.'/') == 0) {
				$description = str_replace
('/admin/fckeditor',$path.'/admin/fckeditor',$curr_product_description);
			} else {
				$description = $curr_product_description;
			}
            echo Html::textarea('prd[description]', $description, 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('prd[description]');
        
            $o_fck->height = 420;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Upload path'); ?></td>
            <td class="entry"><?php
			$dirlist = $dirs = array();
			$base_upload_dir = 'upload/image/';
			$dirs = gtAllDir($base_upload_dir);
			if (is_array($dirs) && count($dirs) > 0) {
				array_unshift($dirs, $base_upload_dir);
			} else {
				$dirs = array($base_upload_dir);
			}
			//array_unshift($dirs, $base_upload_dir);

			function gtAllDir($base_upload_dir) {
				global $dirlist;

				$handle = dir(ROOT."/$base_upload_dir");
				while(($path = $handle->read()) !== false) {
					if (!in_array($path, array(".", "..", ".svn")) && is_dir(ROOT."/{$base_upload_dir}{$path}/"))	{
						$dirlist[] = $base_upload_dir."{$path}/";
						gtAllDir($base_upload_dir."{$path}/");
					} else continue;
				}
				$handle->close();
				
				return $dirlist;
			}
			?>
			<select name="curdir" id="gtcurdir" class="textselect">
				<?php
				// display dir list
				foreach($dirs as $dir) {
				?>
				<option value="<?php echo $dir;?>"><?php echo $dir;?></option>
				<?php }?>
			</select>&nbsp;<a href="javascript:;" style="color:#FF0000;" onclick="addSort(this)"><?php _e('New Folder'); ?></a>&nbsp;<span style="display:none;"><input autocomplete="off" class="textinput" style="width:150px;" type="text" onkeyup="value=value.replace(/[^\w\)\(\- ]/g,'')" size="10" /><input type="button" onclick="newDir(this)" name="btnSubmit" value=" <?php _e('New'); ?> " /></span>&nbsp;&nbsp;</font>
			</td>
        </tr>
        <tr>
            <td class="label"><?php _e('Full Image'); ?></td>
            <td class="entry">
            <?php
			if(isset($p_id)&&$p_id) {
			echo Html::input('hidden', 'prd[feature_img]', $curr_product->feature_img);
			?>
			<img src="../<?php echo $curr_product->feature_smallimg;?>"  onload="DrawImage(this,180,120);"><br />
			<?php
				echo Html::input('file', 'prd_file', '', 
                '', $prd_form);
			}else{
				echo Html::input('file', 'prd_file', '', 
                '', $prd_form, 'RequiredTextbox', 
                __('Please select a product big image to upload!'));
			}
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
		<!--tr>
            <td class="label"><?php _e('Thumbnail'); ?></td>
            <td class="entry">
            <?php
			if($p_id) {
			?>
			<img src="../<?php echo $curr_product->feature_smallimg?>"><br />
			<?php
				echo Html::input('file', 'prd_small_file', '', 
                '', $prd_form);
			}else{
				echo Html::input('file', 'prd_small_file', '', 
                '', $prd_form, 'RequiredTextbox', 
                __('Please select a product small image to upload!'));
			}
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr-->
        <tr>
            <td class="label"><?php _e('More Pics'); ?></td>
            <td class="entry">
                <?php _e("Click picture to remove from product gallary!"); ?><br />
                <div id="prd_gala_pics">
            <?php
            // Now get all extra pictures
               
                if (sizeof($ext_pics) > 0) {
                    foreach ($ext_pics as $pic) {
            ?>
            
                <div  onclick="remove_extpic(<?php echo $pic->id; ?>);"  style="width:auto;height:auto;">
                <img class="prd_gala_pic" src="../<?php echo $pic->pic; ?>" onload="DrawImage(this,180,120);"   id="exp_pic_<?php echo $pic->id; ?>" />
                </div>
            <?php
                    }
                }
            
            ?>
                </div>
 
                <div id="prd_gala_pic_uploader">&nbsp;&nbsp;<br>
                    <input type="file" name="prd_extpic[]" /><br />
                    <input type="file" name="prd_extpic[]" /><br />
                    <input type="file" name="prd_extpic[]" /><br />
                </div>
                <a href="#" onclick="add_pic_upload();return false;"><?php _e('More'); ?></a><br />
                <?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?><br />
                <?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
        <?php if (intval(EZSITE_LEVEL) > 1) { ?>
        <tr>
            <td class="label"><?php _e('Price'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[price]', (isset($curr_product->price)&&$curr_product->price)?$curr_product->price:'0.00', 
                'class="textinput" style="width:80px;"', $prd_form, 'RequiredTextbox', 
                __('Please input price!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Discount Price'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[discount_price]', isset($curr_product->discount_price)?$curr_product->discount_price:'0.00', 
                'class="textinput" style="width:80px;"', $prd_form);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Delivery Fee'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[delivery_fee]', isset($curr_product->delivery_fee)?$curr_product->delivery_fee:'0.00', 
                'class="textinput" style="width:80px;"', $prd_form, 'RequiredTextbox', 
                __('Please input delivery fee!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_product_online_orderable='';
			if(isset($curr_product->online_orderable)){
				$curr_product_online_orderable=$curr_product->online_orderable;
			}
            echo Html::input('checkbox', 'prd[online_orderable]', '1', 
                Toolkit::switchText($curr_product_online_orderable, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Online Orderable'); ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_product_recommended='';
			if(isset($curr_product->recommended)){
				$curr_product_recommended=$curr_product->recommended;
			}
            echo Html::input('checkbox', 'prd[recommended]', '1', 
                Toolkit::switchText($curr_product_recommended, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Recommend'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_product_for_roles='';
			if(isset($curr_product->for_roles)){
				$curr_product_for_roles=$curr_product->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_product_for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$prd_form->close();
$prd_form->writeValidateJs();
?>
