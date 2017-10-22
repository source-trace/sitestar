<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$prdsearch_form = new Form($_SERVER['REQUEST_URI'], 'prdsearchform', 'check_prdsearch_info');
$prdsearch_form->open();
?>
<table cellspacing="0" class="front_form_table">
    <tbody>
        <tr>
            <td class="label"><?php echo __('Keyword').': '; ?></td>
            <td class="entry">
                <?php echo Html::input('text', 'prd_keyword', $prd_keyword, '', 
                        $prdsearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?>
            </td>
            <td class="normal">
                <?php echo Html::input('submit', 'prdsearch_submit', __('Search')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$prdsearch_form->close();
$prdsearch_form->writeValidateJs();
?>