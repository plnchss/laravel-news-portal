<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\Click;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatMail;

class SendStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily site statistics to moderator email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Получаем статистику просмотров статей
        $article_counts = Click::groupBy('article_id')
            ->get()
            ->map(function($item){
                return [
                    'article_title' => $item->article_title ?? 'Нет заголовка',
                    'count' => $item->count() ?? 0
                ];
            })
            ->toArray(); // Приводим к массиву

        // Очищаем таблицу кликов
        Click::whereNotNull('article_id')->delete();

        // Считаем новые комментарии за сегодня
        $comments = Comment::whereDate('created_at', Carbon::today())->count();

        // Отправляем письмо
        Mail::to('p.nazarenko04@mail.ru')->send(new StatMail($comments, $article_counts));

        $this->info('Статистика отправлена на почту p.nazarenko04@mail.ru');
    }
}
