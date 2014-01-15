	<?php
class Notifications_helper extends Html_helper {
	
	public static function notification($type, $notification) {
		if (method_exists('Notifications_helper', $type)) {
			return call_user_func(array('Notifications_helper', $type), $notification);
		}
		return '';
	}
	
	public static function samples($notification) {
		$templates = array_keys(get_instance()->notification_model->notification_types);

		$ret = '';
		foreach ($templates as $template) {
			$ret .= call_user_func(array('Notifications_helper', $template), $notification)."\n";
		}
		return $ret;
	}
	
	public static function folder_contributor( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_collaborator_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-folder_contributor"'
				.self::user_from_link_data()
				.self::folder_link_data()
			.'>'.$content.'</script>';
		}
 
		return $content;
	}

	public static function newsfeed( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_collaboration_newsfeed_lexicon' ), 
			self::user_from_link( $notification ), 
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-newsfeed"'
				.self::user_from_link_data()
				.self::folder_link_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}

	public static function collaboration_newsfeed( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_collaboration_newsfeed_lexicon' ), 
			self::user_from_link( $notification ), 
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-collaboration_newsfeed"'
				.self::user_from_link_data()
				.self::folder_link_data()				
			.'>'.$content.'</script>';
		}
		return $content;
	}

	public static function follow( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_following_lexicon' ),
			self::user_from_link( $notification )
		)
		.self::follow_btn($notification)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-follow"'
				.self::user_from_link_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function follow_folder( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_following_folder_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-follow_folder"'
				.self::user_from_link_data()
				.self::folder_link_data()				
			.'>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function folder_like( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_likes_folder_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-folder_like"'
				.self::user_from_link_data()
				.self::folder_link_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function badge( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_badge_lexicon' ),
			self::tag('span', array('class'=>'badge'), $notification->badge->name) 
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-badge"
				data-_badge-name = "span.badge"
			>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function message( $notification ) {		
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_sent_message_lexicon' ),
			self::user_from_link( $notification ),
			self::tag('span', array('class'=>'message_preview'), $notification->msg_content->msg_body)
		)
		. self::anchor('/view_msg/'.$notification->msg_content->thread_id, 
			get_instance()->lang->line( 'notification_read_sent_message_lexicon' ), array('class' => 'read')
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-message"
				'.self::user_from_link_data().'
				data-_msg_content-msg_body = "span.message_preview"
				data-_msg_content-thread_id = "a.read @href"
			>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function link_like( $notification ) {


		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_likes_link_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		.self::a_preview_popup( $notification, 'notification_read_likes_link_lexicon' );
		//.self::follow_btn($notification);
		

		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-link_like"'
				.self::user_from_link_data()
				.self::folder_link_data()
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}

		return $content;
	}

	public static function photo_like( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_likes_link_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		. self::a_preview_popup( $notification, 'notification_read_likes_link_lexicon' );
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-photo_like"'
				.self::user_from_link_data()
				.self::folder_link_data()				
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}

	public static function link_comm_like( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_likes_comment_lexicon' ),
			self::user_from_link( $notification ),
			'<span class="comment">'.$notification->comment->comment .'</span>',
			self::folder_link($notification)
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		.self::a_preview_popup( $notification, 'notification_read_likes_comment_lexicon' );

		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-link_comm_like"'
				.self::user_from_link_data()
				.'data-_comment-comment = ".comment"'
				.self::folder_link_data()				
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}

	public static function u_comm( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_commented_link_lexicon' ),
			self::user_from_link( $notification ),
			'<span class="comment">'.$notification->comment->comment.'</span>',
			self::folder_link($notification)
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>';
		//.self::a_preview_popup( $notification, 'notification_read_commented_link_lexicon' );
		//.self::follow_btn($notification);;
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-u_comm"'
				.self::user_from_link_data()
				.'data-_comment-comment = ".comment"'
				.self::folder_link_data()
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function at_comm( $notification ) {

		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_commented_mention_lexicon' ),
			 self::user_from_link( $notification ),
			 self::folder_link($notification)
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		.self::a_preview_popup( $notification, 'notification_read_lexicon' );
		//.self::follow_btn($notification);
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-at_comm"'
				.self::user_from_link_data()
				.self::folder_link_data()
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}
	
	public static function at_drop( $notification ) {
		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_commented_mention_at_drop_lexicon' ),
			self::user_from_link( $notification )
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		.self::a_preview_popup( $notification, 'notification_read_lexicon' );
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-at_drop"'
				.self::user_from_link_data()
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}

	public static function redrop( $notification ) {

		$content = '<div class="notification">'.sprintf( get_instance()->lang->line( 'notification_smbdy_redrop_newsfeed_lexicon' ),
			self::user_from_link( $notification ),
			self::folder_link($notification)
		)
		.'<small class="converted_time">'.$notification->converted_time.'</small>'
		.'</div>'
		.self::a_preview_popup( $notification, 'notification_read_lexicon' );
		
		if ($notification->id <= 0) {
			$content = '<script type="template/html" id="tmpl-notification-redrop"'
				.self::user_from_link_data()
				.self::a_preview_popup_data()
			.'>'.$content.'</script>';
		}
		return $content;
	}

	
	/* ==================== PRIVATE ======================== */
	
	private  static function follow_btn($notification) {

		$is_following = get_instance()->user_model->is_following( get_instance()->user, $notification->user_id_from);

		return self::tag('span', array('class'=>'follow_box'),
					self::tag('span', array('class'=>'grayed js_allready_follow','style'=> $is_following ? 'display:inline-block' : 'display:none'),
							get_instance()->lang->line( 'notification_already_following_lexicon' )
					)
					.self::tag('span', array('class'=>'grayed js_follow'),
						self::anchor('follow_user/'.$notification->user_id_from, get_instance()->lang->line( 'notification_follow_back_btn' ), array(
							'class' => 'request_status accept_connection header_view blue_bg blueButton',
							'rel' => 'ajaxButton',
							'style'=> $is_following ? 'display:none' : 'display:inline-block'
						))
					)
				);

	}

	private static function a_preview_popup( $notification, $str ) {

		// TODO text drops provides crash images
		if ( (isset($notification->newsfeed->link_type) && $notification->newsfeed->link_type == 'text') || !$notification->newsfeed )
			return '';

		$img_bigsquare = isset($notification->newsfeed->_img_bigsquare) ? $notification->newsfeed->_img_bigsquare : ''; 
		$description = str_replace( '_hash_', '#', strip_tags( $notification->newsfeed->description ));
		$description = self::hashtag_link($description);

		return self::anchor('#preview_popup', '<img src="'.$img_bigsquare.'" data-complete="'.$notification->newsfeed->complete.'" class="notification_drop_image">', array(
					'rel' => 'popup-disabled', 'class'=>'read notification_post_image_container link-popup',
					'data-newsfeed_id' => $notification->newsfeed->newsfeed_id,
					'data-thumbnail' => $notification->newsfeed->link_type != 'text' ? $notification->newsfeed->_img_thumb : '',
					'data-description' => $description,
					'data-newsfeed_url' =>  $notification->newsfeed->url,
					'data-id'=> $notification->id
				));
	}
	private static function a_preview_popup_data() {
		return 'data-_newsfeed-newsfeed_id = ".link-popup @data-newsfeed_id"
				data-_newsfeed-url = ".link-popup @data-newsfeed_url"
				data-_newsfeed-_img_bigsquare = ".notification_drop_image @src, .link-popup @data-thumbnail"
				data-_newsfeed-description = ".link-popup @data-description"
				data-_newsfeed-complete = ".notification_drop_image @data-complete"
				data-id = ".link-popup @data-id"';
	}

	private static function user_from_link( $notification ) {
		return self::anchor($notification->user->url, $notification->user->full_name, array('class'=>'user_from_link'));
	}
	private static function user_from_link_data() {
		return ' data-_user-url = "a.user_from_link @href"
				data-_user-full_name = "a.user_from_link" ';
	}

	// replace a hashtag by a link to hashtag search
	private static function hashtag_link( $description ) {
		preg_match_all( '/#[^\s|#]+/', $description, $hashtags );
		foreach ($hashtags[0] as $k=>$hashtag) {
			// <a target="_blank" href="/search?q=%23Food" class="link_in_comment">#Food</a>
			$description = str_replace($hashtag, '<a target="_blank" href="/search?q=%23'
				.str_replace('#', '', $hashtag)
				.'" class="link_in_comment">'. $hashtag . '</a>', $description);
		}
		$description = str_replace('"', '\'', $description);
		return $description;
	}
	
	private static function folder_link( $notification ) {
		
		$folder_url  = $notification->folder ? $notification->folder->folder_url  : '';
		$folder_name = $notification->folder ? $notification->folder->folder_name : '';
		//return self::anchor($notification->folder->folder_url, $notification->folder->folder_name, array('class'=>'link'));
		return self::anchor($folder_url, $folder_name, array('class'=>'link'));
	}
	private static function folder_link_data() {
		return ' data-_folder-folder_url = ".link @href"
				data-_folder-folder_name = ".link" ';
	}
	
}
