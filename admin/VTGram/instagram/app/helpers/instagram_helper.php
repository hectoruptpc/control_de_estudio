<?php
function Instagram_Get_Avatar($username){
    try{
        $sites_html = file_get_contents('https://www.instagram.com/'.$username);

        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        $meta_og_img = null;
        //Get all meta tags and loop through them.
        foreach($html->getElementsByTagName('meta') as $meta) {
            //If the property attribute of the meta tag is og:image
            if($meta->getAttribute('property')=='og:image'){ 
                //Assign the value from content attribute to $meta_og_img
                $meta_og_img = $meta->getAttribute('content');
            }
        }
        return $meta_og_img;
    }catch(Exception $e){
        return BASE."assets/images/noavatar.png";
    }
}

function Instagram_Get_Image($code){
    try{
        $sites_html = file_get_contents('https://www.instagram.com/p/'.$code);

        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        $meta_og_img = null;
        //Get all meta tags and loop through them.
        foreach($html->getElementsByTagName('meta') as $meta) {
            //If the property attribute of the meta tag is og:image
            if($meta->getAttribute('property')=='og:image'){ 
                //Assign the value from content attribute to $meta_og_img
                $meta_og_img = $meta->getAttribute('content');
            }
        }
        return $meta_og_img;
    }catch(Exception $e){
        return BASE."assets/images/noavatar.png";
    }
}

if(!function_exists("INSTAGRAM_TYPE")){
    function INSTAGRAM_TYPE($meadia_type){
        switch ($meadia_type) {
            case 1:
                $type = l('Photo');
                break;
            case 2:
                $type = l('Video');
                break;
        }

        return $type;
    }
}

if(!function_exists("INSTAGRAM_STATUS")){
    function INSTAGRAM_STATUS($status_id){
        switch ($status_id) {
            case 1:
                $json = array(
                    "label" => "primary",
                    "text"  => l('Processing')
                );
                break;
            case 2:
                $json = array(
                    "label" => "success",
                    "text"  => l('Published')
                );
                break;
            case 3:
                $json = array(
                    "label" => "danger",
                    "text"  => l('Failure')
                );
                break;
            case 4:
                $json = array(
                    "label" => "warning",
                    "text"  => l('Repost')
                );
                break;
            case 5:
                $json = array(
                    "label" => "default",
                    "text"  => l('Cancel')
                );
                break;
        }

        return (object)$json;
    }
}

if(!function_exists("INSTAGRAM_STATUS_AUTO")){
    function INSTAGRAM_STATUS_AUTO($status_id){
        switch ($status_id) {
            case 1:
                $json = array(
                    "label" => "success",
                    "text"  => l('Started')
                );
                break;
            case 2:
                $json = array(
                    "label" => "success",
                    "text"  => l('Started')
                );
                break;
            case 3:
                $json = array(
                    "label" => "success",
                    "text"  => l('Started')
                );
                break;
            case 4:
                $json = array(
                    "label" => "success",
                    "text"  => l('Started')
                );
                break;
            case 5:
                $json = array(
                    "label" => "danger",
                    "text"  => l('Stoped')
                );
                break;
        }

        return (object)$json;
    }
}


