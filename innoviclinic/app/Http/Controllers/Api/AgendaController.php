<?php

namespace App\Http\Controllers\Api;

use App\Services\AgendaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAgendaRequest;

class AgendaController extends Controller
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
        $this->middleware('check-agenda-profissional-id-empresa-id')->only(['store', 'update']);
    }

    public function store(StoreAgendaRequest $request)
    {
        $input = $request->validated();
        return $this->agendaService->createAgenda($input);
    }

    public function update(StoreAgendaRequest $request, string $id)
    {
        $input = $request->validated();
        return $this->agendaService->updateAgenda($input, $id);
    }
}
