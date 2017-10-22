<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (!function_exists('showCategoryMenuP')) {
    function showCategoryMenuP(&$category_tree) {
        
        foreach ($category_tree as $category) {
    ?>
    <li>
    <?php
            if (sizeof($category->slaves['ProductCategory']) > 0) {
    ?>
    <span><?php echo $category->name; ?></span>
    <ul>
	    <?php showCategoryMenuP($category->slaves['ProductCategory']); ?>
    </ul>
    <?php
            } else {
    ?>
    <a href="<?php echo Html::uriquery('mod_product', 'prdlist', array('cap_id' => $category->id)); ?>">
    <?php echo $category->name; ?></a>
    <?php
            }
    ?>
    </li>
    <?php
        }
    }
}
$id_seed = Toolkit::randomStr();
?>

<ul id="category_p_menu_<?php echo $id_seed; ?>">
	<?php showCategoryMenuP($categories); ?>
</ul>

<script type="text/javascript" language="javascript">
<!--
	$(function() {
        $('#category_p_menu_<?php echo $id_seed; ?>').ddMenu({
            rootTitle: "<?php echo ParamHolder::get('block_title'); ?>"
        });
    });
//-->
</script>
