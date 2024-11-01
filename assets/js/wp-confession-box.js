(function(window, document, $, undefined){

	window.WPCB = {};
	WPCB.manageAdminActions = function(){

		/*------------------------------*
		* Confession update feature start
		*--------------------------------*/
		var wpcb_confessions_container = $('#wpcb_confessions_container');

		if($('.wpcb-color-field').length > 0){
			$('.wpcb-color-field').wpColorPicker();
		}
		
		
		var wpcb_sc = $('#wpcb_single_confession');
		/*var wpcb_title =  wpcb_sc.find('#wpcb_title');
		var wpcb_category =   wpcb_sc.find('#wpcb_category');
		var wpcb_desc =   wpcb_sc.find('#wpcb_desc');
		var wpcb_author_name =   wpcb_sc.find('#wpcb_author_name');
		var wpcb_add_confession = wpcb_sc.find('#wpcb_add_confession');	
		var form = wpcb_sc.find('form');
		form.prepend('<input type="hidden" name="cf_id" id="cf_id" value="0">');
		form.find('#wpcb_add_confession').after('<input type="button" style="float:right" name="wpcb_cancel" id="wpcb_cancel" value="Cancel">');
		form.find('#wpcb_add_confession').val('Update Confession');*/

		/*
		$('.wpcb_update').click(function(event){
			event.preventDefault();
			var confession_id = $(this).attr('id');
			var params={
				'action' : 'wpcb_get_confession',
				'confession_id' : confession_id
			};
			$.post(wpcb_ajax.ajaxurl,params,function(res){
				var obj = JSON.parse(res);
				if(obj.confession.id > 0){
					wpcb_sc.show();
					wpcb_title.val(obj.confession.title);
					wpcb_category.find('option').each(function(){
						if($(this).val()==obj.confession.category){
							$(this).prop('selected',true);
						}
					});
					wpcb_desc.html(obj.confession.confession);
					wpcb_author_name.val(obj.confession.author_name);
					form.find('#cf_id').val(obj.confession.id);
				}else if(obj.error){
					alert(obj.error);
				}
			});
		});*/

		if(wpcb_confessions_container.length>0){
			wpcb_confessions_container.find('table').DataTable({
				"order": [[ 0, "desc" ]]
			});
		}

		$('.wpcb_delete_category').click(function(){
			event.preventDefault();
			var c= confirm('Are you sure?');
			if(c==false){
				return false;
			}
			var action = $(this);
			var category_id = action.attr('id');
			var params={
				'action' : 'wpcb_delete_category',
				'category_id' : category_id,
			};

			$.post(wpcb_ajax.ajaxurl,params,function(res){
				if(res){
					action.parents('li').remove();
					alert('Category Deleted Successfully!');
				}else{
					alert('No Category Found');
				}
			});
		});

		$('a.button').on("click",function(event){
			event.preventDefault();
			var apply='';
						
			var action = $(this);
			if(action.hasClass('wpcb_delete')){
				apply='delete';
			}
			if(action.hasClass('wpcb_block')){
				apply='block';
			}

			if(apply.length>0){
			var c = confirm('Are you sure?');
			if(c==false){
			return false;
			}
			}else{
				return false;
			}
			var confession_id = $(this).attr('id');
			var params={
				'action' : 'manage_confession_actions',
				'apply' : apply,
				'confession_id' : confession_id,
				'extra_param'   : '0'
			};
			$.post(wpcb_ajax.ajaxurl,params,function(res){
				
				obj=JSON.parse(res);
				if(obj.success){
					alert(obj.success);
					if(obj.action=='deleted'){
						action.parents('tr').remove();
					}
					if(obj.action=='blocked'){
						action.html('Unblock');
					}
					if(obj.action=='unblocked'){
						action.html('Block');
					}
				}else{
					alert(obj.error);
				}
			});
			//window.location.href="?page=wp-confession-manager&action=delete&cf_id="+confession_id;
		});
		$('.wpcb_update').click(function(event){

			event.preventDefault();
			var confession_id = $(this).attr('id');
			var params={
				'action' : 'wpcb_get_confession',
				'confession_id' : confession_id
			};
			$.post(wpcb_ajax.ajaxurl,params,function(res){
				
				wpcb_sc.show();
				wpcb_sc.html(res);

				return false;
				var obj = JSON.parse(res);
				if(obj.confession.length > 0){
					
					/*wpcb_title.val(obj.confession.title);
					wpcb_category.find('option').each(function(){
						if($(this).val()==obj.confession.category){
							$(this).prop('selected',true);
						}
					});*/
					/*wpcb_desc.html(obj.confession.confession);
					wpcb_author_name.val(obj.confession.author_name);
					form.find('#cf_id').val(obj.confession.id);*/
				}else if(obj.error){
					alert(obj.error);
				}
			});
		});
		/*------------------------------*
		* Confession update feature Ends
		*--------------------------------*/

		wpcb_sc.on("click",'#wpcb_cancel',function(event){
			window.location.href='?page=wp-confession-manager';
		});

		wpcb_sc.on("click",'.wpcb_delete_comment',function(event){
			event.preventDefault();
			var c = confirm('Are you sure?');

			if(c==false){
				return false;
			}
			var action = $(this);
			var confession_id = action.parents('li').attr('id');
			var comment_id = action.attr('id');

			var params={
				'action' : 'manage_confession_actions',
				'apply' : 'delete_comment',
				'confession_id' : confession_id,
				'extra_param'   : comment_id
			};
			$.post(wpcb_ajax.ajaxurl,params,function(res){

				obj=JSON.parse(res);
				if(obj.success){
					alert(obj.success);
					action.parents('li').remove();
				}else{
					alert(obj.error);
				}
			});
		});
		
		
	},
	WPCB.syncConfession=function(){
		var ca_inner=$('#confessions_area_inner');
		/*var odd_confessions=ca_inner.find('#odd_confessions');
		var even_confessions=ca_inner.find('#even_confessions');*/
		if(ca_inner.length<=0){
			return false;
		}
		var wpcb_confessions=ca_inner.find('#wpcb_confessions');
		var last_con_id=0;
		if(wpcb_ajax.confession>0){
			return false;
		}
		setInterval(function(){ 
		console.log('WPCB syncronize confessions');
		/*if((odd_confessions.children().length > 0) || (even_confessions.children().length > 0 )){
			last_odd_con_id=odd_confessions.children().first().attr('id');
			last_even_con_id=even_confessions.children().first().attr('id');

			if(last_even_con_id==undefined){
				last_even_con_id=0;
			}
			if(last_odd_con_id==undefined){
				last_odd_con_id=0;
			}
			
			if(last_odd_con_id > last_even_con_id){
				last_con_id=last_odd_con_id;
			}

			if(last_odd_con_id < last_even_con_id){
				last_con_id=last_even_con_id;
			}
		}*/

		if((wpcb_confessions.children().length > 0 )){
			last_con_id=wpcb_confessions.children().first().attr('id');
			
			if(last_con_id==undefined){
				last_con_id=0;
			}
		}
	
		var params={
			'action' : 'wpcb_sync_confessions',
			'last_con_id' : last_con_id,
			'category':wpcb_ajax.category

		};
		/*$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);
					var oddDone=0;
					var evenDone=0;
					if(obj.odd){
						if(odd_confessions.children().length>0){
							odd_confessions.children().first().before(obj.odd);
						}else{
							odd_confessions.html(obj.odd);
						}
						oddDone=1;
					}
					if(obj.even){
						if(even_confessions.children().length>0){
							even_confessions.children().first().before(obj.even);
						}else{
							even_confessions.html(obj.even);
						}
						
						evenDone=1;
					}

					if(obj.new && (evenDone || oddDone)){
					$('.new_feeds').show();
					}

					

				});	*/	

			$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);
					var Done=0;
					
					if(obj.confessions){
						if(wpcb_confessions.children().length>0){
							wpcb_confessions.children().first().before(obj.confessions);
						}else{
							wpcb_confessions.html(obj.confessions);
						}
						Done=1;
					}
					
					if(obj.new && Done){
					$('.new_feeds').show();
					}

				});	
			}, 10000);

	},
	WPCB.firstLoadConfession=function(){
		console.log('first load confession');

		var ca_inner=$('#confessions_area_inner');
		/*var odd_confessions=ca_inner.find('#odd_confessions');
		var even_confessions=ca_inner.find('#even_confessions');*/

		var wpcb_confessions = ca_inner.find('#wpcb_confessions');

		ca_inner.on('click','.comment_head',function(){
			$(this).next().toggle();
		});

		$.post(wpcb_ajax.ajaxurl,{'action':'fetch_old_confession','category':wpcb_ajax.category,'confession':wpcb_ajax.confession},function(res){
					var obj=JSON.parse(res);
					
					if(obj.confessions){
						wpcb_confessions.html(obj.confessions);
					}else{
						wpcb_confessions.html('<h2 style="text-align:center;color:gray;margin-top:30%">No Confession Found !</h2>');
					}
					/*if(obj.odd){
						odd_confessions.html(obj.odd);
					}
					if(obj.even){
						even_confessions.html(obj.even);
					}*/

				});	
	},
	WPCB.manageActions = function(){

	var cf_area=$('#display_confessions_area');
	var cf_area_inner=$('#confessions_area_inner');
	var action = '';
	cf_area_inner.on('click','.dashicons',function(){

		//var confirm= confirm('');
		var allow;

		var click = $(this);
		var parent = $(this).parent().parent();
		var confession_id = parent.attr('id');
		var comment_id = 0;
		if(click.hasClass('cb_delete_confession')){
			action = 'delete';
			allow = confirm("Are you sure to delete this confession ?");
		}

		if(click.hasClass('cb_block_confession')){
			action = 'block';

			if(click.hasClass('blocked')){
				allow = confirm("Are you sure to unblock this confession ?");
			}else{
				allow = confirm("Are you sure to block this confession ?");
			}
			
		}
		if(click.hasClass('cb_delete_comment')){
			action = 'delete_comment';

			confession_id = $(this).parents('div').attr('id'); // here confession id == comment id 
			comment_id = $(this).parents('li').attr('id');
			allow = confirm("Are you sure to delete this comment ?");
		}

		if(allow==false || action==''){
			return false;
		}
		var params={
				'action' : 'manage_confession_actions',
				'apply' : action,
				'confession_id' : confession_id,
				'extra_param'   : comment_id
			};

			//console.log(params);
			$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);

					if(obj.success){
						alert(obj.success);
						if(obj.action=='blocked'){
							click.addClass('blocked');
						}
						if(obj.action=='unblocked'){
							click.removeClass('blocked');
						}

						if(obj.action=='deleted'){
							parent.remove();
						}
						if(obj.action=='comment_deleted'){
							click.parent().remove();
						}

					}else{
						alert(obj.error);
					}
			});
		
	});

	},
	WPCB.manageComments = function(){

		var cf_area=$('#display_confessions_area');
		var cf_area_inner=$('#confessions_area_inner');
		var comment_box_container=cf_area_inner.find('p span.conf_comments_container');
		var comment_list_container=cf_area_inner.find('#conf_comment_list');
		var comment_box = comment_box_container.children().first();



		cf_area_inner.on('click','#wpcb_send_comment',function(){
		//cf_area_inner.on('keypress','.conf_comments_container textarea',function(event){
			var button = $(this);
			
			var cb=button.prev();
			var parent=cb.parent().parent();
			var con_id=parent.attr('id');
			//var keycode = (event.keyCode ? event.keyCode : event.which);
			var comment = cb.val();
			var comment_holder = parent.find('#conf_comment_list');

			//if(keycode == '13'){ //Enter Key == 13
			if(comment.length>0){
				button.attr('disabled','disabled');
				button.val('Wait');
			//event.preventDefault();
			/*if(comment.length==0){
				return false;
			}*/
			comment_box.hide();
				var params={
					'action' : 'manage_confession_comments',
					'comment' : comment,
					'confession_id' : con_id
				};
				$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);
					button.removeAttr('disabled');
					button.val('Send');
					if(obj.success!=undefined){
						comment_holder.append(obj.success);
						cb.val('');
						comment_box.show();
						

					}

				});
			}
			
		});

	},
	WPCB.readMore = function(){
		var cf_area=$('#display_confessions_area');
		var cf_area_inner=$('#confessions_area_inner');
		var wpcb_confessions = cf_area_inner.find('#wpcb_confessions');
		
		wpcb_confessions.on('click','.wpcb_read_more',function(){
			var hit = $(this);
			hit.addClass('readmode_clicked');
			hit.text('Loading...');
			var first_con_id = $(this).attr('first_id');
			var params={
				'action' : 'fetch_old_confession',
				'first_con_id' : first_con_id,
				'category':wpcb_ajax.category
			};

			//console.log(params);
			$.post(wpcb_ajax.ajaxurl,params,function(res){
				var obj=JSON.parse(res);
					var Done=0;
					
					hit.remove();

					if(obj.confessions){
						if(wpcb_confessions.children().length>0){
							wpcb_confessions.children().last().after(obj.confessions);
						}else{
							wpcb_confessions.html(obj.confessions);
						}
						Done=1;
					}else{
						wpcb_confessions.children().last().after('<div style="text-align:center">No more confessions found!</div>');
					}
					
					
			});
		});				
	},
	WPCB.manageLike = function(){

		var cf_area=$('#display_confessions_area');
		var cf_area_inner=$('#confessions_area_inner');
		var like_box=cf_area_inner.find('p span.like-cf');
		var action;

		cf_area_inner.on('click','.like-cf',function(){
			var hit=$(this);
			var parent=hit.parent();
			var con_id=parent.attr('id');
			var like_counts=parent.find('.like_counts');
			var dislike_counts=parent.find('.dislike_counts');
			var confession_msg=parent.find('.conf_msg');

			if($(this).hasClass('dashicons-thumbs-up')){
				action='like';
			}

			if($(this).hasClass('dashicons-thumbs-down')){
				action='dislike';
			}

			var params={
				'action' : 'manage_confession_likes',
				'apply' : action,
				'confession_id' : con_id
			};

			//console.log(params);
			$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);

					if(obj.success!=undefined){

					
						if(obj.success.current_user_action=='liked'){
						hit.addClass('liked');
						hit.siblings().removeClass('disliked');
						}else if(obj.success.current_user_action=='disliked'){
						hit.addClass('disliked');
						hit.siblings().removeClass('liked');
						}
					
						
					if(obj.success.likes!=undefined){
						like_counts.html('('+obj.success.likes+')');
					}

					if(obj.success.dislikes!=undefined){
						dislike_counts.html('('+obj.success.dislikes+')');
					}
					}else{
						confession_msg.show().css('color','red').html(obj.error);
					}
					
					setTimeout(function(){ 
						confession_msg.hide().css('color','').html('');
						// hit.removeClass('liked disliked');
					}, 1000);
					});
		});

		
	},
	WPCB.init = function() {
		// put your custom functions here
		console.log('WPCB loaded');
		var cf_id=0;
		var cf_area=$('#display_confessions_area');
		var cf_area_inner=$('#confessions_area_inner');
		var cf=$('#confession_form');
		var cform=cf.find('form');

		var ctitle=cform.find('#wpcb_title');
		var cdesc=cform.find('#wpcb_desc');
		var cauthor=cform.find('#wpcb_author_name');
		var ccategory=cform.find('#wpcb_category');
		var cnonce = cform.find('#verify_cf_submission');
		var message=$('.wpcb_messages');
		/*var odd_confessions=cf_area_inner.find('#odd_confessions');
		var even_confessions=cf_area_inner.find('#even_confessions');*/
		var wpcb_confessions=cf_area_inner.find('#wpcb_confessions');
		var main = $('#main');

		WPCB.firstLoadConfession();
		WPCB.syncConfession();
		WPCB.readMore();
		WPCB.manageLike();
		WPCB.manageComments();
		WPCB.manageActions();
		WPCB.manageAdminActions();
		
		
		cf_area.on('click','#view_cform',function(){
			cf.toggle();
			if($(this).hasClass('hidden_cform')){
				cf.show();
				$(this).removeClass('hidden_cform');
				$(this).text('Hide Confession Form');
			}else{
				cf.hide();
				$(this).addClass('hidden_cform');
				$(this).text('View Confession Form');
			}
		});
		
		cf.on('keyup','#wpcb_desc',function(){
			var length = $(this).val().length + ' Characters Written';
			$(this).parent().find('.wpcb_desc_length').html(length);
		});
		cf.on('click','#wpcb_add_confession',function(){
			var btn = $(this);
			var c_id_field = cform.find('#cf_id');
			if(c_id_field.length>0){
			cf_id = c_id_field.val();
			}

			var params=WPCB.validateForm(cf_id,ctitle,cdesc,cauthor,ccategory,cnonce);

			if(params!=false){
			console.log(params);
			btn.attr('disabled','disabled');
			btn.val('Wait');
			$.post(wpcb_ajax.ajaxurl,params,function(res){
					var obj=JSON.parse(res);
					btn.removeAttr('disabled');
					btn.val('Add Confession');

					if(obj.success){
						//message.css('color','green').show().html(obj.success);
						alert(obj.success);
					}else{
						//message.css('color','red').show().html(obj.error);
						alert(obj.error);
					}

					ctitle.val('');
					cdesc.val('');
					cauthor.val('')
					ccategory.val(1);
					main.scrollTop(0);
					setTimeout(function(){ 

				console.log('WPCB Saved');
				message.hide().html('');
					
				}, 10000);
			}); //Ajax request


			}
			
		});

		cf_area.on("click",'.new_feeds',function(){
			/*odd_confessions.children().removeClass('hide_new');
			even_confessions.children().removeClass('hide_new');*/
			wpcb_confessions.children().removeClass('hide_new');
			$(this).hide();
			cf_area_inner.scrollTop(0);
		});

		cf_area.on("click",'.like_counts',function(){
			$(this).prev().toggleClass('hide');
		});
		
		cf_area.on("click",'.dislike_counts',function(){
			$(this).prev().toggleClass('hide');
		});
		
	},

	WPCB.validateForm=function(cf_id,ctitle,cdesc,cauthor,ccategory,cnonce){
		var error=0;

		if(ctitle.length>0 && ctitle.val().length<3){
			ctitle.addClass('show-danger');
			error=1;
		}else{
			ctitle.removeClass('show-danger').addClass('show-success');
		}
		if(cdesc.val().length<wpcb_ajax.cf_length){
			cdesc.addClass('show-danger');
			error=1;
		}else{
			cdesc.removeClass('show-danger').addClass('show-success');
		}
		if(cauthor.length>0 && cauthor.val().length==0){
			cauthor.addClass('show-danger');
			error=1;
		}else{
			cauthor.removeClass('show-danger').addClass('show-success');
		}
		
		if(error){
			console.log('Validation Error');
			return false;
			
		}else{

			var cf_params={
				'action' : 'cf_save_confession',
				'cf_id' : cf_id,
				'cf_title' : ctitle.val(),
				'cf_category' : ccategory.val(),
				'cf_author' : cauthor.val(),
				'cf_desc' : cdesc.val(),
				'verify_cf_submission' : cnonce.val()
			}
			return cf_params;
		}
		//console.log(ctitle.length);
	}

	$(document).on( 'ready load_ajax_content_done', WPCB.init );

})(window, document, jQuery);