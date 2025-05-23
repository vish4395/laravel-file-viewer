<?php
$page_title=$fileName;
?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.2/plyr.css" />
<style>
    .file-detail-card {
        width: 100%;
        bottom: 0px;
        left: 0px;
        z-index: 999;
        background: #ffffffed;
    }
    .preview_container {
        overflow: scroll;
        background: white;
        padding: 1em;
        height: 90vh;
    }
    canvas {
        width: 100%;
        height: 150px;
        background: #f4f4f4;
        display: block;
        margin-top: 20px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card file-detail-card m-0">
            <div class="card-body p-1">
                @include('laravel-file-viewer::previewFileDetails')
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div id="resolte-contaniner" class="preview_container">
            <canvas id="audio-visualizer" style="margin-top: 20px"></canvas>
            <audio
                id="audio-player"
                controls
                class="w-100"
                style="margin-top: 10px"
                src="{!! $fileUrl !!}">
                Your browser does not support the
                <code>audio</code> element.
            </audio>
        </div>
    </div>
</div>
<script>
    const audio = document.getElementById('audio-player');
    const canvas = document.getElementById('audio-visualizer');
    const canvasCtx = canvas.getContext('2d');

    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const analyser = audioContext.createAnalyser();
    const source = audioContext.createMediaElementSource(audio);

    source.connect(analyser);
    analyser.connect(audioContext.destination);

    analyser.fftSize = 256;
    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);

    function draw() {
        requestAnimationFrame(draw);

        analyser.getByteFrequencyData(dataArray);

        canvasCtx.fillStyle = '#f4f4f4';
        canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

        const barWidth = (canvas.width / bufferLength) * 2.5;
        let barHeight;
        let x = 0;

        for (let i = 0; i < bufferLength; i++) {
            barHeight = dataArray[i];

            canvasCtx.fillStyle = 'rgb(' + (barHeight + 100) + ',50,50)';
            canvasCtx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);

            x += barWidth + 1;
        }
    }

    audio.addEventListener('play', () => {
        audioContext.resume().then(() => {
            draw();
        });
    });
</script>
@endsection