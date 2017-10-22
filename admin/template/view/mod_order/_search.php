<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$articlesearch_form = new Form($_SERVER['REQUEST_URI'], 'articlesearchform', 'check_articlesearch_info');
$articlesearch_form->open();
?>
<table cellspacing="0" class="front_form_table">
    <tbody>
        <tr>
            <td class="label"><?php echo __('Keyword').': '; ?></td>
            <td class="entry">
                <?php echo Html::input('text', 'article_keyword', $article_keyword, '', 
                        $articlesearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?>
            </td>
            <td class="normal">
                <?php echo Html::input('submit', 'articlesearch_submit', __('Search')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$articlesearch_form->close();
$articlesearch_form->writeValidateJs();
?>
