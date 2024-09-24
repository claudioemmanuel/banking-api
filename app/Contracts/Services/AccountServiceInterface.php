<?php

namespace App\Contracts\Services;

use App\Dtos\AccountDTO;
use App\Dtos\TransferDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\InsufficientFundsException;

interface AccountServiceInterface
{
  /**
   * Reset the accounts.
   *
   * @return void
   */
  public function reset(): void;

  /**
   * Get the balance of an account.
   *
   * @param string $accountId
   * @return float|null
   */
  public function getBalance(string $accountId): ?float;

  /**
   * Deposit an amount into an account.
   *
   * @param string $destination
   * @param float $amount
   * @return AccountDTO
   */
  public function deposit(string $destination, float $amount): AccountDTO|null;

  /**
   * Withdraw an amount from an account.
   *
   * @param string $origin
   * @param float $amount
   * @return AccountDTO
   *
   * @throws AccountNotFoundException
   * @throws InsufficientFundsException
   */
  public function withdraw(string $origin, float $amount): AccountDTO|null;

  /**
   * Transfer an amount from one account to another.
   *
   * @param string $origin
   * @param string $destination
   * @param float $amount
   * @return TransferDTO
   *
   * @throws AccountNotFoundException
   * @throws InsufficientFundsException
   */
  public function transfer(string $origin, string $destination, float $amount): TransferDTO|null;
}
