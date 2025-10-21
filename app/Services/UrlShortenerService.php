<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UrlShortenerService
{
    protected $disk;
    protected $directory = 'urls';

    public function __construct()
    {
        $this->disk = Storage::disk('local');
        
        if (!$this->disk->exists($this->directory)) {
            $this->disk->makeDirectory($this->directory);
        }
    }

    public function shortenUrl(string $url): array
    {
        $code = $this->generateCode();

        $data = [
            'code' => $code,
            'original_url' => $url,
        ];

        $this->saveUrl($code, $data);

        return [
            'code' => $code,
            'short_url' => url("/api/{$code}"),
            'original_url' => $url,
        ];
    }

    public function getOriginalUrl(string $code): ?string
    {
        $data = $this->loadUrl($code);
        
        if (!$data) {
            return null;
        }

        return $data['original_url'];
    }

    protected function generateCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while ($this->codeExists($code));

        return $code;
    }

    protected function codeExists(string $code): bool
    {
        return $this->disk->exists($this->getFilePath($code));
    }

    protected function saveUrl(string $code, array $data): void
    {
        $this->disk->put(
            $this->getFilePath($code),
            json_encode($data, JSON_PRETTY_PRINT)
        );
    }

    protected function loadUrl(string $code): ?array
    {
        $filePath = $this->getFilePath($code);
        
        if (!$this->disk->exists($filePath)) {
            return null;
        }

        $content = $this->disk->get($filePath);
        return json_decode($content, true);
    }

    protected function getFilePath(string $code): string
    {
        return "{$this->directory}/{$code}.json";
    }
}