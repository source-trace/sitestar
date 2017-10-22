<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<script>
		if(window.opener){
			<?php if($return_type ==1){ ?>
			window.opener.location.reload();
			<?php }elseif($return_type ==2){ ?>
			window.opener.location.reload();
			<?php }elseif($return_type ==3){ ?>
			window.opener.show_open_auth_binding_errorinfo('<?php echo  $error_message;?>',1);
			<?php }elseif($return_type ==4){ ?>
			window.opener.show_open_auth_binding_errorinfo('<?php echo  $error_message;?>',0);
			<?php } ?>
		}
		window.close();
	</script>
</body>