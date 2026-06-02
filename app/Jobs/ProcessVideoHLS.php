<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessVideoHLS implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(protected \App\Models\UnitMedia $media) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting HLS conversion for Media ID: {$this->media->id}");

        $this->media->update(['processing_status' => 'processing']);

        try {
            // Validate file exists
            if (!Storage::disk('public')->exists($this->media->url)) {
                throw new \Exception("Video file not found: {$this->media->url}");
            }

            $filePath = Storage::disk('public')->path($this->media->url);
            $fileSize = Storage::disk('public')->size($this->media->url);

            Log::info("Processing video - Path: {$filePath}, Size: " . number_format($fileSize / 1024 / 1024, 2) . " MB");

            // Check if FFmpeg binaries exist
            $ffmpegPath = config('laravel-ffmpeg.ffmpeg.binaries');
            $ffprobePath = config('laravel-ffmpeg.ffprobe.binaries');

            if (!file_exists($ffmpegPath)) {
                throw new \Exception("FFmpeg binary not found at: {$ffmpegPath}");
            }

            if (!file_exists($ffprobePath)) {
                throw new \Exception("FFprobe binary not found at: {$ffprobePath}");
            }

            Log::info("FFmpeg binaries found - FFmpeg: {$ffmpegPath}, FFprobe: {$ffprobePath}");

            $ffmpeg = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('public')
                ->open($this->media->url);

            $hlsExport = $ffmpeg->exportForHLS()
                ->addFormat(new \FFMpeg\Format\Video\X264('aac', 'libx264'), function ($media) {
                    $media->scale(1280, 720);
                })
                ->addFormat(new \FFMpeg\Format\Video\X264('aac', 'libx264'), function ($media) {
                    $media->scale(640, 360);
                });

            $hlsPath = 'units/hls/' . $this->media->id . '/playlist.m3u8';

            Log::info("Starting HLS export to: {$hlsPath}");

            $hlsExport->toDisk('public')->save($hlsPath);

            Log::info("HLS conversion completed successfully for Media ID: {$this->media->id}");

            $this->media->update([
                'processed_url' => $hlsPath,
                'processing_status' => 'completed'
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();

            Log::error("HLS Conversion Failed for Media ID: {$this->media->id}");
            Log::error("Error: " . $errorMessage);
            Log::error("Trace: " . $errorTrace);

            $this->media->update([
                'processing_status' => 'failed'
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("HLS Job permanently failed for Media ID: {$this->media->id} after {$this->tries} attempts");
        Log::error("Final error: " . $exception->getMessage());

        $this->media->update([
            'processing_status' => 'failed'
        ]);
    }
}
