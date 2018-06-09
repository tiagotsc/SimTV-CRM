<?php
    $menu = (!empty($menu))? $menu: '';
    $corpo = (!empty($corpo))? $corpo: '';
    $rodape = (!empty($rodape))? $rodape: '';
    $html_footer = (!empty($html_footer))? $html_footer: '';
?>
<?php echo $html_header; ?>
<?php echo $menu; ?>
<?php echo $corpo; ?>
<?php echo $rodape; ?>
<?php echo $html_footer; ?>