<script type="text/javascript" language="javascript">
//<![CDATA[
	$(document).ready(function() {

		var modmenu_timeout = false;
		
		var hide_modmenu = function() {
			$("#modmenu").fadeOut(200);
		}
		
		$("#modmenu").hide();
		
		$(".btnaddmod").hover(function() {
			clearTimeout(modmenu_timeout);
			var mypos = $(this).offset();
			var menu_left = mypos.left;
			var menu_top = mypos.top + $(this).height();
//			$("#modmenu li a").attr("currpos", $(this).attr("pos"));
			$("#modmenu").css({
				position: "absolute",
				left: menu_left + "px",
				top: menu_top + "px"
			});
			$("#modmenu").fadeIn(200);
		}, function() {
			modmenu_timeout = setTimeout(hide_modmenu, 200);
		});
		
		$("#modmenu").hover(function() {
			clearTimeout(modmenu_timeout);
		}, function() {
			modmenu_timeout = setTimeout(hide_modmenu, 200);
		});
		
		$("#modmenu *").hover(function() {
			clearTimeout(modmenu_timeout);
		});

		<?php 
		$dialog_str = '';
		$li_str = '';
		if (sizeof($widgets_info) > 0) {
		    foreach ($widgets_info as $key => $name) {
		    	if($key == 'mod_cart-cartstatus' && EZSITE_LEVEL=='2' && EXCHANGE_SWITCH == '0') continue; 
		    	if($key == 'mod_cart-cartstatus' && EZSITE_LEVEL=='1') continue;
		    	if($key == 'mod_counter-counter') { continue;}
				$li_str .= "<li class='modmenu_flag' widget='$key' id='MODBLK_id'>$name</li>";
			}
		} ?>
	});
//]]>
</script>
<ul id="modmenu" class="pos_wrapper">
<?php echo $li_str;?>
</ul>
<script type="text/javascript" language="javascript">
//<![CDATA[
		$("#modmenu").hide();
//]]
</script>