<?php

declare(strict_types=1);

namespace Asseco\CustomFields\Tests\Feature\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Form;
use Asseco\CustomFields\Tests\TestCase;

class FormControllerTest extends TestCase
{
    protected Form $form;

    public function setUp(): void
    {
        parent::setUp();

        $this->form = app(Form::class);
    }

    /** @test */
    public function can_fetch_all_forms()
    {
        $this
            ->getJson(route('custom-field.forms.index'))
            ->assertJsonCount(0);

        $this->form::factory()->count(5)->create();

        $this
            ->getJson(route('custom-field.forms.index'))
            ->assertJsonCount(5);

        $this->assertCount(5, $this->form::all());
    }

    /** @test */
    public function rejects_creating_form_with_invalid_name()
    {
        $request = $this->form::factory()->make([
            'name' => 'invalid name',
        ])->toArray();

        $this
            ->postJson(route('custom-field.forms.store'), $request)
            ->assertStatus(422);
    }

    /** @test */
    public function creates_form()
    {
        $request = $this->form::factory()->make([
            'definition' => ['a' => 'b'],
        ])->toArray();

        $this
            ->postJson(route('custom-field.forms.store'), $request)
            ->assertJsonFragment([
                'name' => $request['name'],
            ]);

        $this->assertCount(1, $this->form::all());
    }

    /** @test */
    public function can_return_form_by_id()
    {
        $forms = $this->form::factory()->count(5)->create();

        $formId = $forms->random()->id;

        $this
            ->getJson(route('custom-field.forms.show', $formId))
            ->assertJsonFragment(['id' => $formId]);
    }

    /** @test */
    public function can_update_form()
    {
        $form = $this->form::factory()->create();

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
        $form = $this->form::factory()->create();

        $this->assertCount(1, $this->form::all());

        $this
            ->deleteJson(route('custom-field.forms.destroy', $form->id))
            ->assertOk();

        $this->assertCount(0, $this->form::all());
    }
}
