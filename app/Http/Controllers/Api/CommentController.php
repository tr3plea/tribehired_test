<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon as Carbon;
use App\Http\Controllers\Controller as Controller;
use App\Post;
use App\Comment;
use Validator;

class CommentController extends Controller
{
    public function index(Request $request)
    {
		$comments = Comment::keyword($request->keyword)
							->createdDate($request->date)
							->get();
		
		$response = [];
		$counter = 0;
		
		foreach($comments as $comment)
		{
			$response[$counter]['comment_id'] = $comment->id;
			$response[$counter]['post_id'] = $comment->post_id;
			$response[$counter]['comment_message'] = $comment->message;
			$response[$counter]['commented_at'] = Carbon::parse($comment->created_at)->format('Y-m-d H:i:s');
			$counter++;
		}
		
		return $response;
    }
	
	public function store(Request $request)
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
		
		$comment = new Comment;
		$comment->post_id = $post_id;
		$comment->message = $request->message;
		
		if($comment->save())
		{
			$response = [
				'message' => 'Comment has been created.',
				'status' => true,
			];
			return response()->json($response, 200);
			
		}
    }
}