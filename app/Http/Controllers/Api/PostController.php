<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller as Controller;
use App\Post;
use Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
		$posts = Post::withCount('comments')->orderByDesc('comments_count')->get();
		
		$response = [];
		$counter = 0;
		
		foreach($posts as $post)
		{
			$response[$counter]['post_id'] = $post->id;
			$response[$counter]['post_title'] = $post->title;
			$response[$counter]['post_body'] = $post->body;
			$response[$counter]['total_number_of_comments'] = $post->comments->count();
			$counter++;
		}
		
		return $response;
    }
	
	public function show(Request $request)
    {
		$post_id = $request->post_id;
		$post = Post::find($post_id);
		if(!$post)
		{
			$response = [
				'message' => 'Record not found.',
				'status' => false,
			];
			return response()->json($response, 400);
		}
		
		// $response = [];
		$response['post_id'] = $post->id;
		$response['post_title'] = $post->title;
		$response['post_body'] = $post->body;
		$response['total_number_of_comments'] = $post->comments->count();
		
		return $response;
    }
}