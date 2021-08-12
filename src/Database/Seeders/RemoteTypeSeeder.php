<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Database\Seeders;

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RemoteTypeSeeder extends Seeder
{
    public function run(): void
    {
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);
        /** @var RemoteType $remoteType */
        $remoteType = app(RemoteType::class);

        $methods = ['GET', 'POST', 'PUT'];

        // Force casting remote types to string unless we decide on different implementation.
        $plainTypeId = $plainType::query()->where('name', 'string')->firstOrFail()->id;

        $remoteTypes = $remoteType::factory()->count(50)->make()
            ->each(function (RemoteType $remoteType) use ($plainTypeId, $methods) {
                if (config('asseco-custom-fields.migrations.uuid')) {
                    $remoteType->id = Str::uuid();
                }

                $remoteType->timestamps = false;
                $remoteType->plain_type_id = $plainTypeId;
                $remoteType->method = $methods[array_rand($methods)];
            })->makeHidden('name')->toArray();

        $remoteType::query()->insert($remoteTypes);
    }
}
