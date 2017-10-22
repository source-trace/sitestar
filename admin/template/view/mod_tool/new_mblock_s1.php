<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$new_mblock_form_s1 = new Form('index.php', 'newmblockform');
$new_mblock_form_s1->p_open('mod_tool', 'new_mblock_s2');
?>
<table id="mblockform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Next'));
            echo Html::input('hidden', 'dispage', $_SERVER['HTTP_REFERER']);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Select Function'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('widget', $widgets_info);
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$new_mblock_form_s1->close();
?>