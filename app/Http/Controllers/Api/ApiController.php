<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AccountServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController
{
  public function __construct(
    private AccountServiceInterface $accountService
  ) {}

  public function reset(): JsonResponse
  {
    $this->accountService->reset();

    return response()->json('OK', 200);
  }

  public function getBalance(Request $request): JsonResponse
  {
    $accountId = $request->input('account_id');
    $account = $this->accountService->getBalance($accountId);

    if ($account === null) {
      return response()->json(0, 404);
    }

    return response()->json($account, 200);
  }

  public function postEvent(Request $request): JsonResponse
  {
    switch ($request->input('type')) {
      case 'deposit':
        return $this->handleDeposit($request);
      case 'withdraw':
        return $this->handleWithdraw($request);
      case 'transfer':
        return $this->handleTransfer($request);
      default:
        return response()->json('Invalid event type', 400);
    }
  }

  private function handleDeposit(Request $request): JsonResponse
  {
    $destination = $request->input('destination');
    $amount = (float) $request->input('amount');

    $result = $this->accountService->deposit($destination, $amount);

    return response()->json(['destination' => $result], 201);
  }

  private function handleWithdraw(Request $request): JsonResponse
  {
    $origin = $request->input('origin');
    $amount = (float) $request->input('amount');

    $result = $this->accountService->withdraw($origin, $amount);

    if ($result === null) {
      return response()->json(0, 404);
    }

    return response()->json(['origin' => $result], 201);
  }

  private function handleTransfer(Request $request): JsonResponse
  {
    $origin = $request->input('origin');
    $destination = $request->input('destination');
    $amount = (float) $request->input('amount');

    $result = $this->accountService->transfer($origin, $destination, $amount);

    if ($result === null) {
      return response()->json(0, 404);
    }

    return response()->json($result, 201);
  }
}
