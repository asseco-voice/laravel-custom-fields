<?php

declare(strict_types=1);

namespace Asseco\Relations\Tests\Feature;

use Asseco\CustomFields\App\Models\Relation;
use Asseco\CustomFields\Tests\TestCase;

class RelationControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_relations()
    {
        $this
            ->getJson(route('custom-field.relations.index'))
            ->assertJsonCount(0);

        Relation::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.relations.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, Relation::all());
    }

    /** @test */
    public function creates_relation()
    {
        $request = Relation::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.relations.store'), $request)
            ->assertJsonFragment([
                'id'        => 1,
                'parent_id' => strval($request['parent_id']),
                'child_id'  => strval($request['child_id'])
            ]);

        $this->assertCount(1, Relation::all());
    }

    /** @test */
    public function can_return_relation_by_id()
    {
        Relation::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.relations.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_relation()
    {
        $relation = Relation::factory()->create();

        $request = [
            'child_id'  => now()->timestamp,
            'parent_id' => now()->timestamp,
        ];

        $this
            ->putJson(route('custom-field.relations.update', $relation->id), $request)
            ->assertJsonFragment([
                'child_id'  => strval($request['child_id']),
                'parent_id' => strval($request['parent_id'])
            ]);

        $relation->refresh();

        $this->assertEquals($request['child_id'], $relation->child_id);
        $this->assertEquals($request['child_id'], $relation->parent_id);
    }

    /** @test */
    public function can_delete_relation()
    {
        $relation = Relation::factory()->create();

        $this->assertCount(1, Relation::all());

        $this
            ->deleteJson(route('custom-field.relations.destroy', $relation->id))
            ->assertOk();

        $this->assertCount(0, Relation::all());
    }
}
