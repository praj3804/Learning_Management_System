@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold">Edit Quiz: {{ $quiz->title }}</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">&larr; Back to Dashboard</a>
    </div>

    <form method="POST" action="{{ route('admin.quiz.update', $quiz->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Quiz Title</label>
                <input type="text" name="title" value="{{ $quiz->title }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Passing Percentage</label>
                <input type="number" name="passing_percentage" value="{{ $quiz->passing_percentage }}" min="1" max="100" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2">Questions (10)</h3>
        
        <div class="space-y-6">
            @foreach($quiz->questions as $i => $question)
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <input type="hidden" name="questions[{{$i}}][id]" value="{{ $question->id }}">
                    <label class="font-bold block mb-2 text-gray-800">Q{{ $i + 1 }}</label>
                    <input type="text" name="questions[{{$i}}][question]" value="{{ $question->question }}" placeholder="Question text" required class="w-full border rounded px-3 py-2 mb-3 focus:outline-none focus:ring focus:border-indigo-300">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Option A</label>
                            <input type="text" name="questions[{{$i}}][option_a]" value="{{ $question->option_a }}" placeholder="Option A" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Option B</label>
                            <input type="text" name="questions[{{$i}}][option_b]" value="{{ $question->option_b }}" placeholder="Option B" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Option C</label>
                            <input type="text" name="questions[{{$i}}][option_c]" value="{{ $question->option_c }}" placeholder="Option C" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Option D</label>
                            <input type="text" name="questions[{{$i}}][option_d]" value="{{ $question->option_d }}" placeholder="Option D" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300">
                        </div>
                    </div>
                    
                    <label class="block text-sm font-semibold mb-1">Correct Answer:</label>
                    <select name="questions[{{$i}}][correct_answer]" class="border rounded px-3 py-2 w-full md:w-1/3 focus:outline-none focus:ring focus:border-indigo-300">
                        <option value="a" {{ $question->correct_answer == 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ $question->correct_answer == 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ $question->correct_answer == 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ $question->correct_answer == 'd' ? 'selected' : '' }}>D</option>
                    </select>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 shadow-md">
                Update Quiz
            </button>
        </div>
    </form>
</div>
@endsection
