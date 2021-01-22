<?php

declare(strict_types=1);

namespace Asseco\Forms\Tests\Feature;

use Asseco\CustomFields\App\Models\Form;
use Asseco\CustomFields\Tests\TestCase;

class FormControllerTest extends TestCase
{
    /** @test */
    public function can_fetch_all_forms()
    {
        $this
            ->getJson(route('custom-field.forms.index'))
            ->assertJsonCount(0);

        Form::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.forms.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, Form::all());
    }

    /** @test */
    public function rejects_creating_form_with_invalid_name()
    {
        $request = Form::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.forms.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_form()
    {
        $request = Form::factory()->make()->toArray();

        $this
            ->postJson(route('custom-field.forms.store'), $request)
            ->assertJsonFragment([
                'id'   => 1,
                'name' => $request['name'],
            ]);

        $this->assertCount(1, Form::all());
    }

    /** @test */
    public function can_return_form_by_id()
    {
        Form::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.forms.show', 3))
            ->assertJsonFragment(['id' => 3]);
    }

    /** @test */
    public function can_update_form()
    {
        $form = Form::factory()->create();

        $request = [
            'name' => 'updated_name',
        ];

        $this
            ->putJson(route('custom-field.forms.update', $form->id), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertEquals($request['name'], $form->refresh()->name);
    }

    /** @test */
    public function can_delete_form()
    {
        $form = Form::factory()->create();

        $this->assertCount(1, Form::all());

        $this
            ->deleteJson(route('custom-field.forms.destroy', $form->id))
            ->assertOk();

        $this->assertCount(0, Form::all());
    }
}
