<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Jobs\VeryLongJob;
use App\Notifications\NewCommentNotify;



class CommentController extends Controller
{
    public function index()
{
    $comments = Comment::where('accept', 0)
        ->latest()
        ->paginate(10);

    return view('comment.index', compact('comments'));
}



    public function store(Request $request){

        $request->validate([
            'text'=>'min:10|required',
        ]);
        $article = Article::FindOrFail($request->article_id);
        $comment = new Comment;
        $comment-> text = $request->text;
        $comment->article_id = $request->article_id;
        $comment->user_id = auth()->id();
        if($comment->save()){
            VeryLongJob::dispatch($article, $comment, auth()->user()->name);
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments_*[0-9]'])->get();
            foreach($keys as $param){
                Cache::forget($param->key);
            }
        }
        return redirect()->route('article.show', $request->article_id)->with('message', "Comment add succesful and enter for moderation");
    }

    public function edit(Comment $comment)
    {
    Gate::authorize('comment', $comment);

    return view('comment.edit', compact('comment'));
    }


   public function update(Request $request, Comment $comment)
   {
    Gate::authorize('comment', $comment);

    $request->validate([
        'text' => 'required|min:5',
    ]);

    $comment->text = $request->text;
    $comment->save();

    Cache::flush();

    return redirect()
        ->route('article.show', $comment->article_id)
        ->with('message', 'Comment updated');
    }


    public function delete(Comment $comment){
    Gate::authorize('comment', $comment);

    $articleId = $comment->article_id;
    $comment->delete();

    Cache::flush();

    return redirect()
        ->route('article.show', $articleId)
        ->with('message', 'Comment deleted');
    }


   public function accept(Comment $comment)
{
    // Отмечаем комментарий как одобренный
    $comment->accept = true;
    $article = Article::findOrFail($comment->article_id);

    if ($comment->save()) {
        // Очищаем кэш комментариев
        Cache::flush();

        // Получаем всех читателей (role = 'reader'), кроме автора комментария
        $readers = User::where('role', 'reader')
            ->where('id', '!=', $comment->user_id)
            ->get();

        // Получаем всех модераторов (role = 'moderator')
        $moderators = User::where('role', 'moderator')->get();

        // Отправляем уведомление каждому модератору
        foreach ($moderators as $moderator) {
            $moderator->notify(new NewCommentNotify($article->title, $article->id));
        }
        foreach ($readers as $reader) {
            $reader->notify(new NewCommentNotify($article->title, $article->id));
}

    }

    return redirect()->route('comment.index')
        ->with('message', 'Комментарий одобрен и уведомления отправлены.');
}


    public function reject(Comment $comment)
{
    $comment->delete();
    Cache::flush();
    return redirect()->route('comment.index');
}


}