<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js">  </script>
<script type="text/javascript" language="javascript">
<!--
function createXml(tag){
	$.post("../index.php?_m=mod_sitemap&_a=create_sitemap",{tag:tag},function(msg){
		if(tag=="b"){
		document.getElementById("stbaidu").innerHTML="<a target='_blank' href='../sitemap_baidu.xml'>"+"<?php _e('Baidu map'); ?>"+"</a>";;
		}else if(tag=="g"){
			document.getElementById("stgoogle").innerHTML="<a target='_blank' href='../sitemap.xml'>"+"<?php _e('Google map'); ?>"+"</a>";
		}
		alert(msg);
	});
}

//-->
</script>
<?php echo Notice::get('mod_site/msg');?>
<ul style="margin-left:1px;height:51px;line-height:51px;">
	<li style="margin-top:14px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:16px;line-height:0px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
</ul>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_site', 'save_seo_info');
?>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
			echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'si[id]', $curr_siteinfo->id);
            echo Html::input('hidden', 'si[s_locale]', $lang_sw);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
       <tr>
            <td class="label" ></td>
            <td class="entry">
            <a href="<?php echo Html::uriquery('mod_static', 'seo')?>" target="_blank"><?php _e('About SEO'); ?></a>
            </td>
        </tr>
        <tr>
            <td class="label" title="<?php _e('Keyword Title');?>"><?php _e('Keyword'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'si[keywords]', $curr_siteinfo->keywords, 'class="textinput"');
            
            // 25/03/2010 Jane Add >>
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer2" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set mete note');?>"/>
            <?php
            // 25/03/2010 Jane Add <<
            }?>
            </td>
        </tr>
       
        <tr>
            <td class="label" title="<?php _e('Description Title');?>"><?php _e('Description'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('si[description]', $curr_siteinfo->description, 'class="textinput" cols="48" rows="6" style="width:400px;"');
            
            // 25/03/2010 Jane Add >>
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer3" class="title" style="position:relative;bottom:50px;" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set domain note');?>"/>
            <?php
            // 25/03/2010 Jane Add <<
            }?>
            </td>
        </tr>
         <tr>
            <td class="label"></td>
            <td class="entry">
            <a href="javascript:void(0)" class="VILinkText"><?php _e("When global keys set,if the others page not set the key,it will use global keys");?></a>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Submit Web'); ?></td>
            <td class="entry">
            <a target="_blank" href="http://www.baidu.com/search/url_submit.html" class="VILinkText"><?php _e('Submit to baidu');?></a>&nbsp;&nbsp;
            <a target="_blank" href="http://www.google.cn/intl/zh-CN/submit_content.html" class="VILinkText"><?php _e('Submit to google');?></a>&nbsp;&nbsp;
            <a target="_blank" href="http://tool.cnzz.com/yahoo/ti.php" class="VILinkText"><?php _e('Submit to Yahoo');?></a>&nbsp;&nbsp;
            <a target="_blank" href="http://www.soso.com/help/usb/urlsubmit.shtml" class="VILinkText"><?php _e('Submit to soso');?></a>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Create sitemap');?></td>
            <td class="entry">&nbsp;&nbsp;
            <font style="border-top:1px solid #e7e7d9;cursor:pointer; width:60px;border-left:1px solid #e7e7d9;border-right:1px solid #a0a097;border-bottom:1px solid #a0a097;" onclick="createXml('b')"><?php _e('Baidu');?></font>&nbsp;&nbsp;&nbsp;&nbsp;
            <font style="border-top:1px solid #e7e7d9;cursor:pointer; width:60px;border-left:1px solid #e7e7d9;border-right:1px solid #a0a097;border-bottom:1px solid #a0a097;" onclick="createXml('g')"><?php _e('Google');?></font>&nbsp;&nbsp;&nbsp;&nbsp;(<?php _e("Good for search engineer get it");?>)
            
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">&nbsp;&nbsp;
            <span id="stbaidu"><?php 
            if (file_exists(ROOT."/sitemap_baidu.xml")) {
            	echo '<a target="_blank" href="../sitemap_baidu.xml">'.__("Baidu map").'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
			?>
			</span>
			<span id="stgoogle">
			<?php
            if (file_exists(ROOT."/sitemap.xml")) {
            	echo '<a target="_blank" href="../sitemap.xml">'.__("Google map").'</a>&nbsp;&nbsp;';
            }
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
