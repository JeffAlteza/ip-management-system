<?php

namespace App\Http\Controllers;

use App\DTOs\StoreIpDTO;
use App\DTOs\UpdateIpDTO;
use App\Http\Requests\StoreIpRequest;
use App\Http\Requests\UpdateIpRequest;
use App\Models\Ip;
use App\Services\IpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IpController extends Controller
{
    public function __construct(
        protected IpService $ipService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->ipService->list());
    }

    public function store(StoreIpRequest $request): JsonResponse
    {
        $result = $this->ipService->create(
            StoreIpDTO::from($request->validated()),
            $request,
        );

        return response()->json($result['data'], $result['status']);
    }

    public function update(UpdateIpRequest $request, Ip $ip): JsonResponse
    {
        $result = $this->ipService->update(
            $ip,
            UpdateIpDTO::from($request->validated()),
            $request,
        );

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['status']);
        }

        return response()->json($result['data'], $result['status']);
    }

    public function destroy(Request $request, Ip $ip): JsonResponse
    {
        $result = $this->ipService->delete($ip, $request);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['status']);
        }

        return response()->json(['message' => 'IP deleted'], $result['status']);
    }
}
