<?php
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;

class TimerController extends Controller
{
    public function updateTimer(Request $request)
    {
        $now = Carbon::now();
        $endTime = $now->addSeconds(30); // Установите конечное время таймера (30 секунд от текущего времени)

        // Обновление значения таймера в сессии
        Session::put('timer_end_time', $endTime);

        // Запустите обновление таймера каждую секунду
        Queue::push(new TimerUpdateJob());

        return response()->json(['message' => 'Timer updated'], 200);
    }
}