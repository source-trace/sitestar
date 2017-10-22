<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('My Account'); ?></div>
    <div class="rightmeta rightblock">
        <a href="<?php echo Html::uriquery('mod_onlinepay', 'saving'); ?>"><?php _e('Online Saving'); ?></a>
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
<table cellspacing="1" class="front_form_table" id="useraccount_state">
    <tbody>
        <tr>
            <td class="label"><?php _e('Total Saving'); ?></td>
            <td class="entry"><?php echo $curr_user_ext->total_saving; ?></td>
            <td class="label"><?php _e('Total Payment'); ?></td>
            <td class="entry"><?php echo $curr_user_ext->total_payment; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Balance'); ?></td>
            <td class="entry" colspan="3"><?php echo $curr_user_ext->balance; ?></td>
        </tr>
    </tbody>
</table>
</div>
<div class="space"></div>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Transaction History'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
<table cellspacing="1" class="front_list_table" id="userfinance_list">
	<thead>
		<tr>
            <th><?php _e('Time'); ?></th>
            <th><?php _e('Amount'); ?></th>
            <th><?php _e('Type'); ?></th>
            <th><?php _e('Memo'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($transactions) > 0) {
        $row_idx = 0;
        foreach ($transactions as $transaction) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
            <td class="aligncenter"><?php echo date('Y-m-d H:i:s', $transaction->action_time); ?></td>
        	<td class="aligncenter"><?php echo $transaction->amount; ?></td>
        	<td class="aligncenter"><?php echo Toolkit::switchText($transaction->type, 
                    array('1' => __('Saving'), '2' => __('Deduction'))); ?></td>
        	<td class="aligncenter"><?php echo $transaction->memo; ?></td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="4"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
</div>
<div class="space"></div>
<?php
include_once(P_TPL.'/common/pager.php');
?>
