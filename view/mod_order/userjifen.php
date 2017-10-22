<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<style type="text/css">
.exchange_div{display:block; height:30px; border:1px solid #CCCCCC; padding:10px;}
.exchange_div input{margin-left:10px;}

</style>
<div class="art_list">
	<div class="art_list_title"><?php _e('Redeem Points'); ?></div>
	<div class="ordernow"><input type="button" value="<?php _e('Online Saving'); ?>" class="saving_o_b" onclick="location.href='<?php echo Html::uriquery('mod_onlinepay', 'saving'); ?>'" /></div>
	<br />
	<div style="display:block; height:40px; padding:10px"><?php _e('Current Credit'); ?>:<span style="color:red; font-weight:bold;"><?php echo $curr_user_ext->total_point; ?></span> 

		</div>
		<div class="exchange_div">
		<form action="<?php echo Html::uriquery('mod_order', 'userjifen_save'); ?>" method="post" name="exchange">
			<input type="radio" name="exchange" value="100" />10<?php echo CURRENCY_SIGN; ?>(<?php _e('Need'); ?>100<?php _e('Points'); ?>)
			<input type="radio" name="exchange" value="200" />20<?php echo CURRENCY_SIGN; ?>(<?php _e('Need'); ?>200<?php _e('Points'); ?>)
			<input type="radio" name="exchange" value="500" />50<?php echo CURRENCY_SIGN; ?>(<?php _e('Need'); ?>500<?php _e('Points'); ?>)
			<input type="radio" name="exchange" value="1000" />100<?php echo CURRENCY_SIGN; ?>(<?php _e('Need'); ?>1000<?php _e('Points'); ?>)
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="su" value="<?php _e("Redeem Points");?>" class="saving_o_b" />
			</form>
		</div>
<!-- 我的账户 -->
	<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
		<tbody>
		<thead bgcolor="#CCCCCC">
			<td height="40"  align="center" colspan="4"><?php _e("Redeem list");?></td>
		</thead>
				<tr>
			<th><?php _e('Time'); ?></th>
			<th><?php _e('Consumption points'); ?></th>
			<th><?php _e('Nominal exchange'); ?></th>
			<th><?php _e('Memo'); ?></th>
		</tr>

		<?php
if (sizeof($transactions) > 0) {
    $row_idx = 0;
    foreach ($transactions as $transaction) {
?>
		<tr>
			<td><?php echo date('Y-m-d H:i:s', $transaction->create_time); ?></td>
			<td><?php echo $transaction->point; ?></td>
			<td><?php if($transaction->point==100){
				echo 10;
			}elseif($transaction->point==200){
				echo 20;
			}elseif($transaction->point==500){
				echo 50;
			}elseif($transaction->point==1000){
				echo 100;
			} ?></td>
			<td><?php  _e("$transaction->momo"); ?></td>
		</tr>
<?php
        $row_idx = 1 - $row_idx;
    }
} else {
?>
	<tr>
		<td colspan="4"><?php _e('No Records!');?></td>
	</tr>
<?php } ?>

	  </tbody>
	</table>
<!-- //我的账户 -->
</div>