<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$currencr = array(
'CNY'=>__('China Yuan'),
'USD'=>__('U.S. Dollor'),
'EUR'=>__('Euro'),
'GBP'=>__('Pound Sterling'),
'CAD'=>__('Canada Dollor'),
'AUD'=>__('Australian Dollor'),
'RUB'=>__('Russian Ruble'),
'HKD'=>__('Hong Kong Dollor'),
'TWD'=>__('Taiwan New Dollor'),
'KRW'=>__('South Korean Won'),
'SGD'=>__('Singapore Dollor'),
'NZD'=>__('New Zealand Dollor'),
'JPY'=>__('Japanese Yen'),
'MYR'=>__('Malaysian DOllar'),
'CHF'=>__('Swiss Franc'),
'SEK'=>__('Swedish Krona'),
'DKK'=>__('Danish Krone'),
'PLZ'=>__('Polish złoty'),
'NOK'=>__('Norwegian Krone'),
'HUF'=>__('Hungarian Forint'),
'CSK'=>__('Czech koruna'),
'MOP'=>__('Macau Pataca')
);
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer').cluetip({splitTitle: '|',width: '300px',height:'68px'});
	$('#answer1').cluetip({splitTitle: '|',width: '105px',height:'33px'});
	$('#answer2').cluetip({splitTitle: '|',width: '300px',height:'90px'});
	$('#answer3').cluetip({splitTitle: '|',width: '400px',height:'90px'});
	$('#answer4').cluetip({splitTitle: '|',width: '250px',height:'53px'});
	$('#answer5').cluetip({splitTitle: '|',width: '210px',height:'33px'});
	$('#answer6').cluetip({splitTitle: '|',width: '250px',height:'88px'});
	$('#answer7').cluetip({splitTitle: '|',width: '250px',height:'33px'});
	$('#answer8').cluetip({splitTitle: '|',width: '230px',height:'33px'});
	$('#answer9').cluetip({splitTitle: '|',width: '250px',height:'53px'});
	$('#answer10').cluetip({splitTitle: '|',width: '230px',height:'33px'});
	$('#answer11').cluetip({splitTitle: '|',width: '210px',height:'33px'});
	$('#answer12').cluetip({splitTitle: '|',width: '260px',height:'50px'});
	$('#answer13').cluetip({splitTitle: '|',width: '260px',height:'50px'});
	$('#answer14').cluetip({splitTitle: '|',width: '260px',height:'50px'});
	//$('#answer12').cluetip({splitTitle: '|',width: '210px',height:'auto'});
	$("#sparam_SITE_OFFLINE_").click(function(){
		if($("#sparam_SITE_OFFLINE_").attr("checked")==true){
			$("#offline_msg").show();
			$("#tab_offline").show();
		}else{
			$("#offline_msg").hide();
			$("#tab_offline").hide();
		}
	})
	if($("#offline_msg").css("display")=='none'){
		$("#tab_offline").hide();
	}
	if($("#credits_msg").css("display")=='none'){
		$("#tab_credits").hide();
	}
	
	$("#sparam_CREDITS_SWITCH_").click(function(){
		if($("#sparam_CREDITS_SWITCH_").attr("checked")==true){
			$("#credits_msg").show();
			$("#tab_credits").show();
		}else{
			$("#credits_msg").hide();
			$("#tab_credits").hide();
		}
	})
});

/*function logoselector1(src)
{
	show_iframe_win('SitestarMaker/index1.php?_m='+$('#'+src).val());
}

function bannerselect1(src)
{
	show_iframe_win('SitestarMaker/index1.php?_m='+$('#'+src).val());
}*/
function clear_cache(){
	$.post("index.php?_m=mod_site&_a=del_cache",{},function(data){
		alert("<?php _e("Delete cache success!");?>");
	})
}

function tb_remove()
{
	reloadPage();
}

