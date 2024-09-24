<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    public function test_reset(): void
    {
        $response = $this->post('/api/reset');

        $response->assertStatus(200);
    }

    public function test_get_balance_for_non_existing_account(): void
    {
        $response = $this->get('/api/balance?account_id=1234');

        $response->assertStatus(404)
            ->assertSee('0');
    }

    public function test_create_account_with_initial_balance(): void
    {
        # Create account with initial balance
        // POST /api/event {"type":"deposit", "destination":"100", "amount":10}
        // 201 {"destination": {"id":"100", "balance":10}}
        $response = $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'destination' => [
                    'id' => '100',
                    'balance' => 10.0,
                ],
            ]);
    }

    public function test_deposit_into_existing_account(): void
    {
        # Deposit into existing account
        // POST /api/event {"type":"deposit", "destination":"100", "amount":10}
        // 201 {"destination": {"id":"100", "balance":20}}
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $response = $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'destination' => [
                    'id' => '100',
                    'balance' => 20.0,
                ],
            ]);
    }

    public function test_get_balance_for_existing_account(): void
    {
        # Get balance for existing account
        // GET /api/balance?account_id=100
        // 200 20
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 20,
        ]);

        $response = $this->get('/api/balance?account_id=100');

        $response->assertStatus(200)
            ->assertSee('20');
    }

    public function test_withdraw_from_existing_account(): void
    {
        # Withdraw from non-existing account
        // POST /api/event {"type":"withdraw", "origin":"200", "amount":10}
        // 404 0
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '200',
            'amount' => 20,
        ]);

        $response = $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '200',
            'amount' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'origin' => [
                    'id' => '200',
                    'balance' => 10.0,
                ],
            ]);
    }

    public function test_withdraw_from_non_existing_account(): void
    {
        # Withdraw from existing account
        // POST /api/event {"type":"withdraw", "origin":"100", "amount":5}
        // 201 {"origin": {"id":"100", "balance":15}}
        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 20,
        ]);

        $response = $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'origin' => [
                    'id' => '100',
                    'balance' => 15.0,
                ],
            ]);
    }

    public function test_transfer_from_existing_account_to_existing_account(): void
    {
        # Transfer from existing account
        // POST /api/event {"type":"transfer", "origin":"100", "amount":15, "destination":"300"}
        // 201 {"origin": {"id":"100", "balance":0}, "destination": {"id":"300", "balance":15}}
        $this->post('/api/reset');

        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 20,
        ]);

        $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '300',
            'amount' => 10,
        ]);

        $response = $this->postJson('/api/event', [
            'type' => 'transfer',
            'origin' => '100',
            'amount' => 15,
            'destination' => '300',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'origin' => [
                    'id' => '100',
                    'balance' => 5.0,
                ],
                'destination' => [
                    'id' => '300',
                    'balance' => 25.0,
                ],
            ]);
    }
}
