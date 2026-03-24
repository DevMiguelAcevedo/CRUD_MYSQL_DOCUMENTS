<?php

namespace App\Models;

class Proceso extends BaseModel
{
    protected $table = 'pro_proceso';

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->findById($id);
    }
}
