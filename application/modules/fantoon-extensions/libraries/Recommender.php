<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Recommender
{
    /**
    * Recommender Class
    *
    * Provides methods to build recommender system
    * This class uses some procedures and functions already defined in databse to speed up some calculations
    *
    **/

    private $CI;
    
    // weigths
    private $wtPageTopics;
    private $wtPageUserRatings;
    private $wtUserAge;
    private $wtUserGender;
    private $wtUserExperience;

    // recommendations weigths
    private $wtRecUserPreferences;
    private $wtRecSimilarUsers;
    private $wtRecUsersFriends;
    private $wtRecAdmin;

    // max count of friends and similars used in recommendations calculus
    // max number of recommendations returned
    private $maxFriends;
    private $maxSimilars;
    private $maxRecommendations;

    public function __construct()
    {
        $this->CI =& get_instance();
        
        // load database helper; configurations in config/database.php
        $this->CI->load->database();
        // load recommender system custom configuration
        $this->CI->config->load('recommender', TRUE);
        
        // load configuration
        // weights
        $this->wtPageTopics = $this->CI->config->item('wtPageTopics', 'recommender');
        $this->wtPageUserRatings = $this->CI->config->item('wtPageUserRatings', 'recommender');
        $this->wtUserAge = $this->CI->config->item('wtUserAge', 'recommender');
        $this->wtUserGender = $this->CI->config->item('wtUserGender', 'recommender');
        $this->wtUserExperience = $this->CI->config->item('wtUserExperience', 'recommender');
        // recommendations weights
        $this->wtRecUserPreferences = $this->CI->config->item('wtRecUserPreferences', 'recommender');
        $this->wtRecSimilarUsers = $this->CI->config->item('wtRecSimilarUsers', 'recommender');
        $this->wtRecUsersFriends = $this->CI->config->item('wtRecUsersFriends', 'recommender');
        $this->wtRecAdmin = $this->CI->config->item('wtRecAdmin', 'recommender');        
        // max number of friends and similars used in recommendations calculus
        $this->maxFriends = $this->CI->config->item('maxfriends', 'recommender');
        $this->maxSimilars = $this->CI->config->item('maxsimilars', 'recommender');        
        // max number of recommendations returned
        $this->maxRecommendations = $this->CI->config->item('maxRecommendations', 'recommender');        
    }
    
    
    /**********************************
    * Users
    **********************************/
    
    /**
    * get page recommendations for one user 
    * 
    * @userId   int     id of user
    * @max      int     max number of recommendations returned
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>points
    */
    public function getRecommendations($userId) 
    {
        // user preferences recommendations
        $prefs = $this->getUserPreferencesRecommendations($userId, $this->maxRecommendations);
        // weight it!
        array_walk($prefs, create_function('&$val', '$val = $val*'.$this->wtRecUserPreferences.';'));
        // user similars recommendations
        $sims = $this->getUserSimilarsRecommendations($userId, $this->maxSimilars, $this->maxRecommendations);        
        // weight it!
        array_walk($sims, create_function('&$val', '$val = $val*'.$this->wtRecSimilarUsers.';'));
        // user friends recommendations
        $friends = $this->getUsersFriendsRecommendations($userId, $this->maxFriends, $this->maxRecommendations);        
        // weight it!
        array_walk($friends, create_function('&$val', '$val = $val*'.$this->wtRecUsersFriends.';'));
        // admin recommended
        $adminRecs = $this->getTopAdminRecommendedPages($this->maxRecommendations);        
        // weight it!
        array_walk($adminRecs, create_function('&$val', '$val = $val*'.$this->wtRecAdmin.';'));
        
        // add all recommendations; remove lower score duplicates
        $result = $this->addRecommendations($prefs, $sims);
        $result = $this->addRecommendations($result, $friends);
        $result = $this->addRecommendations($result, $adminRecs);
        
        // sort        
        arsort($result);
        // return top n
        return array_slice($result, 0, $this->maxRecommendations, true);
    }
    
    /**
    * get user preferences recommendations
    * 
    * @userId   int     id of user
    * @max      int     max number of recommendations returned
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>score
    */    
    public function getUserPreferencesRecommendations($userId, $max=5)
    {
        // get top n of most scored pages based on user preferences
        // user id is the id of user to get recommendations to
        // the second parameter purges from result those pages already rated by the target user
        $recommendations = $this->getUserPreferencesRecommendationsHelper ($userId, $userId, $max);
        return $recommendations;
    }

    /**
    * get user's friends recommendations; top n friends, selected by greater similarity
    * 
    * @userId   int     id of user
    * @friends  int     max number of user's friends to be considered    
    * @max      int     max number of recommendations returned
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>points
    */    
    public function getUsersFriendsRecommendations($userId, $friends=5, $max=5)
    {
        // get user's friends; most similar on top
        $statement = 'select c.user2_id friend_id from connections c left join users_similarity u on (c.user1_id=u.user1_id and c.user2_id=u.user2_id ) or '
                    .'(c.user1_id=u.user2_id and c.user2_id=u.user1_id ) where c.user1_id=? order by u.similarity desc limit ?';
        $query = $this->CI->db->query($statement, array($userId, $friends));
        $recommendations = array();
        if ($query->num_rows() > 0)      
            foreach($query->result() as $row) {
                // get recommendations for user's friend but excludes pages already rated by target user
                $temp = $this->getUserPreferencesRecommendationsHelper ($row->friend_id, $userId, $max);
                // adds recommendations and removes duplicates; in duplicates maintain most rated item
                $recommendations = $this->addRecommendations($recommendations, $temp);
            }
        // sort
        arsort($recommendations);
        // get only n first elements
        return array_slice($recommendations, 0, $max, true);    
    }
    
    /**
    * get user similars recommendations
    * 
    * @userId   int     id of user
    * @similars int     max number of similar users to be considered
    * @max      int     max number of recommendations returned
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>points
    */    
    public function getUserSimilarsRecommendations($userId, $similars=5, $max=5)
    {
        // get top similar users
        $statement = 'select user1_id + user2_id - ? similar_id from users_similarity where user1_id = ? or user2_id = ? order by similarity desc limit ?';
        $query = $this->CI->db->query($statement, array($userId, $userId, $userId, $similars));
        $recommendations = array();
        if ($query->num_rows() > 0)      
            foreach($query->result() as $row) {
                // get recommendations for similar user but excludes pages already rated by target user
                $temp = $this->getUserPreferencesRecommendationsHelper ($row->similar_id, $userId, $max);
                // adds recommendations and removes duplicates; in duplicates maintain most rated item
                $recommendations = $this->addRecommendations($recommendations, $temp);
            }
        // sort
        arsort($recommendations);
        // get only n first elements
        return array_slice($recommendations, 0, $max, true);    
    }
    
    /**
    * adds new user similarities to dataset 
    * use this method to add one new user at a time to dataset
    */
    public function addUserSimilaritiesToDataset($userId)
    {
        try {
            // add new user similarities
            $statement = 'call AddUserSimilarities(?, ?, ?, ?);';
            $this->CI->db->query($statement, array($userId, $this->wtUserExperience, $this->wtUserAge, $this->wtUserGender));
        }
        catch (Exception $ex) {
            return 0;
        }
    }
    
    /**
    * recreates user similarity dataset
    * user similarity dataset entries have user1Id, user2Id and similiarity columns; user1Id is always the lower id between the two  
    * use this method to recreate all dataset
    */
    public function buildUserSimilarityDataset()
    {
        try {
            // ensure that execution time will be properly set
            $timeout = $this->CI->config->item('executionTimeout', 'recommender');
            set_time_limit ($timeout);
        
            // runs mysql stored procedure
            $statement = 'call BuildUserSimilaritiesDataset (?, ?, ?)';
            $query = $this->CI->db->query($statement, array($this->wtUserExperience, $this->wtUserAge, $this->wtUserGender));
        }
        catch (Exception $ex) {
            return 0;
        }
    }
    
    /**
    * return similarity between two users (based on rated/visited pages)
    * 
    * @user1Id      int     1st user Id
    * @user2Id      int     2nd user Id
    * @return       float   user similarity; from -1 (inverse similarity) to 0 (not similar) to 1 (identical)
    */
    public function getUserSimilarity($user1Id, $user2Id)
    {
        try {
            if($user1Id==$user2Id) 
                return 1;
                  
            $statement = 'select GetUserSimilarity(?, ?, ?, ?, ?) sim;';
            $query = $this->CI->db->query($statement, array($user1Id, $user2Id, $this->wtUserExperience, $this->wtUserAge, $this->wtUserGender));
            return $query->row()->sim;
            
            return $similarity;
        } 
        catch (Exception $ex) {
            return 0;
        }
    }
    
    /**
    * get top pages recommended by admin
    * 
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>points
    */    
    public function getTopAdminRecommendedPages($max=5)
    {
        // get top admin recommended pages
        $statement = 'select page_id, score from pages where score > 0 order by score desc limit ?';
        $query = $this->CI->db->query($statement, array($max));
        $ratings = array();
        if ($query->num_rows() > 0)
            foreach($query->result() as $row)
                    $ratings[$row->page_id]=$row->score;
        return $ratings;    
    }
    
    /**********************************
    * Pages
    **********************************/
    
    /**
    * adds new page similarities to dataset 
    * use this method to add one new page at a time to dataset
    */
    public function addPageSimilaritiesToDataset($pageId)
    {
        try {
            // add new page similarities
            $statement = 'call AddPageSimilarities(?, ?, ?);';
            $this->CI->db->query($statement, array($pageId, $this->wtPageUserRatings, $this->wtPageTopics));
        }
        catch (Exception $ex) {
            return 0;
        }
    }
        
    /**
    * recreates page similarity dataset
    * page similarity dataset entries have page1Id, page2Id and similiarity columns; page1Id is always the lower id between the two  
    * use this method to recreate all dataset
    */
    public function buildPageSimilarityDataset()
    {
        try {
            // ensure that execution time will be properly set
            $timeout = $this->CI->config->item('executionTimeout', 'recommender');
            set_time_limit ($timeout);
        
            // runs mysql stored procedure
            $statement = 'call BuildPageSimilaritiesDataset (?, ?)';
            $query = $this->CI->db->query($statement, array($this->wtPageUserRatings, $this->wtPageTopics));
        }
        catch (Exception $ex) {
            return 0;
        }
    }
    
    /**
    * return similarity between two pages (based on topics and user experience)
    * 
    * @page1Id      int     1st page Id
    * @page2Id      int     2nd page Id
    * @return       float   page similarity; from 0 (not similar at all) to 1 (identical)
    */
    public function getPageSimilarity($page1Id, $page2Id)
    {
        try {
            if($page1Id==$page2Id) 
                return 1;
            
            $statement = 'select GetPageSimilarity(?, ?, ?, ?) sim;';
            $query = $this->CI->db->query($statement, array($page1Id, $page2Id, $this->wtPageUserRatings, $this->wtPageTopics));
            return $query->row()->sim;
        } 
        catch (Exception $ex) {
            return 0;
        }
    }
    
    
    /**********************************
    * Utilities & helpers
    **********************************/
    
    /**
    * get user preferences recommendations; exclude pages rated by another user
    * 
    * @userId   int     id of user to find preferences from
    * @exclude  int     result will be purged of the pages this user already voted
    * @max      int     max number of recommendations returned
    * @return   array   array of ordered recommended pages, from most to less recommended; pageid=>score
    */    
    public function getUserPreferencesRecommendationsHelper ($userId, $exclude, $max=5)
    {
        // get top n of most scored pages based on user preferences
        $statement = 'select pe.page1_id + pe.page2_id - ps.page_id page_id, sum(pe.similarity) similarity, sum(ps.points * pe.similarity) rating, '
                    .'sum(ps.points * pe.similarity) / sum(pe.similarity) score '
                    .'from points_system ps inner join pages_similarity pe on pe.page1_id=ps.page_id or pe.page2_id=ps.page_id '
                    .'left join points_system up on up.user_id=? and up.page_id = pe.page1_id + pe.page2_id - ps.page_id '
                    .'where ps.user_id = ? and up.page_id is null group by pe.page1_id + pe.page2_id - ps.page_id having score > 0 order by score desc limit ?';
        $query = $this->CI->db->query($statement, array($exclude, $userId, $max));
        // build result
        $rating=array();
        if ($query->num_rows() > 0)
            foreach($query->result() as $row)
                $rating[$row->page_id]=$row->score;
        return $rating;
    }
   
    /**
    * adds two recommendations arrays; removes duplicates and ensure greater scores
    *
    * @left         array   associative array with recommendations pageId=>score
    * @right        array   associative array with recommendations pageId=>score    
    * @return       array   associative array with recommendations pageId=>score; result of $left+$rigth without duplicates    
    */
    private function addRecommendations($left, $right) 
    {
        $recommendations = $left + $right;
        foreach($recommendations as $key=>$value) {
            if(array_key_exists($key,$right) && $right[$key]>$value)
                $recommendations[$key]=$right[$key];
        };
        return $recommendations;
    }

}

?>