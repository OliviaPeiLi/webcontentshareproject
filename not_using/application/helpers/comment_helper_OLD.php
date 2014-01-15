<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//helper fro get favicon from url
//example getFavicon('http://google.com');
//this will return address faviacon if not found will return false

/* THREADED COMMENTS
 *	Inputs: news_array 		- All comments for the single news post
 *			prev_comments	- All comments that have been touched by this function (to ensure we do not repeat the same comment)
 *			children		- ids of children comments
 *			page_id			- id of the page/interest
 *			fvalue			- Newsfeed data
 *			entity_id			- Id of page/interest
 *			view_type		- Type of view page/profile/home
 *			level			- used internally (optional)
 *			is more			- used internally (optional)
 *			order			- used internally (optional)
 *			nextParentMore	- used internally (optional)
 */
 	function testtesttest()
 	{
 		//echo 'Dmitry';
 		$ci = & get_Instance();
 		echo $ci->session->userdata('id');
 		include(APPPATH.'/modules/comment/views/comment.php');
 	}
 	
	//function get_loopCommentEvent(&$news_array, &$prev_comments, $children, &$page_id, &$fvalue, &$entity_id, &$view_type,$parent=0,$level=0,$ismore=false,$order='asc',$nextParentMore=false){
	function get_loopCommentEvent($news_array, $prev_comments, $children, $page_id, $fvalue, $entity_id, $view_type,$in,$level=0,$ismore=false,$order='asc',$nextParentMore=false){
		//echo 'ENTITY_id='.$entity_id;
		$ci = & get_Instance();


		$level = $level + 1;

		if($children === null || count($children) <= 0) return false;
		else{
			$i=0;
			$limit_reached = false;
			//for each child id get child data
			echo '<ul class="child_comments">';
			foreach($children as $k => $v){
			
				$child_id = $v['children_id'];
				$disp = true;

				//echo 'BEGIN SINGLE COMMENT';
				
				if(!$ismore){
					//if($i>=2 && $level!=1) break;
					if($i>=2 && $level!=1) {
						//break;
						$disp = false;
					}
					$i++;
				}
				else{ 
					$i++;
					if($i<=2 && $nextParentMore==false) {
						//continue;
						$disp = false;
					}
				}
				
				//$pote = $_this->db->get('comment_vote');
				$point=0;
				
				$already_vote=false;
				

				if(!in_array($child_id,$prev_comments)) {
					if (!$disp) {$hide = 'none'; } else {$hide = 'block';}
					echo '<li id="blockLevel'.$child_id.'" class="blockLevel" style="display:'.$hide.'">';
						
						$can_comment = '1';
						$cv = $news_array['comments'][$child_id];
						//print_r($cv);
						$thumb = $cv['thumbnail'];
						//echo $thumb;
						if($thumb == s3_url())
						{
							if ($is_from_page_child) 
							{
								$thumb = s3_url().'pages/default/defaultInterest/'.$cv['category_id'].'.png';
							} 
							else 
							{	
								$thumb = s3_url().'users/default/defaultMale.png';			
							}
						}
						$cv = $news_array['comments'][$child_id];
						$ci =& get_instance(); 
						include(APPPATH.'/modules/comment/views/comment.php');

						
						if ($news_array['comments'][$child_id]['children'] && count($news_array['comments'][$child_id]['children'] > 0) ) {
							get_loopCommentEvent($news_array,$prev_comments,$news_array['comments'][$child_id]['children'],$page_id, $fvalue,$entity_id,$view_type,$in,$level,$ismore,$order,true);
						}
						echo '<div class="add_comment_reply_box" style="display:none">';
							echo form_open('add_comment');
							echo Form_Helper::form_input('comm_msg','','class="reply_textbox"');
							echo form_submit('submit','Reply','class="add_child_comment blue_bg" rel="'.$child_id.'"');
							echo form_close();
						echo '</div>';					
						
					echo '</li>';
				}	
				$prev_comments[] = $child_id;
				$num_children = count($news_array['comments'][$child_id]['children']);
	
				//echo '</li>';
				if(count($children) > 2 && $level>1 && $i>=2 && !$ismore && !$limit_reached){
					$limit_reached = true;
					echo '
						<li class="linkToViewMore" id="linkToViewMore'.$news_array['comments'][$child_id]['comment_id'].'">
						<a href="#">load more comments</a>
						</li>
					';
					/*
					echo '
						<li class="linkToViewMore" id="linkToViewMore'.$news_array['comments'][$child_id]['comment_id'].'">
						<a href="#">load more comments (<span class="comment_count>">'.(count($children)-2).'</span> replies)</a>
						</li>
					';
					*/
				}
				
				
				//echo 'END SINGLE COMMENT';
				
			}
			echo '</ul>';
		}
		//echo 'END COMMENTS LIST';
		//include('application/modules/comment/views/comment.php');
		
		
	}
?>
