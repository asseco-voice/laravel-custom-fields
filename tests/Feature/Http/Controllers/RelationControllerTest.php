<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Models\CustomField;
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
        $customFields = CustomField::factory()->count(2)->create();

        $this
            ->postJson(route('custom-field.relations.store'), [
                'parent_id' => $customFields->first()->id,
                'child_id'  => $customFields->last()->id,
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
        $customFields = CustomField::factory()->count(5)->create();

        $cf1 = $customFields->first()->id;
        $cf2 = $customFields->last()->id;

        $relation = Relation::factory()->create([
            'parent_id' => $cf1,
            'child_id'  => $cf2,
        ]);

        $random1 = $customFields->whereNotIn('id', [$cf1, $cf2])->random()->id;
        $random2 = $customFields->whereNotIn('id', [$cf1, $cf2, $random1])->random()->id;

        $this
            ->putJson(route('custom-field.relations.update', $relation->id), [
                'parent_id' => strval($random1),
            ])
            ->assertJsonFragment([
                'parent_id' => strval($random1),
            ]);

        $this
            ->putJson(route('custom-field.relations.update', $relation->id), [
                'child_id' => strval($random2),
            ])
            ->assertJsonFragment([
                'child_id' => strval($random2),
            ]);

        $relation->refresh();

        $this->assertEquals($random1, $relation->parent_id);
        $this->assertEquals($random2, $relation->child_id);
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
