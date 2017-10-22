<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

//get type of language
$lang_name = '';
$separator = '&nbsp;&nbsp;|&nbsp;&nbsp;';
$o_lang = new Language();
$langs =& $o_lang->findAll('published=?', array('1'));
$ln = count($langs);
if ($ln > 3) {
$separator = '<br />';
$lang_name = '<div class="lngbar_select">'.__('Please select language').'</div><div class="lngbar_option">';
?>
<script language="javascript">
$(function(){
	$('.lngbar_select').toggle(function(){
		$('.lngbar_option').css({'width':$(this).width()+17+'px','display':'block'});
		$('.lngbar_option > a').css('text-decoration','none').hover(function(){
			$(this).css({'background-color':'#316AC5','color':'#FFF'});
		  },function(){
			$(this).css({'background-color':'#FFF','color':'#000'});
		});
	  },function(){
	  	  if ($('.lngbar_option').css('display') == 'block') {
	  	  	  $('.lngbar_option').css('display','none');
	  	  } else {
	  	  	  $('.lngbar_option').css({'width':$(this).width()+17+'px','display':'block'});
			  $('.lngbar_option > a').css('text-decoration','none').hover(function(){
				  $(this).css({'background-color':'#316AC5','color':'#FFF'});
			    },function(){
				  $(this).css({'background-color':'#FFF','color':'#000'});
			  });
	  	  }
	});
	$('body').not($('.lngbar_select')).click(function(){$('.lngbar_option').css('display','none');});
});
</script>
<?php
}
// Language List
if($ln>1){
foreach($langs as $lang) {
	//if ($lang->locale == SessionHolder::get('_LOCALE')) {
		//continue;
	//} else {
		$lang_name .= '<a href="javascript:;" onclick="set_default_lang('.$lang->id.',\'other\','. MOD_REWRITE.')">'.$lang->name.'</a>'.$separator;
	//}
}
}
if ($ln > 0 ) {
?>

<?php

	$lang_name = substr($lang_name, 0, -strlen($separator));
	
	if ($ln > 3) {
		echo $lang_name.'</div>';
	} else {
		echo $lang_name;
	}	
}
?>
