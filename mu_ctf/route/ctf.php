<?php

/*
* Copyright (C) 2018 www.muruoxi.com
*/

!defined('DEBUG') AND exit('Access Denied.');

// SEO
$header['title'] = $conf['sitename']; 				// site title
$header['keywords'] = ''; 					// site keyword
$header['description'] = $conf['sitebrief']; 			// site description

if(param(0)==='ctf'){
    if(param(1)==='team_score'){
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_score.htm');
    }
    elseif(param(1)==='team_join'){
        if(empty(user_token_get())){
            //header("Location: ".url('../../user-create'));
            http_location(url('user-create'));
        }
        //user_login_check();
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_join.htm');
    }
    elseif(param(1)==='team_found'){
        if(empty(user_token_get())){
            //header("Location: ".url('../../user-create'));
            http_location(url('user-create'));
        }
        if($method ==='GET'){
            include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-team_found.htm');
        }
        elseif($method === 'POST'){
            $avatar = param('upfile','',false);
            $name = db_maxid('ctf','id') +1;
            $size = strlen($avatar);
            if($size > 0){
                $avatar = json_decode($avatar);
                $avatar = $avatar->data;
                $avatar = base64_decode_file_data($avatar);
                $size = strlen($avatar);
                $size > 40000 AND message(-1, lang('filesize_too_large', array('maxsize'=>'40K', 'size'=>$size)));
                $name === FALSE AND message(-1,lang('sql_create_error'));
                $filename = "$name.png";
                $dir = substr(sprintf("%09d", $name), 0, 3).'/';
                $path = 'plugin/mu_ctf/team/avatar/'.$dir;
                !is_dir($path) AND (mkdir($path, 0777, TRUE) OR message(-2, lang('directory_create_failed')));
                // hook my_avatar_post_save_before.php
                file_put_contents($path.$filename, $avatar) OR message(-1, lang('write_to_file_failed'));
            }
            $isavatar = param('isavatar');
            $avatar_path = "";
            if($isavatar == 1){
                $avatar_path = 'plugin/mu_ctf/team/avatar/'.substr(sprintf("%09d", $name), 0, 3)."/$name.png";
            }
            else{
                $avatar_path = 'plugin/mu_ctf/team/avatar/'.substr(sprintf("%09d", $name), 0, 3)."/avatar.png";
            } 
            $team_name = param('team_name');
            strlen($team_name) == 0 AND message(-1,lang('team_name_is_empty'));
            $count = db_sql_find_one("SELECT COUNT(*) AS num FROM `bbs_ctf` WHERE `name` = '$team_name'");
            $count['num'] > 1 AND message(-1,lang('team_name_repeat'));
            $team_sign = param('team_sign');
            strlen($team_sign) == 0 AND message(-1,lang('team_sign_is_empty'));
            $introduce = param('introduce');
            strlen($introduce) == 0 AND message(-1,lang('introduce_is_empty'));
            $sql = [
                'id'=>$name,
                'user1'=>$uid,
                'name'=>$team_name,
                'sign'=>$team_sign,
                'introduce'=>$introduce,
                'avatar'=>$avatar_path,
                'points'=>0];
            !db_create('ctf', $sql) OR message(-1,lang('sql_create_error'));   
            message(0,lang('create_success'));
        }
    }
    elseif(xn_substr(param(1),0,5)==='teams'){
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-teams.htm');
    }
    else{
        include _include(APP_PATH.'plugin/mu_ctf/view/htm/ctf-index.htm');
    }
}


?>