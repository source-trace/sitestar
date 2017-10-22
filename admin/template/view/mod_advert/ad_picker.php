<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript" src="../script/jquery.min.js"></script>
<style type="text/css">
<!--
* {margin:0;padding:0}
a {text-decoration: none;}
ul,li {list-style:none}
.box {margin:0;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;}
.tagMenu {height:28px;line-height:28px;background:#efefef;position:relative;border-bottom:1px solid #999;}
.tagMenu h2 {font-size:12px;padding-left:10px;}
.tagMenu ul {position:absolute;left:100px;bottom:-1px;height:26px;}
ul.menu li {float:left;margin-bottom:1px;line-height:16px;height:14px;margin:5px 0 0 -1px;text-align:center;padding:0 12px;cursor:pointer;}
ul.menu li.current {border:1px solid #999;border-bottom:none;background:#fff;height:25px;line-height:26px;margin:0;}
.tabmain {padding:0}
span{margin-left:9px;}
img {border-color:#dedede;border-style:solid;border-width:2px;margin:2px 12px 2px 18px;cursor:pointer;}
table {font-size:12px;line-height:30px;margin:10px 0px 0px 15px;}
table td {border-bottom:1px dashed #ccc;height:155px;width:25%;}
.btnimg {border:none;margin-top:7px;}
-->
</style>
<script language="javascript">
<!--
$(document).ready(function(){
	var tt = "<?php echo $act;?>";
	if (tt.lastIndexOf('h') == -1) {
		$("ul.menu li:first-child").addClass("current");
		$("ul.menu li:last-child").removeClass("current");
	} else {
		$("ul.menu li:first-child").removeClass("current");
		$("ul.menu li:last-child").addClass("current");
	}
});

function get_theme( obj )
{
	var p = "<?php echo $position;?>";
	var s = $(obj).prev().find('img').attr('src');
	if( p == '1' ) {
		window.parent.document.getElementById('ad_src').innerHTML = '<img src="'+s+'" border="0" />';
		window.parent.document.getElementById('sparam_ADVERT_THEME_').value = s;
	} else {
		window.parent.document.getElementById('rad_src').innerHTML = '<img src="'+s+'" border="0" />';
		window.parent.document.getElementById('sparam_ADVERT_RTHEME_').value = s;
	}
	window.parent.tb_remove();
}
//-->
</script>
<?php
$themes = array();
$theme_dir = '../data/adtool/theme';
$themes = getThemes( $theme_dir, $act );

$lngext = '';
if (SessionHolder::get('_LOCALE') == 'en') {
	$lngext = 'en_';
} elseif (SessionHolder::get('_LOCALE') == 'zh_TW') {
	$lngext = 'tw_';
}
$sum = count($themes);
switch( $act ) {
	case 't':  // 通用弹出型
	case 'ht': // 节日祝福弹出型
		$cols = 2;
		$rows = 1;
		break;
	case 'f':   // 通用浮动型
	case 'hf':  // 节日祝福浮动型
		$cols = 5;
	    $rows = 2;
		break;
	case 'd':   // 通用对联型
	case 'hd':  // 节日祝福对联型
		$cols = 5;
	    $rows = 1;
		break;
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$sum_page = ceil($sum / ($cols * $rows));

$start_page_number = ($page - 1) * $cols * $rows;
$end_page_number = $page * $cols * $rows - 1;

if( $_GET['page'] > $sum_page ) {
	die("页数错误！");
} else if( ($_GET['page'] == $sum_page) && is_float($sum / ($cols * $rows)) ) {
	$end_page_number = $start_page_number - 1 + ($sum % ($cols * $rows));
}

function getThemes( $theme_dir, $act )
{	
	$themes = array();
	
	if( is_readable($theme_dir) ) 
	{
		$files = is_array(glob($theme_dir.'/'.$act.'*')) ? glob($theme_dir.'/'.$act.'*') : array();
		foreach( $files as $theme ) {
			if ( is_dir( $theme ) ) {
				$themes = array_merge( $themes, getThemes( $theme, $act ) );
			} else {
				$themes[] = $theme;
			}
		}
	} else {
		echo "&nbsp;&nbsp;<font color='#FF0000'>The '{$theme_dir}' isn't readable.</font>";
	}
	
	return $themes;
}
?>
<div style="overflow:auto;width:100%;">
<div class="box">
  	<div class="tagMenu">
      <h2>&nbsp;&nbsp;</h2>
      <ul class="menu">
          <li><?php 
    	  echo '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('adtype'=>$type, 'p'=>$position)).'">'.__('Universal').'</a>';?></li>
          <li><?php 
    	  echo '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('adtype'=>$type, 'tag'=>'h', 'p'=>$position)).'">'.__('Holiday Blessing').'</a>';?></li>
       </ul>
	</div>
	<div class="tabmain">
       <div class="layout">
<?php
if( $tag != 'h' ) {
$j = 1;
echo '<table cellspacing="0" cellspacing="0"><tr>';
for( $i = $start_page_number; $i <= $end_page_number; $i++ )
{
  if( $i < $sum ) {
	if( $j <= $cols ) {
		echo "<td align='center'><div><img onmouseout='$(this).css(\"border\",\"2px solid #DEDEDE\");' onmouseover='$(this).css(\"border\",\"2px solid #F68B17\");' width='$width' height='$height' src='$themes[$i]' /></div><img onclick='get_theme(this)' class='btnimg' src='images/".$lngext."select_img.png' /></td>";
	} else {
		echo "</tr>";
		echo "<tr><td align='center'><div><img onmouseout='$(this).css(\"border\",\"2px solid #DEDEDE\");' onmouseover='$(this).css(\"border\",\"2px solid #F68B17\");' width='$width' height='$height' src='$themes[$i]' /></div><img onclick='get_theme(this)' class='btnimg' src='images/".$lngext."select_img.png' /></td>";
		$j = 1;
	}
	$j++;
  } else {
  	  break;
  }
}
?>
</table>
<?php
if( $sum_page >= 1 ) {
$str = '<div style="padding-left:15px;padding-bottom:2px;">';
$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$sum_page, 'adtype'=>$type, 'p'=>$position)).'">'.__('Last').'</a> | ';
if( $page < $sum_page ) {
	$next_page = $page + 1;
	$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$next_page, 'adtype'=>$type, 'p'=>$position)).'">'.__('Next').'</a> | ';
}

if( $page > 1 ) {
	$prev_page = $page - 1;
	$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$prev_page, 'adtype'=>$type, 'p'=>$position)).'">'.__('Previous').'</a> | ';
}
$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>1, 'adtype'=>$type, 'p'=>$position)).'">'.__('First').'</a>';
$str .= "<span style='margin-top:10px;display:inline-block;margin-left:320px;'>$page/$sum_page</span>";
$str .= '</div>';
echo $str;
}
?>
       </div>
    
