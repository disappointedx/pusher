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

    public function import(Request $request)
    {
        // Получите загруженный файл .gift
        $giftFile = $request->file('gift');
        
        // Проверьте, что файл успешно загружен
        if ($giftFile) {
            $contents = file_get_contents($giftFile->getPathname());
            return response()->json(['message' => 'File imported successfully.']);
        }
        
        // Возвращаем ошибку, если файл не был загружен
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
    public function parseQuestion()
    {
        $filePath = public_path('dates/quiz.gift');
        $fileContent = file_get_contents($filePath);
        $lines = explode("\n", $fileContent);
        $questions = [];
        $answers = [];
        $correctAnswer = '';
        $timer = null;

        foreach ($lines as $line) {
            // if (str_starts_with($line, '::')) {
            //     echo substr($line, 2, -1) . PHP_EOL;
            //     echo '<br>';
            // }
        }

        // return [
        //     'question' => $questionText,
        //     'answers' => $answers,
        //     'correctAnswer' => $correctAnswer,
        //     'timer' => $timer,
        // ];
    }
}

