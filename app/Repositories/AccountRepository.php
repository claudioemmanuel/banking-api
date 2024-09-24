<?php

namespace App\Repositories;

use App\Contracts\Repositories\AccountRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AccountRepository implements AccountRepositoryInterface
{
  private $cacheKey = 'accounts';

  public function reset(): void
  {
    Cache::forget($this->cacheKey);
  }

  private function readAccounts(): array
  {
    return Cache::get($this->cacheKey, []);
  }

  private function writeAccounts(array $accounts): void
  {
    Cache::put($this->cacheKey, $accounts);
  }

  public function getBalance(string $accountId): ?float
  {
    $accounts = $this->readAccounts();

    return $accounts[$accountId] ?? null;
  }

  public function deposit(string $accountId, float $amount): ?float
  {
    $accounts = $this->readAccounts();

    if (!isset($accounts[$accountId])) {
      $accounts[$accountId] = 0;
    }

    $accounts[$accountId] += $amount;

    $this->writeAccounts($accounts);

    return $accounts[$accountId];
  }

  public function withdraw(string $accountId, float $amount): ?float
  {
    $accounts = $this->readAccounts();

    if (!isset($accounts[$accountId])) {
      return null;
    }

    $accounts[$accountId] -= $amount;

    $this->writeAccounts($accounts);

    return $accounts[$accountId];
  }
}
