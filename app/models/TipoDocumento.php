<?php

namespace App\Models;

class TipoDocumento extends BaseModel
{
    protected $table = 'tip_tipo_doc';

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->findById($id);
    }
}
