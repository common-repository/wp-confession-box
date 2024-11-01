<style type="text/css">
	tr, th, td {
    border: 1px solid #eee !important;
}
</style>
<div id="wpcb_single_confession">
	<div id="wpcb_single_confession_inner" style="">
	<?php
	//global $wp_confession_box;
	//include($wp_confession_box->wpcb_dirpath.'view/confession-form.php');
	?>
	</div>
</div>

<div id="wpcb_confessions_container" style="padding-top:100px;background: #fff;width:100%;min-height:500px">
<!-- <h1 style="text-align: center;color: skyblue">WP Confession Box </h1> -->
	<?php
	echo wpcb_messages($log);
	?>
	<!-- <h1 style="text-align: center;color: skyblue">WP Confession Manager</h1> -->
	
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Category</th>
				<th>Author</th>
				<!-- <th>Status</th> -->
				<th>Likes</th>
				<th>Dislikes</th>
				<th>Comments</th>
				<th>Created At</th>
				<th>Actions</th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Category</th>
				<th>Author</th>
				<!-- <th>Status</th> -->
				<th>Likes</th>
				<th>Dislikes</th>
				<th>Comments</th>
				<th>Created At</th>
				<th>Actions</th>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$list='';
		if(!empty($confession->blocked) && $confession->blocked==0){
		if(!empty($confession->approved) && $confession->approved==0 ){
			$status = 'Pending';
		}elseif(!empty($confession->approved) && $confession->approved==1 ){
			$status = 'Active';
		}
		}elseif(!empty($confession->blocked) && $confession->blocked==1){
			$status = 'Blocked';
		}
		
		
		
		foreach ($confessions as $key => $confession) {
		$lc=0;
		$dlc=0;	
			if(!empty($confession->likes)){
				foreach ($confession->likes as $cnt) {
				if($cnt->applied==1){
					$lc++;
				}
				if($cnt->applied==0){
					$dlc++;
				}
				}
			}
			
			if($confession->blocked==1){
				$block='Unblock';
			}
			else{
				$block='Block';
			}
			$list.='
			<tr>
				<td>'.$confession->id.'</th>
				<td>'.(!empty($confession->title) ? $confession->title : '-').'</td>
				<td>'.(!empty($confession->category) ? $wpcb->wpcb_category[$confession->category] : '').'</td>
				<td>'.(!empty($confession->author_name) ? $confession->author_name : '-').'</td>
				<td><a href="#">( '.$lc.' )</a></td>
				<td><a href="#">( '.$dlc.' )</a></td>
				<td><a href="#">( '.count($confession->comments).' )</a></td>
				<td>'.date_i18n( get_option( 'date_format' ), strtotime( $confession->created_at ) ).'</td>
				<td><a id="'.$confession->id.'" href="#" class="button button-small wpcb_update" >View</a>
					<a id="'.$confession->id.'" class="button button-small wpcb_delete" >Delete</a>
					<a id="'.$confession->id.'" class="button button-small wpcb_block" >'.$block.'</a>
				</td>
			</tr>
			';
		}
		echo $list;
		?>
		</tbody>
	</table>
	
</div>