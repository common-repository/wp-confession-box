<div id="wpcb_settings_container" style="padding-top:100px;background: #fff;width:100%;min-height:500px">
	<?php
	echo wpcb_messages($log);
	?>
	<!-- <h1 style="text-align: center;color: skyblue">WP Confession Box Settings</h1> -->
	<form action="" method="post">
	<input type="hidden" name="wpcb_setting" value="1">
	<table class="wp-list-table widefat fixed striped posts">
		<tbody>
			<tr>
				
				<td>
					<h2 style="background: #eee;padding: 15px;">Shortcodes <br /><em style="font-size:12px">(Use shortcodes anywhere to populate confession box and confession form)</em><h2>
					<h4>A. You can copy shortcodes from here and paste to any post or page .</h4>
					<ol>
						<li><input type="text" value="[wp-confession-form]" onclick="select()"></li>
						<li><input type="text" value="[wp-confession-box]" onclick="select()"></li>
					</ol>
					<h4>Adjust Width :</h4>
					<ol>
						<li><input style="width: 300px;" type="text" value="[wp-confession-form width='100%']" onclick="select()">
							<em>(Change width to any in px or %)</em>
						</li>
						<li><input style="width: 300px;" type="text" value="[wp-confession-box width='100%']" onclick="select()">
						<em>(Change width to any in px or %)</em>
						</li>
					</ol>
					<h4>B. Or you also have option in each post , page or custom post type to inject shortcodes.</h4>
					<img style="width:50%" src="<?php echo $wpcb->wpcb_urlpath.'assets/images/shortcodes.png'; ?>">

					<h4>C. To apply shortcodes in PHP files , Use following scripts. </h4>
					<ol>
						<li onclick="select()"><?php highlight_string("<?php echo do_shortcode('[wp-confession-form]');?>");?></li>
						<li onclick="select()"><?php highlight_string("<?php echo do_shortcode('[wp-confession-form]');?>");?></li>
						
					</ol>

				</td>
			</tr>

			<tr>
				
				<td>
					<h2 style="background: #eee;padding: 15px;">Elements in confession form/box <br /><em style="font-size:12px">(Show/Hide Elements of form and confession box. Ex: Do not need Title ? Just mark it hide and it will not appear on form and confession box.)</em></h2>

					<ol>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Select Confession Box Page - 
							
							<select name="wpcb_confession_page">
								<?php
								$page_list='<option value="0">--Select Confession Box Page--</option>'; 
								if(!empty(get_pages())){
								$confession_page=get_option('wpcb_confession_page');
									foreach (get_pages() as $page) {
										$page_list.='<option value="'.$page->ID.'" '.($confession_page==$page->ID ? 'selected' : '').'>'.$page->post_title.'</option>';
									}
									echo $page_list;
								}
								?>
							</select>
							<br /><em style="font-size: 12px;">(Select the page there you would like to use [wp-confession-box] shortcode.)</em>
							 
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">
							<table style="width:45%;display: inline-block;">
								<tr>
									<td style="padding-left: 0;">Background Color 1 - </td>
									<td><input style=""  type="text"  name="wpcb_cb_background_color_1" value="<?php echo get_option('wpcb_cb_background_color_1');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box background color 1.)</em>
									</td>
									
								</tr>
								<tr>
									<td style="padding-left: 0;">Background Color 2 - </td>
									<td><input style=""  type="text"  name="wpcb_cb_background_color_2" value="<?php echo get_option('wpcb_cb_background_color_2');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box background color 2.)</em>
									</td>
									
								</tr>
								<tr>
									<td style="padding-left: 0;">Background Color 3 - </td>
									<td><input style=""  type="text"  name="wpcb_cb_background_color_3" value="<?php echo get_option('wpcb_cb_background_color_3');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box background color 3.)</em>
									</td>
									
								</tr>
								<tr>
									<td style="padding-left: 0;">Background Color 4 - </td>
									<td><input style=""  type="text"  name="wpcb_cb_background_color_4" value="<?php echo get_option('wpcb_cb_background_color_4');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box background color 4.)</em>
									</td>
									
								</tr>
								<tr>
									<td style="padding-left: 0;">Button Background Color - </td>
									<td><input style=""  type="text"  name="wpcb_cb_background_color_btn" value="<?php echo get_option('wpcb_cb_background_color_btn');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box button backgroud color.)</em>
									</td>
									
								</tr>

								<tr>
									<td style="padding-left: 0;">Button Text Color - </td>
									<td><input style=""  type="text"  name="wpcb_cb_text_color_btn" value="<?php echo get_option('wpcb_cb_text_color_btn');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box button text color.)</em>
									</td>
									
								</tr>
								<tr>
									<td style="padding-left: 0;">Display Date Color - </td>
									<td><input style=""  type="text"  name="wpcb_cb_date_color" value="<?php echo get_option('wpcb_cb_date_color');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box displayed date color.)</em>
									</td>
									
								</tr>

								<tr>
									<td style="padding-left: 0;">Confession Box Text Color - </td>
									<td><input style=""  type="text"  name="wpcb_cb_text_color" value="<?php echo get_option('wpcb_cb_text_color');?>" class="wpcb-color-field">
										<em style="font-size: 12px;">(Select confession box text color.)</em>
									</td>
									
								</tr>
							</table>
							<img style="width:50%;display: inline-block;vertical-align: top;" src="<?php echo $wpcb->wpcb_urlpath.'assets/images/ConfessionBoxColorPicker.png'; ?>">
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Manage Likes Method - 
							
							<select name="wpcb_likes_manage_by">
								<?php
								$method_list=''; 								
								$likes_method_selected = $wpcb->wpcb_settings['manage_likes_by'];

									foreach ($wpcb->likes_methods as $method => $m_name) {
										$method_list.='<option value="'.$method.'" '.($likes_method_selected == $method ? 'selected' : '').'>'.$m_name.'</option>';
									}
									echo $method_list;
								
								?>
							</select>
							<br /><em style="font-size: 12px;">(Select the method to manage confessions likes.)</em>
							 
						</li>

						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Show Title - <input type="checkbox" name="wpcb_toggle_title" value="1" <?php echo (!empty(get_option('wpcb_toggle_title') && get_option('wpcb_toggle_title') == 1) ? "checked":'');?>> 
						<br /><em style="font-size: 12px;">(Allows visitors to add a title to their confessions)</em>
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Show Categories - <input type="checkbox" name="wpcb_toggle_category" value="1" <?php echo (!empty(get_option('wpcb_toggle_category') && get_option('wpcb_toggle_category') == 1) ? "checked":'');?>>
						<br /><em style="font-size: 12px;">(Allows visitors to select a category to their confessions)</em> 
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Show Author Name - <input type="checkbox" name="wpcb_toggle_author" value="1" <?php echo (!empty(get_option('wpcb_toggle_author') && get_option('wpcb_toggle_author') == 1) ? "checked":'');?>> 
							<br /><em style="font-size: 12px;">(Allows visitors to show name on their confessions)</em>
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Show Date - <input type="checkbox" name="wpcb_toggle_date" value="1" <?php echo (!empty(get_option('wpcb_toggle_date') && get_option('wpcb_toggle_date') == 1) ? "checked":'');?>> 
							<br /><em style="font-size: 12px;">(Allows visitors to show date on their confessions)</em>
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Min confession words - <input style="width:20%" min='1' type="number"  name="wpcb_desc_length" value="<?php echo get_option('wpcb_desc_length');?>"> 
							<br /><em style="font-size: 12px;">(Minimum number of words for a confession..)</em>
						</li>
						<li style="border-bottom:2px dotted #eee;margin: 20px 0;">Add category - <input type="text"  name="wpcb_add_category">
							<br /><em style="font-size: 12px;">(Write your own category and click on submit)</em>
						</li>
					</ol>
				</td>

			</tr>
			<tr>
				<td style="float: right;"><input class="button button-primary" type="submit" name="wpcb_submit" value="Submit"></td>
			</tr>
			<tr>
				
				<td>
				<h2 style="background: #eee;padding: 15px;">Available Categories -</h2>
					<ol style="max-height:200px;overflow: auto;">
						<?php 
						if(!empty($wpcb->wpcb_category)){
							foreach ($wpcb->wpcb_category as $id=>$category) {
								if($id>0){
									echo '<li style="width:50%">'.$category.' <a id="'.$id.'" style="float:right" class=" wpcb_delete_category"><span class="dashicons dashicons-trash"></span></a></li>';
								}
							}
						}
						

						?>
					</ol>
				</td>
			</tr>

			<tr>
				
				<td>
					<h2 style="background: #eee;padding: 15px;">Custom CSS Editor <br /><em style="font-size:12px">(Overwrite Default Confession Box Style)</em></h2>
					<textarea id="wpcb_custom_css" name="wpcb_custom_css" style="resize:none;height: 300px;width: 100%;overflow: auto;color: yellow;background: gray;font-size:18px"><?php echo $wpcb_cutom_css; ?></textarea>
				</td>
			</tr>
			<tr>
				<td style="float: right;"><input class="button button-primary" type="submit" name="wpcb_submit" value="Submit"></td>
			</tr>
		</tbody>
	</table>
	</form>
</div>