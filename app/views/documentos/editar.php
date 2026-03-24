<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Editar Documento'); ?></title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

        .doc-code {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .doc-code strong {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        textarea {
            resize: vertical;
            min-height: 300px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            flex: 1;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 3px;
            border-left: 4px solid;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Editar Documento</h1>
            <div class="user-info">
                Bienvenido: <strong><?php echo htmlspecialchars($usuario ?? 'Usuario'); ?></strong>
            </div>
        </div>

        <div class="doc-code">
            Código actual: <strong><?php echo htmlspecialchars($documento['DOC_CODIGO']); ?></strong>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/documentos/<?php echo $documento['DOC_ID']; ?>/actualizar">
            <div class="form-group">
                <label for="nombre">Nombre del documento:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($documento['DOC_NOMBRE']); ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="id_tipo">Tipo de documento:</label>
                <select id="id_tipo" name="id_tipo" required>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo['TIP_ID']); ?>" <?php echo $tipo['TIP_ID'] == $documento['DOC_ID_TIPO'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo['TIP_NOMBRE']); ?> (<?php echo htmlspecialchars($tipo['TIP_PREFIJO']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_proceso">Proceso:</label>
                <select id="id_proceso" name="id_proceso" required>
                    <?php foreach ($procesos as $proceso): ?>
                        <option value="<?php echo htmlspecialchars($proceso['PRO_ID']); ?>" <?php echo $proceso['PRO_ID'] == $documento['DOC_ID_PROCESO'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($proceso['PRO_NOMBRE']); ?> (<?php echo htmlspecialchars($proceso['PRO_PREFIJO']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" required><?php echo htmlspecialchars($documento['DOC_CONTENIDO']); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar documento</button>
                <a href="/documentos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
