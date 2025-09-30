<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'CO.DE Platform'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'code-blue': '#1e40af',        // Blu professionale scuro
                        'code-light-blue': '#2563eb', // Blu medio
                        'code-dark-blue': '#1e3a8a',  // Blu navy
                        'code-accent': '#0ea5e9',     // Celeste accento
                        'code-gray': '#6b7280',       // Grigio neutro
                        'aqua': '#0e7490',           // Celeste scuro professionale
                        'celeste': '#0891b2',        // Celeste medio
                        'azzurro': '#0284c7',        // Azzurro scuro
                        'acquamarina': '#0369a1',    // Acquamarina scura
                        'tiffany': '#075985'         // Tiffany scuro
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Gradients aggiornati con palette celeste scura */
        .gradient-bg { 
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%); 
        }
        .card-shadow { 
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.2); 
        }
        .code-gradient { 
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 25%, #3b82f6 50%, #0ea5e9 100%); 
        }
        
        /* Gradient personalizzati aggiuntivi */
        .aqua-gradient {
            background: linear-gradient(135deg, #0e7490 0%, #0891b2 50%, #0284c7 100%);
        }
        .ocean-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #0369a1 50%, #0284c7 100%);
        }
        .fresh-gradient {
            background: linear-gradient(135deg, #0ea5e9 0%, #0891b2 50%, #0e7490 100%);
        }
        
        /* Effetti hover professionali */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(30, 64, 175, 0.25);
        }
        
        .btn-professional {
            transition: all 0.3s ease;
        }
        
        .btn-professional:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }
        .break-words {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .overflow-wrap-anywhere {
            overflow-wrap: anywhere;
        }
    </style>
    
    
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php echo $__env->yieldContent('content'); ?>
    
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH /app/resources/views/layouts/app.blade.php ENDPATH**/ ?>