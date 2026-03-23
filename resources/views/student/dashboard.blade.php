@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded-lg shadow-md col-span-2">
        @if($isEligibleForCertificate)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">You have completed all requirements! You can now download your certificate.</span>
            </div>
            <a href="{{ route('student.certificate.download') }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 w-full text-center inline-block">Download Certificate (PDF)</a>
        @else
            <div class="bg-yellow-50 text-yellow-800 p-4 rounded text-sm">
                Complete all assigned videos (90%+) and pass all quizzes (>= passing %) to download your certificate.
            </div>
        @endif
    </div>

    <!-- Course Videos -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">Course Videos</h3>
        @if($videos->isEmpty())
            <p class="text-gray-500 text-sm">No videos available.</p>
        @else
            <ul class="space-y-4">
                @foreach($videos as $video)
                @php
                    $userProgress = $progress[$video->id] ?? null;
                    $percentage = $userProgress ? $userProgress->percentage : 0;
                    $completed = $userProgress ? $userProgress->completed : false;
                @endphp
                <li class="border rounded p-4">
                    <h4 class="font-semibold text-lg">{{ $video->title }}</h4>
                    <div class="flex justify-between text-sm text-gray-600 mt-2 mb-1">
                        <span>Progress</span>
                        <span>{{ number_format($percentage, 0) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                      <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    
                    @if($video->path)
                        <video controls class="w-full rounded mb-2" ontimeupdate="trackProgress(this, {{ $video->id }})">
                            <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($video->url)
                        @php
                            $embedUrl = $video->url;
                            if (str_contains($video->url, 'youtube.com/watch?v=')) {
                                $embedUrl = str_replace('watch?v=', 'embed/', $video->url);
                                $embedUrl = explode('&', $embedUrl)[0];
                            } elseif (str_contains($video->url, 'youtu.be/')) {
                                $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $video->url);
                                $embedUrl = explode('?', $embedUrl)[0];
                            }
                        @endphp
                        
                        @if(str_contains($embedUrl, 'youtube.com/embed/'))
                            <iframe width="100%" height="200" src="{{ $embedUrl }}" frameborder="0" allowfullscreen class="rounded mb-2"></iframe>
                        @else
                            <video controls class="w-full rounded mb-2" ontimeupdate="trackProgress(this, {{ $video->id }})">
                                <source src="{{ $embedUrl }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endif
                    
                    @if(!$completed)
                        <!-- Manual mark complete fallback for all videos -->
                        <button onclick="mockProgress({{ $video->id }})" class="mt-2 bg-gray-200 px-3 py-1 rounded text-sm hover:bg-gray-300 w-full text-center">Mark 100% Complete</button>
                    @endif

                    @if($completed)
                        <span class="text-green-600 text-xs font-bold inline-block mt-2">✓ Completed</span>
                    @endif
                </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Quizzes -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">Quizzes</h3>
        @if($quizzes->isEmpty())
            <p class="text-gray-500 text-sm">No quizzes available.</p>
        @else
            <ul class="space-y-4">
                @foreach($quizzes as $quiz)
                @php
                    $userResult = $results[$quiz->id] ?? null;
                    $passed = $userResult ? $userResult->passed : false;
                @endphp
                <li class="border rounded p-4 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-lg">{{ $quiz->title }}</h4>
                        <p class="text-sm text-gray-500">Passing Score: {{ $quiz->passing_percentage }}%</p>
                    </div>
                    <div>
                        @if($userResult)
                            @if($passed)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Passed ({{ $userResult->percentage }}%)</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold block mb-2 text-center">Failed ({{ $userResult->percentage }}%)</span>
                                <a href="{{ route('student.quiz.show', $quiz->id) }}" class="bg-indigo-600 text-white px-3 py-1 text-sm rounded hover:bg-indigo-700 ml-2">Retake</a>
                            @endif
                        @else
                            <a href="{{ route('student.quiz.show', $quiz->id) }}" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded hover:bg-indigo-700">Attempt</a>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function trackProgress(videoElement, videoId) {
        let duration = videoElement.duration;
        let currentTime = videoElement.currentTime;
        if(duration > 0) {
            let percentage = (currentTime / duration) * 100;
            // Only update occasionally to prevent spamming the server
            if (Math.round(currentTime) % 5 === 0) {
                updateServerProgress(videoId, percentage);
            }
        }
    }

    function mockProgress(videoId) {
        updateServerProgress(videoId, 100);
        location.reload();
    }

    function updateServerProgress(videoId, percentage) {
        fetch("{{ route('student.video.progress') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                video_id: videoId,
                percentage: percentage
            })
        }).then(response => response.json())
          .then(data => {
              if(data.completed) {
                  // reload logic if completed newly could go here
              }
          });
    }
</script>
@endpush
@endsection
