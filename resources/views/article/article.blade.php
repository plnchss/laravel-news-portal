@extends('layout')
@section('content')

 @if(session()->has('message'))
  <div class="alert alert-success" role="alert">
      {{session('message')}}
  </div>
@endif
<table class="table">
  <thead>
    <tr>
      <th scope="col">Дата публикации</th>
      <th scope="col">Заголовок</th>
      <th scope="col">Текст</th>
      <th scope="col">Автор</th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <th scope="row">{{$article->date_public}}</th>
      <td><a href="/article/{{$article->id}}">{{$article->title}}</a></td>
      <td>{{$article->text}}</td>
      <td>{{\App\Models\User::findOrFAil($article->users_id)->name}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{$articles->links()}}
@endsection