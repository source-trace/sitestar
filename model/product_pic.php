<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ProductPic extends RecordObject {
    public $belong_to = array('Product');
}
?>
