<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<style type="text/css">
@import "template/css/popup.css";
</style>
<!--div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php //_e('Please select article to display'); ?></div></td>
				<td>
				    <a href="<?php //echo Html::uriquery('mod_article', 'admin_mi_quick_add', array('txt' => $type_text)); ?>" title=""><?php _e('Add Article'); ?></a>
				</td>
			</tr>
		</tbody>
	</table>
</div-->
<!--div class="space"></div-->
<table cellspacing="0" class="list_table" id="admin_article_list">
	<thead>
		<tr>
		    <th width="20"></th>
            <th><?php _e('Title'); ?></th>
            <th><?php _e('Category'); ?></th>
            <th width="48"><a href="#" title="" onclick="parent.$('#showContents').show();parent.$('#showContents1').remove();" style="color:#4372b0;font-weight:bold;"><?php _e('Back'); ?></a></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($articles) > 0) {
        $row_idx = 0;
        foreach ($articles as $article) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td></td>
        	<td class="left"><?php echo $article->title; ?></td>
        	<?php $article->loadRelatedObjects(REL_PARENT, array('ArticleCategory')); ?>
        	<td><?php echo $article->masters['ArticleCategory']->name; ?></td>
        	<td>
        	   <a href="#" onclick="select_for_menu_item('<?php echo $type_text.' - '.$article->title; ?>', '<?php echo $article->id; ?>'); return false;"><?php _e('Select'); ?></a>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="4"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<div class="space"></div>
<?php
	if (MOD_REWRITE == '2') $type = "popupwin";
	include_once(P_TPL.'/common/pager.php');
?>
