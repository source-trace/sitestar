<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php echo $category->name; ?></div>
    <div class="rightblock">
        <?php include_once(dirname(__FILE__).'/_search.php'); ?>
    </div>
    <div class="clearer"></div>
</div>
<div class="contentbody">
    <table cellspacing="1" class="front_list_table">
        <tbody>
        <?php
        if (sizeof($downloads) > 0) {
            $row_idx = 0;
            foreach ($downloads as $download) {
        ?>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td class="mainlistlink">
                    <a href="<?php echo Html::uriquery('mod_download', 'download', array('dw_id' => $download->id)); ?>"
                        title="<?php echo $download->description; ?>"><?php echo Toolkit::substr_MB($download->description, 0, 20); ?>...</a>
                </td>
                <td class="mainlistlink" width="64">
                    <a href="<?php echo Html::uriquery('mod_download', 'download', array('dw_id' => $download->id)); ?>">
                        <?php _e('Download'); ?></a>
                </td>
                <td class="smallgray aligncenter" width="96"><?php echo date('Y-m-d H:i', $article->create_time); ?></td>
            </tr>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
            <tr class="row_style_0">
                <td colspan="3" class="aligncenter"><?php _e('No Records!'); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="contentpager">
    <?php include_once(P_TPL.'/common/pager.php'); ?>
</div>