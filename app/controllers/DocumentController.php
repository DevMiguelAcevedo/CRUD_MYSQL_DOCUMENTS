<?php

namespace App\Controllers;

use App\Models\Documento;
use App\Models\Proceso;
use App\Models\TipoDocumento;

class DocumentController extends BaseController
{
    private $documentoModel;
    private $procesoModel;
    private $tipoDocumentoModel;

    public function __construct()
    {
        $this->documentoModel = new Documento();
        $this->procesoModel = new Proceso();
        $this->tipoDocumentoModel = new TipoDocumento();
    }

    public function index()
    {
        $this->requireLogin();

        $documentos = $this->documentoModel->getAllWithJoin();

        $this->view('documentos/index', [
            'titulo' => 'Documentos',
            'documentos' => $documentos,
            'usuario' => $_SESSION['usuario'] ?? 'Usuario'
        ]);
    }

    public function create()
    {
        $this->requireLogin();

        $procesos = $this->procesoModel->getAll();
        $tipos = $this->tipoDocumentoModel->getAll();

        $this->view('documentos/crear', [
            'titulo' => 'Crear Documento',
            'procesos' => $procesos,
            'tipos' => $tipos,
            'usuario' => $_SESSION['usuario'] ?? 'Usuario'
        ]);
    }

    public function store()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/documentos');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');
        $idTipo = (int)($_POST['id_tipo'] ?? 0);
        $idProceso = (int)($_POST['id_proceso'] ?? 0);

        if (empty($nombre) || empty($contenido) || $idTipo === 0 || $idProceso === 0) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/documentos/crear');
        }

        if ($this->documentoModel->create($nombre, $contenido, $idTipo, $idProceso)) {
            $_SESSION['success'] = 'Documento creado exitosamente';
            $this->redirect('/documentos');
        } else {
            $_SESSION['error'] = 'Error al crear el documento';
            $this->redirect('/documentos/crear');
        }
    }

    public function edit($id)
    {
        $this->requireLogin();

        $id = (int)$id;
        $documento = $this->documentoModel->getByIdWithJoin($id);

        if (!$documento) {
            $_SESSION['error'] = 'Documento no encontrado';
            $this->redirect('/documentos');
        }

        $procesos = $this->procesoModel->getAll();
        $tipos = $this->tipoDocumentoModel->getAll();

        $this->view('documentos/editar', [
            'titulo' => 'Editar Documento',
            'documento' => $documento,
            'procesos' => $procesos,
            'tipos' => $tipos,
            'usuario' => $_SESSION['usuario'] ?? 'Usuario'
        ]);
    }

    public function update($id)
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/documentos');
        }

        $id = (int)$id;
        $nombre = trim($_POST['nombre'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');
        $idTipo = (int)($_POST['id_tipo'] ?? 0);
        $idProceso = (int)($_POST['id_proceso'] ?? 0);

        if (empty($nombre) || empty($contenido) || $idTipo === 0 || $idProceso === 0) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect("/documentos/{$id}/editar");
        }

        if ($this->documentoModel->update($id, $nombre, $contenido, $idTipo, $idProceso)) {
            $_SESSION['success'] = 'Documento actualizado exitosamente';
            $this->redirect('/documentos');
        } else {
            $_SESSION['error'] = 'Error al actualizar el documento';
            $this->redirect("/documentos/{$id}/editar");
        }
    }

    public function delete($id)
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/documentos');
        }

        $id = (int)$id;

        if ($this->documentoModel->delete($id)) {
            $_SESSION['success'] = 'Documento eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el documento';
        }

        $this->redirect('/documentos');
    }

    public function search()
    {
        $this->requireLogin();

        $termino = trim($_GET['q'] ?? '');

        if (empty($termino)) {
            $this->json(['documentos' => []]);
        }

        $documentos = $this->documentoModel->search($termino);
        $this->json(['documentos' => $documentos]);
    }
}
