<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$downloadsearch_form = new Form($_SERVER['REQUEST_URI'], 'downloadsearchform', 'check_downloadsearch_info');
$downloadsearch_form->open();
?>
<table cellspacing="0" class="front_form_table">
    <tbody>
        <tr>
            <td class="label"><?php echo _e('Keyword').': '; ?></td>
            <td class="entry">
                <?php echo Html::input('text', 'download_keyword', $download_keyword, '', 
                    $downloadsearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?>
            </td>
            <td class="normal">
                <?php echo Html::input('submit', 'downloadsearch_submit', __('Search')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$downloadsearch_form->close();
$downloadsearch_form->writeValidateJs();
?>