<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>


<div class="art_list">
	<div class="art_list_title"><?php _e('My Account'); ?></div>
	<div class="ordernow"><input type="button" value="<?php _e('Online Saving'); ?>" class="saving_o_b" onclick="location.href='<?php echo Html::uriquery('mod_onlinepay', 'saving'); ?>'" /></div>
<!-- 我的账户 -->
	<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
		<tr>
			<td><?php _e('Total Saving'); ?></td>
			<td><?php echo $curr_user_ext->total_saving; ?></td>
		</tr>
		<tr>
			<td><?php _e('Total Payment'); ?></td>
			<td><?php echo $curr_user_ext->total_payment; ?></td>
		</tr>
		<tr>
			<td><?php _e('Balance'); ?></td>
			<td><?php echo $curr_user_ext->balance; ?></td>
		</tr>
		 <?php if(CREDITS_SWITCH==1){ ?>
		<tr>
			<td><?php _e('User Score'); ?></td>
			<td><span><?php echo $curr_user_ext->total_point; ?></span> <span style="margin-left:30px;"><a href="<?php echo Html::uriquery('mod_order', 'userjifen'); ?>" style="color:red;"><?php _e('Redeem Points'); ?></a></span></td>
		</tr>
		<?php } ?>
	</table>
<!-- //我的账户 -->
</div>


<div class="art_list">
	<div class="art_list_title"><?php _e('Transaction History'); ?></div>
<!-- 交易记录 -->
	<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
		<tr>
			<th><?php _e('Time'); ?></th>
			<th><?php _e('Amount'); ?></th>
			<th><?php _e('Type'); ?></th>
			<th><?php _e('Memo'); ?></th>
		</tr>
<?php

if (sizeof($transactions) > 0) {
    $row_idx = 0;
    foreach ($transactions as $transaction) {
	 if(CREDITS_SWITCH!=1){
	 	if($transaction->type==1) continue;
	 }
?>
		<tr>
			<td><?php echo date('Y-m-d H:i:s', $transaction->action_time); ?></td>
			<td><?php echo $transaction->amount; ?></td>
			<td><?php echo Toolkit::switchText($transaction->type, 
                    array('1' => __('Saving'), '2' => __('Deduction'))); ?></td>
			<td><?php echo $transaction->memo; ?></td>
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
	</table>
<!-- //交易记录 -->
</div>

<?php
include_once(P_TPL_VIEW.'/view/common/pager.php');
?>
