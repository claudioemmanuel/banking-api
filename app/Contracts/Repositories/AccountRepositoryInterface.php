<?php

namespace App\Contracts\Repositories;

interface AccountRepositoryInterface
{
  public function reset(): void;

  public function getBalance(string $accountId): ?float;

  public function deposit(string $accountId, float $amount): ?float;

  public function withdraw(string $accountId, float $amount): ?float;
}
