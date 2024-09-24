<?php

namespace App\Dtos;

class AccountDTO
{
  public function __construct(
    public string $id,
    public float $balance
  ) {}
}
