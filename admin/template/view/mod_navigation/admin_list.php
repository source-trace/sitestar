<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cols = 6;
?>
<script type="text/javascript" language="javascript">
<!--
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_del_failure(response);
    }
    
    var stat = document.getElementById("adminnavlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
        reloadPage();
    } else {
        return on_del_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminnavlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_nav(nav_id, nav) {
    if (confirm("<?php _e('Delete the selected navigation?'); ?>")) {
        var stat = document.getElementById("adminnavlst_stat");
        stat.style.display = "block";
        stat.innerHTML = "<?php _e('Deleting selected navigation...'); ?>";
        _ajax_request("mod_navigation", 
            "admin_delete", 
            {
                nav_id:nav_id,
                nav:nav
            }, 
            on_del_success, 
            on_del_failure);
    }
}

function on_tog_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_del_failure(response);
    }
    
    var stat = document.getElementById("adminnavlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
        reloadParent();
    } else {
        return on_del_failure(response);
    }
}

function on_tog_failure(response) {
    on_del_failure(response);
}

function toggle_default(nav_id) {
    var stat = document.getElementById("adminnavlst_stat");
    stat.style.display = "block";
    stat.innerHTML = "<?php _e('Setting default navigation...'); ?>";
    _ajax_request("mod_navigation", 
        "admin_default", 
        {
            nav_id:nav_id
        }, 
        on_tog_success, 
        on_tog_failure);
}
//-->
</script>
<span id="adminnavlst_stat" class="status" style="display:none;"></span>
<ul style="margin-left:1px;">
	<li><a class="iconna" href="<?php echo Html::uriquery('mod_navigation', 'admin_upload'); ?>" title=""><?php _e('Upload Navigation'); ?></a></li>
</ul>

<table width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-left:15px;" id="admin_tpl_list">
    <tbody>
	    <tr>
		    <td>
		    <?php
		    $n_navs = sizeof($navigations);
//		    if ($n_navs > 0) {
		    ?>
			    <table class="template_grid" cellspacing="0">
			    	<?php
			        	for($i = 0; $i < $n_navs; $i++)
			        	{
			        		if($navigations[$i]->navigation == DEFAULT_NAV)
			        		{
			        			echo "<tr>";
			        			echo '<td style="text-align:left;" width="'.intval(100 / $cols).'%">';
			        			//echo '<img class="tpl_thumb" src="../navigation/'.$navigations[$i]->navigation.'/screenshot.jpg" alt='.$navigations[$i]->navigation.'/><br/><span class="small">';
			        			echo $navigations[$i]->navigation.'<br/><span class="small">';
			        			echo '<a style="text-align:left;" href="#" onclick="toggle_default(-1);return false;" title="'.__('Cancel as default navigation').'">'.__('Cancel as default navigation').'</a>';
			        			echo '</span></td></tr>';
			            		break; 	
			        		}
			        	}
			        	echo '</table>';
			        	echo '<table class="template_grid" cellspacing="0">';
			        	$i = 0;
			        	while($i < $n_navs)
			        	{
			        		echo "<tr>";
			        		for($j = 0; $j < $cols; $j++)
			        		{
				        		if( $i < $n_navs && $navigations[$i]->navigation == DEFAULT_NAV)
				        		{
				        			$i++;
				        			$j--;
				        			continue;	
				        		}
				        		if($i >= $n_navs)
				        		{
				        			break;	
				        		}
				        ?>
			            <td width="<?php echo intval(100 / $cols); ?>%">
			            	<!--img class="tpl_thumb" src=<?php echo '../navigation/'.$navigations[$i]->navigation.'/screenshot.jpg'; ?> alt=<?php echo $navigations[$i]->navigation; ?> /-->
			            	<?php echo $navigations[$i]->navigation;?><br />
			            	<span class="small">
				            <?php
//							if ($navigations[$i]->navigation == DEFAULT_NAV) {
//								echo '<a href="#" onclick="toggle_default(-1);return false;" title="'.__('Cancel as default navigation').'">'.__('Cancel as default navigation').'</a>';
//				            } else {
				                echo '<a href="#" onclick="toggle_default(\''.$navigations[$i]->navigation.'\');return false;" title="'.__('Set as default navigation').'">'.__('Set as default navigation').'</a>';
				                echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="delete_nav('.$navigations[$i]->id.',\''.$navigations[$i]->navigation.'\');return false;" title="'.__('Delete').'">'.__('Delete').'</a>'
//							}
				            ?>
				            &nbsp;
			            	</span>
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
			        ?>
			    </table>
		    <?php //} ?>
		    </td>
	    </tr>
    </tbody>
</table>
