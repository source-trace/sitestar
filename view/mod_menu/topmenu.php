<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

if (!function_exists('showMenu')) {
	
    function showMenu(&$menu_tree, $level = 0) {
        foreach ($menu_tree as $menu) {
		if(strstr($menu->published,'|')){
			$pub = explode("|",$menu->published);
		}else{
			$pub[0] = $menu->published;
			$pub[1] = 0;
		}
		if($pub[0]==0) continue
    ?>
    <li>
    <?php
            if (sizeof($menu->slaves['MenuItem']) > 0) {
                $level++;
    ?>
    <span><a href="<?php if($menu->mi_category == 'outer_url'){ echo $menu->link; }else echo $menu->link; ?>" <?php if($pub[1] == '1'){echo "target='_blank'";}else{echo "target='_self'";}?>>
    <?php echo $menu->name; ?></a>
    <?php
            echo '<ul>'."\n";
        //if ($level == 1) {
            //echo '<ul class="drop_level">'."\n";
        //} else {
            //echo '<ul class="flyout">'."\n";
        //}
    ?>
        <?php showMenu($menu->slaves['MenuItem'], $level); ?>
    </ul></span>
    <?php
                $level--;
            } else {
    ?>
    <a href="<?php  echo $menu->link; ?>" <?php if($pub[1] == '1'){echo "target='_blank'";}else{echo "target='_self'";}?>>
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


			<div class="nav_l"></div>
        	<ul id="nav_<?php echo $id_seed; ?>" class="navigation">
			<?php showMenu($menus); ?>
            </ul>
			<div class="nav_r"></div>

		
<script type="text/javascript">
//$(".navigation li:first").css("background-image","none");
var topMenuNum = 0;
$("#nav_<?php echo $id_seed; ?> li span").hover(
	function(){
		topMenuNum++;
		$(this).attr("id","kindMenuHover"+topMenuNum);
		$("#kindMenuHover" + topMenuNum + " > ul").show();
		$(this).parent().addClass("hover");
	},
	function(){
		$("#"+$(this).attr("id")+" > ul").hide();
		$(this).attr("id","");
		$(this).parent().removeClass("hover");
	}
);
</script>
