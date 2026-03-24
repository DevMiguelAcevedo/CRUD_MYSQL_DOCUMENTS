<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Documentos'); ?></title>
    <link rel="icon" href="/img/favicon.png" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        h1 {
            color: #333;
            font-size: 28px;
        }

        .user-info {
            text-align: right;
            font-size: 14px;
            color: #666;
        }

        .user-info strong {
            color: #007bff;
        }

        .actions {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 14px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 3px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        table td {
            padding: 12px;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .actions-col {
            text-align: center;
            white-space: nowrap;
        }

        .actions-col .btn {
            padding: 5px 10px;
            font-size: 12px;
            margin: 0 2px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }

        .search-results {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            max-height: 300px;
            overflow-y: auto;
            width: 100%;
            z-index: 1000;
        }

        .search-result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .search-result-item:hover {
            background-color: #f0f0f0;
        }

        .logout-btn {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Documentos</h1>
            <div class="user-info">
                Bienvenido: <strong><?php echo htmlspecialchars($usuario ?? 'Usuario'); ?></strong>
                <br>
                <a href="/documentos/logout" class="btn btn-secondary logout-btn" style="padding: 5px 10px; font-size: 12px;">Cerrar sesión</a>
            </div>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="/documentos/crear" class="btn btn-primary">+ Crear nuevo documento</a>
        </div>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por nombre o código..." onkeyup="buscarDocumentos()">
            <div id="searchResults" class="search-results" style="display: none;"></div>
        </div>

        <?php if (empty($documentos)): ?>
            <div class="empty-state">
                No hay documentos registrados. <a href="/documentos/crear">Crear el primer documento</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 15%;">Código</th>
                        <th style="width: 20%;">Nombre</th>
                        <th style="width: 15%;">Tipo</th>
                        <th style="width: 15%;">Proceso</th>
                        <th style="width: 15%;">Contenido</th>
                        <th style="width: 10%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documentos as $doc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($doc['DOC_ID']); ?></td>
                            <td><strong><?php echo htmlspecialchars($doc['DOC_CODIGO']); ?></strong></td>
                            <td><?php echo htmlspecialchars($doc['DOC_NOMBRE']); ?></td>
                            <td><?php echo htmlspecialchars($doc['TIP_NOMBRE']); ?></td>
                            <td><?php echo htmlspecialchars($doc['PRO_NOMBRE']); ?></td>
                            <td><?php echo substr(htmlspecialchars($doc['DOC_CONTENIDO']), 0, 50) . (strlen($doc['DOC_CONTENIDO']) > 50 ? '...' : ''); ?></td>
                            <td class="actions-col">
                                <a href="/documentos/<?php echo $doc['DOC_ID']; ?>/editar" class="btn btn-warning">Editar</a>
                                <form method="POST" action="/documentos/<?php echo $doc['DOC_ID']; ?>/eliminar" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este documento?');">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function buscarDocumentos() {
            const termino = document.getElementById('searchInput').value.trim();
            const resultsDiv = document.getElementById('searchResults');

            if (termino.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }

            fetch('/api/documentos/buscar?q=' + encodeURIComponent(termino))
                .then(response => response.json())
                .then(data => {
                    if (data.documentos.length === 0) {
                        resultsDiv.innerHTML = '<div class="search-result-item">No se encontraron resultados</div>';
                    } else {
                        resultsDiv.innerHTML = data.documentos.map(doc => `
                            <div class="search-result-item" onclick="window.location.href='/documentos/${doc.DOC_ID}/editar'">
                                <strong>${doc.DOC_CODIGO}</strong> - ${doc.DOC_NOMBRE}
                            </div>
                        `).join('');
                    }
                    resultsDiv.style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('click', function(e) {
            if (e.target.id !== 'searchInput') {
                document.getElementById('searchResults').style.display = 'none';
            }
        });
    </script>
</body>

</html>