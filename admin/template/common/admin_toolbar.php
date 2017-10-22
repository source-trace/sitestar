<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<?php 
	$i = 0;$flag = false;
	foreach ($content_entries as $content_entry) {
		if($i == 5)
		{
			$flag = true;
?>
			<div onclick="menuCollapse();" class="top" id="collapse"><?php echo _e('More'); ?><span class="adminmenu_indent">&raquo;</span></div>
			<div id="menu_collapse">
<?php	}
?>
	<?php if($content_entry->text != 'News') { //页面上屏蔽公司新闻菜单项?>
    <a class="sblinks" href="<?php echo Html::uriquery($content_entry->module, $content_entry->action); ?>" title=""><?php _e($content_entry->text); ?></a>
    <?php }?>
<?php $i++;} if($flag) echo '</div>';$i = 0;?>
<?php foreach ($admin_entries as $section => $entries) { ?>
			<?php if($i == 0) {?>
            	<div class="top" onclick="menuToolCollapse();" id="menuTool"><?php echo _e($section); ?>&nbsp;<span class="adminmenu_indent">&raquo;</span></div>
            	<div id="menu_tool">
            <?php }else{?>
            	<?php if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) == 'en'){?>
            	<div class="top" onclick="menuSystemCollapse();" id="menuSystem"><?php echo _e($section); ?>&nbsp;<span class="adminmenu_indent">&raquo;</span></div>
            	<?php }else{?>
            	<div class="top" onclick="menuSystemCollapse();" id="menuSystem"><?php echo _e($section); ?>&nbsp;<span class="adminmenu_indent">&raquo;</span></div>
            	<?php }?>
            	<div id="menu_system">	
            <?php }?>
            
            <?php foreach ($entries as $menu_entry) {?>
                <?php if ($menu_entry[0] == '0') { ?>
                    <a href="<?php echo Html::uriquery($menu_entry[1], $menu_entry[2]); ?>" title="" class="sblinks"><?php _e($menu_entry[3]); ?></a>
                <?php } else if ($menu_entry[0] == '1') { ?>
                    <a href="<?php echo $menu_entry[1]; ?>" target="_blank" title="" class="sblinks"><?php _e($menu_entry[2]); ?></a>
                <?php } ?>
            <?php } echo '</div>'; $i++;?>
<?php } ?>
<!--  
    <div class="admintoolbar_right">
    	&raquo; <a href="javascript:save_layout();"><?php _e('Save Layout'); ?></a>&nbsp;
        &raquo; <a href="index.php?<?php echo $_SERVER['QUERY_STRING']?$_SERVER['QUERY_STRING'].'&amp;':''; ?>_v=preview" target="_blank" title=""><?php _e('Preview'); ?></a>
        &raquo; <a href="<?php echo Html::uriquery('frontpage', 'dologout'); ?>" title="<?php _e('Logout') ?>"><?php _e('Logout') ?></a>
    </div>
-->
<script type="text/javascript" language="javascript">
<!--
    function save_layout() {
        var pos_form = document.forms["SAVE_POS"];
        save_position(pos_form, on_save_pos_success, on_save_pos_failure);
    }
    
    function on_save_pos_success(response) {
        var o_result = _eval_json(response);
        if (!o_result) {
            return on_failure(response);
        }
        
        if (o_result.result == "OK") {
            alert("<?php _e('Layout saved!'); ?>");
            return true;
        } else {
            return on_failure(response);
        }
    }

    function on_save_pos_failure(response) {
        alert("<?php _e('Request failed!'); ?>");
        return false;
    }
    
    function delayHide(jq_obj) {
        setTimeout(
            function() {
                jq_obj.children("div").hide("normal");
            },
            200
        );
    }
    
    function addHoverHide() {
        $("#admintoolbar_nav li").each(
            function() {
                $(this).hover(
	                function(){
	                    $(this).children("div").show("normal");
	                },
	                function(){
	                    delayHide($(this));
	                }
                );
            }
        );
    }
    addHoverHide();
//-->
</script>
