<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Batch;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Progress;
use App\Models\Result;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $studentRole = Role::where('name', 'Student')->first();

        $query = User::where('role_id', $studentRole->id)
            ->with(['batch', 'progress', 'results']);

        if ($request->has('batch_id') && $request->batch_id != '') {
            $query->where('batch_id', $request->batch_id);
        }

        $students = $query->get();
        $batches = Batch::all();
        $videos = Video::all();
        $quizzes = Quiz::all();

        // Stats
        $totalStudents = User::where('role_id', $studentRole->id)->count();
        $completedVideos = Progress::where('completed', true)->count();
        $passedQuizzes = Result::where('passed', true)->count();

        return view('admin.dashboard', compact('students', 'batches', 'videos', 'quizzes', 'totalStudents', 'completedVideos', 'passedQuizzes'));
    }

    public function createBatch(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:batches']);

        Batch::create([
            'name' => $request->name,
            'token' => Str::random(10), // Generating a unique token
        ]);

        return redirect()->back()->with('success', 'Batch created successfully!');
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|max:102400', // Relaxed mime type check, increased limit to 100MB
        ]);

        if (!$request->video_url && !$request->hasFile('video_file')) {
            return redirect()->back()->with('error', 'Please provide a video URL or upload a file.');
        }

        $video = new Video();
        $video->title = $request->title;

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $video->path = $path;
        } else {
            $video->url = $request->video_url;
        }

        $video->save();

        return redirect()->back()->with('success', 'Video uploaded successfully!');
    }

    public function createQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_percentage' => 'required|integer|min:1|max:100',
            'questions' => 'required|array|min:10|max:10', // Enforce 10 questions
            'questions.*.question' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'passing_percentage' => $request->passing_percentage,
        ]);

        foreach ($request->questions as $qData) {
            $quiz->questions()->create($qData);
        }

        return redirect()->back()->with('success', 'Quiz created successfully!');
    }

    public function deleteBatch($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();
        return redirect()->back()->with('success', 'Batch deleted successfully!');
    }

    public function deleteVideo($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return redirect()->back()->with('success', 'Video deleted successfully!');
    }

    public function editQuiz($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('admin.quiz_edit', compact('quiz'));
    }

    public function updateQuiz(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_percentage' => 'required|integer|min:1|max:100',
            'questions' => 'required|array|min:10|max:10',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.question' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz = Quiz::findOrFail($id);
        $quiz->update([
            'title' => $request->title,
            'passing_percentage' => $request->passing_percentage,
        ]);

        foreach ($request->questions as $qData) {
            $question = Question::findOrFail($qData['id']);
            $question->update($qData);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Quiz updated successfully!');
    }

    public function deleteQuiz($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->back()->with('success', 'Quiz deleted successfully!');
    }
}
