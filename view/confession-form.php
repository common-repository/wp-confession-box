<div id="confession_form" style="width:<?php echo (!empty($width) ? $width : '');?>">
<div class="wpcb_container">
  <h2>Confession Form</h2>
<div class="wpcb_messages"> </div>
<form>
<?php wp_nonce_field( 'validate_confession', 'verify_cf_submission' ); ?>

<?php if(get_option('wpcb_toggle_title')==1): ?>
<div class="form-group">
<label for="wpcb_title"><?php _e('Confession Title',''); ?></label>
<input type="text" name="wpcb_title" id="wpcb_title" >
</div>
<?php endif;?>


<?php 

if(!empty($wpcb->wpcb_category) && get_option('wpcb_toggle_category')==1): 
	?>
<div class="form-group">
<label for="wpcb_category"><?php _e('Confession Category',''); ?></label>
<select id="wpcb_category" name="wpcb_category">
<?php

foreach($wpcb->wpcb_category as $catID => $category){
	if($catID>0){
		echo '<option value="'.($catID).'">'.$category.'</option>';
	}

}
?>
</select>
</div>
<?php endif;?>


<div class="form-group">
<label for="wpcb_description"><?php _e('Confession Description',''); ?></label>
<i style="clear:both;float:left"><?php _e(sprintf('(Atleast %d Characters)',get_option('wpcb_desc_length')),''); ?></i>
<em style="float:left;margin-left:10px" class="wpcb_desc_length"></em>
<textarea type="text" col="50" name="wpcb_desc" id="wpcb_desc" placeholder="<?php __('Add the description here');?>"></textarea>
</div>
<?php if(get_option('wpcb_toggle_author')==1): ?>
<div class="form-group">
<label for="wpcb_author_name"><?php _e('Your Name',''); ?></label>
<input type="text"  name="wpcb_author_name" id="wpcb_author_name" >
</div>
<?php endif; ?>
<?php do_action('wpcb_after_wpcb_form_fields'); ?>

<div class="form-group">
<label></label>
<?php
$settings = $wpcb->wpcb_settings;
$button_bg=(!empty($settings['button_background']) ? $settings['button_background'] : 'skyblue');
$button_text=(!empty($settings['button_text']) ? $settings['button_text'] : '#fff');
?>
<input style="background: <?php echo $button_bg;?>;color: <?php echo $button_text;?>" type="button" id="wpcb_add_confession"  value="<?php _e('Add Confession',''); ?>" >
</div>

</form>
</div>
</div>