<x-mail::message>
# Ежедневная статистика сайта

Количество добавленных комментариев: {{ $countComment }}

Количество просмотров статей: {{ array_sum(array_column($countArticle, 'count')) }}

Просмотрены следующие статьи:
@forelse($countArticle as $value)
- {{ $value['article_title'] }} ({{ $value['count'] }} просмотров)
@empty
- Нет просмотров
@endforelse

<x-mail::button :url="''">
Перейти на сайт
</x-mail::button>

Спасибо,<br>
{{ config('app.name') }}
</x-mail::message>
