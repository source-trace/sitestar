<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$permissions=array();
?>
<style>
#man_div{background:url(images/icon_bg.jpg) repeat-x;
	width:940px;  margin:0 auto; padding-bottom:100px;*padding-bottom:0px;_padding-bottom:0px; vertical-align:middle}
	
.iconleft{ width:180px; float:left; height:1000px;}
.iconright{ width:660px; float:left; margin-top:2px; } 
.nameicontext{  font-size:16px; color:#fff;  font-weight: bold; *float:left; margin-left:4px;  }
.nametext{   margin-left:10px; border: solid 1px #E6E6E6; height:20px;}
.icontitle{ font-size:16px; color:#333; font-weight:bold;}
.icontitle1{ font-size:12px; color:#333; font-weight:bold;  margin-left:35px; }
.iconxiaotext{ font-size:12px; color:#444444; line-height: normal;   }
.iconkuan{ background-image: url(images/kuan.jpg); background-repeat: no-repeat; height:13px; width:13px; background-attachment: fixed; background-position: center center; }
.iconxian{ border-bottom-color:#eee; border-bottom-style:solid; border-bottom-width:1px; margin-top:30px; float:left; padding-bottom:15px;}
.iconxian2{ border-bottom-color:#eee; border-bottom-style:solid; border-bottom-width:1px; margin-top:20px; float:left; padding-bottom:15px;}
.iconxian5{ border-bottom-color:#eee; border-bottom-style:solid; border-bottom-width:1px; margin-top:20px; float:left; padding-bottom:15px;}
.iconxian3{  margin-top:15px; float:left;  }
.iconbottom{ margin-bottom:30px;}
.ictop{ vertical-align:top; float:left; background-position:top; margin-top:3px; margin-right:1px; }
.foot{ background:url(images/aniu_07.jpg) repeat-x; width:510px; height:42px; float:left; margin-top:40px; padding-left:430px; padding-top:10px;}
.footp{ float:left; margin-left:8px;}
#mod_addblock_sapn{vertical-align:middle; margin-top:-2px; margin-bottom:1px;}
.checkbox{vertical-align:bottom; margin-top:4px;}
</style>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminaddusrform_stat");
    if (o_result.result == "ERROR") {       
        stat.innerHTML = o_result.errmsg;
//        document.forms["adminaddusrform"].reset();
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
        window.location.href = o_result.forward;
//		  parent.window.location.reload();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
//    document.forms["adminaddusrform"].reset();
    
    document.getElementById("adminaddusrform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}



function on_chk_login_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_chk_login_failure(response);
    }
    
    var chk_rs = document.getElementById("chk_login_rs");
    if (o_result.result == "OK") {
        chk_rs.innerHTML = o_result.msg;
    } else {
        chk_rs.innerHTML = o_result.errmsg;;
    }
}

function on_chk_login_failure(response) {
    var chk_rs = document.getElementById("chk_login_rs");
    chk_rs.innerHTML = "<?php _e('Check failed!'); ?>";
}

function backPrv(){
	window.location.href="index.php?_m=mod_roles&_a=admin_list";	
}
//-->
</script>
<span id="adminaddusrform_stat" class="status" style="display:none;"></span>
<?php
$admin_addusr_form = new Form('index.php', 'adminaddusrform', 'check_reg_info');
$admin_addusr_form->p_open('mod_roles', 'admin_create', '_ajax');
?>

<div id="man_div">  
  <div class="iconleft"></div>
   
   <div class="iconright">
     <table width="680" border="0">
       <tr class="iconbottom">
         <td width="27"><img src="images/nameicon.jpg" width="26" height="27" /></td>
         <td width="36" class="nameicontext"><?php _e('Name'); ?></td>
         <td width="603">
           <label>
             <?php
            echo Html::input('text', 'role[desc]', '', 'class="textinput"');
            ?>
            </label>
		</td>
       </tr>
     </table>
</div>
<?php include_once(P_TPL.'/view/mod_roles/permission.php'); ?>

<div class="foot">
<span class="footp"><?php echo Html::input('submit', 'submit', __('Save'), 'style="float: none;"');?></span>
<span class="footp"><?php echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();" style="float: none;"');?></span>
<span class="footp"><?php echo Html::input('reset', 'reset', __('Reset'), 'style="float: none;"');?></span>

</div>
</div>
<?php
$admin_addusr_form->close();

$running_msg = __('Creating role...');
$rolename_msg = __('Role Name cannot be empty');
$custom_js = <<<JS
if($.trim($('#role_desc_').val())==''){
   alert('$rolename_msg');
   $('#role_desc_').focus();
   return false;
}
$("#adminaddusrform_stat").css({"display":"block"});
$("#adminaddusrform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$admin_addusr_form->addCustValidationJs($custom_js);
$admin_addusr_form->writeValidateJs();
?>
