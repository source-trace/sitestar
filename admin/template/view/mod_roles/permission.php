
<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style>

.input_align_5{margin-bottom:1px;margin-top:-2px;vertical-align:middle;}
.font01{font-size:12px; _margin-top:3px;}
#mod_acticle_sapn{ _margin-top:5px;}
.kuan5{ margin-left:10px; margin-top:1px; *margin-top:-1px;   }
.lefttitle{ margin-top:2px !important; margin-top:3px\9 !important; *margin-top:-2px !important;   _margin-top:-3px;}
.lefttitle2{ margin-top:1px !important; margin-top:4px\9 !important; *margin-top:4px !important;   _margin-top:-3px;}
.title{ margin-top:3px;}
</style>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script>
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '300px',height:'80px'});
});
 function  toogle_checkbox(el,spanid){
         var spanel=$('#'+spanid);
         var checked=el.checked;
         spanel.find('input[type=checkbox]').each(function(){
                 var checkboxel=this;
                 checkboxel.checked=checked;
         });
 }
function changePic(m,a,edit,show){
	var m=m,a=a,edit=edit;
	var ht = '';
	if(edit==0){//如果是首次添加，点击图片只需要隐藏并更换图标即可
		if(show==1){
			ht = "<img onclick='changePic(\""+m+"\",\""+a+"\",\""+edit+"\",0);' src='template/images/no.gif' id='"+a+"_id'  title=\"<?php _e("Control home page adminpanel display this menu");?>\">";
			$("#"+a+"_id").parent().empty().append(ht);
			$("#"+a+"_input").val();
			$("."+a+"_tag").hide();
		}else{
			ht = "<img onclick='changePic(\""+m+"\",\""+a+"\",\""+edit+"\",1);' src='template/images/yes.gif' id='"+a+"_id'  title=\"<?php _e("Control home page adminpanel display this menu");?>\"><input type=\"hidden\" value=\""+a+"\" id=\""+a+"_input\" name=\"permission[mod_all_"+a+"][]\">";
			$("#"+a+"_id").parent().empty().append(ht);
			$("#"+a+"_input").val(a);
			$("."+a+"_tag").show();
		}
		
	}else{
		if(show==1){
			ht = "<img onclick='changePic(\""+m+"\",\""+a+"\",\""+edit+"\",0);' src='template/images/no.gif' id='"+a+"_id'  title=\"<?php _e("Control home page adminpanel display this menu");?>\">";
			$("#"+a+"_id").parent().empty().append(ht);
			$("#"+a+"_input").val();
			$("."+a+"_tag").hide();
		}else{
			ht = "<img onclick='changePic(\""+m+"\",\""+a+"\",\""+edit+"\",1);' src='template/images/yes.gif' id='"+a+"_id'  title=\"<?php _e("Control home page adminpanel display this menu");?>\"><input type=\"hidden\" value=\""+a+"\" id=\""+a+"_input\" name=\"permission[mod_all_"+a+"][]\">";
			$("#"+a+"_id").parent().empty().append(ht);
			$("#"+a+"_input").val(a);
			$("."+a+"_tag").show();
		}
		
	}
}
</script>
<div class="iconxian">
     <table width="686" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="163" valign="top"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="35"><img src="images/icon1.jpg" width="32" height="32" /></td>
        <td width="72" class="icontitle"><?php _e("Articles");?></td>
		
		<td width="10" align="center">
		<?php $article_p = Role::rolepermissionimg('mod_all_articles', 'articles', __('View'), $permissions,$edit);
			echo $article_p[0];
		 ?>
		<input type="hidden" value="articles" id="article_input" name="permission[mod_all_articles][]">
		</td>
        <td width="20" class="articles_tag" <?php if($article_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_acticle_sapn\");' " ); ?>           
			 </label>                </td>
      </tr>
    </table></td>
    <td width="533" class="articles_tag" <?php if($article_p[1]==0){ ?> style="display:none;" <?php } ?>>
