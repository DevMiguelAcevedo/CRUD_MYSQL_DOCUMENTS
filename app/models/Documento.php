<?php

namespace App\Models;

class Documento extends BaseModel
{
    protected $table = 'doc_documento';

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->findById($id);
    }

    public function create($nombre, $contenido, $idTipo, $idProceso)
    {
        $codigo = $this->generarCodigo($idTipo, $idProceso);
        
        $stmt = $this->prepare("INSERT INTO {$this->table} (DOC_NOMBRE, DOC_CODIGO, DOC_CONTENIDO, DOC_ID_TIPO, DOC_ID_PROCESO) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $nombre, $codigo, $contenido, $idTipo, $idProceso);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $nombre, $contenido, $idTipo, $idProceso)
    {
        $codigo = $this->generarCodigo($idTipo, $idProceso);
        
        $stmt = $this->prepare("UPDATE {$this->table} SET DOC_NOMBRE = ?, DOC_CODIGO = ?, DOC_CONTENIDO = ?, DOC_ID_TIPO = ?, DOC_ID_PROCESO = ? WHERE DOC_ID = ?");
        $stmt->bind_param("sssiii", $nombre, $codigo, $contenido, $idTipo, $idProceso, $id);
        
        return $stmt->execute();
    }

    public function search($termino)
    {
        $termino = "%{$this->escape($termino)}%";
        
        $stmt = $this->prepare("
            SELECT d.*, t.TIP_NOMBRE, p.PRO_NOMBRE 
            FROM {$this->table} d
            JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
            JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
            WHERE d.DOC_NOMBRE LIKE ? OR d.DOC_CODIGO LIKE ?
            ORDER BY d.DOC_ID DESC
        ");
        $stmt->bind_param("ss", $termino, $termino);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllWithJoin()
    {
        $result = $this->query("
            SELECT d.*, t.TIP_NOMBRE, t.TIP_PREFIJO, p.PRO_NOMBRE, p.PRO_PREFIJO
            FROM {$this->table} d
            JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
            JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
            ORDER BY d.DOC_ID DESC
        ");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByIdWithJoin($id)
    {
        $stmt = $this->prepare("
            SELECT d.*, t.TIP_NOMBRE, t.TIP_PREFIJO, p.PRO_NOMBRE, p.PRO_PREFIJO
            FROM {$this->table} d
            JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
            JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
            WHERE d.DOC_ID = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    private function generarCodigo($idTipo, $idProceso)
    {
        // Obtener prefijos
        $stmtTipo = $this->prepare("SELECT TIP_PREFIJO FROM TIP_TIPO_DOC WHERE TIP_ID = ?");
        $stmtTipo->bind_param("i", $idTipo);
        $stmtTipo->execute();
        $tipo = $stmtTipo->get_result()->fetch_assoc();
        
        $stmtProceso = $this->prepare("SELECT PRO_PREFIJO FROM PRO_PROCESO WHERE PRO_ID = ?");
        $stmtProceso->bind_param("i", $idProceso);
        $stmtProceso->execute();
        $proceso = $stmtProceso->get_result()->fetch_assoc();
        
        // Obtener consecutivo
        $stmtConseq = $this->prepare("
            SELECT COALESCE(MAX(CAST(SUBSTRING(DOC_CODIGO, POSITION('-' IN REVERSE(DOC_CODIGO)) - 3) AS UNSIGNED)), 0) + 1 as consecutivo
            FROM {$this->table}
            WHERE DOC_ID_TIPO = ? AND DOC_ID_PROCESO = ?
        ");
        $stmtConseq->bind_param("ii", $idTipo, $idProceso);
        $stmtConseq->execute();
        $conseq = $stmtConseq->get_result()->fetch_assoc();
        
        $consecutivo = $conseq['consecutivo'];
        
        return "{$tipo['TIP_PREFIJO']}-{$proceso['PRO_PREFIJO']}-{$consecutivo}";
    }
}
