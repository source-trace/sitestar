<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style>
	#content .fr ul#admin_field_list li.form_table_list{margin-left: 15px;margin-top: 0;margin-bottom: 0;line-height: 24px;text-align: center;}
	ul#admin_field_list{width:100%;overflow:hidden;margin-bottom: 15px;}
	#content .fr ul#admin_field_list li.form_table_list a{float:none;padding:0;}
	#content .fr ul#admin_field_list li{margin-left: 0px;}
	li.sortable_column tr:hover td{background-color: #F1F1F1;}
	li.sortable_column.ui-sortable-helper tr:hover td{background-color: #FAFAFA;}
	li.sortable_column td.img_achor img{cursor:pointer;}
</style>
<script type="text/javascript" language="javascript">
<!--
function changeFont() {
	$('#param_service_').html('');
}
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminqqlst_stat");
    if (o_result.result == "ERROR") {
        document.forms["contactusform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["contactusform"].reset();
    
    document.getElementById("adminqqlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function parseToAdminURL(module,action,anotherparams){
		var defaultparams={'_m':module,'_a':action}
		var urlparams=$.extend({}, anotherparams, defaultparams);
		var paramstr=$.param(urlparams);
		return "index.php?"+paramstr;
}

function delete_field(field_id) {
	if (confirm("<?php _e('Delete the selected field?'); ?>")) {
		$.get(parseToAdminURL('mod_user_field',"admin_delete",{field_id:field_id}), function(response) {
			var o_result = $.parseJSON(response);
			if (!o_result) {
					alert('<?php _e('Request failed!'); ?>'); 
			}
			 if (o_result.result == "ERROR") {
				 alert(o_result.errmsg); 
			} else if (o_result.result == "OK") {
				window.location.reload();
			} else {
					alert('<?php _e('Request failed!'); ?>'); 
			}
		}).error(function() { 
			alert('<?php _e('Request failed!'); ?>'); 
		})


	}
}


function changePic(isTrue,id,url)
{
	$.ajax({
		type:"POST",
		url:url,
		beforeSend:function(data){
			$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/loader.gif');
		},
		success:function(data){
			if(data == 1)
			{
				$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/yes.gif').attr('alt','Yes');
			}
			else
			{
				$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/no.gif').attr('alt','No');
			}
		},
		error:function(data){
			$("#"+id).attr('src','template/images/warning.gif');
		}
	});
}


$(function(){
	$('#admin_field_list').sortable({
		items:'li.sortable_column',
		cancel:'td.img_achor img,td.field_op a',
		cursor:'move',
		stop:function(e,ui){
			var field_ids=[];
			$('#admin_field_list').find('li.sortable_column input[name=field_id]').each(function(){
				var field_id_input=$(this);
				var field_id=field_id_input.val();
				field_ids.push(field_id);
			})
			var data='';
			for(var i=0;i<field_ids.length;i++){
				var name='i_order['+field_ids[i]+']';
				var val=i+1;
				if(data!='') data+='&'
				data+=(name+"="+encodeURIComponent(val));
			}
			$.ajax({
				 type: "POST",
				 url: parseToAdminURL('mod_user_field',"admin_order"),
				 data:data,
				 success: function(response){
					 var o_result = $.parseJSON(response);
					if (!o_result) {
							alert('<?php _e('Request failed!'); ?>'); 
					}
					 if (o_result.result == "ERROR") {
						 alert(o_result.errmsg); 
					} else if (o_result.result == "OK") {
						
					} else {
							alert('<?php _e('Request failed!'); ?>'); 
					}
				 }
			 });
		}
	});
});
//-->
</script>
<ul style="margin-left:1px;min-height: 20px;">
 <?php
    if(ACL::isAdminActionHasPermission('mod_user', 'admin_list')){
?>
	<li><a class="iconbk nopngfilter_spec" href="<?php echo Html::uriquery('mod_user', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></li>
<?php
}
?>
 <?php
    if(ACL::isAdminActionHasPermission('mod_user_field', 'admin_add')){
?>
	<li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_user_field', 'admin_add');?>" title=""><?php _e('Add custom field'); ?></a></li>
<?php
}
?>
</ul>
<div class="status_bar">
	<span id="adminusrlst_stat" class="status" style="display:none;"></span>
</div>
<ul id="admin_field_list">
	<li class="form_table_list"  >
		<table class="form_table_list" border="0" cellspacing="1" cellpadding="0" style="line-height:24px;margin:0;width:100%;">
			<thead>
				<tr>
								<th width="20%"><?php _e('Field label'); ?></th>
								<th width="25%"><?php _e('Field style'); ?></th>
								<th width="15%"><?php _e('Field type'); ?></th>
								<th width="10%"><?php _e('Show in list'); ?></th>
					 <th width="10%"><?php _e('Required'); ?></th>
					 <th width="10%"><?php _e('Edit'); ?></th>
				<th width="10%"><?php _e('Delete'); ?></th>
						</tr>
				</thead>
		 </table>
	</li>
	 <?php
    if (sizeof($fields) > 0) {
        $row_idx = 0;
		$currentlanguage=SessionHolder::get('_LOCALE');
        foreach ($fields as $field) {
		
    ?>
	<li class="form_table_list sortable_column">
		<input type="hidden" name="field_id" value="<?php echo $field['id']; ?>">
		<table border="0" class="form_table_list" cellspacing="1" cellpadding="0" style="margin:0;line-height:24px;width:100%;">
	
    <tbody>
   
        <tr >
					<td width="20%"><?php echo UserField::getUserDefineLabel($field); ?></td>
		<td width="25%" title="<?php _e('You can drag the field to the corresponding position'); ?>" style="cursor:move;">
			<?php echo UserField::showDisplayFieldStyle($field); ?>
		</td>
        	<td width="15%">
			<?php echo $field_types[$field['field_type']];?>
			
		</td>

        	<td width="10%" class="img_achor"><?php echo Toolkit::validateYesOrNo($field['showinlist'],"img_showinlist_".$field['id'],Html::uriquery('mod_user_field', 'admin_pic', array('_id' => $field['id'],'_tag'=>'showinlist')));?></td>
			<td width="10%" class="img_achor"><?php echo Toolkit::validateYesOrNo($field['required'],"img_required_".$field['id'],Html::uriquery('mod_user_field', 'admin_pic', array('_id' => $field['id'],'_tag'=>'required')));?></td>
        	<td width="10%" class="field_op">
			<?php if($field['field_type']!=0){ ?>
        		<a href="<?php echo Html::uriquery('mod_user_field', 'admin_edit', array('id' => $field['id']));?>" title="<?php _e('Edit'); ?>">
				<img style="border:none;position:relative;top:3px;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>	
			</a>
        	<?php } ?>					
		</td>
        	<td width="10%" class="field_op">
			<?php if($field['field_type']!=0){ ?>
			<a href="#" onclick="delete_field(<?php echo $field['id']; ?>);return false;" title="<?php _e('Delete'); ?>"> 
			<img style="border:none;position:relative;top:3px;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
			</a>			
        	<?php } ?>					
		</td>
        </tr>
  
    </tbody>
</table>
	</li>
	  <?php
            $row_idx = 1 - $row_idx;
        }
    } 
    ?>
</ul>
