<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follower;

class FollowerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $dir_path   = '../resources/instagramUsers/';
    private $pagination_attr = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function displayAllFollowers(Request $request)
    {
        $this->beforeDisplay();
        $followers = $this->followersListToDisplay($request);
        return view('followers')->with('followers', $followers);
    }

    public function displayFollowersAndFollowing(Request $request)
    {
        $this->beforeDisplay();
        $followers = $this->followersListToDisplay($request);
        return view('followers')->with('followers', $followers);
    }

    public function displayFollowers(Request $request)
    {
        $this->beforeDisplay();
        $followers = $this->followersListToDisplay($request);
        return view('followers')->with('followers', $followers);
    }

    // public function displayFollowing(Request $request)
    // {
    //     $this->beforeDisplay();
    //     $followers = $this->followersListToDisplay($request);
    //     return view('followers')->with('followers', $followers);
    // }

    public function displayPastFollowers(Request $request)
    {
        $this->beforeDisplay();
        $followers = $this->followersListToDisplay($request);
        return view('followers')->with('followers', $followers);
    }

    public function displayPastFollowing(Request $request)
    {
        $this->beforeDisplay();
        $followers = $this->followersListToDisplay($request);
        return view('followers')->with('followers', $followers);
    }

    private function beforeDisplay()
    {
        $prepared_followers_list = $this->prepareFollowersList($this->dir_path);
        $this->updateFollowersInDb($prepared_followers_list);
        $this->updateFollowing();
    }

    private function prepareFollowersList(string $dir_path) : array
    {
        $dir_path = $dir_path.'followers/';
        $filesList = scandir($dir_path);
        $followers = [];

        foreach($filesList as $file){
			if(!($file === '.' OR $file === '..')){
				$users = file_get_contents($dir_path.$file);
				$users = json_decode($users);
				foreach($users->data->user->edge_followed_by->edges as $user){
					$end_follower['instagramID'] = intval($user->node->id);
					$end_follower['username'] = $user->node->username;
                    $end_follower['full_name'] = $user->node->full_name;
                    $end_follower['is_follower'] = true;
					$followers[] = $end_follower;
				}
			}
        }
        return $followers;
    }

    private function updateFollowersInDb(array $prepared_followers_list)
    {
        foreach($prepared_followers_list as $follower){
            $exists_in_db = (int)Follower::where('instagramID', '=', $follower['instagramID'])->get()->count();
                if($exists_in_db == 0){
                    Follower::insert([
                        [
                            'instagramID' => $follower['instagramID'],
                            'username' => $follower['username'],
                            'full_name' => $follower['full_name'],
                            'is_follower' => $follower['is_follower'],
                            'is_following' => false,
                            'was_follower' => false,
                            'was_following' => false
                        ]
                    ]);
                } else if($follower['is_follower'] == true){
                    Follower::where('instagramID', $follower['instagramID'])->update(['is_follower' => 1]);
                }
        }

        $this->findArchiveFollowers($prepared_followers_list);

    }

    private function findArchiveFollowers(array $prepared_followers_list)
    {
        $followersDb = Follower::where('is_follower', 1)->get();

        $followersDbInstagramIDs = [];
        foreach($followersDb as $instaID){
            array_push($followersDbInstagramIDs, $instaID->instagramID);
        }

        $followersActiveInstagramIDs = [];
        foreach($prepared_followers_list as $instaID){
            array_push($followersActiveInstagramIDs, $instaID['instagramID']);
        }

        $idFollowersToDelete = array_diff($followersDbInstagramIDs, $followersActiveInstagramIDs);

        foreach($idFollowersToDelete as $toDelete){
            Follower::where('instagramID', $toDelete)->update(['is_follower' => 0, 'was_follower' => 1]);
        }
    }

    private function followersListToDisplay(Request $request)
    {
        $followersList = new Follower();
        $url_path = $request->route()->getName();
        $followers = null;

        switch ($url_path)
        {
            case 'followers-and-following':
                $followers = $followersList->getFollowersAndFollowing($this->pagination_attr);
            break;

            case 'followers':
                $followers = $followersList->getFollowers($this->pagination_attr);
            break;

            case 'following':
                $followers = $followersList->getFollowing($this->pagination_attr);
            break;

            case 'past-followers':
                $followers = $followersList->getPastFollowers($this->pagination_attr);
            break;

            case 'past-following':
                $followers = $followersList->getPastFollowing($this->pagination_attr);
            break;

            default:
                $followers = $followersList->getAllFollowers($this->pagination_attr);
            break;
        }

        return $followers;
    }

    private function updateFollowing()
    {
        $prepared_following_list = $this->prepareFollowingList($this->dir_path);
        $this->updateFollowingInDb($prepared_following_list);
    }

    private function prepareFollowingList(string $dir_path) : array
    {
        $dir_path = $dir_path.'following/';
        $filesList = scandir($dir_path);
        $following = [];

        foreach($filesList as $file){
			if(!($file === '.' OR $file === '..')){
				$users = file_get_contents($dir_path.$file);
                $users = json_decode($users);
				foreach($users->data->user->edge_follow->edges as $user){
					$end_following['instagramID'] = intval($user->node->id);
					$end_following['username'] = $user->node->username;
                    $end_following['full_name'] = $user->node->full_name;
                    $end_following['is_following'] = true;
					$following[] = $end_following;
				}
			}
        }
        return $following;
    }

    private function updateFollowingInDb(array $prepared_following_list)
    {
        foreach($prepared_following_list as $following){
            $exists_in_db = (int)Follower::where('instagramID', '=', $following['instagramID'])->get()->count();
                if($exists_in_db == 0){
                    Follower::insert([
                        [
                            'instagramID' => $following['instagramID'],
                            'username' => $following['username'],
                            'full_name' => $following['full_name'],
                            'is_following' => $following['is_following'],
                            'is_follower' => false,
                            'was_following' => false,
                            'was_follower' => false
                        ]
                    ]);
                } else if($following['is_following'] == true){
                    Follower::where('instagramID', $following['instagramID'])->update(['is_following' => 1]);
                }
        }

        $this->findArchiveFollowing($prepared_following_list);

    }

    private function findArchiveFollowing(array $prepared_following_list)
    {
        $followingDb = Follower::where('is_following', 1)->get();

        $followingDbInstagramIDs = [];
        foreach($followingDb as $instaID){
            array_push($followingDbInstagramIDs, $instaID->instagramID);
        }

        $followingActiveInstagramIDs = [];
        foreach($prepared_following_list as $instaID){
            array_push($followingActiveInstagramIDs, $instaID['instagramID']);
        }

        $idFollowingToDelete = array_diff($followingDbInstagramIDs, $followingActiveInstagramIDs);

        foreach($idFollowingToDelete as $toDelete){
            Follower::where('instagramID', $toDelete)->update(['is_following' => 0, 'was_following' => 1]);
        }
    }
}