if(!function_exists("INSTAGRAM_SEARCH_FEED")){
    function INSTAGRAM_SEARCH_FEED($type,$account,$user_search){
        $i = Instagram($account, "");
        $result = array();
        try{
            switch ($type) {
                case "timeline":
                    $timeline_feed = $i->timelineFeed();
                    $result = array();
                    $feeds  = $timeline_feed->feed_items;
                    if(!empty($feeds)){
                        foreach ($feeds as $key => $row) {
                            if(isset($row->media_or_ad)){
                                $result[] = $row->media_or_ad;
                            }
                        }
                    }
                    break;
                case 'popular':
                    $result = $i->getPopularFeed();
                        if($result->status == "ok"){
                            $result = $result->items;
                        }
                    break;
                case 'explore':
                    $explode_feed = $i->explore();
                    if($explode_feed->status == "ok"){
                        $result = array();
                        $feeds = $explode_feed->items;
                        if(!empty($feeds)){
                            foreach ($feeds as $key => $row) {
                                if(isset($row->media)){
                                    $result[] = $row->media;
                                }
                            }
                        }
                    }
                    break;
                case 'tray':
                    $reels_tray_feed = $i->getReelsTrayFeed();
                    if($reels_tray_feed->status == "ok"){
                        $result = $reels_tray_feed->tray[0]->items;
                    }
                    break;
                case 'self':
                    $self_user_feed = $i->getSelfUserFeed();
                    if($self_user_feed->status == "ok"){
                        $result = $self_user_feed->items;
                    }
                    break;
                case 'user':
                    $hashtag_feed = $i->getHashtagFeed($user_search);
                    if($hashtag_feed->status == "ok"){
                        $result = $hashtag_feed->items;
                    }
                    break;
                case 'following':
                    $max_id = ""; 
                    for ($j=0; $j < 10; $j++) { 
                        $followers = $i->getSelfUsersFollowing($max_id);
                        if($followers->status == "ok"){
                            if(!empty($followers->users) && count($result) <= 700){
                                $result = array_merge($result, $followers->users);
                                $max_id = $followers->next_max_id;
                                if($followers->next_max_id == ""){
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
                    }
                    break;
                case 'followers':
                    $max_id = ""; 
                    for ($j=0; $j < 10; $j++) { 
                        $followers = $i->getSelfUserFollowers($max_id);
                        if($followers->status == "ok"){
                            if(!empty($followers->users) && count($result) <= 700){
                                $result = array_merge($result, $followers->users);
                                $max_id = $followers->next_max_id;
                                if($followers->next_max_id == ""){
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
                    }
                    
                    break;
            }

        } catch (Exception $e){
            $result = $e->getMessage();
        }
        
        return $result;


        return $result;
    }
}

if(!function_exists("Instagram_Get_Feed")){
    function Instagram_Get_Feed($i, $type, $keyword = ""){
        $result = false;
        try {
            switch ($type) {
                case 'timeline':
                    $timeline_feed = $i->timelineFeed();
                    $result = array();
                    $feeds  = $timeline_feed->feed_items;
                    if(!empty($feeds)){
                        foreach ($feeds as $key => $row) {
                            if(isset($row->media_or_ad)){
                                $result[] = $row->media_or_ad;
                            }
                        }
                    }
                    break;
                case 'popular':
                    $result = $i->getPopularFeed();
                    if($result->status == "ok"){
                        $result = $result->items;
                    }
                    break;
                case 'explore_tab':
                    $explode_feed = $i->explore();
                    if($explode_feed->status == "ok"){
                        $result = array();
                        $feeds = $explode_feed->items;
                        if(!empty($feeds)){
                            foreach ($feeds as $key => $row) {
                                if(isset($row->media)){
                                    $result[] = $row->media;
                                }
                            }
                        }
                    }
                    break;
                case 'reels_tray':
                    $reels_tray_feed = $i->getReelsTrayFeed();
                    if($reels_tray_feed->status == "ok"){
                        $result = $reels_tray_feed->tray[0]->items;
                    }
                    break;
                case 'your_feed':
                    $self_user_feed = $i->getSelfUserFeed();
                    if($self_user_feed->status == "ok"){
                        $result = $self_user_feed->items;
                    }
                    break;
                case 'tag':
                    $hashtag_feed = $i->getHashtagFeed($keyword);
                    if($hashtag_feed->status == "ok"){
                        $result = $hashtag_feed->items;
                    }
                    break;
                case 'search_tags':
                    $search_tags = $i->searchTags($keyword);
                    if($search_tags->status == "ok"){
                        $result = Instagram_Sort_Tags($search_tags->results);
                    }
                    break;
                case 'search_users':
                    $search_users = $i->searchUsers($keyword);
                    if($search_users->status == "ok"){
                        $result = $search_users->users;
                    }
                    break;
                case 'following':
                    $following = $i->getSelfUsersFollowing();
                    if($following->status == "ok"){
                        $result = $following->users;
                    }
                    break;
                case 'followers':
                    $followers = $i->getSelfUserFollowers();
                    if($followers->status == "ok"){
                        $result = $followers->users;
                    }
                    break;
                case 'feed':
                    $mediaId   = Instagram_Get_Id($keyword);
                    if($mediaId != ""){
                        $feed      = $i->mediaInfo($mediaId);
                        if($feed->status == "ok"){
                            $result = $feed->items[0];
                        }
                    }
                    break;
                case 'feed_by_id':
                    $feed = $i->mediaInfo($keyword);
                    if($feed->status == "ok"){
                        $result = $feed->items[0];
                    }
                    break;
                case 'user_feed':
                    $array_username = explode("|", $keyword);
                    if(count($array_username) == 2){
                        $user_feed = $i->getUserFeed($array_username[0]);
                        if($user_feed->status == "ok"){
                            $result = $user_feed->items;
                        }
                    }
                    break;
                case 'user_following': 
                    $uid = $i->getUsernameId($keyword);
                    if(is_string($uid) || is_numeric($uid)){
                        $following = $i->getUserFollowings($uid);
                        if($following->status == "ok"){
                            $result = $following->users;
                        }
                    }
                    break;
                case 'user_followers':
                    $uid = $i->getUsernameId($keyword);
                    if(is_string($uid) || is_numeric($uid)){
                        $followers = $i->getUserFollowers($uid);
                        if($followers->status == "ok"){
                            $result = $followers->users;
                        }
                    }
                    break;
                case 'following_recent_activity':
                    $followback = $i->getRecentActivity();
                    if($followback->status == "ok"){
                        $result = array();
                        $list = $followback->old_stories;
                        foreach ($list as $key => $row) {
                            if(isset($row->args->inline_follow) && $row->args->inline_follow->following != 1 && $row->args->inline_follow->outgoing_request != 1 && strpos($row->args->text, 'started following you') !== false ){
                                $result[] = $row->args->inline_follow->user_info;
                            }
                        }
                    }
                    break;

                case 'location':
                    $array_location = explode(",", $keyword);
                    if(count($array_location) == 2){
                        $locations = $i->searchLocation($array_location[0], $array_location[1]);
                        if($locations->status == "ok" && !empty($locations->venues)){
                            $locations = $locations->venues;
                            $location = $locations[array_rand($locations)];
                            $location_feed = $i->getLocationFeed($location->external_id);
                            if($location_feed->status == "ok"){
                                $result = $location_feed->items;
                            }
                        }
                    }
                case 'username':
                    $follow_types  = array("user_following","user_followers");
                    $follow_index  = array_rand($follow_types);
                    $follow_type   = $follow_types[$follow_index];
                    switch ($follow_type) {
                        case 'user_following':
                            $array_username = explode("|", $keyword);
                            if(count($array_username) == 2){
                                $following = $i->getUserFollowings($array_username[0]);
                                if($following->status == "ok"){
                                    $result = $following->users;
                                }
                            }
                            break;
                        case 'user_followers':
                            $array_username = explode("|", $keyword);
                            if(count($array_username) == 2){
                                $followers = $i->getUserFollowers($array_username[0]);
                                if($followers->status == "ok"){
                                    $result = $followers->users;
                                }
                            }
                            break;
                    }
                    break;
            }
        } catch (Exception $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
}

if(!function_exists("Instagram_Sort_Tags")){
    function Instagram_Sort_Tags($data){
        usort($data, function($a, $b) {
            if($a->media_count==$b->media_count) return 0;
            return $a->media_count < $b->media_count?1:-1;
        });
        return $data;
    }
}

if(!function_exists("INSTAGRAM_FOLLOW")){
    function INSTAGRAM_FOLLOW($action, $account, $userId){
        $i = Instagram($account, "");
        $result = array();
        switch ($action) {
            case 'follow':
                try{
                    $result = $i->follow($userId);
                }catch(InstagramException $e){
                    return $e->getMessage();
                }
                break;
            case 'unfollow':
                try{
                    $result = $i->unfollow($userId);
                }catch(InstagramException $e){
                    return $e->getMessage();
                }
                break;
        }

        return $result;
    }
}

if(!function_exists("INSTAGRAM_SEARCH")){
    function INSTAGRAM_SEARCH($type,$account,$keyword){
        $i = Instagram($account, "");
        switch ($type) { 
            case 'user':
                try{
                    $result = $i->searchUsers($keyword);
                }catch(InstagramException $e){
                    return $e->getMessage();
                }
                break;
            default:
                try{
                    $search_tags = $i->searchTags($keyword);
                }catch(InstagramException $e){
                    return $e->getMessage();
                }
                
                if($search_tags->status == "ok"){
                    $result = Instagram_Sort_Tags($search_tags->results);
                }
                break;
        }

        return $result;
    }
}

if(!function_exists("Instagram_Login")){
    function Instagram_Login($username, $password){
        try {
            Delete(APPPATH."libraries/InstagramAPI/data/".$username);
            $i = Instagram($username, $password);
            $data = $i->login(true);
            return $i;
        }
        catch ( Exception $e ) {
            $error_arr = $e->getTrace();
            $txt  = $error_arr[0]['args'][0]->error_title;
            $type = $error_arr[0]['args'][0]->error_type;
            if($type == "checkpoint_logged_out"){
                $txt = "Please go to <a href='http://instagram.com/' target='_blank'>http://instagram.com/</a> to verify email and then login at here again";
            }
            return array(
                "txt"   => ($txt != "")?$txt:$type,
                "type"  => $type,
                "label" => "bg-red",
                "st"    => "error",
            );
        }
    }
}

if(!function_exists("Instagram")){ 
    function Instagram($username, $password, $debug = false){
        listIt(APPPATH. "libraries/InstagramAPI/");
        $i = new Instagram(false, false);
        $i->setUser($username, $password);
        try {
            $i->timelineFeed();
        } catch(InstagramException $e){
            if (strpos($e->getMessage(), 'login_required') !== false || strpos($e->getMessage(), 'login-enforced') !== false) {
                try {
                    $result = $i->login(true);
                } catch (Exception $k) {}
            }
        }
        return $i;
    }
}

if(!function_exists("require_load")){
    function require_load($file){
        if(file_exists($file))
            include $file;
    }
}

if(!function_exists("INSTAGRAM_GET_POST")){
    function INSTAGRAM_GET_POST($data){
        $response = array();
        $i = Instagram($data->username, $data->password);
        try {
            $response =$i->mediaInfo($data->result);
        } catch (Exception $e){
            $response = "...";
        }
        return $response;
    }
}

if(!function_exists("INSTAGRAM_POST")){
    function INSTAGRAM_POST($data){
        $response = array();
        $CI = &get_instance();
        $i = Instagram($data->username, $data->password);
        if(!is_string($i)){
            switch ($data->schedule_type) {
                case 'post':
                    switch ($data->type) {
                        case 'photo':
                            try {
                                $response =$i->uploadPhoto($data->image, $data->description);
                            } catch (Exception $e){
                                $response = $e->getMessage();
                            }

                            break;
                        case 'story':
                            try {
                                $response =$i->uploadPhotoStory($data->image, $data->description);
                            } catch (Exception $e){
                                $response = $e->getMessage();
                            }

                            break;
                        case 'video':
                            $url = $data->url;
                            $id = getIdYoutube($data->url);
                            if (strpos($url, 'youtube.com') !== false || strpos($url, 'vimeo.com') !== false) {
                                try{
                                    $videos = VideoDownloader($url);
                                    if(!empty($videos)){
                                        foreach ($videos as $video) {
                                            if($video['format'] == 'mp4'){
                                                try {
                                                    $response =$i->uploadVideo($video['url'], $data->description);
                                                } catch (Exception $e){
                                                    $response = $e->getMessage();
                                                }
                                                break;
                                            }
                                        }
                                    }else{
                                        $response = array(
                                            "st"  => "error",
                                            "txt" => l("Can't get video")
                                        );
                                    }
                                } catch(Exceptions $e) {
                                    $response = array(
                                        "st"  => "error",
                                        "txt" => l("Can't get video")
                                    );
                                }

                            }else{
                                if (strpos($url, 'facebook.com') != false) {
                                    $url = fbdownloadVideo($url);
                                }

                                try {
                                    $response =$i->uploadVideo($url, $data->description);
                                } catch (Exception $e){
                                    $response = $e->getMessage();
                                }
                            }

                            break;
                    }

                    if(isset($response->status) && $response->status == "ok"){
                        $response = array(
                            "status"  => "success",
                            "id"      => $response->media->pk,
                            "code"    => $response->media->code
                        );
                    }
                    break;

                case 'comment':
                    try {
                        $response = $i->comment($data->media_id, $data->description);
                        if($response->status == "ok"){
                            $response = array(
                                "status"  => "success",
                                "code"    => $data->code
                            );
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;

                case 'auto_comment':

                    $target = $data->title;
                    switch ($target) {
                        case 2:
                            try {
                                $feeds  = Instagram_Get_Feed($i, "location", $data->description);
                                if(!empty($feeds) && is_array($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                    if(empty($history)){
                                        $response = $i->comment($feed->pk, $data->image);
                                        //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                        if($response->status == "ok"){
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $feed->pk,
                                                "name"         => $feed->code,
                                                "type"         => "comment",
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed->user),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 3:
                            try {
                                $follow_types  = array("user_following","user_followers"); //
                                $follow_index  = array_rand($follow_types);
                                $follow_type   = $follow_types[$follow_index];

                                $list_username   = explode(",", $data->description);
                                $username_index  = array_rand($list_username);
                                $username        = $list_username[$username_index];
                                $users  = Instagram_Get_Feed($i, $follow_type, $username);
                                if(!empty($users)){
                                    $index  = array_rand($users);
                                    $user   = $users[$index];

                                    $feeds  = Instagram_Get_Feed($i, "user_feed", $user->pk."|".$user->username);
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                    if(empty($history)){
                                        $response = $i->comment($feed->pk, $data->image);
                                        //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                        if($response->status == "ok"){
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $feed->pk,
                                                "name"         => $feed->code,
                                                "type"         => "comment",
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed->user),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }

                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                        
                        case 1:
                            try {
                                $list_tag   = explode(",", $data->description);
                                $tag_index  = array_rand($list_tag);
                                $tag        = $list_tag[$tag_index];

                                $feeds  = Instagram_Get_Feed($i, "tag", $tag);
                                $index  = array_rand($feeds);
                                $feed   = $feeds[$index];
                                $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                if(empty($history)){
                                    $response = $i->comment($feed->pk, $data->image);
                                    //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                    if($response->status == "ok"){
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $feed->pk,
                                            "name"         => $feed->code,
                                            "type"         => "comment",
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                        $response = array(
                                            "st"      => "success",
                                            "data"    => json_encode($feed->user),
                                            "code"    => $feed->code,
                                            "txt"     => l('Successfully')
                                        );
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }
                    return $response;
                    break;

                case 'message':
                    try {
                        $response = $i->direct_message($data->media_id, $data->description);
                        $response = array(
                            "status"  => "success"
                        );
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'like':
                    try {
                        $response = $i->like($data->media_id);
                        if($response->status == "ok"){
                            $response = array(
                                "status"  => "success",
                                "code"    => $data->code
                            );
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;

                case 'auto_like':
                    $target = $data->title;
                    switch ($target) {
                        case 2:
                            try {
                                $feeds  = Instagram_Get_Feed($i, "location", $data->description);
                                if(!empty($feeds) && is_array($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                    if(empty($history)){
                                        $reponse = $i->like($feed->pk);
                                        //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                        if($reponse->status == "ok"){
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $feed->pk,
                                                "name"         => $feed->code,
                                                "type"         => "like",
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed->user),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 3:
                            try {
                                $follow_types  = array("user_following","user_followers"); //
                                $follow_index  = array_rand($follow_types);
                                $follow_type   = $follow_types[$follow_index];

                                $list_username   = explode(",", $data->description);
                                $username_index  = array_rand($list_username);
                                $username        = $list_username[$username_index];
                                $users  = Instagram_Get_Feed($i, $follow_type, $username);
                                if(!empty($users)){
                                    $index  = array_rand($users);
                                    $user   = $users[$index];

                                    $feeds  = Instagram_Get_Feed($i, "user_feed", $user->pk."|".$user->username);
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                    if(empty($history)){
                                        $reponse = $i->like($feed->pk);
                                        //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                        if($reponse->status == "ok"){
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $feed->pk,
                                                "name"         => $feed->code,
                                                "type"         => "like",
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed->user),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }

                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                        
                        case 1:
                            try {
                                $list_tag   = explode(",", $data->description);
                                $tag_index  = array_rand($list_tag);
                                $tag        = $list_tag[$tag_index];

                                $feeds  = Instagram_Get_Feed($i, "tag", $tag);
                                $index  = array_rand($feeds);
                                $feed   = $feeds[$index];
                                $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", $data->schedule_type)->where("account_id", $data->account)->get(INSTAGRAM_FOLLOW_TB)->row();
                                if(empty($history)){
                                    $reponse = $i->like($feed->pk);
                                    //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                    if($reponse->status == "ok"){
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $feed->pk,
                                            "name"         => $feed->code,
                                            "type"         => "like",
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                        $response = array(
                                            "st"      => "success",
                                            "data"    => json_encode($feed->user),
                                            "code"    => $feed->code,
                                            "txt"     => l('Successfully')
                                        );
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }
                    return $response;
                    break;

                case 'follow':
                    $target = $data->title;
                    switch ($target) {
                        case 2:
                            try {
                                $feeds  = Instagram_Get_Feed($i, "location", $data->description);
                                if(!empty($feeds) && is_array($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    if($feed->user->friendship_status->following == "" && $feed->user->friendship_status->outgoing_request == ""){
                                        $follow = $i->follow($feed->user->pk);
                                        if($follow->status == "ok"){
                                            //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                            $CI =& get_instance();
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $feed->user->pk,
                                                "name"         => $feed->user->username,
                                                "type"         => $data->schedule_type,
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed->user),
                                                "code"    => $feed->user->username,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 3:
                            try {
                                $follow_types  = array("user_following","user_followers"); //
                                $follow_index  = array_rand($follow_types);
                                $follow_type   = $follow_types[$follow_index];

                                $list_username   = explode(",", $data->description);
                                $username_index  = array_rand($list_username);
                                $username        = $list_username[$username_index];
                                $users  = Instagram_Get_Feed($i, $follow_type, $username);
                                if(!empty($users)){
                                    $index  = array_rand($users);
                                    $user   = $users[$index];
                                    $info   = $i->userFriendship($user->pk);
                                    if($info->status == "ok"){
                                        if($info->following == "" && $info->outgoing_request == ""){
                                            $follow = $i->follow($user->pk);
                                            //echo "<a href='https://instagram.com/".$user->username."' target='_blank'>".$user->username."</a>";
                                            $CI =& get_instance();
                                            $CI->load->model('Schedule_model', 'schedule_model');
                                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                                "pk"           => $user->pk,
                                                "name"         => $user->username,
                                                "type"         => $data->schedule_type,
                                                "uid"          => $data->uid,
                                                "account_id"   => $data->account,
                                                "account_name" => $data->name,
                                                "created"      => NOW
                                            ));
                                            if($follow->status == "ok"){
                                                $response = array(
                                                    "st"      => "success",
                                                    "data"    => json_encode($user),
                                                    "code"    => $user->username,
                                                    "txt"     => l('Successfully')
                                                );
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                        
                        case 1:
                            try {
                                $list_tag   = explode(",", $data->description);
                                $tag_index  = array_rand($list_tag);
                                $tag        = $list_tag[$tag_index];

                                $feeds  = Instagram_Get_Feed($i, "tag", $tag);
                                $index  = array_rand($feeds);
                                $feed   = $feeds[$index];
                                if($feed->user->friendship_status->following == "" && $feed->user->friendship_status->outgoing_request == ""){
                                    $follow = $i->follow($feed->user->pk);
                                    //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                    if($follow->status == "ok"){
                                        $CI =& get_instance();
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $feed->user->pk,
                                            "name"         => $feed->user->username,
                                            "type"         => $data->schedule_type,
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                        $response = array(
                                            "st"      => "success",
                                            "data"    => json_encode($feed->user),
                                            "code"    => $feed->user->username,
                                            "txt"     => l('Successfully')
                                        );
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }
                    return $response;
                    break;

                case 'followback':  
                    try { 
                        $result = $i->getRecentActivity();
                        if(!empty($result) && $result->status == "ok" && !empty($result->old_stories)){
                            foreach ($result->old_stories as $key => $row) {
                                $text = $row->args->text;
                                if(strpos($text,"started following you.") != "" && $row->args->inline_follow->following == "" && $row->args->inline_follow->outgoing_request == ""){
                                    $response = $i->follow($row->args->profile_id);
                                    if($response->status == "ok"){
                                        if($data->description != ""){
                                            $i->direct_message($row->args->profile_id, $data->description);
                                        }
                                        $CI =& get_instance();
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $row->args->profile_id,
                                            "name"         => $row->args->inline_follow->user_info->username,
                                            "type"         => $data->schedule_type,
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                        $response = $response->status;
                                        break;  
                                    }
                                }
                            }
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'unfollow':
                    try {
                        $result = $i->getSelfUsersFollowing();
                        if(!empty($result) && $result->status == "ok" && !empty($result->users)){
                            $row = $result->users[0];
                            $response = $i->unfollow($row->pk);
                            $response = $response->status;
                            $CI =& get_instance();
                            $CI->load->model('Schedule_model', 'schedule_model');
                            $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                "pk"           => $row->pk,
                                "name"         => $row->username,
                                "type"         => $data->schedule_type,
                                "uid"          => $data->uid,
                                "account_id"   => $data->account,
                                "account_name" => $data->name,
                                "created"      => NOW
                            ));
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
            }

            if(is_string($response)){
                $response = array(
                    "status"  => "error",
                    "message" => $response
                );
            }
        }else{
            $response["message"] = "Upload faild, Please try again";
            $response = array(
                "status"  => "error",
                "message" => $response["message"]
            );
        }
        
        return $response;
        
    }
}
