<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$field_label=array();
$field_option=array();
if(!empty($curr_field['label'])) $field_label=  unserialize($curr_field['label']);
if(!empty($curr_field['options'])) $field_option = unserialize($curr_field['options']);
$opts_data=$field_option['data'];
if(empty($opts_data)) $opts_data=array();
?>
<style>
	#other_label_div,.opts_label_div,#opts_set_tr,#fake_opts_line{display: none;}
	<?php if(count($all_lang)==1){ ?>.opts_one_line{margin-top:5px;}<?php } ?>

	#content a.wp-new-member-up-arrow{margin-left:5px;}
	#content a.wp-new-member-up-arrow,#content a.wp-new-member-down-arrow{margin-right:3px;height:13px;}
</style>
<script src="../script/jquery.validate.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(function(){	
	function redefineheight(){
		var iframe=$(parent.document.getElementById('adminiframe'))
		if(iframe.size() && iframe.is('iframe')){
			iframe=iframe[0];
			if(iframe.onload) iframe.onload();
		}else{
			iframe=$(parent.document.getElementById('showContents'))
			if(iframe.size() && iframe.is('iframe')){
				iframe=iframe[0];
				if(iframe.onload) iframe.onload();
			}
		}
		
	}
	
	function parseToAdminURL(module,action,anotherparams){
		var defaultparams={'_m':module,'_a':action}
		var urlparams=$.extend({}, anotherparams, defaultparams);
		var paramstr=$.param(urlparams);
		return "index.php?"+paramstr;
	}
	
	jQuery.extend(jQuery.validator.messages, { 
			required: "<?php _e('The field cannot be empty!'); ?>",
			date: '<?php _e('Invalid Input!');?>'
	 });
	
	 $('#fieldaform').validate({
			rules: { 
				'field[label][<?php echo $cur_locale;?>]':{required: true}
			},
			messages: { 
				'field[label][<?php echo $cur_locale;?>]':'<?php _e('The field cannot be empty!'); ?>',
			},
			submitHandler: function() {
				var typeval=$('#field_field_type_').val();
				if(typeval !='3' && typeval !='4') $('#opts_set_tr').remove();
				 var param=$('#fieldaform').serialize();
//				 addLoadingDiv();
				 $.post(parseToAdminURL('mod_user_field','admin_save'),param ,function(o_result) {
						 $('#wp-ajaxsend_loading2').remove();
						 o_result=$.parseJSON(o_result);
						 if (o_result.result == "ERROR") {
								alert(o_result.errmsg); 
								return false;
						} else if (o_result.result == "OK") {
							location.href="<?php echo Html::uriquery('mod_user_field', 'admin_list');?>";
							$('#submit').attr('disabled','disabled');
						} else {
							alert('<?php _e('Request failed!'); ?>'); 
						}
						
				}).error(function() { 
				 //   $('#wp-ajaxsend_loading2').remove();
					alert('<?php _e('Request failed!'); ?>'); 
				});
				return false;
			}
		})
		
		function replyOptsDiv(dom){
			var lang_a=dom.find('.opts_label_show');
			var lang_div=dom.find('.opts_label_div');
			lang_a.click(function(event){
				event.preventDefault();
				lang_div.toggle();
				redefineheight();
			})
			
			dom.find('.opts-line-up').click(function(event){
				event.preventDefault();
				var prevel=dom.prev('.opts_one_line');
				if(prevel.length>0){
					prevel.before(dom);
				}
			})
			
			dom.find('.opts-line-down').click(function(event){
				event.preventDefault();
				var afterel=dom.next('.opts_one_line');
				if(afterel.length>0){
					afterel.after(dom);
				}
			})
			
			dom.find('.opts-line-del').click(function(event){
				console.log(dom)
				event.preventDefault();
				if($('.opts_one_line:not(#fake_opts_line)').length>1 && confirm('<?php _e('Confirm to delete?'); ?>')){
					dom.remove();
					redefineheight();
				}
			})
		}
		 
		 $('.opts_one_line:not(#fake_opts_line)').each(function(){
			 replyOptsDiv($(this));
		 })
				
		 
		 $('#field_field_type_').change(function(){
			 var val=this.value;
			 if(val=='3'|| val=='4') $('#opts_set_tr').show();
			 else $('#opts_set_tr').hide();
			 redefineheight();
		 })
		 
		 if('<?php echo $curr_field['field_type'] ?>' =='3' || '<?php echo $curr_field['field_type'] ?>'=='4'){
			 $('#opts_set_tr').show();
		 }
		 
		 $('#add_opts_achor').click(function(event){
			 event.preventDefault();
			 var new_opts_line=$('#fake_opts_line').clone().appendTo('#opts_all_lines').removeAttr('id');
			 var newkey=genOptsKey();
			 new_opts_line.find('input[type=text]').attr('name', function(i, val) {
				 return val.replace('$actkey',newkey);
			 }).end().find('input[name="key[]"]').val(newkey);
			 replyOptsDiv(new_opts_line); 
			 redefineheight();
		 })
		 
		  if($('.opts_one_line:not(#fake_opts_line)').length==0) $('#add_opts_achor').triggerHandler('click');
		 
		 function genOptsKey(){
			 var val=parseInt($('#opts_incr').val())||0;
			 var nextval=val+1;
			 $('#opts_incr').val(nextval);
			 return "k"+nextval;
			 
		 }
		 
		 
			$('#other_label_show').click(function(event){
				event.preventDefault();
				$('#other_label_div').toggle();
				redefineheight();
			})
});
function backPrv(){
	window.location.href="index.php?_m=mod_user_field&_a=admin_list";	
}
//-->
</script>
<div class="wp-new-member-outside">
<div class="status_bar">
	<span id="admincateafrm_stat" class="status" style="display:none;"></span>
