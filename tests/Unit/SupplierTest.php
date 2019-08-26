<?php

namespace Tests\Unit;

use App\Supplier;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_can_create_supplier() {
        $data = [
            'name' => $this->faker->sentence,
        ];
        $this->post(route('supplier.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_can_update_supplier() {
        $supplier = factory(Supplier::class)->create();
        $data = [
            'name' => $this->faker->sentence,
        ];
        $this->put(route('supplier.update', $supplier->id), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_can_list_suppliers() {
        $supplier = factory(Supplier::class, 2)->create()->map(function ($supplier) {
            return $supplier->only(['id', 'name']);
        });
        $this->get(route('supplier.index'))
            ->assertStatus(200)
            ->assertJson($supplier->toArray())
            ->assertJsonStructure([
                '*' => [ 'id', 'name' ],
            ]);
    }
}
