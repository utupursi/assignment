<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_product()
    {
        $response = $this->postJson('/api/create-product', [
            'title' => 'test',
            'price' => 145,
            'quantity' => 2,
            'slug' => '27g'
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['success' => true]);

    }

    public function test_create_product_with_exist_slug()
    {
        $response = $this->postJson('/api/create-product', [
            'title' => 'test',
            'price' => 145,
            'quantity' => 2,
            'slug' => '27g'
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['success' => 'false']);

    }


    public function test_add_to_cart_not_exist_product()
    {
        $id = 9999;
        $response = $this->postJson('/api/add-to-cart', [
            "product_id" => $id,
            "quantity" => 2,
            "total_price" => 200
        ]);
        $response
            ->assertStatus(400)
            ->assertJsonFragment(['success' => 'false']);
    }

    public function test_add_to_cart_product_that_do_not_have_enough_quantity()
    {
        $product = Product::orderBy('id', 'desc')->first();

        $response = $this->postJson('/api/add-to-cart', [
            "product_id" => $product->id,
            "quantity" => 10,
            "total_price" => 200
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['success' => 'false']);
    }

    public function test_delete_not_exist_product_in_cart()
    {

        $response = $this->deleteJson('/api/delete-from-cart', [
            "product_id" => 1111
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);
        $response
            ->assertStatus(400)
            ->assertJsonFragment(['success' => 'false']);

    }

    public function test_add_product_to_cart()
    {
        $product = Product::orderBy('id', 'desc')->first();

        $response = $this->postJson('/api/add-to-cart', [
            "product_id" => $product->id,
            "quantity" => 2,
            "total_price" => 200000
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    public function test_add_existing_product_to_cart()
    {
        $product = Product::orderBy('id', 'desc')->first();

        $response = $this->postJson('/api/add-to-cart', [
            "product_id" => $product->id,
            "quantity" => 2,
            "total_price" => 200000
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }


    public function test_make_order_with_not_enough_balance()
    {
        $product = Product::orderBy('id', 'desc')->first();

        $response = $this->postJson('/api/add-order', [
            "products" => [$product->id],
            "pay_method" => 'credit card',
        ], ['Authorization' => 'Bearer 9|W5ct55wXelR3HpIQGYaAtg9JR8l7lFyrFggCvgLD']);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['success' => 'false']);
    }


    public function test_delete_product()
    {
        $product = Product::orderBy('id', 'desc')->first();

        $response = $this->deleteJson('/api/delete-product', ['id' => $product->id], ['Authorization' => 'Bearer 8|4Np4bT0DsCNPKboT7ZfyqFywDelCSSjbJGjyTccd']);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);

    }

}
