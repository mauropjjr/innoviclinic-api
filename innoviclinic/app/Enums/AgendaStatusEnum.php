<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self CONFIRMAR()
 * @method static self CONFIRMADO()
 * @method static self CANCELADO()
 * @method static self CHEGOU()
 * @method static self EM_ATENDIMENTO()
 * @method static self ATENDIDO()
 * @method static self FALTOU()
 */
class AgendaStatusEnum extends Enum
{
    const CONFIRMAR = 1;
    const CONFIRMADO = 2;
    const CANCELADO = 3;
    const CHEGOU = 4;
    const EM_ATENDIMENTO = 5;
    const ATENDIDO = 6;
    const FALTOU = 7;
}
