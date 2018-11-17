<?php

/*
* Copyright (C) 2018 www.muruoxi.com
*/

!defined('DEBUG') AND exit('Access Denied.');

// SEO
$header['title'] = $conf['sitename']; 				// site title
$header['keywords'] = ''; 					// site keyword
$header['description'] = $conf['sitebrief']; 			// site description



if(param(0)=='ctf'){
    if(param(1)=='team_score'){
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_score.htm');
    }
    else if(param(1)=='team_join'){
        if(empty(user_token_get())){
            //header("Location: ".url('../../user-create'));
            http_location(url('user-create'));
        }
        //user_login_check();
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_join.htm');
    }
    else if(param(1)=='team_found'){
        if(empty(user_token_get())){
            //header("Location: ".url('../../user-create'));
            http_location(url('user-create'));
        }
        if($method =='GET'){
            include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_found.htm');
        }
        elseif($method == 'POST'){
            $team_name = param('team_name');
            $team_sign = param('team_sign');
            $introduce = param('introduce');
            $avatar = param('data', '', FALSE);
            empty($avatar) AND message(-1, lang('avatar_is_empty'));
            $avatar = base64_decode_file_data($avatar);
            $size = strlen($avatar);
            $size > 40000 AND message(-1, lang('filesize_too_large', array('maxsize'=>'40K', 'size'=>$size)));
            $name = xn.rand(8);
            $filename = "$name.png";
            $dir = substr(sprintf("%09d", $name), 0, 3).'/';
            $path = 'plugin/mu_ctf/team/avatar/'.$dir;
            $url = 'plugin/mu_ctf/team/avatar/'.$dir.$filename;
            !is_dir($path) AND (mkdir($path, 0777, TRUE) OR message(-2, lang('directory_create_failed')));
            
            // hook my_avatar_post_save_before.php
            file_put_contents($path.$filename, $avatar) OR message(-1, lang('write_to_file_failed'));
            
            $team_info = ['team_name'=>$team_name]; 
            $sql = ['team_name'=>$team_name];
            !db_create('ctf', $sql) AND message(-1,lang('sql_create_error'));   
            exit(json_encode(array('code'=> 0, 'message'=>$team_info)));
        }
    }
    else{
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-index.htm');
    }
}


?>