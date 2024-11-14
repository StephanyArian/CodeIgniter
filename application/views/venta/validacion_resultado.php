<!-- application/views/venta/validacion_resultado.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Validación de Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .result-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 300px;
            width: 100%;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .message {
            font-size: 18px;
            margin-bottom: 10px;
            color: <?php echo $color; ?>;
        }
        .timestamp {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="status-icon">
            <?php echo $status === 'success' ? '✅' : '❌'; ?>
        </div>
        <div class="message">
            <?php echo $message; ?>
        </div>
        <div class="timestamp">
            <?php echo date('d/m/Y H:i:s'); ?>
        </div>
    </div>
</body>
</html>