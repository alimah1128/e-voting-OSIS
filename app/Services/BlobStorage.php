<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use VercelBlobPhp\Client;
use VercelBlobPhp\CommonCreateBlobOptions;

class BlobStorage
{
    public function upload(UploadedFile $file, string $directory): string
    {
        if ($file->getSize() > 4 * 1024 * 1024 || ! in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
            throw ValidationException::withMessages(['foto' => 'Foto harus JPEG, PNG, GIF, atau WebP, maksimal 4 MB.']);
        }

        $result = $this->client()->put(
            path: trim($directory, '/').'/'.Str::uuid().'.'.$file->guessExtension(),
            content: fopen($file->getRealPath(), 'rb'),
            options: new CommonCreateBlobOptions(
                access: 'public',
                contentType: $file->getMimeType(),
                cacheControlMaxAge: 31536000,
            ),
        );

        return $result->url;
    }

    /** Hapus blob lama. Hanya URL Blob; nilai legacy (R2/lokal) dilewati. Gagal hapus = dicatat, tidak menggagalkan request. */
    public function hapus(?string $url): void
    {
        if (! $url || ! str_ends_with(parse_url($url, PHP_URL_HOST) ?: '', '.blob.vercel-storage.com')) {
            return;
        }

        try {
            $this->client()->del([$url]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Blob URL dipakai apa adanya. Nilai lama: basename -> public/image (ada di git), path relatif -> R2 sampai dimigrasi. */
    public function url(?string $value): ?string
    {
        if (! $value || str_starts_with($value, 'http')) {
            return $value;
        }

        if (! str_contains($value, '/')) {
            return asset('image/'.$value);
        }

        $base = rtrim((string) config('filesystems.disks.s3.url'), '/');

        return $base ? $base.'/'.$value : asset('storage/'.$value);
    }

    private function client(): Client
    {
        return new Client(config('services.vercel_blob.token'));
    }
}
