<div id="display_confessions_area" style="width:<?php echo (!empty($width) ? $width : '');?>">
	<div style="display:none" class="new_feeds">new feeds <span class="dashicons dashicons-arrow-up-alt"></span></div>
<div id="confessions_area_inner">
<!-- <div id="odd_confessions"></div>
<div id="even_confessions"></div> -->
<?php
$settings = $wpcb->wpcb_settings;
$bg2=(!empty($settings['background_2']) ? $settings['background_2'] : 'skyblue');
   $bg2_border=(!empty($settings['background_2']) ? '3px solid '.$settings['background_2'] : '3px solid skyblue');
   $bg3=(!empty($settings['background_3']) ? $settings['background_3'] : '#fff');
   $bg4=(!empty($settings['background_4']) ? $settings['background_4'] : '#fff');
   $text_color=(!empty($settings['text_color']) ? $settings['text_color'] : '#000');
   $date_color=(!empty($settings['date_color']) ? $settings['date_color'] : '#fff');
   $button_bg=(!empty($settings['button_background']) ? $settings['button_background'] : 'skyblue');
   $button_text=(!empty($settings['button_text']) ? $settings['button_text'] : '#fff');

   $css='';
   $css.='<style id="wpcb_hover_likes">
   .like-cf:hover, .dashicons:hover, .liked, .disliked, .blocked {
    color: '.$bg2.' !important;
   }
   .wpcb_clear{
      border-color: '.$bg2.' !important;
   }
   #wpcb_confessions div p,#wpcb_confessions div h5,#wpcb_confessions div li,#wpcb_confessions div li b,#wpcb_confessions div textarea {
    color: '.$text_color.' !important;
   }
   #wpcb_confessions div textarea{
   background : '.$bg3.' !important;
   }
   #wpcb_confessions span.conf_comments_container input[type="button"]{
   background : '.$button_bg.' !important;
   color : '.$button_text.' !important;
   }
   </style>';
   echo $css;
?>
<div id="wpcb_confessions" style="background: <?php echo (!empty($wpcb->wpcb_settings['background_1']) ? $wpcb->wpcb_settings['background_1'] : '');?>"><h1 style="text-align:center;color:gray;margin-top:30%">Loading ..</h1></div>
</div>
<!-- <h4 style="display:none;text-align:center" class="cb_no_data" >No confession found!</h4> -->
<!-- <button id="view_cform" class="hidden_cform" style="width: 100%">View Confession Form</button> -->
</div>
