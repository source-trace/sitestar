<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (!function_exists('showCategoryMenuP')) {
    function showCategoryMenuP(&$category_tree) {
		$o_url = new MenuItem();
        if(empty($category_tree)) $category_tree = array();
        foreach ($category_tree as $category) {
			//用户自定义URL处理
			if(MOD_REWRITE == '3'){
				$ser = "_m=mod_product&_a=prdlist&cap_id={$category->id}";
				$url_res = $o_url->find(' link=?',array($ser));
				if(empty($url_res->url)){
					$href_url = Html::uriquery2('mod_product', 'prdlist', array('cap_id' => $category->id));
				}else{
					$href_url = $url_res->url.".html";
				}
			}else{
				$href_url = Html::uriquery2('mod_product', 'prdlist', array('cap_id' => $category->id));
			}
    ?>
    <li>
    <?php
            if (sizeof($category->slaves['ProductCategory']) > 0) {
    ?>
    <a href="<?php echo $href_url; ?>">
    <?php echo $category->name; ?></a>
    <ul>
	    <?php showCategoryMenuP($category->slaves['ProductCategory']); ?>
    </ul>
    <?php
            } else {
    ?>
    <a href="<?php echo $href_url; ?>">
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
<?php if (trim(ParamHolder::get('product_category_type',''))=="click") { ?>
	

<style type="text/css">
@media screen, print { 
	ul.mktree  li { list-style: none; } 
	ul.mktree, ul.mktree ul , ul.mktree li { margin-left:7px; padding:0px; }
	ul.mktree  li           .bullet { padding-left: 10px; }
	ul.mktree  li.liOpen    .bullet { cursor: pointer; background: url(<?php echo P_TPL_WEB; ?>/images/minus.gif)  center left no-repeat; }
	ul.mktree  li.liClosed  .bullet { cursor: pointer; background: url(<?php echo P_TPL_WEB; ?>/images/plus.gif)   center left no-repeat; }
	ul.mktree  li.liBullet  .bullet { cursor: default;}
	ul.mktree  li.liOpen    ul { display: block; }
	ul.mktree  li.liClosed  ul { display: none; }

}
</style>

<?php } ?>
<?php if (trim(ParamHolder::get('product_category_type',''))=="always") { ?>
<style type="text/css">
#pro_type_<?php echo $id_seed; ?> ul ul {
    display: block;
}
</style>
<?php } ?>
<div class="list_main category">
	<div class="prod_type">
	<div id="pro_type_<?php echo $id_seed; ?>">
		<ul class="<?php if (trim(ParamHolder::get('product_category_type',''))=="click") { ?>mktree<?php } ?>" id="tree1">
			<?php showCategoryMenuP($categories); ?>
			<div class="blankbar1"></div>
		</ul>		
	</div>
	</div>
	<div class="list_bot"></div>
</div>
<div class="blankbar"></div>

<script type="text/javascript" language="javascript">
/**
 * for menu-drop type
 */
var type = "<?php echo trim(ParamHolder::get('product_category_type',''));?>";
if (type == 'click') {

	} else if (type == 'slide'){
 	$("#pro_type_<?php echo $id_seed; ?> > ul").droppy();
	$("#pro_type_<?php echo $id_seed; ?> ul ul li:last-child").css("border","0px");
}
</script>

