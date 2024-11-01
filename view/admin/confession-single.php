<div id="wpcb_single_confession_inner">
	<div class="wpcb_half">
		<?php //echo '<pre>'; print_r($confession); ?>
			<h3>Confession - </h3>
		<table class="wp-list-table widefat fixed striped">
				<colgroup>
					<col style="width:20%">
    				<col style="width:80%">
				</colgroup>
				<tr>
					<th>ID</th>
					<td><?php echo $confession->id; ?></td>
				</tr>
				<tr>
					<th>Title</th>
					<td><?php echo (!empty($confession->title) ? $confession->title : '-'); ?></td>
				</tr>
				<tr>
					<th>Author</th>
					<td><?php echo (!empty($confession->author_name) ? $confession->author_name : '-'); ?></td>
				</tr>
				<tr>
					<th>Category</th>
					<td><?php echo $wpcb->wpcb_category[$confession->category]; ?></td>
				</tr>
				<tr>
					<th>Description</th>
					<td><p style="max-height:200px;overflow: auto;"><?php echo $confession->confession; ?></p></td>
				</tr>
				<tr>
					<th>Created At</th>
					<td><?php echo date_i18n( get_option( 'date_format' ), strtotime( $confession->created_at ) ); ?></td>
				</tr>
			
		</table>
	<input style="margin-top:20px;" type="button" id="wpcb_cancel" class="button" value="Cancel">
	</div>
	<div class="wpcb_half">
		<div class="wpcb_likes" style="border-bottom:2px solid #eee;">
		<?php 
			$list='<h3>Who liked this confession - </h3>';
			if(!empty($confession->likes)){
				foreach ($confession->likes as $like) {
				if($like->applied==1){
				$user=get_user_by('id',$like->user_id);
				$list.='<li><a target="_blank" href="user-edit.php?user_id='.$like->user_id.'">'.$user->display_name.'</a></li>';	
				}
				}

			}

			echo $list;
		 ?>
		 </div>

		 <div class="wpcb_likes" style="border-bottom:2px solid #eee;">
		<?php 
			$list='<h3>Who disliked this confession - </h3>';
			if(!empty($confession->likes)){
				foreach ($confession->likes as $like) {
				if($like->applied==0){
					$user=get_user_by('id',$like->user_id);
				$list.='<li><a target="_blank" href="user-edit.php?user_id='.$like->user_id.'">'.$user->display_name.'</a></li>';	
				}
				
				}

			}

			echo $list;
		 ?>
		 </div>
		 <div class="wpcb_comments">
		 	<?php 
		 	//echo '<pre>';
		 	//print_r($confession->comments);
			$username='';
			$list='<h3>Comments - </h3>';
			$list.='<div><ol>';
			if(!empty($confession->comments)){
				foreach ($confession->comments as $comment) {
					if($comment->user>0){
					$user=get_user_by('id',$comment->user);	
					$username=$user->display_name;	
					}else{
					$username='#'.$comment->id;
					}
				
				$list.='<li id="'.$confession->id.'" style="border-bottom:1px solid skyblue;">
						<b>'.$username.' :  </b>  '.$comment->comment.'
						<a id="'.$comment->id.'" href="" style="float:right;text-decoration:none" class="wpcb_delete_comment">
						<span class="dashicons dashicons-no-alt wpcb_delete_comment_in"></span>
						</a>
						</li>';	
				}

			}
			$list.='</div></ol>';
			echo $list;
		 ?>
		 </div>
	</div>

</div>