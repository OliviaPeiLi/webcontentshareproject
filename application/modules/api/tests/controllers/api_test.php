<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class api_test extends Web_Test_Case
{
    /**
     *  The test method for the /api/me
     */
    public function test_api_me()
    {
        print("Test /api/me\n");
        $this->get('api/me');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            $this->assertTrue( ($json['id'] ? true : false), 'Bad response data. User ID is not set');
            $this->assertTrue( ($json['full_name'] ? true : false), 'Bad response data. Full Name is not set');
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/folders
     */
    public function test_api_me_folders()
    {
        print("Test /api/me/folders\n");
        $response = $this->get('api/me/folders');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/folders');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( ($row['folder_id'] ? true : false), 'Bad response data. Folder ID is not set for #'.$row['folder_id']);
                $this->assertTrue( ($row['folder_url'] ? true : false), 'Bad response data. Folder URL is not set for #'.$row['folder_id']);
                $this->assertTrue( ($row['folder_name'] ? true : false), 'Bad response data. Folder Name is not set for #'.$row['folder_id']);

                if($row['folder_url']) {
                    print("Test folder_url: ".$row['folder_url']."\n");
                    $this->get($row['folder_url']);
                    $this->assertResponse(200);
                    $this->assertPattern('#<h1>'.$row['folder_name'].'</h1>#msi');
                }
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/mentions
     */
    public function test_api_me_mentions()
    {
        print("Test /api/me/mentions\n");
        $response = $this->get('api/me/mentions');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/mentions');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( is_array($row['folder']), 'Bad response data. Folder is not set for mention #'.$row['id']);
                $this->assertTrue( is_array($row['user_from']), 'Bad response data. User From is not set for mention #'.$row['id']);
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/likes?filter=true&newsfeed_id=0
     */
    public function test_api_me_likes()
    {
        print("Test /api/me/likes?filter=true&newsfeed_id=0\n");
        $response = $this->get('api/me/likes?filter=true&newsfeed_id=0');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/likes?filter=true&newsfeed_id=0');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( ($row['folder_id'] || $row['comment_id'] || $row['newsfeed_id']), 'Target of the comment is not set for like #'.$row['like_id']);
                $this->assertTrue( is_array($row['user_from']), 'Bad response data. User From is not set for like #'.$row['like_id']);
                $this->assertTrue( is_array($row['user_to']), 'Bad response data. User To is not set for like #'.$row['like_id']);
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/notifications
     */
    public function test_api_me_notifications()
    {
        print("Test /api/me/notifications\n");
        $response = $this->get('api/me/notifications');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/notifications');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( (isset($row['id']) && $row['id'] != 0), 'Bad response data. Notification ID is not set for notification');
                $this->assertTrue( (isset($row['read']) && in_array($row['read'], array(0,1))), 'Bad response data. Wrong read status for notification #'.$row['id']);
                $this->assertTrue( isset($row['time']), 'Bad response data. Notification time is not set for notification #'.$row['id']);
                $this->assertTrue( isset($row['type']), 'Bad response data. Notification type is not set for notification #'.$row['id']);
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/user_followings
     */
    public function test_api_me_user_followings()
    {
        print("Test /api/me/user_followings\n");
        $response = $this->get('api/me/user_followings');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/user_followings');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( isset($row['id']), 'Bad response data. Following ID is not set for the connection');
                $this->assertTrue( is_array($row['user']), 'Bad response data. Following User object is not set for the connection #'.$row['id']);
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/me/user_followers
     */
    public function test_api_me_user_followers()
    {
        print("Test /api/me/user_followers\n");
        $response = $this->get('api/me/user_followers');
        $this->assertResponse(401);

        $this->login();
        $response = $this->get('api/me/user_followers');
        $this->assertResponse(200);
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( isset($row['id']), 'Bad response data. Follower ID is not set for the connection');
                $this->assertTrue( is_array($row['user']), 'Bad response data. Follower User object is not set for the connection #'.$row['id']);
            }
        }
        $this->logout();
    }

    /**
     *  The test method for the /api/user/{USER_ID}
     */
    public function test_api_user()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."\n");
            $this->get('api/user/'.$user['id']);
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id']);
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                $this->assertTrue( ($json['id'] ? true : false), 'Bad response data. User ID is not set');
                $this->assertTrue( ($json['avatar'] ? true : false), 'Bad response data. User avatar is not set for user #'.$user['id']);
                $this->assertTrue( ($json['full_name'] ? true : false), 'Bad response data. Full Name is not set for user #'.$user['id']);
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/user/{USER_ID}/folders
     */
    public function test_api_user_folders()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/folders\n");
            $response = $this->get('api/user/'.$user['id'].'/folders');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/folders');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( ($row['folder_id'] ? true : false), 'Bad response data. Folder ID is not set for folder #'.$row['folder_id']);
                    $this->assertTrue( ($row['folder_url'] ? true : false), 'Bad response data. Folder URL is not set for folder #'.$row['folder_id']);
                    $this->assertTrue( ($row['folder_name'] ? true : false), 'Bad response data. Folder Name is not set for folder #'.$row['folder_id']);

                    if($row['folder_url']) {
                        print("Test folder_url: ".$row['folder_url']."\n");
                        $this->get($row['folder_url']);
                        $this->assertResponse(200);
                        $this->assertPattern('#<h1>'.$row['folder_name'].'</h1>#msi');
                    }
                }
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/user/{USER_ID}/mentions
     */
    public function test_api_user_mentions()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/mentions\n");
            $response = $this->get('api/user/'.$user['id'].'/mentions');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/mentions');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( isset($row['id']), 'Bad response data. Mention ID is not set for mention #'.$row['id']);
                    $this->assertTrue( is_array($row['folder']), 'Bad response data. Folder is not set for mention #'.$row['id']);
                    $this->assertTrue( is_array($row['user_from']), 'Bad response data. User From is not set for mention #'.$row['id']);
                }
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/user/{USER_ID}/likes?filter=true&newsfeed_id=0
     */
    public function test_api_user_likes()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/likes?filter=true&newsfeed_id=0\n");
            $response = $this->get('api/user/'.$user['id'].'/likes?filter=true&newsfeed_id=0');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/likes?filter=true&newsfeed_id=0');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( (isset($row['folder_id']) || isset($row['comment_id']) || isset($row['newsfeed_id'])), 'Target of the comment is not set for like #'.$row['like_id']);
                    $this->assertTrue( is_array($row['user_from']), 'Bad response data. User From is not set for like #'.$row['like_id']);
                    $this->assertTrue( is_array($row['user_to']), 'Bad response data. User To is not set for like #'.$row['like_id']);
                }
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/user/{USER_ID}/notifications
     */
    public function test_api_user_notifications()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/notifications\n");
            $response = $this->get('api/user/'.$user['id'].'/notifications');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/notifications');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( (isset($row['id']) && $row['id'] != 0), 'Bad response data. Notification ID is not set for notification');
                    $this->assertTrue( (isset($row['read']) && in_array($row['read'], array(0,1))), 'Bad response data. Wrong read status for notification #'.$row['id']);
                    $this->assertTrue( isset($row['time']), 'Bad response data. Notification time is not set for notification #'.$row['id']);
                    $this->assertTrue( isset($row['type']), 'Bad response data. Notification type is not set for notification #'.$row['id']);
                }
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/user/{USER_ID}/user_followings
     */
    public function test_api_user_followings()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/user_followings\n");
            $response = $this->get('api/user/'.$user['id'].'/user_followings');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/user_followings');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( isset($row['id']), 'Bad response data. Following ID is not set for the connection');
                    $this->assertTrue( is_array($row['user']), 'Bad response data. Following User object is not set for the connection #'.$row['id']);
                }
            }
            $this->logout();
    }
}

    /**
     *  The test method for the /api/user/{USER_ID}/user_followers
     */
    public function test_api_user_followers()
    {
        $user = $this->get_test_user();
        if($user) {
            print("Test /api/user/".$user['id']."/user_followers\n");
            $response = $this->get('api/user/'.$user['id'].'/user_followers');
            $this->assertResponse(401);

            $this->login();
            $response = $this->get('api/user/'.$user['id'].'/user_followers');
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( isset($row['id']), 'Bad response data. Follower ID is not set for the connection');
                    $this->assertTrue( is_array($row['user']), 'Bad response data. Follower User object is not set for the connection #'.$row['id']);
                }
            }
            $this->logout();
        }
    }

    /**
     *  The test method for the /api/top_hashtags*
     */
    public function test_api_top_hashtags()
    {
        // Test /api/top_hashtags
        print("Test /api/top_hashtags\n");
        $response = $this->get('api/top_hashtags');
        $this->assertResponse(200);

        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( (isset($row['id']) && $row['id'] != 0), 'Bad response data. Hashtag ID is not set for top hashtag');
                $this->assertTrue( (isset($row['hashtag'])), 'Bad response data. Hashtag Name is not set for top hashtag #'.$row['id']);
            }
        }
    }

    /**
     *  The test method for the /api/search?q=test
     */
    public function test_api_search()
    {
        // Test /api/search?q=test
        print("Test /api/search?q=test\n");
        $response = $this->get('api/search?q=test');
        $this->assertResponse(200);

        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            if(is_array($json['results'])) {
                // Hashtags
                if(is_array($json['results']['hashtags'])) foreach ($json['results']['hashtags'] as $row) {
                    $this->assertTrue( (isset($row['id']) && $row['id'] != 0), 'Bad response data. Hashtag ID is not set for top hashtag');
                    $this->assertTrue( (isset($row['hashtag'])), 'Bad response data. Hashtag Name is not set for top hashtag #'.$row['id']);
                }

                //Lists
                if(is_array($json['results']['lists'])) foreach ($json['results']['lists'] as $row) {
                    $this->assertTrue( isset($row['thumb']), 'Bad response data. Thumbnail is not set for folder #'.$row['folder_id']);
                    $this->assertTrue( isset($row['folder_id']), 'Bad response data. Folder ID is not set for folder #'.$row['folder_id']);
                    $this->assertTrue( isset($row['folder_name']), 'Bad response data. Folder Name is not set for folder #'.$row['folder_id']);
                }

                //Users
                if(is_array($json['results']['users'])) foreach ($json['results']['users'] as $row) {
                    $this->assertTrue( (isset($row['id']) && $row['id'] != 0), 'Bad response data. User ID is not set for user');
                    $this->assertTrue( (isset($row['avatar'])), 'Bad response data. Avatar is not set for the user #'.$row['id']);
                    $this->assertTrue( (isset($row['full_name'])), 'Bad response data. Full Name is not set for the user #'.$row['id']);
                }
            }
        }
    }

    /**
     *	The test method for the /api/folder*
     */
    public function test_api_folder()
    {
        // Test /api/folder
        print("Test /api/folder\n");
        $this->get('api/folder');
        $this->assertResponse(200);

        $this->login();
        $this->get('api/folder');
        $this->assertResponse(200);

        //Test /api/folder?filter[is_landing]=1
        print("Test /api/folder?filter[is_landing]=1\n");
        $response = $this->get('/api/folder?filter[is_landing]=1');
        $this->assertResponse(200);
        $hashtags = array();
        $json = json_decode($response, true);

        $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
        if($json) {
            $this->assertTrue( count($json['results']) > 0, 'No folders on landing');
            if(is_array($json['results'])) foreach ($json['results'] as $row) {
                $this->assertTrue( ($row['folder_id'] ? true : false), 'Bad response data. Folder ID is not set for #'.$row['folder_id']);
                $this->assertTrue( ($row['folder_url'] ? true : false), 'Bad response data. Folder URL is not set for #'.$row['folder_id']);
                $this->assertTrue( ($row['folder_name'] ? true : false), 'Bad response data. Folder Name is not set for #'.$row['folder_id']);

                if($row['folder_url']) {
                    print("Test folder_url: ".$row['folder_url']."\n");
                    $this->get($row['folder_url']);
                    $this->assertResponse(200);
                    $this->assertPattern('#<h1>'.$row['folder_name'].'</h1>#msi');
                }

                if(count($hashtags) < 4 && is_array($row['hashtags']) && $row['hashtags'][0] && !isset($hashtags[$row['hashtags'][0]['id']])) {
                    $hashtags[$row['hashtags'][0]['id']] = $row['hashtags'][0];
                }
            }
        }

        // Test /api/folder?filter[hashtag_id]={HASHTAG_ID}
        if(count($hashtags) == 0) {
            $hashtags[573] = array('id' => 573, 'hashtag' => '#Aww');
        }
        foreach ($hashtags as $hashtag) {
            print('/api/folder?filter[hashtag_id]='.$hashtag['id']."\n");
            $response = $this->get('/api/folder?filter[hashtag_id]='.$hashtag['id']);
            $this->assertResponse(200);
            $json = json_decode($response, true);

            $this->assertTrue( ($json ? true : false), 'Bad response data. Expects to be in JSON format');
            if($json) {
                $this->assertTrue( count($json['results']) > 0, 'No folders for hashtag: '.$hashtag['hashtag']);
                if(is_array($json['results'])) foreach ($json['results'] as $row) {
                    $this->assertTrue( ($row['folder_id'] ? true : false), 'Bad response data. Folder ID is not set for #'.$row['folder_id']);
                    $this->assertTrue( ($row['folder_url'] ? true : false), 'Bad response data. Folder URL is not set for #'.$row['folder_id']);
                    $this->assertTrue( ($row['folder_name'] ? true : false), 'Bad response data. Folder Name is not set for #'.$row['folder_id']);
                }
            }
        }
    }

    protected function get_test_user() {
        return $this->db_interface->db->where_in('email', $this->config['api_data']['users'])->get('users')->row_array();
    }
}