<?php
} else{// tab2

if( $sum_page >= 1 ) {
$j = 1;
echo '<table cellspacing="0" cellspacing="0"><tr>';
for( $i = $start_page_number; $i <= $end_page_number; $i++ )
{
  if( $i < $sum ) {
	if( $j <= $cols ) {
		echo "<td align='center'><div><img onmouseout='$(this).css(\"border\",\"2px solid #DEDEDE\");' onmouseover='$(this).css(\"border\",\"2px solid #F68B17\");' width='$width' height='$height' src='$themes[$i]' /></div><img onclick='get_theme(this)' class='btnimg' src='images/".$lngext."select_img.png' /></td>";
	} else {
		echo "</tr>";
		echo "<tr><td align='center'><div><img onmouseout='$(this).css(\"border\",\"2px solid #DEDEDE\");' onmouseover='$(this).css(\"border\",\"2px solid #F68B17\");' width='$width' height='$height' src='$themes[$i]' /></div><img onclick='get_theme(this)' class='btnimg' src='images/".$lngext."select_img.png' /></td>";
		$j = 1;
	}
	$j++;
  } else {
  	  break;
  }
}
echo '</table>';

$str = '<div style="padding-left:15px;padding-bottom:2px;">';
$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$sum_page, 'adtype'=>$type, 'tag'=>'h', 'p'=>$position)).'">'.__('Last').'</a> | ';
if( $page < $sum_page ) {
	$next_page = $page + 1;
	$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$next_page, 'adtype'=>$type, 'tag'=>'h', 'p'=>$position)).'">'.__('Next').'</a> | ';
}

if( $page > 1 ) {
	$prev_page = $page - 1;
	$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>$prev_page, 'adtype'=>$type, 'tag'=>'h', 'p'=>$position)).'">'.__('Previous').'</a> | ';
}
$str .= '<a href="index.php?'.Html::xuriquery('mod_advert', 'ad_picker', array('page'=>1, 'adtype'=>$type, 'tag'=>'h', 'p'=>$position)).'">'.__('First').'</a>';
$str .= "<span style='margin-top:10px;display:inline-block;margin-left:320px;'>$page/$sum_page</span>";
$str .= '</div>';
echo $str;
} else {
	_e('No Image!');
}
}
?></div>
	</div>
</div>
</div>
