<?php

namespace App\Application\CashExtractions;

use App\Models\CashExtraction;
use InvalidArgumentException;

class ValidateCashExtraction
{
    public function handle(int $id, string $result, ?string $note): CashExtraction
    {
        if (!in_array($result, ['cuadro', 'faltante', 'sobrante'], true)) {
            throw new InvalidArgumentException('Resultado de validación inválido.');
        }

        if (in_array($result, ['faltante', 'sobrante'], true) && blank($note)) {
            throw new InvalidArgumentException('La nota es obligatoria si hay faltante o sobrante.');
        }

        $extraction = CashExtraction::findOrFail($id);

        $extraction->update([
            'status' => 'validado',
            'cash_validation_result' => $result,
            'cash_validation_note'   => $note,
        ]);

        return $extraction->fresh(['user']);
    }
}
