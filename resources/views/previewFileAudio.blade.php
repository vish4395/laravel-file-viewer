<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 flex flex-col bg-slate-900 p-6 gap-5">
        <canvas id="audio-visualizer" class="w-full rounded-xl bg-slate-800" style="height:160px;display:block;"></canvas>
        <audio id="audio-player"
               controls
               class="w-full"
               src="{{ $fileUrl }}">
            Your browser does not support the <code>audio</code> element.
        </audio>
    </div>
</div>

<script>
const audio      = document.getElementById('audio-player');
const canvas     = document.getElementById('audio-visualizer');
const canvasCtx  = canvas.getContext('2d');

const audioCtx   = new (window.AudioContext || window.webkitAudioContext)();
const analyser   = audioCtx.createAnalyser();
const source     = audioCtx.createMediaElementSource(audio);

source.connect(analyser);
analyser.connect(audioCtx.destination);

analyser.fftSize = 256;
const bufferLength = analyser.frequencyBinCount;
const dataArray    = new Uint8Array(bufferLength);

function draw() {
    requestAnimationFrame(draw);
    analyser.getByteFrequencyData(dataArray);

    canvasCtx.clearRect(0, 0, canvas.width, canvas.height);

    const barWidth = (canvas.width / bufferLength) * 2.5;
    let x = 0;

    for (let i = 0; i < bufferLength; i++) {
        const barHeight = dataArray[i];
        const r = Math.round(barHeight * 0.8 + 40);
        const g = Math.round(80);
        const b = Math.round(200 - barHeight * 0.5);
        canvasCtx.fillStyle = `rgb(${r},${g},${b})`;
        canvasCtx.beginPath();
        canvasCtx.roundRect(x, canvas.height - barHeight / 2, Math.max(barWidth - 1, 1), barHeight / 2, 2);
        canvasCtx.fill();
        x += barWidth + 1;
    }
}

audio.addEventListener('play', function () {
    audioCtx.resume().then(function () { draw(); });
});
</script>
@endsection
