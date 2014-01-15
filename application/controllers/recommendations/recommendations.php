<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommendations extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // load helpers
        $this->load->helper('url');
        $this->load->helper('form');
        // load library
        $this->load->library('Recommender');
    }

    public function index()
    {
        $this->load->view('recommender');
    }

    public function buildUserSimDataset()
    {
        $then = time();
        // build users similarity dataset
        $rec = new Recommender();
        $rec->buildUserSimilarityDataset();
        $now = time();
        // show result
        $this->load->view('recommendations/recommender', array('processTime'=>$now-$then));
    }

    public function buildPageSimDataset()
    {
        $then = time();
        // build pages similarity dataset
        $rec = new Recommender();
        $rec->buildPageSimilarityDataset();
        $now = time();
        // show result
        $this->load->view('recommendations/recommender', array('processTime'=>$now-$then));
    }

    public function getUserSimilarity()
    {
        $rec = new Recommender();
        // params
        $user1Id = $_POST['user1Id'];
        $user2Id = $_POST['user2Id'];
        $result = $rec->getUserSimilarity($user1Id, $user2Id);
        // load view
        $data = array (
                    'userSimResult' => $result,
                    'user1Id' => $user1Id,
                    'user2Id' => $user2Id
                );
        $this->load->view('recommendations/recommender', $data);
    }

    public function getPagesSimilarity()
    {
        $rec = new Recommender();
        // params
        $page1Id = $_POST['page1Id'];
        $page2Id = $_POST['page2Id'];
        $result = $rec->getPageSimilarity($page1Id, $page2Id);
        // load view
        $data = array (
                    'pageSimResult' => $result,
                    'page1Id' => $page1Id,
                    'page2Id' => $page2Id
                );
        $this->load->view('recommendations/recommender', $data);
    }

    public function getUserPreferences()
    {
        $rec = new Recommender();
        // params
        $userId = $_POST['userId'];
        $result = $rec->getUserPreferencesRecommendations($userId);
        // load view
        $data = array (
                    'methodName' => 'getUserPreferences('.$userId.')',
                    'recommendations' => $result,
                    'userId' => $userId
                );
        $this->load->view('recommendations/recommender', $data);
    }

    public function getUserSimRecs()
    {
        $rec = new Recommender();
        // params
        $userId = $_POST['userId'];
        $result = $rec->getUserSimilarsRecommendations($userId);
        // load view
        $data = array (
                    'methodName' => 'getUserSimilarsRecommendations('.$userId.')',
                    'recommendations' => $result,
                    'userId' => $userId
                );
        $this->load->view('recommendations/recommender', $data);
    }

    public function getUserFriendsRecs()
    {
        $rec = new Recommender();
        // params
        $userId = $_POST['userId'];
        $result = $rec->getUsersFriendsRecommendations($userId);
        // load view
        $data = array (
                    'methodName' => 'getUserFriendsRecommendations('.$userId.')',
                    'recommendations' => $result,
                    'userId' => $userId
                );
        $this->load->view('recommendations/recommender', $data);
    }

    public function getRecommendations()
    {
        $rec = new Recommender();

        // params
        $userId = $_POST['userId'];
        $result = $rec->getRecommendations($userId);

        // load view
        $data = array (
                    'methodName' => 'getRecommendations('.$userId.')',
                    'recommendations' => $result,
                    'userId' => $userId
                );

        $this->load->view('recommendations/recommender', $data);
    }
}
