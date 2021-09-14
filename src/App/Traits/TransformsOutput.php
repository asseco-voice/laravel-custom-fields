<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

trait TransformsOutput
{
    protected function transform(array $response, ?array $mappings): array
    {
        if (!$mappings) {
            return $response;
        }

        $transformed = [];
        foreach ($response as $item) {
            $transformed[] = $this->mapSingle($mappings, $item);
        }

        return $transformed;
    }

    /**
     * @param  array  $mappings
     * @param $item
     * @return array
     */
    protected function mapSingle(array $mappings, array $item): array
    {
        $data = [];

        foreach ($mappings as $localKey => $remoteKey) {
            if (!array_key_exists($remoteKey, $item)) {
                continue;
            }

            $data = array_merge_recursive($data, [
                $localKey => $item[$remoteKey],
            ]);
        }

        return $data;
    }
}
