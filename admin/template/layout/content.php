<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>

<?php 
	include_once(P_TPL_WEB."/layout/header.php");
?>
        <div id="content">
	        <?php 
	        	if(ParamHolder::get('frame', 1))
	        	{
	        	//	include_once(P_TPL_WEB."/layout/menu.php");
	        	}
	        ?>
	        <div class="fr">
	        	<?php include_once($_content_); ?>
	        </div><a id="translateLink" href="#" style="display:none;">·±ów</a>
        </div>
<?php 
	include_once(P_TPL_WEB."/layout/footer.php");
?>