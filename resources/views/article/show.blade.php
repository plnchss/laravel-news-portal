@extends('layout')

@section('content')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

{{-- ARTICLE --}}
<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title mb-1">{{ $article->title }}</h3>
        <div class="text-muted mb-3">
            {{ $article->date_public }}
        </div>

        <p class="card-text">
            {{ $article->text }}
        </p>

        @can('create')
            <div class="mt-3 d-flex gap-2">
                <a href="/article/{{ $article->id }}/edit"
                   class="btn btn-primary btn-sm">
                    Edit article
                </a>

                <form action="/article/{{ $article->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete article?')">
                        Delete article
                    </button>
                </form>
            </div>
        @endcan
    </div>
</div>

{{-- ADD COMMENT --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Add comment</h5>

        @if($errors->any())
            <ul class="list-group mb-3">
                @foreach($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        @endif

        <form action="/comment" method="POST">
            @csrf
            <input type="hidden" name="article_id" value="{{ $article->id }}">

            <div class="mb-3">
                <textarea name="text"
                          class="form-control"
                          rows="3"
                          placeholder="Write your comment..."></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-sm">
                Save
            </button>
        </form>
    </div>
</div>

{{-- COMMENTS --}}
<h5 class="mb-3">Comments</h5>

@forelse($comments as $comment)
    <div class="card mb-3">
        <div class="card-body">
            <p class="mb-2">
                {{ $comment->text }}
            </p>

            @can('comment', $comment)
                <div class="d-flex gap-2">
                    <a href="/comment/edit/{{ $comment->id }}"
                       class="btn btn-primary btn-sm">
                        Edit
                    </a>

                    <a href="/comment/delete/{{ $comment->id }}"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete comment?')">
                        Delete
                    </a>
                </div>
            @endcan
        </div>
    </div>
@empty
    <p class="text-muted">No comments yet</p>
@endforelse

@endsection
