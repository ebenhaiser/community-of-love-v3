<?php

namespace App\Services\Bible;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SonnyLabBibleService
{
    protected string $endpoint = 'https://bible.sonnylab.com/';

    /**
     * Get Bible chapter.
     */
    public function getChapter(
        string $book,
        int $chapter,
        string $version = 'tb'
    ): array {
        $query = sprintf(
            <<<'GRAPHQL'
            {
                passages(
                    version: %s
                    book: "%s"
                    chapter: %d
                ) {
                    verses {
                        verse
                        type
                        content
                    }
                }
            }
            GRAPHQL,
            $version,
            addslashes($book),
            $chapter
        );

        $response = Http::timeout(15)
            ->acceptJson()
            ->post($this->endpoint, [
                'query' => $query,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Gagal menghubungi Bible API. HTTP ' . $response->status()
            );
        }

        $json = $response->json();

        if (isset($json['errors'])) {
            throw new RuntimeException(
                $json['errors'][0]['message']
                    ?? 'Bible API mengembalikan error.'
            );
        }

        return $json['data']['passages']['verses'] ?? [];
    }
}