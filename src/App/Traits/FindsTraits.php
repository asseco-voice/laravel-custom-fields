<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

trait FindsTraits
{
    public function getModelsWithTrait(string $traitPath): array
    {
        $paths = config('asseco-custom-fields.models_path');
        $models = [];

        foreach ($paths as $path => $namespace) {
            $files = scandir($path);

            foreach ($files as $file) {
                if (stripos($file, '.php') === false) {
                    continue;
                }

                $className = substr($file, 0, -4);
                $model = $namespace . $className;

                if (self::hasTrait($traitPath, $model)) {
                    $models[] = $model;
                }
            }
        }

        return $models;
    }

    protected function hasTrait(string $traitPath, string $class): bool
    {
        $traits = class_uses($class);

        return in_array($traitPath, $traits, true);
    }
}
