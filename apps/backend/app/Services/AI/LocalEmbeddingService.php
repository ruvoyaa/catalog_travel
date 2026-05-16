<?php

namespace App\Services\AI;

class LocalEmbeddingService
{
    public function dimensions(): int
    {
        return 64;
    }

    public function provider(): string
    {
        return 'local';
    }

    public function model(): string
    {
        return 'hash-embedding-v1';
    }

    public function embed(string $text): array
    {
        $vector = array_fill(0, $this->dimensions(), 0.0);

        preg_match_all('/[\p{L}\p{N}]+/u', $this->normalizeText($text), $matches);

        foreach ($matches[0] as $token) {
            $index = abs(crc32($token)) % $this->dimensions();
            $vector[$index] += 1.0;
        }

        return $this->normalize($vector);
    }

    public function cosineSimilarity(array $left, array $right): float
    {
        $dot = 0.0;
        $leftNorm = 0.0;
        $rightNorm = 0.0;

        foreach ($left as $index => $value) {
            $other = (float) ($right[$index] ?? 0.0);
            $value = (float) $value;
            $dot += $value * $other;
            $leftNorm += $value * $value;
            $rightNorm += $other * $other;
        }

        if ($leftNorm <= 0.0 || $rightNorm <= 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($leftNorm) * sqrt($rightNorm));
    }

    private function normalize(array $vector): array
    {
        $norm = sqrt(array_sum(array_map(fn (float $value) => $value * $value, $vector)));

        if ($norm <= 0.0) {
            return $vector;
        }

        return array_map(fn (float $value) => round($value / $norm, 8), $vector);
    }

    public function normalizeText(string $text): string
    {
        return str_replace('ё', 'е', mb_strtolower($text));
    }
}
