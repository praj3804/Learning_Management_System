@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mt-6">
        <h2 class="text-3xl font-bold mb-4 text-center text-gray-800">{{ $quiz->title }}</h2>
        <p class="text-center text-gray-600 mb-8 border-b pb-4">Passing Percentage: {{ $quiz->passing_percentage }}%</p>

        <form method="POST" action="{{ route('student.quiz.submit', $quiz->id) }}">
            @csrf

            <div class="space-y-8">
                @foreach($quiz->questions as $index => $question)
                    <div class="bg-gray-50 border p-6 rounded-lg">
                        <h3 class="font-bold text-lg mb-4">{{ $index + 1 }}. {{ $question->question }}</h3>

                        <div class="space-y-2">
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-white rounded">
                                <input type="radio" name="q_{{ $question->id }}" value="a" required
                                    class="form-radio h-5 w-5 text-indigo-600">
                                <span>A) {{ $question->option_a }}</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-white rounded">
                                <input type="radio" name="q_{{ $question->id }}" value="b"
                                    class="form-radio h-5 w-5 text-indigo-600">
                                <span>B) {{ $question->option_b }}</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-white rounded">
                                <input type="radio" name="q_{{ $question->id }}" value="c"
                                    class="form-radio h-5 w-5 text-indigo-600">
                                <span>C) {{ $question->option_c }}</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-white rounded">
                                <input type="radio" name="q_{{ $question->id }}" value="d"
                                    class="form-radio h-5 w-5 text-indigo-600">
                                <span>D) {{ $question->option_d }}</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                <button type="submit"
                    class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 w-full md:w-auto shadow-lg text-lg">
                    Submit Quiz
                </button>
            </div>
        </form>
    </div>
@endsection