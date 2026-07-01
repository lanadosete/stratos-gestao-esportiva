<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card-stratos { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-verde { background-color: #28a745; color: white; }
        .btn-verde:hover { background-color: #218838; color: white; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .bg-gradient-stratos {
        background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);
        }
        .sidebar-stratos {
            background: linear-gradient(180deg, #dcedc8 0%, #ffffff 100%);
        }
    </style>
</head>
<body style="background-color: #f4f7f6;">
    <nav class="navbar navbar-light bg-white border-bottom border-success border-3">
        <div class="container">
            <a class="navbar-brand text-success fw-bold" href="#">STRATOS</a>
        </div>
    </nav>
    <main class="container py-5">
        @yield('conteudo')
    </main>
</body>
</html>