<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<form id="server" method="post" action="index.php?_m=mod_param&_a=save_mail_server"  name="sparamform">

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="data_table" id="admin_lang_list" style="line-height:24px;margin-top:0;">
       
    <tbody>
	<tr><td></td><td></td><td>
	</td></tr>
       <tr>
            <td class="label"><?php _e('Mail server set'); ?>
            </td>
			<td class="entry" colspan="2"><?php _e("Can generally recommended to use QQ mailbox, 163 mailboxes only apply before 2006");?>&nbsp;&nbsp;&nbsp;<a href="index.php?_m=mod_email&_a=email_list"><?php _e("Test send");?></a></td>
        </tr>
        <tr>
            <td class="label"><?php _e('SMTP Server'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_SERVER]', SMTP_SERVER);
            ?>
            </td>
			<td></td>
        </tr>
		<tr>
            <td class="label"><?php _e('SMTP Port'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_PORT]', SMTP_PORT?SMTP_PORT:25);
            ?>
            </td>
			<td></td>
        </tr>
        <tr>
            <td class="label"><?php _e('SMTP User'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_USER]', SMTP_USER);
            ?>
            </td>
			<td></td>
        </tr>

        <tr>
            <td class="label"><?php _e('SMTP Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'sparam[SMTP_PASS]', SMTP_PASS);
            ?>
            </td>
			<td></td>
        </tr>	
		 <tr>
		 <td class="label"></td>
            <td class="entry"><?php
            echo Html::input('submit', 'submit', __('Save')," class='search_button'");
            ?>
           
            </td>
			<td width="300">&nbsp;</td>
        </tr>	
    </tbody>
</table>
</form>