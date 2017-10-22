<?php
/*
 * 文章分页显示视图
 *@author: renzhen
 *@since 2011-04-21
 */
if (!defined('IN_CONTEXT')) die('access violation error!');
if($pagetotal>1){
?>
<style type="text/css">
.pagination {
   /* float: right;*/
    padding: 5px;
    text-align: center;
}
.pagination a, .pagination a:link, .pagination a:visited {
    border: 1px solid #AAAADD;
    color: #006699;
    margin: 2px;
    padding: 2px 5px;
    text-decoration: none;
}
.pagination a:hover, .pagination a:active {
    border: 1px solid #006699;
    color: #000000;
    text-decoration: none;
}
.pagination span.current {
    background-color: #006699;
    border: 1px solid #006699;
    color: #FFFFFF;
    font-weight: bold;
    margin: 2px;
    padding: 2px 5px;
}
.pagination span.disabled {
    border: 1px solid #EEEEEE;
    color: #DDDDDD;
    margin: 2px;
    padding: 2px 5px;
}
</style>
<?php
$last_page=$pagetotal;
$page_number=$pagenum;
$str11 = '';
$per_page = 5;
$leftsidepage=intval(($per_page-1) /2);
$rightsidepage=$per_page-$leftsidepage-1;
$firstshowpage=1;
$endshowpage=$last_page;
$prevpageblock='';
$nextpageblock='';
if($page_number==1){
    $prevpageblock='<span class="disabled prev_page">« '.__('Previous').'</span>';
}else{
   $prevpageblock= '<a class="prev_page" href="'.Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>$page_number-1))).'">« '.__('Previous').'</a>';
}
if($page_number==$last_page){
   $nextpageblock='<span class="disabled next_page"> '.__('Next').' »</span>';
}else{
   $nextpageblock= '<a  class="next_page" href="'.Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>$page_number+1))).'"> '.__('Next').' »</a>';
}
if($last_page <= $per_page)
{
	for($ii = 1;$ii <= $last_page;$ii++)
	{
		if($page_number == $ii)
		{
			$str11 .='<span class="current">'. $ii.'</span>';
		}
		else
		{
			$str11 .= "<a href='".Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>$ii)))."'>$ii</a>";
		}
	}
}
else
{
     if($page_number-$leftsidepage<=0){
         $firstshowpage=1;
         $endshowpage=$per_page;
     }elseif($page_number+$rightsidepage>$last_page){
         $firstshowpage=$last_page+1-$per_page;
         $endshowpage=$last_page;
     }else{
         $firstshowpage=$page_number-$leftsidepage;
         $endshowpage=$page_number+$rightsidepage;
     }
     $str11='';
     if($firstshowpage>=4) {
         $str11 .= "<a href='".Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>1)))."'>1</a><span class=\"gap\">…</span>";
     }
	for($ii = $firstshowpage;$ii <=$endshowpage;$ii++)
	{
		if($page_number == $ii)
		{
			$str11 .='<span class="current">'. $ii.'</span>';
		}
		else
		{
			$str11 .= "<a href='".Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>$ii)))."'>$ii</a>&nbsp;|&nbsp;";
		}
	}
	
     if( $endshowpage!=$last_page){
      $str11 .= "<span class=\"gap\">…</span><a href='".Html::uriquery2($page_mod, $page_act, array_merge($page_extUrl,array('p'=>$last_page)))."'>$last_page</a>&nbsp;|&nbsp;";
      }
//	$str22 .= "<form style='display:inline;margin:0px;' method='post' action='index.php?_m=mod_template&_a=admin_list' id='langswform' name='langswform'>";
//	$str22 .= "<input name='_cates' type='hidden' value='$category_number'>转跳至:<input style='width:25px;' type='text' name='_p' value='$page_number'><input type='submit' value='确定'></form>";
}
?>
<div class="pagination">
     <?php
      echo $prevpageblock;
	 echo $str11; 
      echo $nextpageblock;
    ?>
</div>
<?php
}
?>