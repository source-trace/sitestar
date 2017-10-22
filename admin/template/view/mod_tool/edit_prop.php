<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

//var_dump($curr_mblock);
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
$mb_form = new Form('index.php', 'mblockform', 'check_login_info');
$mb_form->p_open('mod_tool', 'save_prop', '_ajax');
?>
<table id="mblockform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mb[id]', $curr_mblock->id);
            echo Html::input('hidden', 'dispage', $_SERVER['HTTP_REFERER']);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'mb[title]', $curr_mblock->title);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[show_title]', '1', 
                Toolkit::switchText($curr_mblock->show_title, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Show Title'); ?>
            </td>
        </tr>
        <!-- [Disable publish status temporarily] tr>
            <td class="label"><?php _e('Publish'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'mb[published]', '1', 
                Toolkit::switchText($curr_mblock->published, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr -->
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            $checked = '';
            if ($curr_mblock->s_query_hash == '_ALL') {
                $checked = 'checked="checked"';
            }
            echo Html::input('checkbox', 'dispallpg', '1', $checked);
            ?>
            &nbsp;<?php _e('Display on all pages'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_mblock->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
        <?php
        $arr_params = array();
        if (strlen(trim($curr_mblock->s_param)) > 0) {
            $arr_params = unserialize($curr_mblock->s_param);
        }
        for ($i = 0; $i < sizeof($params); $i++) {
        ?>
        <tr>
            <td class="label"><?php _e($params[$i]['label']); ?></td>
            <td class="entry">
        <?php
            switch ($params[$i]['tag']) {
                case 'input':
                	switch ($params[$i]['type']) {
                		case 'text':
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $arr_params[$params[$i]['id']], 
		                        $params[$i]['extra']);
                			break;
                	    case 'checkbox':
                	    	$element_extra = $params[$i]['extra'];
                	    	if (intval($arr_params[$params[$i]['id']]) == 1) {
                	    	    $element_extra .= ' checked="checked"';
                	    	}
		                    echo Html::input($params[$i]['type'], 
		                        'ex_params['.$params[$i]['id'].']', 
		                        $params[$i]['value'], 
		                        $element_extra);
                	    	break;
                	}
                    break;
                case 'textarea':
                    echo Html::textarea('ex_params['.$params[$i]['id'].']', 
                        $arr_params[$params[$i]['id']], 
                        $params[$i]['extra']);
                    break;
                case 'select':
                	switch ($params[$i]['fill_type']) {
                	    case 'objfunc':
                	    	$obj_name = $params[$i]['obj_name'];
                	    	$obj = new $obj_name();
                	    	$func = $params[$i]['func_name'];
                	    	$hash_entry =& $obj->$func();
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$hash_entry, $arr_params[$params[$i]['id']], 
                	    		$params[$i]['extra']);
                	    	break;
                	    case 'array':
                	    	echo Html::select('ex_params['.$params[$i]['id'].']', 
                	    		$params[$i]['data'], $arr_params[$params[$i]['id']], 
                	    		$params[$i]['extra']);
                	    	break;
                	}
                	break;
                case 'imgpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                $arr_params[$params[$i]['id']], $params[$i]['extra']);
		            echo sprintf('&nbsp;<a href="#" onclick="popup_win=show_imgpicker(\'%s\');return false;" title="">%s</a>', 
		            	'ex_params['.$params[$i]['id'].']', __('Pick Image'));
                	break;
                case 'flvpicker':
		            echo Html::input('text', 
		                'ex_params['.$params[$i]['id'].']', 
		                $arr_params[$params[$i]['id']], $params[$i]['extra']);
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
$mb_form->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$mb_form->addCustValidationJs($custom_js);
$mb_form->writeValidateJs();
?>