</div>
<div class="wp-new-member-adduser-form">
<form id="fieldaform" action="<?php echo Html::xuriquery('mod_user_field', $next_action)?>" >
<table width="100%" border="0" cellspacing="0" class="form_table" cellpadding="0" style="line-height:24px;">
	<tfoot>
        <tr>
            <td colspan="2">
							<?php
	   echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv()"');
		 echo Html::input('reset', 'reset', __('Reset'));
         echo Html::input('submit', 'submit', __('Save'));	
         echo Html::input('hidden', 'field[id]', $curr_field['id']);
		  echo Html::input('hidden', 'act', $act);
      ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
           <td class="label"><?php _e('Label'); ?></td>
            <td class="entry">
				<?php if(count($all_lang)==1){ ?>
					<input type="text" name="field[label][<?php echo $cur_locale;?>]" class="textinput" value="<?php echo $field_label[$cur_locale];?>" style="width:100px;">			
				<?php }else{ ?>
				<div>
				  <table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%; ">
				  <tr>
				  <td width="10%"  ><label style="margin-right:5px;"><?php echo $all_lang[$cur_locale];?>:</label>  </td>
				  <td width="90%">
					<input type="text" name="field[label][<?php echo $cur_locale;?>]" class="textinput" value="<?php echo $field_label[$cur_locale];?>" style="width:100px;">		
				  <a id="other_label_show" href="#"><?php _e('Other languages'); ?></a>
					</td>
				   </tr>
				  </table>
			     </div>
				<div id="other_label_div" style="width:100%;">
					<table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%;">
						
					<?php 
					    foreach($all_lang as $locale=>$display){ 
						 if($locale !=$cur_locale ){
					?>
					<tr>
					 <td width="10%" ><label style="margin-right:5px;"><?php echo $display;?>:</label>  </td>
					<td width="90%">	 <input type="text" name="field[label][<?php echo $locale;?>]"  value="<?php echo $field_label[$locale];?>" class="textinput" style="width:100px;"></td>
					 </tr>
					<?php } } ?>
				 </table>
			     </div>
				<?php } ?>
            </td>
        </tr>
	   <tr>
            <td class="label"><?php _e('Field type'); ?></td>
            <td class="entry">
			<?php if($act=='add'){ ?>
				<?php echo Html::select('field[field_type]', $field_types, $curr_field['field_type'], ''); ?>
				<?php }else{ ?>
				<?php echo $field_types[$curr_field['field_type']];?>
				<input type="hidden" id="field_field_type_" name="n" value="<?php echo $curr_field['field_type']; ?>">
				<?php } ?>
         
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('The list shows'); ?></td>
             <td class="entry">
            <?php
           echo Html::input('checkbox', 'field[showinlist]', '1', $curr_field['showinlist']==1?'checked="checked"':'');
            ?>
            </td>
       <tr>
            <td class="label"><?php _e('Required'); ?></td>
            <td class="entry">
             <?php
              echo Html::input('checkbox', 'field[required]', '1', $curr_field['required']==1?'checked="checked"':'');
             ?>
            </td>
        </tr>
	  <tr id="opts_set_tr">
            <td class="label" style="vertical-align: top;"><?php _e('The Options'); ?></td>
            <td class="entry">
			<div id="opts_all_lines">
				<?php foreach($opts_data as $onedata){ 
					$langval=$onedata['val'];
				?>
				<div class="opts_one_line">
					 <?php if(count($all_lang)==1){ ?>
					<input type="text" name="val[<?php  echo $onedata['key'];?>][<?php echo $cur_locale;?>]" value="<?php  echo $langval[$cur_locale];?>" style="width:100px;" class="textinput" required/>
					 <a class="opts-line-up wp-new-member-up-arrow" href="#"></a>
					  <a class="opts-line-down wp-new-member-down-arrow" href="#"></a>
					 <a class="opts-line-del wp-new-member-delete-button" href="#"></a>
					<?php }else{ ?>
					<table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%;">
					<tr>
					<td width="10%"><label><?php echo $all_lang[$cur_locale];?>:</label> </td>
					<td width="90%">
						<input type="text" name="val[<?php  echo $onedata['key'];?>][<?php echo $cur_locale;?>]" value="<?php  echo $langval[$cur_locale];?>" style="width:100px;" class="textinput" required/>
						 <a class="opts_label_show" href="#"><?php _e('Other languages'); ?></a>
						 <a class="opts-line-up wp-new-member-up-arrow" href="#"></a>
						 <a class="opts-line-down wp-new-member-down-arrow" href="#"></a>
						 <a class="opts-line-del wp-new-member-delete-button" href="#"></a>
					</td>
					</tr>
					</table>	
						 <div class="opts_label_div" style="width:100%;">
							 <table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%;">
							<?php 
								foreach($all_lang as $locale=>$display){ 
								 if($locale !=$cur_locale ){
							?>
								 <tr>
							 <td width="10%"><label><?php echo $display;?>:</label>  </td>
							 <td width="90%">
							 <input type="text" name="val[<?php  echo $onedata['key'];?>][<?php echo $locale;?>]"  value="<?php  echo $langval[$locale];?>" class="textinput" style="width:100px;">
							</td>
							</tr>
							 <?php } } ?>
							 </table>	
							 </div>
							<?php } ?>
					<input type="hidden" name="key[]" value="<?php  echo $onedata['key'];?>"/>
				</div>
				<?php } ?>
			</div>
			<a href="#" id="add_opts_achor" style="<?php  echo (count($all_lang)==1)?'margin-left:120px':'margin-left:200px';?>"><?php _e('Add'); ?></a>
			<input type="hidden" id="opts_incr" name="opts[incr]" value="<?php  echo $field_option['incr'];?>"/>
            </td>
        </tr>
    </tbody>
