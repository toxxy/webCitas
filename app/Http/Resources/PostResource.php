<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    // esto despliega el arreglo de informacion que recopila desde la DB

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'RFC' => $this->RFC,
            'orden' => $this->orden,
            'status' => $this->status,
            'Nombre' => $this->Nombre,
            'descripcion' => $this->descripcion,
            'FechaRealizada' => $this->FechaRealizada,
            'FechaProgramada' => $this->FechaProgramada,
        ];
    }
}
