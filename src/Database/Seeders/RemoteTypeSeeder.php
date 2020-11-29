<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\PlainType;
use Asseco\CustomFields\App\RemoteType;
use Illuminate\Database\Seeder;

class RemoteTypeSeeder extends Seeder
{
    public function run(): void
    {
        $methods = ['GET', 'POST', 'PUT'];

        // Force casting remote types to string unless we decide on different implementation.
        $plainTypeId = PlainType::query()->where('name', 'string')->firstOrFail()->id;

        $remoteTypes = RemoteType::factory()->count(50)->make()
            ->each(function (RemoteType $remoteType) use ($plainTypeId, $methods) {
                $remoteType->timestamps = false;
                $remoteType->plain_type_id = $plainTypeId;
                $remoteType->method = $methods[array_rand($methods)];
            })->makeHidden('name')->toArray();

        RemoteType::query()->insert($remoteTypes);
    }
}
