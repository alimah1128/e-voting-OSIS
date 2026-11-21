<?php

namespace Tests\Unit;

use App\Services\BlobStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

// Tanpa jaringan dan tanpa DB: semua kasus selesai sebelum client Blob dipanggil.
class BlobStorageTest extends TestCase
{
    public function test_url_mapping(): void
    {
        $blob = 'https://abc.public.blob.vercel-storage.com/acara/x.png';

        $this->assertNull((new BlobStorage)->url(null));
        $this->assertSame($blob, (new BlobStorage)->url($blob));
        $this->assertSame(asset('image/kandidat1.jpg'), (new BlobStorage)->url('kandidat1.jpg'));
    }

    public function test_upload_rejects_oversize_and_non_image(): void
    {
        $bad = [
            UploadedFile::fake()->create('big.jpg', 5000, 'image/jpeg'),
            UploadedFile::fake()->create('x.pdf', 10, 'application/pdf'),
        ];

        foreach ($bad as $file) {
            $this->expectException(ValidationException::class);
            (new BlobStorage)->upload($file, 'x');
        }
    }

    public function test_hapus_skips_non_blob_values(): void
    {
        (new BlobStorage)->hapus(null);
        (new BlobStorage)->hapus('acara/legacy.jpg');
        (new BlobStorage)->hapus('https://example.com/a.png');

        $this->addToAssertionCount(1);
    }
}
