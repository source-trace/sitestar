<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (!function_exists('showLMenu')) {
    function showLMenu(&$menu_tree) {
        // TODO : Deal with article categoried list link in menu
        foreach ($menu_tree as $menu) {
    ?>
    <li>
    <?php
            if (sizeof($menu->slaves['MenuItem']) > 0) {
    ?>
    <a href="<?php if($menu->mi_category == 'outer_url') echo $menu->link; else echo 'index.php?'.$menu->link ?>">
    <?php echo $menu->name; ?></a>
    <ul>
	    <?php showLMenu($menu->slaves['MenuItem']); ?>
    </ul>
    <?php
            } else {
    ?>
    <a href="<?php if($menu->mi_category == 'outer_url') echo $menu->link; else echo 'index.php?'.$menu->link ?>">
    <?php echo $menu->name; ?></a>
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

<div class="prod_type">
<ul id="leftmenu_<?php echo $id_seed; ?>">
	<?php showLMenu($menus); ?>
</ul>
</div>
<div class="blankbar"></div>

		<script>
		$("#leftmenu_<?php echo $id_seed; ?> ul").hide();
		$("#leftmenu_<?php echo $id_seed; ?>").droppy();
		</script>

