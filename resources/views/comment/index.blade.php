@extends('layout')

@section('content')

@if(session()->has('message'))
    <div class="alert alert-success" role="alert">
        {{ session('message') }}
    </div>
@endif

<h3 class="mb-4">Comment moderation</h3>

<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Author</th>
            <th>Article</th>
            <th>Text</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($comments as $comment)
            <tr>
                <td>{{ $comment->created_at }}</td>

                <td>
                    {{ \App\Models\User::find($comment->user_id)->name ?? 'Unknown' }}
                </td>

                <td>
                    @php
                        $article = \App\Models\Article::find($comment->article_id);
                    @endphp
                    @if($article)
                        <a href="{{ route('article.show', $article->id) }}">
                            {{ $article->title }}
                        </a>
                    @else
                        Deleted article
                    @endif
                </td>

                <td style="max-width: 400px;">
                    {{ $comment->text }}
                </td>

                <td>
                    <div class="d-flex flex-row gap-2">
                        <a href="{{ url('/comment/accept/' . $comment->id) }}"
                           class="btn btn-success btn-sm">
                            Accept
                        </a>

                        <a href="{{ url('/comment/reject/' . $comment->id) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Reject this comment?')">
                            Reject
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No comments for moderation
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $comments->links() }}
</div>

@endsection
