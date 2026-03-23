<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $videos = Video::all();
        $quizzes = Quiz::all();

        $progress = Progress::where('user_id', $user->id)->get()->keyBy('video_id');
        $results = Result::where('user_id', $user->id)->get()->keyBy('quiz_id');

        // Check if eligible for certificate
        $allVideosCompleted = true;
        foreach ($videos as $video) {
            if (!isset($progress[$video->id]) || !$progress[$video->id]->completed) {
                $allVideosCompleted = false;
                break;
            }
        }

        $allQuizzesPassed = true;
        foreach ($quizzes as $quiz) {
            if (!isset($results[$quiz->id]) || !$results[$quiz->id]->passed) {
                $allQuizzesPassed = false;
                break;
            }
        }

        $isEligibleForCertificate = count($videos) > 0 && count($quizzes) > 0 && $allVideosCompleted && $allQuizzesPassed;

        return view('student.dashboard', compact('videos', 'quizzes', 'progress', 'results', 'isEligibleForCertificate'));
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $progress = Progress::firstOrNew([
            'user_id' => Auth::id(),
            'video_id' => $request->video_id,
        ]);

        $progress->percentage = $request->percentage;
        if ($progress->percentage >= 90) {
            $progress->completed = true;
        }
        $progress->save();

        return response()->json(['success' => true, 'completed' => $progress->completed]);
    }

    public function showQuiz($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        // Prevent re-taking passed quiz or completed
        $result = Result::where('user_id', Auth::id())->where('quiz_id', $id)->first();
        if ($result && $result->passed) {
            return redirect()->route('student.dashboard')->with('error', 'You have already passed this quiz.');
        }

        return view('student.quiz', compact('quiz'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;

        foreach ($quiz->questions as $question) {
            $userAnswer = $request->input('q_' . $question->id);
            if ($userAnswer === $question->correct_answer) {
                $correctAnswers++;
            }
        }

        $percentage = ($correctAnswers / $totalQuestions) * 100;
        $passed = $percentage >= $quiz->passing_percentage;

        $result = Result::updateOrCreate(
            ['user_id' => Auth::id(), 'quiz_id' => $quiz->id],
            ['score' => $correctAnswers, 'percentage' => $percentage, 'passed' => $passed]
        );

        if ($passed) {
            return redirect()->route('student.dashboard')->with('success', 'Congratulations! You passed the quiz.');
        } else {
            return redirect()->route('student.dashboard')->with('error', 'You scored ' . $percentage . '%. You need ' . $quiz->passing_percentage . '% to pass.');
        }
    }

    public function downloadCertificate()
    {
        $user = Auth::user();
        $videos = Video::all();
        $quizzes = Quiz::all();

        $progress = Progress::where('user_id', $user->id)->get()->keyBy('video_id');
        $results = Result::where('user_id', $user->id)->get()->keyBy('quiz_id');

        // Double check eligibility
        $allVideosCompleted = true;
        foreach ($videos as $video) {
            if (!isset($progress[$video->id]) || !$progress[$video->id]->completed) {
                $allVideosCompleted = false;
                break;
            }
        }

        $allQuizzesPassed = true;
        foreach ($quizzes as $quiz) {
            if (!isset($results[$quiz->id]) || !$results[$quiz->id]->passed) {
                $allQuizzesPassed = false;
                break;
            }
        }

        $isEligibleForCertificate = count($videos) > 0 && count($quizzes) > 0 && $allVideosCompleted && $allQuizzesPassed;

        if (!$isEligibleForCertificate) {
            return abort(403, 'You are not eligible for a certificate yet.');
        }

        $pdf = Pdf::loadView('student.certificate', compact('user', 'results'));

        return $pdf->download('certificate.pdf');
    }
}
