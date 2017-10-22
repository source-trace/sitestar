<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

//print_r($params);

?>
<script type="text/javascript" language="javascript">
<!--
var popup_win = false;

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    if (o_result.result == "OK") {
        reloadParent();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    alert("<?php _e('Update failed!'); ?>");
}
//-->
</script>
<?php
$new_mblock_form_s2 = new Form('index.php', 'newmblockform', 'check_mblock_info');
$new_mblock_form_s2->p_open('mod_tool', 'add_mblock', '_ajax');
?>
<table id="mblockform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mb[module]', $w_module);
            echo Html::input('hidden', 'mb[action]', $w_action);
            echo Html::input('hidden', 'dispage', ParamHolder::get('dispage', ''));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'mb[title]');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[show_title]', '1');
            ?>
            &nbsp;<?php _e('Show Title'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Position'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('mb[s_pos]', $positions);
            ?>
            </td>
        </tr>
        <!-- [Disable publish status temporarily] tr>
            <td class="label"><?php _e('Publish'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[published]', '1', 
                'checked="checked"');
            ?>
            </td>
        </tr -->
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'dispallpg', '1');
            ?>
            &nbsp;<?php _e('Display on all pages'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1');
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($params); $i++) {
        ?>
        <tr>
            <td class="label"><?php echo $params[$i]['label']; ?></td>
            <td class="entry">
        <?php
            switch ($params[$i]['tag']) {
                case 'input':
                	switch ($params[$i]['type']) {
                		case 'text':
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], $params[$i]['extra']);
                			break;
                	    case 'checkbox':
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], 
		                        $params[$i]['extra']);
                	    	break;
                	}
                    break;
                case 'textarea':
                    echo Html::textarea('ex_params['.$params[$i]['id'].']', 
                        '', $params[$i]['extra']);
                    break;
                case 'select':
                	switch ($params[$i]['fill_type']) {
                	    case 'objfunc':
                	    	$obj_name = $params[$i]['obj_name'];
                	    	$obj = new $obj_name();
                	    	$func = $params[$i]['func_name'];
                	    	$hash_entry =& $obj->$func();
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$hash_entry, '', $params[$i]['extra']);
                	    	break;
                	    case 'array':
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$params[$i]['data'], '', $params[$i]['extra']);
                	    	break;
                	}
                	break;
                case 'imgpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                '', $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
                	break;
                case 'flvpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                '', $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_flvpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Flash'));
                	break;
            }
        ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
$new_mblock_form_s2->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$new_mblock_form_s2->addCustValidationJs($custom_js);
$new_mblock_form_s2->writeValidateJs();
?>
