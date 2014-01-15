<?php
/**
 *
 * Mentionable behavior - Gets the string from db, parse it and return correct text with link for @mention, #hashtag and link
 * @author ray
 * @usage
 *
 * in the model load the behavior as follows:
 *  //Behaviors
 *	public $behaviors = array(
 *							'mentionable' => array(
 *							   'comment' => array(
 *								   'mention'=>TRUE,
 *								   'hashtag'=>TRUE,
 *								   'link'=>TRUE
 *							   )
 *						   )
 *					   );
 *
 */
class Mentionable_Behavior extends Behavior
{

	/**
	 * Set the virtual fields for the thumbnails
	 */
	public static function _run_after_get($result, $config) {
		$CI = get_instance();
		foreach ($config as $field=>$field_config) {
			if (!isset($result->$field)) continue; //some places we don't always select comment text or caption
			$orig_text = $result->$field;
			
			//if the title is https://bay002.mail.live.com/default.aspx?id=64855_hash_n=907850362&view=1
			//it will replace the url with a link and also the _hash inside which breaks the layout
			$urls = array();
			if($field_config['link']){
				$reg_exUrl = "/(http\:\/\/[w\.]*|https\:\/\/[w\.]*|ftp\:\/\/[w\.]*|ftps\:\/\/[w\.]*|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				$match = preg_match_all($reg_exUrl, $orig_text, $urls); //check if there is url
				if ($match) {
					foreach($urls[0] as $url) {
						$orig_url = $url;
						$url =  'http://'.str_replace(array('http://','https://'),'',$url);
						$urls[] = $url;
						$orig_text = str_replace($orig_url, '{$'.count($urls).'}', $orig_text);
					}
				}
			}
			
			if($field_config['hashtag']) {
				$hashtag = "/_hash_[a-zA-Z0-9\-\.]+(\/\S*)?/";
				$match_hash = preg_match_all($hashtag, $orig_text, $hash_token);
				if($match_hash) {
					foreach($hash_token[0] as $token) {
						$orig_text = $match_hash ? preg_replace('/'.$token.'/', '<a class="link_in_comment" href="/search?q='.str_replace(array('_hash_'),'%23',$token).'" target="_blank">'.str_replace(array('_hash_'),'#',$token).'</a>', $orig_text, 1) : $orig_text;
					}
				}
				$orig_text = str_replace("_hash_","#",$orig_text);
			}
			
			foreach ($urls as $i=>$url) {
				if(is_scalar($url)){
					$url = str_replace('_hash_', '#', $url);
					$orig_text = str_replace('{$'.($i+1).'}', '<a class="link_in_comment" href="'.$url.'" target="_blank">'.str_replace(array('http://','https://'),'',$url).'</a> ', $orig_text);
				}
			}
			
			if($field_config['mention']) {
				//Parse mentions not found in the drop down FD-376
				$mentions = self::get_mentions($orig_text);
				foreach ($mentions as $user_id => $user) {
					$orig_text = str_replace('@'.$user->uri_name, '@<a href="'.$user->url.'" class="link_in_comment show_badge" data-user_id="'.$user->id.'">'.$user->uri_name.'</a>', $orig_text);
				}
				
				$atmention = "/@\[(.*?)\]/";
				$match_at = preg_match_all($atmention, $orig_text, $at_token);
				//var_dump($at_token);
				if($match_at)
				{
					$i=0;
					$CI->load->model('user_model');
					foreach($at_token[0] as $token)
					{
						//echo $at_token[1][$i];
						$user_array = explode(':',$at_token[1][$i]);
						$user = $CI->user_model->get($user_array[0]);
						//RR - removed target="_blank" - http://dev.fantoon.com:8100/browse/FD-3417
						if ($user) {
							$orig_text = $match_at ? str_replace($token, '@<a href="'.$user->url.'" class="link_in_comment show_badge" data-user_id="'.$user_array[0].'">'.$user->uri_name.'</a>', $orig_text) : $orig_text;
						}
						$i++;
					}
				}
				$orig_text = str_replace("@@","@",$orig_text);
			}

			$result->$field = $orig_text;
		}
	}
	
	public static function _run_before_set(&$data, $config) {
		foreach ($config as $field=>$field_config) {
			if (!isset($data[$field])) continue;
			if($field_config['hashtag']) {
				$data[$field] = str_replace('#','_hash_',$data[$field]);
			}
		}
		return $data;
	}
	
