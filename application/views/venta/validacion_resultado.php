<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Ticket</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .message-container {
            text-align: center;
            padding: 2rem;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 90%;
            width: 300px;
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .message {
            font-size: 1.25rem;
            margin: 1rem 0;
            color: <?php echo $color; ?>;
            font-weight: bold;
        }
        .status-text {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: <?php echo $color; ?>;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="status-icon">
            <?php if($status === 'success'): ?>
                ✅
            <?php else: ?>
                ❌
            <?php endif; ?>
        </div>
        <div class="status-text">
            <?php echo $status === 'success' ? 'VÁLIDO' : 'NO VÁLIDO'; ?>
        </div>
        <div class="message">
            <?php echo $message; ?>
        </div>
    </div>
</body>
</html>