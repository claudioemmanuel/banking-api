<?php

namespace App\Services;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Contracts\Services\AccountServiceInterface;
use App\Dtos\AccountDTO;
use App\Dtos\TransferDTO;

class AccountService implements AccountServiceInterface
{
  public function __construct(
    private AccountRepositoryInterface $accountRepository
  ) {}

  public function reset(): void
  {
    $this->accountRepository->reset();
  }

  public function getBalance(string $accountId): ?float
  {
    return $this->accountRepository->getBalance($accountId);
  }

  public function deposit(string $destination, float $amount): AccountDTO | null
  {
    $this->accountRepository->deposit($destination, $amount);

    return new AccountDTO($destination, $this->accountRepository->getBalance($destination));
  }

  public function withdraw(string $origin, float $amount): AccountDTO | null
  {
    $result = $this->accountRepository->withdraw($origin, $amount);

    if ($result === null) {
      return null;
    }

    return new AccountDTO($origin, $this->accountRepository->getBalance($origin));
  }

  public function transfer(string $origin, string $destination, float $amount): TransferDTO | null
  {
    $withdraw = $this->accountRepository->withdraw($origin, $amount);
    $deposit = $this->accountRepository->deposit($destination, $amount);

    if ($withdraw === null || $deposit === null) {
      return null;
    }

    return new TransferDTO(
      new AccountDTO($origin, $this->accountRepository->getBalance($origin)),
      new AccountDTO($destination, $this->accountRepository->getBalance($destination))
    );
  }
}