	public static function _run_after_set(&$data, $config) {
		$CI = get_instance();
		foreach ($config as $field=>$field_config) {
			if (!isset($data[$field])) continue; //some places we don't always select comment text or caption
			if (!isset($data['folder_id'])) {
				$folder = mysql_fetch_object(mysql_query("SELECT folder_id FROM newsfeed WHERE newsfeed_id = ".$data['newsfeed_id']));
				$data['folder_id'] = $folder->folder_id;
			}
			$orig_text = $data[$field];
			
			/* ===================== Mentions ==================== */
			$mentions = self::get_mentions($orig_text);
			foreach ($mentions as $user_id=>$user) {
				if( ! $CI->mention_model->count_by(array('user_id_to'=>$user_id, 'newsfeed_id'=>$data['newsfeed_id'], 'folder_id'=>$data['folder_id']))) {
					$CI->mention_model->insert(array(
													'user_id_from'=>$CI->user->id,
													'user_id_to'=>$user_id,
													'newsfeed_id'=>$data['newsfeed_id'],
													'folder_id'=>$data['folder_id'],
												));
				}
			}
			
			/* ==================== Hashtags ========================== */
			if (!isset($field_config['model'])) continue; //used for both hashtag_id and has_many configs
			
			$primary_key = $CI->{$field_config['model'].'_model'}->primary_key();
			$table = $CI->{$field_config['model'].'_model'}->table();
			if (!isset($data[$primary_key])) continue; //the primary key is used below so we cant continue without it
			
			//update hashtag_id record in the table
			if (isset($field_config['hashtag_id']) && $field_config['hashtag_id']) {
				$CI->db->query("UPDATE $table SET hashtag_id = 0 WHERE $primary_key = {$data[$primary_key]}");
			}
			
			$hashtags = array();
			$main_hashtag = null;
			
			$match_hash = preg_match_all("/_hash_[a-zA-Z0-9\-\.]+(\/\S*)?/", $orig_text, $hash_token);
			if(!$match_hash) continue;
			foreach($hash_token[0] as $token) {
				$hashtag_data = $CI->hashtag_model->get_by(array('hashtag'=>$token));
				if($hashtag_data) {
					$hashtag_data->update(array('count'=>$hashtag_data->count+1));
					$hashtag_id = $hashtag_data->id;
				} else {
					$hashtag_id = $CI->hashtag_model->insert(array('hashtag'=>$token,'count'=>1));
				}
				if (in_array($token, $CI->hashtag_model->top_hahtags)) {
					$main_hashtag = $hashtag_id;
				} 
				$hashtags[] = $hashtag_id;
			}
			
			//Logic for updating hashtag_id in the table (curently used in newsfeed)
			$banned_hashtag = $CI->hashtag_model->get_by(array('hashtag'=>'_hash_NSFW'));
			if (isset($field_config['hashtag_id']) && $field_config['hashtag_id'] && !in_array($banned_hashtag->id, $hashtags)) {
				$main_hashtag = $main_hashtag ? $main_hashtag : $hashtag_id;
				$CI->db->query("UPDATE $table SET hashtag_id = $main_hashtag WHERE newsfeed_id = {$data[$primary_key]}");
			}
			
			//update {model}_hashtags table if there is any (newsfeed_hashtags)
			if (isset($field_config['has_many']) && $field_config['has_many']) {
				$has_many = $CI->{$field_config['model'].'_model'}->has_many();
				if (!isset($has_many[$field_config['has_many']])) continue;
				$has_many = $has_many[$field_config['has_many']];
				
				$CI->load->model($has_many['foreign_model'].'_model');
				foreach($hashtags as $hashtag_id) {
					$check_data = array(
									$has_many['foreign_column'] => $data[$primary_key],
									'hashtag_id' => $hashtag_id
								);
					if($CI->{$has_many['foreign_model'].'_model'}->count_by($check_data)) continue;
					$CI->{$has_many['foreign_model'].'_model'}->insert($check_data);
				}
			}
			
		}	
	}
	
	private function get_mentions($orig_text) {
		$ret = array();
		$mentiontag = "/@[a-zA-Z0-9\-\._]+(\/\S*)?/";
		preg_match_all($mentiontag, $orig_text, $matches);
		$mentions = isset($matches[0]) ? $matches[0] : array();
		foreach ($mentions as $mention) {
			 $user = get_instance()->user_model->get_by(array('uri_name'=>str_replace("@","",$mention)));
			 if ($user && $user->id) $ret[$user->id] = $user;
		}
		return $ret;
	}
	
}