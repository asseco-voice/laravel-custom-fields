<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

trait TransformsOutput
{
    protected function transform(array $response, ?array $mappings, ?string $idProperty = null): array
    {
        if (!$mappings) {
            return $response;
        }

        $transformed = [];
        foreach ($response as $item) {
            $transformed[] = $this->mapSingle($mappings, $item, $idProperty);
        }

        return $transformed;
    }

    /**
     * @param array $mappings
     * @param array $item
     * @param string|null $idProperty
     * @return array
     */
    protected function mapSingle(array $mappings, array $item, ?string $idProperty = null): array
    {
        $data = [];
        foreach ($mappings as $remoteKey => $localKey) {
            if (!array_key_exists($remoteKey, $item)) {
                continue;
            }

            $data = array_merge_recursive($data, [
                $localKey => $item[$remoteKey],
            ]);
        }

        if ($idProperty) {
            $data[ $idProperty] = $item[ $idProperty ] ?? null;
        }

        return $data;
    }
}