<span id="mod_acticle_sapn">
<?php echo Role::rolepermissioncheckbox('mod_article', 'admin_list', __('View'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_article', 'admin_add', __('Add'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_article', 'admin_edit', __('Edit'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_article', 'admin_delete', __('Delete'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_category_a', 'admin_list', __('Manage Categories'), $permissions)?></span>	</td>
</div>
  </tr>
</table>
</div>
 <div class="iconxian2">
     <table width="754" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="35"><img src="images/icon2.jpg" width="32" height="32" /></td>
        <td width="72" class="icontitle"><?php _e("Products");?> </td>
		<td width="10">
		<?php   $product_p = Role::rolepermissionimg('mod_all_products', 'products', __('View'), $permissions,$edit);
				echo $product_p[0];
				if($product_p[1]==1){
		?>
		<input type="hidden" value="products" id="products_input" name="permission[mod_all_products][]">
		<?php } ?>
		</td>
        <td width="20" class="products_tag" <?php if($product_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_product_sapn\");' " ); ?>            </label>                </td>
      </tr>
    </table></td>
    <td width="591"  class="products_tag" <?php if($product_p[1]==0){ ?> style="display:none;" <?php } ?>>
<span id="mod_product_sapn">
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_list', __('View'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_add', __('Add'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_edit', __('Edit'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_delete', __('Delete'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_category_p', 'admin_list', __('Manage Categories'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_batch', __('Batch Import'), $permissions)?>	
<?php echo Role::rolepermissioncheckbox('mod_product', 'admin_export', __('Batch Export'), $permissions)?> </span>	</td>
</tr>
</table>
</div>
<div class="iconxian3">
<table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153">
 <table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
<tr>
        <td width="35"><img src="images/icon3.jpg" width="32" height="32" /></td>
      <td width="72" class="icontitle"><?php _e("Web Edit");?> </td>
	  
	  <td width="10" align="left">
		<?php   $web_p = Role::rolepermissionimg('mod_all_web', 'web', __('View'), $permissions,$edit);
				echo $web_p[0];
				if($web_p[1]==1){
		?>
		<input type="hidden" value="web" id="web_input" name="permission[mod_all_web][]">
		<?php } ?>
		</td>
        <td width="20" class="web_tag" <?php if($web_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_webedit_sapn\");toogle_checkbox(this,\"mod_webedit_sapn2\");' " ); ?>            </label>                </td>
      </tr>
    </table></td>
    <td valign="middle"  class="web_tag" <?php if($web_p[1]==0){ ?> style="display:none;" <?php } ?>>
<span id="mod_webedit_sapn2">
<?php echo Role::rolepermissioncheckbox('edit_block', 'process', __('Layouts'). '&nbsp;<img id="answer1" src="template/images/answer1.gif" alt="help" align="absmiddle" title="'.__('This option control the deletion and move of the block').' "/>', $permissions)?> </span>	</td>
</tr>
</table>

</div>
<span id="mod_webedit_sapn"  class="web_tag" <?php if($web_p[1]==0){ ?> style="display:none;" <?php } ?>>
<div class="iconxian3" >
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="153" valign="top"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="35">&nbsp;</td>
        <td width="72" class="icontitle1">1.<?php _e('Add Module');?> </td>
		<td>&nbsp;</td>
        <td width="20" align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_addblock_sapn\");' " ); ?>            </label>                </td>
      </tr>
    
    </table></td>      
    <td width="560"><table width="500" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td> 
<span id="mod_addblock_sapn">
<?php echo Role::rolepermissioncheckbox('add_block', 'article', __('Article Category translate'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('add_block', 'product', __('Product Category translate'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('add_block', 'effect', __('Effect Plugins'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('add_block', 'other', __('Other Category'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('add_block', 'shopping', __('Shopping Cart'), $permissions)?>
</span>  		
	   </td>
      </tr>
    </table></td>
		
		</tr>
</table>
</div>
<div class="iconxian3">
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="149" valign="top"><table width="141" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="35">&nbsp;</td>
        <td width="100" class="icontitle1">2.<?php _e('Add Page');?> </td>
       
      </tr>
    
    </table></td>      
    <td width="437"><table width="87" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td >
			<?php echo Role::rolepermissioncheckbox('mod_menu_item', 'add_page', __('Add Page'), $permissions)?>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

</div>
<div class="iconxian3">
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="149"><table width="141" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="35">&nbsp;</td>
        <td width="100" class="icontitle1">3.<?php _e('Delete Page');?> </td>
       
      </tr>
    
    </table></td>      
    <td width="437"><table width="87" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td >
			<?php echo Role::rolepermissioncheckbox('mod_menu_item', 'del_page', __('Delete Page'), $permissions)?>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

</div>	
<div class="iconxian3">
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="149"><table width="141" border="0">
      <tr>
        <td width="32">&nbsp;</td>
        <td width="100" class="icontitle1">4.<?php _e('Page Property');?> </td>
       
      </tr>
    
    </table></td>      
    <td width="437"><table width="87" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td >
			<?php echo Role::rolepermissioncheckbox('mod_menu_item', 'admin_edit', __('Page Property'), $permissions)?>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

</div>		
<div class="iconxian3">
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="149"><table width="141" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="37">&nbsp;</td>
        <td width="100" class="icontitle1">5.<?php _e('Manage Templates');?> </td>
       
      </tr>
    
    </table></td>      
    <td width="437"><table width="87" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td >			
<?php echo Role::rolepermissioncheckbox('mod_template', 'admin_list', __('Manage Templates'), $permissions)?>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

</div>	
<div class="iconxian5">
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="157" valign="top"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr class="lefttitle2">
        <td width="35">&nbsp;</td>
        <td width="72" class="icontitle1">6.<?php _e('Preferences');?> </td>
		<td>&nbsp;</td>
        <td width="20" align="right">
          <label>
          <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_preferences_sapn\");' " ); ?>            </label>                </td>
      </tr>
    
    </table>
      <p class="lefttitle2">&nbsp;</p></td>     
	<td width="427">
    <table width="400" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td width="465" ><table width="440"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        
        <td width="480" valign="top" >
<span id="mod_preferences_sapn">
<table width="600" border="0" cellspacing="0" class="kuan5">
  <tr>
    <td width="123"><?php echo Role::rolepermissioncheckbox('mod_site', 'admin_list', __('Web Set'), $permissions)?></td>
    <td width="103"><?php echo Role::rolepermissioncheckbox('mod_site', 'admin_seo', __('SEO Set'), $permissions)?></td>
    <td width="131"><?php echo Role::rolepermissioncheckbox('mod_lang', 'admin_list', __('Language Manager'), $permissions)?></td>
    <td width="235"><?php echo Role::rolepermissioncheckbox('mod_navigation', 'admin_list', __('Home Navigation'), $permissions)?><br/></td>
  </tr>
  <tr>
    <td><?php echo Role::rolepermissioncheckbox('mod_payaccount', 'admin_list', __('Payment Set'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_backup', 'admin_list', __('Data Backup & Recovery'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_attachment', 'admin_list', __('Watemark & Thumbnail'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_advert', 'admin_list', __('Advert Tool'), $permissions)?><br/></td>
  </tr>
  <tr>
    <td><?php echo Role::rolepermissioncheckbox('mod_filemanager', 'admin_dashboard', __('File Manager'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_order', 'admin_list', __('User Order'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_statistics', 'admin_list', __('Statistics'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_bshare', 'admin_list', __('Bshare'), $permissions)?></td>
  </tr>
  <tr>
    <td><?php echo Role::rolepermissioncheckbox('mod_site', 'admin_bg', __('SITE BACKGROUND'), $permissions)?></td>
    <td><?php echo Role::rolepermissioncheckbox('mod_email', 'admin_list', __('Notes/Email send'), $permissions)?></td>
    <td></td>
  </tr>
</table>
</span>					
		</td>
      </tr>
    </table></td>
	</tr>
 </table>
	</td>
	</tr>
 </table>
</div>
</span>
<div class="iconxian">
     <table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon4.jpg" width="32" height="32" /></td>
        <td width="72" class="icontitle"><?php _e("Member Manage");?> </td>
		<td width="10">
		<?php   $member_p = Role::rolepermissionimg('mod_all_member', 'member', __('View'), $permissions,$edit);
				echo $member_p[0];
				if($member_p[1]==1){
		
		?>
		<input type="hidden" value="member" id="member_input" name="permission[mod_all_member][]"> <?php } ?>
		</td>
        <td width="17" class="member_tag"<?php if($member_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_member_sapn\");' " ); ?>            </label>                </td>
      </tr>
    </table></td>
    <td width="464" class="member_tag" <?php if($member_p[1]==0){ ?> style="display:none;" <?php } ?>><table border="0">
      <tr>
        
        <td width="283" class="">
<span id="mod_member_sapn">
<?php echo Role::rolepermissioncheckbox('mod_user', 'admin_list', __('View'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_user','admin_add', __('Add'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_user','admin_edit', __('Edit'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_user', 'admin_delete', __('Delete'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_user', 'admin_finance', __('Finance'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_user', 'admin_search', __('Search'), $permissions)?>
</span>	    </td>
      </tr>
    </table></td>
   
  
  </tr>
</table>

</div>

<!--<div class="iconxian">
     <table width="600" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="136"><table width="134" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon4.jpg" width="32" height="32" /></td>
        <td width="79" class="icontitle"><?php _e("Role Manage");?> </td>
        <td width="17">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_role_sapn\");' " ); ?>            </label>                </td>
      </tr>
    </table></td>
    <td width="464"><table border="0">
      <tr>
        
        <td width="273" class="iconxiaotext">
<span id="mod_role_sapn">
<?php echo Role::rolepermissioncheckbox('mod_roles', 'admin_list', __('View'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_roles','admin_add', __('Add'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_roles','admin_edit', __('Edit'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_roles', 'admin_delete', __('Delete'), $permissions)?>
</span>	    </td>
      </tr>
    </table></td>
   
  
  </tr>
</table>

</div>-->


<div class="iconxian">
     <table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon7.jpg" width="32" height="32" /></td>
        <td width="79" class="icontitle"><?php _e("Bulletins");?> </td>
		<td width="10">
		<?php    $bull_p = Role::rolepermissionimg('mod_all_bulletins', 'bulletins', __('View'), $permissions,$edit);
				echo $bull_p[0];
				if($bull_p[1]==1){
		?>
		<input type="hidden" value="bulletins" id="bulletins_input" name="permission[mod_all_bulletins][]"><?php } ?>
		</td>
        <td width="17" class="bulletins_tag" <?php if($bull_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
          <label>
            <?php echo Html::input('checkbox', '', ''," onclick='toogle_checkbox(this,\"mod_bulletins_sapn\");' " ); ?>            </label>                </td>
      </tr>
    </table></td>
    <td width="464"  class="bulletins_tag" <?php if($bull_p[1]==0){ ?> style="display:none;" <?php } ?>><table border="0">
      <tr>
        
        <td width="273" class="iconxiaotext">
<span id="mod_bulletins_sapn">
<?php echo Role::rolepermissioncheckbox('mod_bulletin', 'admin_list', __('View'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_bulletin', 'admin_add', __('Add'), $permissions)?>
      <?php echo Role::rolepermissioncheckbox('mod_bulletin', 'admin_edit', __('Edit'), $permissions)?>
<?php echo Role::rolepermissioncheckbox('mod_bulletin', 'admin_delete', __('Delete'), $permissions)?></span>	    </td>
      </tr>
    </table></td>
    
  </tr>
</table>

</div>

<div class="iconxian">
     <table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon8.jpg" width="32" height="32" /></td>
        <td width="79" class="icontitle"><?php _e("ContactUs");?> </td>
		<td width="10">
		<?php   $contact_p = Role::rolepermissionimg('mod_all_contact', 'contact', __('View'), $permissions,$edit);
				echo $contact_p[0];
				if($contact_p[1]==1){
		?>
		<input type="hidden" value="contact" id="contact_input" name="permission[mod_all_contact][]"><?php } ?>
		</td>
        <td width="17" class="contact_tag" <?php if($contact_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
                     </td>
      </tr>
    </table></td>
    <td width="464"  class="contact_tag" <?php if($contact_p[1]==0){ ?> style="display:none;" <?php } ?>><table border="0">
      <tr>
        
        <td width="273" class="iconxiaotext">
<span id="mod_contact_sapn">
<?php echo Role::rolepermissioncheckbox('mod_static', 'contact', __('Edit'), $permissions)?>
</span>	    </td>
      </tr>
    </table></td>
    
  </tr>
</table>

</div>

<div class="iconxian">
     <table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon9.jpg" width="32" height="32" /></td>
        <td width="79" class="icontitle"><?php _e("AboutUs");?> </td>
		<td width="10">
		<?php   $about_p = Role::rolepermissionimg('mod_all_about', 'about', __('View'), $permissions,$edit);
				echo $about_p[0];
				if($about_p[1]==1){
		?>
		<input type="hidden" value="about" id="about_input" name="permission[mod_all_about][]"><?php } ?>
		</td>
        <td width="17" class="about_tag" <?php if($about_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
             </td>
      </tr>
    </table></td>
    <td width="464" class="about_tag" <?php if($about_p[1]==0){ ?> style="display:none;" <?php } ?>><table border="0">
      <tr>
        
        <td width="273" class="iconxiaotext">
		<span id="mod_about_sapn">
		<?php echo Role::rolepermissioncheckbox('mod_static', 'about', __('Edit'), $permissions)?>
		</span>	    
		</td>
      </tr>
    </table></td>
    
  </tr>
</table>

</div>

<div class="iconxian">
     <table width="610" border="0" cellpadding="0" cellspacing="0" class="iconright">
  <tr>
    <td width="153"><table width="141" border="0" cellpadding="0" cellspacing="0" class="lefttitle">
      <tr>
        <td width="38"><img src="images/icon10.jpg" width="32" height="32" /></td>
        <td width="79" class="icontitle"><?php _e("Messages");?> </td>
		<td width="10">
		<?php   $message_p = Role::rolepermissionimg('mod_all_message', 'message', __('View'), $permissions,$edit);
				echo $message_p[0];
				if($message_p[1]==1){
		?>
		<input type="hidden" value="message" id="message_input" name="permission[mod_all_message][]"><?php } ?>
		</td>
        <td width="17" class="message_tag" <?php if($message_p[1]==0){ ?> style="display:none;" <?php } ?> align="right">
                        </td>
      </tr>
    </table></td>
    <td width="464" class="message_tag" <?php if($message_p[1]==0){ ?> style="display:none;" <?php } ?>><table border="0">
      <tr>
        
        <td width="273" class="iconxiaotext">
		<span id="mod_message_sapn">
		<?php echo Role::rolepermissioncheckbox('mod_message', 'admin_list', __('Edit'), $permissions)?>
		</span>	    
		</td>
      </tr>
    </table></td>
    
  </tr>
</table>

</div>