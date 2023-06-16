<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $tests = Test::where('uid', Auth::id())->get();
        return view('test', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $test = Test::create($request->all());
        return $test;
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        // echo $test->id;
        $questions = Question::where('test_id', $test->id) -> get();
        $answers = Answer::where('test_id', $test->id) -> get();
        return view('question', compact('test', 'questions', 'answers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //echo "hello";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        if ($test) {
            $test->update($request->all());
            return response()->json($test, 200);
        } else {
            return response()->json(['error' => 'Test not found'], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        $test->delete();
        return response()->json(['success' => 'Тест успешно удален'], 200);
    }

    public function parseQuestion(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Получите расширение файла
            $extension = $file->getClientOriginalExtension();
            // Укажите разрешенные форматы файлов
            $allowedExtensions = ['gift'];
            // Проверьте, соответствует ли формат файла разрешенным форматам
            if (in_array($extension, $allowedExtensions)) {
                $test = new Test();
                $test->uid = $request->get('uid');
                $test->title = $request->get('title');
                $test->save();
                $fileContent = file_get_contents($file->getRealPath());
                $lines = explode("\n", $fileContent);
                $testData = [
                    'qwests' => [],
                    'timers' => [],
                ];
            
                $timer_f = false;
                $qwest_n = -1;
                $timer = 0;
            
                foreach ($lines as $line) {
                    // Таймер
                    if (preg_match("/^\/\/\s*!\s*\d*/", $line)) {
                        $timer = (int) preg_replace("/\/\/\s*!/", "", $line);
                        $timer_f = true;
                    }
    
                    // Вопрос
                    if (preg_match("/^::/", $line)) {
                        $qwest_n += 1;
                        $qwest = preg_replace("/::/", "", $line);
                        $qwest = substr($qwest, 0, strlen($qwest) - 2) . '?';
                        $testData['qwests'][] = ['qwest' => $qwest];
                        $testData['qwests'][$qwest_n]['variants'] = [];
                        if ($timer_f) {
                            $testData['timers'][] = (int) $timer;
                            $timer_f = false;
                        } else {
                            $testData['timers'][] = 20; //  Если таймера нет, сделать обычным значением 20 секунд
                        }
                    }
            
                    // Определить варианты ответа
                    if (preg_match("/~/", $line)) {
                        $variant = preg_replace("/\s*~/", "", $line);
                        $testData['qwests'][$qwest_n]['variants'][] = $variant;
                    }
            
                    // Определить правильный ответ
                    if (preg_match("/=/", $line)) {
                        $variant = preg_replace("/\s*=/", "", $line);
                        $testData['qwests'][$qwest_n]['variants'][] = $variant;
                        $testData['qwests'][$qwest_n]['answer'] = array_search($variant, $testData['qwests'][$qwest_n]['variants']) + 1;
                    }
                }
        
                foreach ($testData['qwests'] as $index => $qwest) {
                    $question = new Question();
                    $question->title = $qwest['qwest'];
                    $question->timer = $testData['timers'][$index];
                    $question->test_id = $test->id;
                    $question->save();
                    foreach ($qwest['variants'] as $variantIndex => $variant) {
                        $answer = new Answer();
                        $answer->title = $variant;
                        
                        if ($variantIndex + 1 == $qwest['answer']) {
                            $answer->status = "true";
                        } else {
                            $answer->status = "false";
                        }
                        
                        $answer->question_id = $question->id;
                        $answer->test_id = $test->id;
                        $answer->save();
                    }
                }
                return response()->json($test, 200);
            }
            
            return response()->json(['message' => 'Недопустимый формат файла'], 400);
        }
        
        return response()->json(['message' => 'Файл не найден'], 400);
    }
    
    public function test(Request $request)
    {
       

    }
    // Вызываем функцию и получаем результат
    
    // Пример обращения к данным

    
}