</table>
</form>
<div id="fake_opts_line" class="opts_one_line">
           <?php if(count($all_lang)==1){ ?>
		<input type="text" name="val[$actkey][<?php echo $cur_locale;?>]" style="width:100px;" class="textinput" required/>
		<a class="opts-line-up wp-new-member-up-arrow" href="#"></a>
		 <a class="opts-line-down wp-new-member-down-arrow" href="#"></a>
		 <a class="opts-line-del wp-new-member-delete-button" href="#"></a>
		<?php }else{ ?>
	       <table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%;">
			<tr>
		     <td width="10%"><label><?php echo $all_lang[$cur_locale];?>:</label>  </td>
			<td width="90%">
			<input type="text" name="val[$actkey][<?php echo $cur_locale;?>]" style="width:100px;" class="textinput" required/>
			 <a class="opts_label_show" href="#"><?php _e('Other languages'); ?></a>
			 <a class="opts-line-up wp-new-member-up-arrow" href="#"></a>
			 <a class="opts-line-down wp-new-member-down-arrow" href="#"></a>
			 <a class="opts-line-del wp-new-member-delete-button" href="#"></a>
			</td>
			</tr>
				 </table>
			<div class="opts_label_div" style="width:100%">
				<table border="0" class="form_table_list2" cellspacing="1" cellpadding="3" style="width:100%;">
					
				<?php 
					foreach($all_lang as $locale=>$display){ 
					 if($locale !=$cur_locale ){
				?>
					<tr>
						<td width="10%"><label><?php echo $display;?>:</label>  </td> 
						<td width="90%"> <input type="text" name="val[$actkey][<?php echo $locale;?>]"  value="" class="textinput" style="width:100px;"></td>
				</tr>
				 <?php } } ?>
				 </table>
				 </div>
		<?php } ?>
		<input type="hidden" name="key[]" />
</div>
</div>
</div>