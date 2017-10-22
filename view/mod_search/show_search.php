<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>

<div class="show_search_con" id="show_search_con">
<form id="search_form" method="post" action="index.php?_m=mod_product&_a=prdlist">
<input id="search_input" type="text" value="" name="prdsearchform">
<select id="search_type" name="search_type" onchange="changeAct()" id="search_type">
<option selected="" value="product">产品</option>
<option value="article">文章</option></select>
<input id="prdsearch_submit" type="submit" value="搜索" name="prdsearch_submit">
</form>
</div>
<div class="blankbar"></div>
<div class="list_bot"></div>


<script>
function changeAct(){
	var act_val = $('#search_type').val();
	var act = '';
	if(act_val=='article'){
		act = 'index.php?_m=mod_article&_a=fullist';
		$('#search_input').attr('name','article_keyword');
		$('#search_form').attr('action',act)
	}else{
		$act = 'index.php?_m=mod_product&_a=prdlist';
		$('#search_input').attr('name','prd_keyword');
		$('#search_form').attr('action',act)
	}
}
</script>

