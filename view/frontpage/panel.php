<h3>Hi, <?php echo SessionHolder::get('username') ?>. You are logged in!</h3>
<p>
But you'd better modify this page for a more meaningful purpose :)<br />
The file location is <em>template/view/frontpage/panel.php</em>.
</p>
<p>
<a href="index.php?m=auth&amp;a=dologout" alt="Logout">Logout</a>
</p>