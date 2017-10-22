<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cols = 4;//每个页面，每行4列展示
$category_manimun = 4;//模板类别展示最小值
$category_cols = 4;//模板分类列表4列排列
$sceenpic = substr($_SERVER['HTTP_REFERER'],0,strpos($_SERVER['HTTP_REFERER'],"admin/index.php"));
$tmp_arr = array();
$tmp_arr1 = array();

function echocolorclass($origincolor,$color){
	if(!empty($origincolor)){
			if(strcmp($origincolor, $color)===0){
				echo " class=\"select\"";
			}
	}
}
?>
<div id="loading_overlay" style="display:none;"></div>
<div id="loading_anim" style="display:none;"><img src="images/loadingAnimation.gif" /></div>
<style type="text/css">
.d_c_list{ width:692px; background:#f2f2f2;border:1px solid #e2e2e2;bottom:12px;right:1px;overflow: hidden;}
.d_c_list ul{background:none; height:auto; border:none;}
.d_c_list ul li{ width: 150px; float:left; _display:inline; white-space: nowrap; height:22px; line-height:22px; overflow:hidden; margin-bottom:5px;}
.template_grid {
	width: 100%;
}
.template_grid td {
	text-align: center;
	background: #f8f8f8;
	border:0px;
	/*padding-top:20px;*/
}
.template_grid td img.tpl_thumb {
	border-top: 2px solid #dedede;
	border-left: 2px solid #dedede;
	border-right: 2px solid #999;
	border-bottom: 2px solid #999;
	width: 160px;
	height: 120px;
	margin-top: 4px;
	padding:5px;
}

.template_grid td img.tpl_thumb1 {
	border-top: 2px solid #dedede;
	border-left: 2px solid #dedede;
	border-right: 2px solid #999;
	border-bottom: 2px solid #999;
	border-color:#f68b17;
	width: 160px;
	height: 120px;
	margin-top: 4px;
	padding:5px;
}

.template_grid .small {
    display: block;
    /*padding: 3px 0px;*/
}
#cate_new{background-color:#F8F8F8;}
#cate_new .selected{ background:url(images/selected_bg.jpg);color:#fff ;}
#cate_new .selected a{color:#fff !Important;}
#cate_new ul{height:33px;line-height:33px;list-style:none; margin:0 0 0 30px !Important; padding:5px 0; font-size:12px;}
#cate_new li{float:left;text-align:center;cursor:pointer;margin:0 !Important; padding:0; width:135px; height:33px; line-height:33px;background:url(images/selectli_bg.jpg)}
#cate_new li a{color:#000 !Important;display:block;width:135px; padding:0px !Important;}
#cate_new li a:hover{color:#fff !Important;}
#cate_new li:hover{background:url(images/selected_bg.jpg);}

</style>
<style>
#init{background:url(images/Init_bg.jpg) repeat-x; height:34px; font-size:12px; line-height:34px;}
#init_sub{float:left;background:url(images/Init_left.jpg) no-repeat;height:34px;}
#init_sub a{text-align:center; color:#fff; width:150px; display:block; background:url(images/Init_a.jpg) no-repeat; line-height:26px; margin:4px 4px 0px 4px;text-decoration:underline;}
#init_sub a:hover{background:url(images/Init_hover.jpg);}
#init_sub p{float:left; }
#init_right { background:url(images/Init_right.jpg) no-repeat top right;}
#tmpl_color{float:left; height:34px;margin-left: 97px;}

#tmpl_color p{float:left;}
.wp-manage-template-color span{ display:block; width:14px; height:14px; }
.wp-manage-template-color span.red_bg{ background:#ff0000;}
.wp-manage-template-color span.orange_bg{ background:#ff6600;}
.wp-manage-template-color span.yellow_bg{ background:#ffff00;}
.wp-manage-template-color span.green_bg{ background:#007600;}
.wp-manage-template-color span.light_blue_bg{ background:#00d9d9;}
.wp-manage-template-color span.blue_bg{ background:#0033ff;}
.wp-manage-template-color span.light_purple_bg{ background:#ff68ff;}
.wp-manage-template-color span.purple_bg{ background:#990099;}
.wp-manage-template-color span.white_bg{ background:#FFF; width:12px; height:12px; border:1px solid #d9d9d9;}
.wp-manage-template-color span.gray_bg{ background:#999;}
.wp-manage-template-color span.black_bg{ background:#000;}
.wp-manage-template-color span.brown_bg{ background:#964b00;}
.wp-manage-template-color span.coloful_bg{ background:url(images/colorful.gif) no-repeat;}
#content .wp-manage-template-color ul li{ margin-right:5px;}
.wp-manage-template-color span.red_bg{ background:#ff0000;}
.wp-manage-template-color{ float:left;height:34px;padding-top:6px; margin-left:9px;}
#content .wp-manage-template-color ul li{ display:block;margin-left:0px;width:14px; padding:1px; border:1px solid #fafafa; cursor:pointer;}
#content .wp-manage-template-color ul li.select{border:1px solid #555; }
</style>
<script type="text/javascript" language="javascript">
<!--

function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
    	reloadPage();
        return on_failure(response);
    }
    
    var stat = document.getElementById("admintpllst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        reloadPage();
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
        reloadPage();
    } else {
    	reloadPage();
        return on_failure(response);
        
    }
}

function on_del_failure(response) {
	//错误消息最好改成“已超时”
	document.getElementById("admintpllst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    reloadPage();
    return false;
}

function delete_tpl(tpl_id) {
    if (confirm("Delete the selected template?")) {
        var stat = document.getElementById("admintpllst_stat");
        stat.style.display = "block";
        stat.innerHTML = "<?php _e('Deleting selected template...'); ?>";
        _ajax_request("mod_template", 
            "admin_delete", 
            {
                tpl_id:tpl_id
            }, 
            on_del_success, 
            on_del_failure);
    }
}

function on_tog_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
    	reloadPage();
        return on_failure(response);
    }
    
    var stat = document.getElementById("admintpllst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        alert(o_result.errmsg);
        reloadPage();
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
        reloadPage();
    } else {
    	reloadPage();
        return on_failure(response);
    }
}

function on_tog_failure(response) {
    on_del_failure(response);
}

function reset_tpl_data() {
	if (!confirm("<?php _e('This action will take some time to download the reset-data from the server,please be patient.'); ?>")) return;
    show_iframe_win("index.php?_m=mod_template&_a=show_reset_data","",'483','210');
//    _ajax_request("mod_template",
//        "reset_tpl_data", false, 
//        on_reset_success, 
//        on_reset_failure);
}

//安装模板时触发
function toggle_default(tpl_id, is_remote,tpl_name) {
    var remote_tpl = "0";
    var url = "index.php?_m=mod_template&_a=admin_getInstallInfo&_r=_ajax&tpl_id="+tpl_id;
    if (is_remote) {
        remote_tpl = "1";
    }

	    $.post(url,{tpl_id:tpl_id},function(data){
				if(data == 'pass'){
					show_iframe_win("index.php?_m=mod_template&_a=template_remote&tag=pass&tpl_id="+tpl_id+"&is_remote="+remote_tpl+"&tpl_name="+tpl_name, '', 483, 210);
				}else if(data == 'refuse'){
					show_iframe_win("index.php?_m=mod_template&_a=template_remote&tag=refuse&tpl_id="+tpl_id+"&is_remote="+remote_tpl+"&tpl_name="+tpl_name, '', 483, 210);
					
				}else if(data == 'error2'){
					show_iframe_win("index.php?_m=mod_template&_a=template_remote&tag=error2&tpl_id="+tpl_id+"&is_remote="+remote_tpl+"&tpl_name="+tpl_name, '', 483, 210);
				}else{
					show_iframe_win("index.php?_m=mod_template&_a=template_remote&tag=net&tpl_id="+tpl_id+"&is_remote="+remote_tpl+"&tpl_name="+tpl_name, '', 483, 210);
					
				}
			})	

}

//设为默认模板时触发
function make_default(tpl_name) {
		show_iframe_win('index.php?_m=mod_template&_a=template_local&tpl_name='+tpl_name, '', 483, 210);
}
function directId(val){
	if (!/\d+/.test(val))
	{
		alert("请输入数字！")
		return false;
	}
	
	location.href="index.php?_m=mod_template&_a=admin_list&tpl_id=" + val;
}
$(function(){
	$('#wp-manage-color-opts span').click(function(e){
			var type='tpl_belong_color';
			var val=$(this).attr('id').replace('color_achor_', '');
			if($(this).parent().is('.select')){
				val=null;
				$(this).parent().removeClass('select');
			}else{
				$(this).parent().addClass('select').siblings('.select').removeClass('select');
			}
			if(val){
				var params={'_cates':'_color','sub_id':val}
				window.location.href="index.php?_m=mod_template&_a=admin_list&"+$.param(params);
			}else{
				window.location.href="index.php?_m=mod_template&_a=admin_list";
			}
			e.preventDefault();
	})
})
//-->
</script>

<?php 
$bool1 = extension_loaded('zip');
$bool2 = extension_loaded('soap');
$bool1 = true;//现在不用soap
$bool2 = true;//现在不用zip

if(!$is_ping)
{
	_e('Abnormal network conditions');
	die;
}

if(($bool1 && $bool2)){
?>


<div id="init">
        <div id="init_sub"><a href="#" onclick="reset_tpl_data();return false;"><?php _e('Website Initialization');?></a></div>
        <div id="init_right"><!--<marquee scrollAmount=2 width="80%"> <?php _e('Website Initialization Help');?></marquee>--></div>
		<div id="tmpl_color">
			<p><?php _e('Color:'); ?></p>
				<div  id="wp-manage-color-opts" class="wp-manage-template-color">
				<ul>
				<li<?php echocolorclass($color,'red') ?>><span id="color_achor_red" class="red_bg" title="<?php _e('Red'); ?>"></span></li>
				<li<?php echocolorclass($color,'orange') ?>><span id="color_achor_orange" class="orange_bg" title="<?php _e('Orange'); ?>"></span></li>
				<li<?php echocolorclass($color,'yellow') ?>><span id="color_achor_yellow" class="yellow_bg" title="<?php _e('Yellow'); ?>"></span></li>
				<li<?php echocolorclass($color,'green') ?>><span id="color_achor_green" class="green_bg" title="<?php _e('Green'); ?>"></span></li>
				<li<?php echocolorclass($color,'light_blue') ?>><span id="color_achor_light_blue" class="light_blue_bg" title="<?php _e('Light blue'); ?>"></span></li>
				<li<?php echocolorclass($color,'blue') ?>><span id="color_achor_blue" class="blue_bg" title="<?php _e('Blue'); ?>"></span></li>
				<li<?php echocolorclass($color,'light_purple') ?>><span id="color_achor_light_purple" class="light_purple_bg" title="<?php _e('Light purple'); ?>"></span></li>
				<li<?php echocolorclass($color,'purple') ?>><span id="color_achor_purple" class="purple_bg" title="<?php _e('Purple'); ?>"></span></li>
				<li<?php echocolorclass($color,'white') ?>><span id="color_achor_white" class="white_bg" title="<?php _e('White'); ?>"></span></li>
				<li<?php echocolorclass($color,'gray') ?>><span id="color_achor_gray" class="gray_bg" title="<?php _e('Grey'); ?>"></span></li>
				<li<?php echocolorclass($color,'brown') ?>><span id="color_achor_brown" class="brown_bg" title="<?php _e('Brown'); ?>"></span></li>
				<li<?php echocolorclass($color,'black') ?>><span id="color_achor_black" class="black_bg" title="<?php _e('Black'); ?>"></span></li>
				<li<?php echocolorclass($color,'colorful') ?>><span id="color_achor_colorful" class="coloful_bg" title="<?php _e('Colourful'); ?>"></span></li>
				</ul>
				</div><!--wp-manage-template-color end-->
		</div>
    </div>
<div class="status_bar">
    <span id="admintpllst_stat" class="status" style="display:none;"></span>
</div>
<?php }?>
<table class="list_table" id="admin_tpl_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<thead>
	</thead>
    <tbody>
	    <tr>
		    <td>
			    <table class="template_grid" cellspacing="0">
			    	<?php
			    	$html_script = "<div class='d_c_list'><ul style='background:none;border:none;'><li><a href='index.php?_m=mod_template&_a=admin_list&_cates=_all&_p=1'>所有模板</a><span style='color:#c7c7c7'></span></li>";
			    	if(empty($tmplate_category)) $tmplate_category = array();
			    	foreach($tmplate_category as $k => $v)
			    	{
			    		if($v['amount'] >= $category_manimun && $v['super_id']==0)
			    		{
			    			$html_script .= "<li><a href='index.php?_m=mod_template&_a=admin_list&_cates={$v['id']}&sub_id={$v['super_id']}&_p=1'>{$v['name']}</a><span style='color:#c7c7c7'></span></li>";
			    		}
			    	}
					
			    	$html_script .= '</ul></div>';
			    	//获取默认模板文件夹名称
			    	$n_tpls = sizeof($output_array);
			    	// 2011/02/25 过滤模板搜索结果中当前模板的记录 >>
			    	$tpl_id = trim(ParamHolder::get('tpl_id', 0));
//			    	if ($n_tpls && (intval($tpl_id) > 0)) {
//			    		if($install_template_num>0) $output_array=array_slice($output_array, $install_template_num);
//			    		$n_tpls -= $install_template_num;
//			    	} // 2011/02/25 <<
			    	$count_number = count($template_owns);
			    	if($category_number == '_all'&&intval($tpl_id)==0){
			    	for($i = 0; $i < $count_number; $i++)
			    	{
			    		if(is_array($template_owns[$i])) 
			    		{
			    			continue;
			    		}
			    		$tmp_arr1 = explode('#$',$template_owns[$i]);
						
			    		if(DEFAULT_TPL == $tmp_arr1[0])	//show choosed template
			    		{
			    			echo "<tr>";
			    			echo '<td width="'.intval(100 / $cols).'%">';
			    			echo '<a href="'.Html::uriquery('mod_modules', 'index').'" target="_blank" title="'.__('Manage Layout').'"><div><img style="position:relative;left:2px;" class="tpl_thumb" src="../template/'.$tmp_arr1[0].'/screenshot.jpg" onmouseover="this.className=\''.'tpl_thumb1'.'\'" onmouseout="this.className=\''.'tpl_thumb'.'\'"/></div></a><span class="small">';
							echo '模板编号:'.$tmp_arr1[2].'&nbsp;&nbsp;&nbsp;'.__('default template');
				            echo '&nbsp;';
			            	echo '</span>请输入模板编号:<input type="text" value="" id="idNumber" style="width:42px;"  name="idNumber" ><a href="###" onClick="javascript:directId(document.getElementById(\'idNumber\').value);">搜索</a></td><td style="text-align:left;">'.$html_script.'</td></tr>';
			            	break;
			    		}
			    	}
					
			    echo '</table>';
			    } else {
			    	echo "<tr>";
			    	echo '<td width="'.intval(100 / $cols).'%">';
			    	echo '<a href="'.Html::uriquery('mod_modules', 'index').'" target="_blank" title="'.__('Manage Layout').'"><div><img style="position:relative;left:2px;" class="tpl_thumb" src="../template/'.DEFAULT_TPL.'/screenshot.jpg" onmouseover="this.className=\''.'tpl_thumb1'.'\'" onmouseout="this.className=\''.'tpl_thumb'.'\'"/></div></a><span class="small">';
			    	//include_once(ROOT.DS.'template'.DS.DEFAULT_TPL.DS.'conf.php');

			    	echo '模板编号:'.$template_name.'&nbsp;&nbsp;&nbsp;'.__('default template');
			    	echo '&nbsp;';
			    	echo '</span>请输入模板编号:<input type="text" value="" id="idNumber" style="width:42px;"  name="idNumber" ><a href="###" onClick="javascript:directId(document.getElementById(\'idNumber\').value);">搜索</a></td><td style="text-align:left;">'.$html_script.'</td></tr>';
			    	echo '</table>';
			    }
			    if(!($bool1 && $bool2))
			    {
			    	echo '</td></tr></tbody></table>';
			    	if(!$bool1 && $bool2)
			    		echo '<div style="font-size:24px;font-weight:24px;">'._e('Please install zip extension!').'</div>';
			    	elseif($bool1 && !$bool2)
			    		echo '<div style="font-size:24px;font-weight:24px;">'._e('Please install soap extension!').'</div>';
			    	else
			    		echo '<div style="font-size:24px;font-weight:24px;">'._e('Please install soap extension!').'</div>';
			    	die;
			    }
				echo '<div style="clear:both;"></div><div id="cate_new"><ul>';
				if($category_number=="_all" || $category_number=="_free"){
					if($category_number=="_free"){
						$sub_id_class = 'class="selected"';
					}else{
						$sub_id_class = '';
					}
					echo "<li ".$sub_id_class."><a href='index.php?_m=mod_template&_a=admin_list&_cates=_free&sub_id={$val['sub_id']}&_p=1'>".'免费模版'."</a></li>";
					if($category_number=="_all"){
						$sub_id_class = 'class="selected"';
					}else{
						$sub_id_class = '';
					}
					echo "<li ".$sub_id_class."><a href='index.php?_m=mod_template&_a=admin_list&_cates=_all&sub_id={$val['sub_id']}&_p=1'>".'商业模版'."</a></li>";
				}else{
				foreach($tmplate_category as $val){
					if($val['sub_id']==$category_sub_id){
						$sub_id_class = 'class="selected"';
					}else{
						$sub_id_class = '';
					}
						if($val['super_id']==$category_number && $val['sub_id']>0){
							echo "<li ".$sub_id_class."><a href='index.php?_m=mod_template&_a=admin_list&_cates={$category_number}&sub_id={$val['sub_id']}&_p=1'>".$val['name']."</a></li>";
						}
					}
				}
				echo '</ul></div>';
			    echo '<table class="template_grid" cellspacing="0">';
			    	$i = 0;
			
			        while($i < $n_tpls)
			        {
			        	echo "<tr>";
			        	for($j = 0; $j < $cols; $j++)
			        	{
			        		if($i >= count($output_array))
			        		{
			        			break;	
			        		}
			        		
			        		if(is_array($output_array[$i]))//When getting remote templates happend..
			        		{
				        		if(DEFAULT_TPL_ID == $output_array[$i]['id'])
				        		{
				        			$i++;
				        			$j--;
				        			continue;	
				        		}	
			        		}
			    	?>
			            <td width="<?php echo intval(100 / $cols); ?>%">
			            	<?php if(!is_array($output_array[$i])){//Get local templates
			            			$tmp_arr = explode('#$',$output_array[$i]);
			            			if($tmp_arr[0] != DEFAULT_TPL)
			            			{
										if(!Toolkit::getAgent()||!Toolkit::getcorp()) {
											$tmp_arr[1] = substr($tmp_arr[1],0,-11).'idccenter.net/index.php?key=agent';
										}
			            	?>
			            	<a href="<?php echo $tmp_arr[1];?>" target="_blank"><div id=<?php echo '"'.@$templates[$i]['id'].'"';?>><img class="tpl_thumb" src="<?php echo '../template/'.$tmp_arr[0].'/screenshot.jpg'; ?>" onmouseover="this.className='tpl_thumb1';" onmouseout="this.className='tpl_thumb';" title="<?php echo '企业网站-'.$output_array_name[$i]?>" /></div></a>
			            	<span class="small">
				            <?php
								echo '模板编号:'.$tmp_arr[2].'&nbsp;&nbsp;&nbsp;'."<a href='#' onclick=\"make_default('$tmp_arr[0]')\">".__('Install the choosed template')."</a>";
				            ?>
				            &nbsp;
			            	</span>
			            	<?php }else {?>
			            		<div id=<?php echo '"'.@$templates[$i]['id'].'"';?>><img class="tpl_thumb" src="<?php echo '../template/'.$tmp_arr[0].'/screenshot.jpg'; ?>" onmouseover="this.className='tpl_thumb1';" onmouseout="this.className='tpl_thumb';" title="<?php echo '企业网站-'.$output_array_name[$i]?>" /></div>
				            	<span class="small">
					            <?php
									echo '模板编号:'.$tmp_arr[2].'&nbsp;&nbsp;&nbsp;';_e('Installed');
					            ?>
					            &nbsp;
				            	</span>
			            	<?php }}else {?><!-- Get remote templates -->
			            	<?php 
			            	//$arr = explode('/',$output_array[$i]['screenshot_url']);
			            	//$image_key = count($arr)-1;
//			            	$image_name = $arr[$image_key];
                              $image_name =$output_array[$i]['screenshot'];
							if(!Toolkit::getAgent()||!Toolkit::getcorp()) {
								$output_array[$i]['demourl'] = substr($output_array[$i]['demourl'],0,-11).'idccenter.net/index.php?key=agent';
							}
			            	?>
			            	<a target='_blank' href="<?php echo $output_array[$i]['demourl'];?>"><div id=<?php echo '"'.$output_array[$i]['id'].'"';?>><img class="tpl_thumb" src="<?php echo SCREENSHOT_URL.'screenshots/'.$image_name; ?>" onmouseover="this.className='tpl_thumb1';" onmouseout="this.className='tpl_thumb';" title="<?php echo $output_array[$i]['name'];?>"/></div></a>
					        <span class="small">
						    <?php

//						        $remote_flag = isset($output_array[$i]['package_url'])?'true':'false';
                                       $remote_flag ='true';
						        $tpl_name = substr($output_array[$i]['archive'],0,-4);
						        echo '模板编号:'.$output_array[$i]['id'].'&nbsp;&nbsp;'.'<a href="#" onclick="toggle_default(\''.$output_array[$i]['id'].'\', '.$remote_flag.',\''.$tpl_name.'\');return false;" title="'.__('Install the choosed template').'">'.__('Install the choosed template').'</a>';
						    ?>
						    &nbsp;
					        </span>
			            	<?php }?>
			            </td>
			        <?php
				        	$i++;
			        	}
			        	for($a = 0; $a < $cols-$j; $a++)
			        	{
			        		 echo '<td width="'.intval(100 / $cols).'%"></td>';
			        	}
			        	echo "</tr>";
			        }
			        // 2011/02/25 no records
			        if (!$n_tpls) echo '<tr><td align="center">'.__('No Records!').'</td></tr>';
			        ?>
			    </table>
		    </td>
	    </tr>
    </tbody>
</table>
<?php if($n_tpls&&intval($tpl_id)==0){?>
<div style="text-align:center;margin-top:2px;">
<?php 
$str11 = '';
$str22='';
$per_page = 5;
$leftsidepage=intval(($per_page-1) /2);
$rightsidepage=$per_page-$leftsidepage-1;
$firstshowpage=1;
$endshowpage=$last_page;
if($last_page <= $per_page)
{
	for($ii = 1;$ii <= $last_page;$ii++)
	{
		if($page_number == $ii)
		{
			$str11 .= $ii."&nbsp;|&nbsp;";
		}
		else
		{
			$str11 .= "<a href='index.php?_m=mod_template&_a=admin_list&_p=$ii&_cates=$category_number&sub_id=$category_sub_id'>$ii</a>&nbsp;|&nbsp;";
		}
	}
}
else
{
     if($page_number-$leftsidepage<=0){
         $firstshowpage=1;
         $endshowpage=$per_page;
     }elseif($page_number+$rightsidepage>$last_page){
         $firstshowpage=$last_page+1-$per_page;
         $endshowpage=$last_page;
     }else{
         $firstshowpage=$page_number-$leftsidepage;
         $endshowpage=$page_number+$rightsidepage;
     }
     $str11='';
     if($firstshowpage>=4) {
         $str11 .= "<a href='index.php?_m=mod_template&_a=admin_list&_p=1&_cates=$category_number&sub_id=$category_sub_id'>1..</a>&nbsp;|&nbsp;";
     }
	for($ii = $firstshowpage;$ii <=$endshowpage;$ii++)
	{
		if($page_number == $ii)
		{
			$str11 .= $ii."&nbsp;|&nbsp;";
		}
		else
		{
			$str11 .= "<a href='index.php?_m=mod_template&_a=admin_list&_p=$ii&_cates=$category_number&sub_id=$category_sub_id'>$ii</a>&nbsp;|&nbsp;";
		}
	}
	
     if( $endshowpage!=$last_page){
      $str11 .= "<a href='index.php?_m=mod_template&_a=admin_list&_p=$last_page&_cates=$category_number&sub_id=$category_sub_id'>..$last_page</a>&nbsp;|&nbsp;";
      }
	$str22 .= "<form style='display:inline;margin:0px;' method='post' action='index.php?_m=mod_template&_a=admin_list' id='langswform' name='langswform'>";
	$str22 .= "<input name='_cates' type='hidden' value='$category_number'>转跳至:<input style='width:25px;' type='text' name='_p' value='$page_number'><input type='submit' value='确定'></form>";
}
if($page_number == $last_page)
{
	if($page_number > 1){
?>
	<a href='index.php?_m=mod_template&_a=admin_list&_p=1&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>'><?php _e('First');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $page_number-1;?>"><?php _e('Previous');?></a>&nbsp;|&nbsp;<?php echo $str11;?><a href="#"><?php _e('Next');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $last_page;?>"><?php _e('Last');?></a><?php echo '&nbsp|&nbsp;'.$str22;?>
<?php } elseif($page_number == 1) {?>
	<a href='index.php?_m=mod_template&_a=admin_list&_p=1&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>'><?php _e('First');?></a>&nbsp;|&nbsp;<a href="#"><?php _e('Previous');?></a>&nbsp;|&nbsp;<?php echo $str11;?><a href="#"><?php _e('Next');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $last_page;?>"><?php _e('Last');?></a><?php echo '&nbsp|&nbsp;'.$str22;?>
<?php	}?>
<?php
}
elseif($page_number == 1)
{
?>
	<a href='index.php?_m=mod_template&_a=admin_list&_p=1&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>'><?php _e('First');?></a>&nbsp;|&nbsp;<a href="#"><?php _e('Previous');?></a>&nbsp;|&nbsp;<?php echo $str11;?><a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $page_number+1;?>"><?php _e('Next');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $last_page;?>"><?php _e('Last');?></a><?php echo '&nbsp|&nbsp;'.$str22;?>
<?php
}
else
{
?>
<a href='index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=1'><?php _e('First');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $page_number-1;?>"><?php _e('Previous');?></a>&nbsp;|&nbsp;<?php echo $str11;?><a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $page_number+1;?>"><?php _e('Next');?></a>&nbsp;|&nbsp;<a href="index.php?_m=mod_template&_a=admin_list&_cates=<?php echo $category_number;?>&sub_id=<?php echo $category_sub_id ?>&_p=<?php echo $last_page;?>"><?php _e('Last');?></a><?php echo '&nbsp|&nbsp;'.$str22;?>
<?php 
}

//if($last_page > $per_page)
//{
//	echo $str22;
//}
?>
</div>
<?php }?>