function imageEditor(src)
{
	src = "phpimageeditor/index.php?imagesrc="+src;
	show_iframe_win(src, '', 732, 503);
}
function on_success(response) {alert(response);
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminsinfofrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["sinfoform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('Site information saved!'); ?>";
//	    window.parent.tb_remove();
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["sinfoform"].reset();
    
    document.getElementById("adminsinfofrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<?php echo Notice::get('mod_site/msg');?>
<ul style="margin-left:1px;height:51px;line-height:51px;">
	<li style="margin-top:14px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:16px;line-height:0px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
</ul>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_site', 'save_info');
?>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_siteinfo->id = isset($curr_siteinfo->id)?$curr_siteinfo->id:'';
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
            <td class="label"><?php _e('Site Name'); ?></td>
            <td class="entry">
            <?php
			$curr_siteinfo->site_name = isset($curr_siteinfo->site_name)?$curr_siteinfo->site_name:'';
            echo Html::input('text', 'si[site_name]', $curr_siteinfo->site_name, 'class="textinput"');
            
            // 25/03/2010 Jane Add >>
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Website name');?>"/>
            <?php
            // 25/03/2010 Jane Add <<
            }?>
            </td>
        </tr>
        
        
        <tr>
            <td class="label"><?php _e('Records per page'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[PAGE_SIZE]', PAGE_SIZE, 'class="textinput" style="width:30px;"', 
            	$sinfo_form, 'RequiredTextbox', __('Please input record number displayed on one page!'));
            ?>
            <img id="answer5" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set num note');?>"/>
            </td>
        </tr>
		
        <tr>
        	<td class="label"><?php _e('Default LANG'); ?></td>
        	<td class="entry">
            <?php
            //get type of language
            $o_lang = new Language();
        	$langs =& $o_lang->findAll();
        	$arr = array();
        	$i = $j = 0;
        	foreach($langs as $lang)
        	{
        		/*$arr[] = $lang->name;
        		if($lang->locale == SessionHolder::get('_LOCALE'))
        		{
        			$j = $i;
        		}
        		$i++;*/
        		$arr[$lang->id] = $lang->name;
        		if($lang->locale == $_SITE->s_locale)
        		{
        			$j = $lang->id;
        		}
        	}
            echo Html::select('sparam[USE_LANGUAGE]', $arr, $j, 'class="textselect"');
            ?>
            </td>
        </tr>
		<tr>
        	<td class="label"><?php _e('Access Mode'); ?></td>
        	<td class="entry">
            <?php
            $access_arr = array(1=>__('Dynamic mode'),2=>__('Rewrite mode'),3=>__('Custom link'));
            echo Html::select('sparam[MOD_REWRITE]', $access_arr,MOD_REWRITE, 'class="textselect"');
            ?><img id="answer4" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set web style note');?>"/>
            </td>
        </tr>
		<?php if (intval(EZSITE_LEVEL) > 1) { ?>
		<tr>
        	<td class="label"><?php _e('Currency'); ?></td>
        	<td class="entry">
            <?php
			if($currency_!=''){
				$s_currency=$currency_;
			}else{
				$s_currency=CURRENCY;
			}
            echo Html::select('sparam[CURRENCY]', $currencr,$s_currency, 'class="textselect"');
            ?>
            </td>
        </tr>
		  <tr>
            <td class="label"><?php _e('Currency Sign'); ?></td>
            <td class="entry">
            <?php
			if($currency_sign_!=''){
				$s_currency_sign=$currency_sign_;
			}else{
				$s_currency_sign=CURRENCY_SIGN!='CURRENCY_SIGN'?CURRENCY_SIGN:'';
			}
//			echo $s_currency_sign;
            echo Html::input('text', 'sparam[CURRENCY_SIGN]', $s_currency_sign, 'class="textinput" style="width:60px;"',$sinfo_form);
            ?>
             <img id="answer11" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('eg:U.S. dollar is $');?>"/>
            </td>
        </tr>
		<?php } ?>
		<tr>
            <td class="label"><?php _e('Login Security Code'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_LOGIN_VCODE]', '1', 
                Toolkit::switchText(SITE_LOGIN_VCODE, 
                    array('0' => '', '1' => 'checked="checked"')));
            
            // 25/03/2010 Jane Add >>
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer7" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set login note');?>"/>
            <?php
            // 25/03/2010 Jane Add <<
            }?>
            </td>
        </tr>
        
        <?php if(EZSITE_LEVEL == '2'){?>
        <tr>
            <td class="label"><?php _e('Exchange Switch'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[EXCHANGE_SWITCH]', '1', 
                Toolkit::switchText(EXCHANGE_SWITCH, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            <img id="answer6" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('All of shopping function will be disable.');?>"/>
            </td>
        </tr>
        <?php }?>
		<tr>
            <td class="label"><?php _e('Member Switch'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[MEMBER_VERIFY]', '1', 
                Toolkit::switchText(MEMBER_VERIFY, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            <img id="answer9" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('By enabling this function, member has to pass the validation in order to login.');?>"/>
            </td>
        </tr>
		<?php if(EZSITE_LEVEL == '2'){?>
        <tr>
            <td class="label"><?php _e('Credits transaction switch'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[CREDITS_SWITCH]', '1', 
                Toolkit::switchText(CREDITS_SWITCH, array('0' => '', '1' => 'checked="checked"')));
            ?> <img id="answer13" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Open this function, after the success of the members to shopping, will give corresponding score according to integral conversion ratio');?>"/> 
            </td>
        </tr>
        <div  id="credits_msg" <?php if(CREDITS_SWITCH==1){ ?>style="display:block;"<?php }else{ ?> style="display:none;"<?php } ?>>
		<tr id="tab_credits">
            <td class="label"><?php _e('Integral conversion ratio'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[CREDITS_RATE]', CREDITS_RATE=='CREDITS_RATE'?'':CREDITS_RATE, 'class="textinput"  style="width:30px;"');
		  ?>
		  <img id="answer12" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('10 yuan gift a plot copies, can be set to 0.1,100 yuan gift a plot copies, can be set to 0.01');?>"/>
            </td>
        </tr>
		</div>
		<?php }?>
		<tr>
            <td class="label"><?php _e('ICO image'); ?></td>
            <td class="entry">
            <input type="file" name="ico_file" size="15" />
			<img id="answer14" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Address bar icon only support *. Ico format file to upload');?>"/>           
			 </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Clear cache'); ?></td>
            <td class="entry">
            <input type="button" name="cache" id="cache" value="<?php _e("Clear cache");?>" onclick="clear_cache();" />         
			 </td>
        </tr>

		<tr>
            <td class="label"><?php _e('Site offline'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_OFFLINE]', '1', Toolkit::switchText(SITE_OFFLINE,array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
		<div id="offline_msg" <?php if(SITE_OFFLINE==1){ ?>style="display: block;"<?php }else{ ?>style="display:none;"<?php } ?>>
        <tr id="tab_offline">
            <td class="label"><?php _e('Site offline msg'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('sparam[SITE_OFFLINE_MSG]', SITE_OFFLINE_MSG, 
                'cols="48" rows="4" class="textinput" style="width:358px;"');
            ?>
            </td>
        </tr>
        </div>
       
		<tr>
            <td class="label"><?php _e('Foot Information');?></td>
            <td class="entry" style="padding-top:5px;">
			<?php
			//echo Html::input('hidden', 'logo[id]', $curr_logo->id);
            //echo Html::input('hidden', 'param[logo_img]', $p_logo['img_src']);
           // echo Html::input('hidden', 'banner[id]', $curr_banner->id);
			//echo Html::input('hidden', 'param[banner_img]', $p_banner['img_src']);
            echo Html::input('hidden', 'foot[id]', isset($curr_foot->id)?$curr_foot->id:'');
            echo Html::textarea('param[html]', isset($p_foot['html'])?$p_foot['html']:'', 'rows="4" cols="76" class="textinput" style="width:358px;"')
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Website authentication area'); ?></td>
            <td class="entry" style="padding-top:5px;">
		 <?php
            echo Html::textarea('verify_meta', isset($meta)?$meta:'', 'rows="4" cols="76" class="textinput" style="width:358px;"')
            ?>
		 <img id="answer12" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif"  title='<?php _e('Website authentication Desc'); ?>' alt="help"/>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('website approve'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[WEB_ICP]', WEB_ICP, 'class="textinput"', 
            	$sinfo_form);
            ?><img id="answer8" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set beian note');?>"/>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Background music'); ?></td>
            <td class="entry">
            <?php
			$o_bgmusic = new BackgroundMusic();
            $bgmusic_items = $o_bgmusic->findAll();
			if(isset($bgmusic_items[0]->music_path)){
				$music_path=$bgmusic_items[0]->music_path;
			}
            echo Html::input('text', 'music_file', $music_path, 'class="textinput"');
            ?><img id="answer10" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Background Music Url');?>"/>
			<br />
            <?php 
            

            $once_play_checked = (isset($bgmusic_items[0]->play) && $bgmusic_items[0]->play == 1) ? 'checked="checked"' : '';
            $loop_play_checked = (isset($bgmusic_items[0]->play) && $bgmusic_items[0]->play == 2) ? 'checked="checked"' : '';
            $stop_play_checked = (isset($bgmusic_items[0]->play) && $bgmusic_items[0]->play == 3) ? 'checked="checked"' : '';
			$music_name = (isset($bgmusic_items[0]->music_name))?$bgmusic_items[0]->music_name:'';
            if(empty($once_play_checked) && empty($loop_play_checked) && empty($stop_play_checked))
            {
            	$once_play_checked = 'checked="checked"';
            }
            ?>
            <input type="radio" value="1" id="play_type" name="radio[play_type]" <?php echo $once_play_checked;?>><?php _e('once play');?>&nbsp;<input type="radio" value="2" id="play_type" name="radio[play_type]" <?php echo $loop_play_checked;?>><?php _e('loop play');?>
            &nbsp;<input type="radio" value="3" id="play_type" name="radio[play_type]" <?php echo $stop_play_checked;?>><?php _e('play sotp');?>
			
            </td>
        </tr>
    </tbody>
</table>
</div>
<?php
$sinfo_form->close();
$sinfo_form->writeValidateJs();

//include_once(P_TPL.'/view/mod_param/admin_list.php');// load mod_param' page
?>
