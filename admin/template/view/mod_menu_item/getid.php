<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<?php if (Toolkit::editMode()) { ?>
<script type="text/javascript" language="javascript">
<!--
function getId(id, val) {
    var p_val = parent.document.getElementById('mi_link_');
    p_val.value = val;
    parent.document.getElementById('tmp_id').value = id;
    window.parent.tb_remove();
}
//-->
</script>
<?php } ?>
<table cellspacing="1" class="list_table" id="full_bulletin_list">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php _e(ucfirst($_field)); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($datas) > 0) {
        $row_idx = 0;
        foreach ($datas as $data) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
            <td><?php echo $data->id; ?></td>
            <td><a href="javascript:void(0);" onclick="getId(<?php echo $data->id;?>, '<?php echo $data->$_field;?>')"><?php echo $data->$_field; ?></a></td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
        <tr class="row_style_0">
            <td colspan="2"><?php _e('No Records!'); ?></td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>
<div class="space"></div>
<?php
include_once(P_TPL.'/common/pager.php');
?>
