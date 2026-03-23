@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Stats Section -->
        <div class="bg-white p-6 rounded-lg shadow-md col-span-3 grid grid-cols-3 gap-4 border-l-4 border-indigo-500">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Students</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Passed Quizzes</h3>
                <p class="text-3xl font-bold text-green-600">{{ $passedQuizzes }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Completed Videos</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $completedVideos }}</p>
            </div>
        </div>

        <!-- Create Batch -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Create New Batch</h3>
            <form method="POST" action="{{ route('admin.batch.create') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Batch Name (e.g. CSE_2026)</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">Create
                    Batch</button>
            </form>

            <h4 class="mt-6 font-semibold text-sm">Existing Batches</h4>
            <ul class="mt-2 text-sm divide-y">
                @foreach($batches as $batch)
                    <li class="py-2 flex items-center justify-between">
                        <span>{{ $batch->name }} <span class="font-mono bg-gray-100 px-2 rounded ml-2">{{ $batch->token }}</span></span>
                        <form method="POST" action="{{ route('admin.batch.delete', $batch->id) }}" onsubmit="return confirm('Are you sure you want to delete this batch?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Upload Video -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Upload/Add Video</h3>
            <form method="POST" action="{{ route('admin.video.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Title</label>
                    <input type="text" name="title" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Video URL (Optional)</label>
                    <input type="url" name="video_url" placeholder="https://youtube.com/..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Or Upload File</label>
                    <input type="file" name="video_file" accept="video/mp4,video/x-m4v,video/*" class="w-full text-sm">
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">Add
                    Video</button>
            </form>

            <h4 class="mt-6 font-semibold text-sm">Existing Videos</h4>
            <ul class="mt-2 text-sm divide-y">
                @foreach($videos as $video)
                    <li class="py-2 flex items-center justify-between">
                        <span class="truncate pr-2">{{ $video->title }}</span>
                        <form method="POST" action="{{ route('admin.video.delete', $video->id) }}" onsubmit="return confirm('Are you sure you want to delete this video?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Create Quiz -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Create Quiz (10 Questions)</h3>
            <form method="POST" action="{{ route('admin.quiz.create') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Quiz Title</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Passing Percentage</label>
                    <input type="number" name="passing_percentage" value="50" min="1" max="100" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                </div>

                <div class="h-48 overflow-y-auto mb-4 border p-2 rounded bg-gray-50 text-sm">
                    @for($i = 0; $i < 10; $i++)
                        <div class="mb-4 border-b pb-2">
                            <label class="font-bold">Q{{ $i + 1 }}</label>
                            <input type="text" name="questions[{{$i}}][question]" placeholder="Question text" required
                                class="w-full border rounded px-2 py-1 mb-1">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="questions[{{$i}}][option_a]" placeholder="Option A" required
                                    class="border rounded px-2 py-1">
                                <input type="text" name="questions[{{$i}}][option_b]" placeholder="Option B" required
                                    class="border rounded px-2 py-1">
                                <input type="text" name="questions[{{$i}}][option_c]" placeholder="Option C" required
                                    class="border rounded px-2 py-1">
                                <input type="text" name="questions[{{$i}}][option_d]" placeholder="Option D" required
                                    class="border rounded px-2 py-1">
                            </div>
                            <label class="block mt-1">Correct Answer:</label>
                            <select name="questions[{{$i}}][correct_answer]" class="border rounded px-2 py-1 h-8">
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                    @endfor
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">Create
                    Quiz</button>
            </form>

            <h4 class="mt-6 font-semibold text-sm">Existing Quizzes</h4>
            <ul class="mt-2 text-sm divide-y">
                @foreach($quizzes as $quiz)
                    <li class="py-2 flex items-center justify-between">
                        <span class="truncate pr-2">{{ $quiz->title }}</span>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.quiz.edit', $quiz->id) }}" class="text-blue-500 hover:text-blue-700 text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.quiz.delete', $quiz->id) }}" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Students List -->
        <div class="bg-white p-6 rounded-lg shadow-md col-span-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold border-b pb-2">Students Progress</h3>

                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex">
                    <select name="batch_id" class="border rounded py-1 px-2 text-sm mr-2">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-200 px-3 rounded py-1 text-sm hover:bg-gray-300">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 text-sm font-semibold">Name</th>
                            <th class="p-3 text-sm font-semibold">Email</th>
                            <th class="p-3 text-sm font-semibold">Batch</th>
                            <th class="p-3 text-sm font-semibold">Video Status</th>
                            <th class="p-3 text-sm font-semibold">Quiz Status</th>
                            <th class="p-3 text-sm font-semibold">Eligible for Cert.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $userVideosCompleted = 0;
                                foreach ($student->progress as $prog) {
                                    if ($prog->completed)
                                        $userVideosCompleted++;
                                }

                                $userQuizzesPassed = 0;
                                foreach ($student->results as $res) {
                                    if ($res->passed)
                                        $userQuizzesPassed++;
                                }

                                $isEligible = (count($videos) > 0 && count($quizzes) > 0) && ($userVideosCompleted >= count($videos)) && ($userQuizzesPassed >= count($quizzes));
                            @endphp
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm">{{ $student->name }}</td>
                                <td class="p-3 text-sm">{{ $student->email }}</td>
                                <td class="p-3 text-sm"><span
                                        class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs">{{ $student->batch->name }}</span>
                                </td>
                                <td class="p-3 text-sm">
                                    {{ $userVideosCompleted }} / {{ count($videos) }} Completed
                                </td>
                                <td class="p-3 text-sm">
                                    {{ $userQuizzesPassed }} / {{ count($quizzes) }} Passed
                                </td>
                                <td class="p-3 text-sm">
                                    @if($isEligible)
                                        <span class="text-green-600 font-bold">Yes</span>
                                    @else
                                        <span class="text-red-500">No</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if(count($students) === 0)
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No students found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection