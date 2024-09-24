<?php

namespace App\Dtos;

class TransferDTO
{
  public function __construct(
    public AccountDTO $origin,
    public AccountDTO $destination
  ) {}
}
