<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public $timestamps = false;

    public function getAllFollowers($followers_on_page)
    {
        return Follower::paginate($followers_on_page);
    }

    public function getFollowersAndFollowing($followers_on_page)
    {
        return Follower::where([
            ['is_follower', '=', 1],
            ['is_following', '=', 1]
        ])->paginate($followers_on_page);
    }

    public function getFollowers($followers_on_page)
    {
        return Follower::where([
            ['is_follower', '=', 1],
            ['is_following', '=', 0]
        ])->paginate($followers_on_page);
    }

    public function getFollowing($followers_on_page)
    {
        return Follower::where([
            ['is_follower', '=', 0],
            ['is_following', '=', 1]
        ])->paginate($followers_on_page);
    }

    public function getPastFollowers($followers_on_page)
    {
        return Follower::where([
            ['is_follower', '=', 0],
            ['was_follower', '=', 1]
        ])->paginate($followers_on_page);
    }

    public function getPastFollowing($followers_on_page)
    {
        return Follower::where([
            ['is_following', '=', 0],
            ['was_following', '=', 1]
        ])->paginate($followers_on_page);
    }
}
