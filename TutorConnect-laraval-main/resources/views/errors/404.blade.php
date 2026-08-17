<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page Not Found | TutorConnect</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col items-center justify-center p-6 text-slate-800 bg-slate-50">

    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-2xl border border-gray-100 shadow-xl">
        <!-- 404 Illustration Badge -->
        <div class="w-20 h-20 rounded-3xl bg-blue-50 text-primary-800 border border-blue-100 flex items-center justify-center text-4xl mx-auto shadow-sm">
            <i class="fa-solid fa-compass"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs font-bold font-heading uppercase tracking-widest text-primary-800">Error 404</span>
            <h1 class="text-3xl font-extrabold text-slate-900">Page Not Found</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                The page you are looking for might have been moved, removed, or is temporarily unavailable.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-6 py-3 rounded-xl shadow-md shadow-primary-800/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Go to Homepage</span>
            </a>
            <a href="javascript:history.back()" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-5 py-3 rounded-xl transition-colors">
                Go Back
            </a>
        </div>
    </div>

    <p class="mt-8 text-xs text-slate-400">&copy; 2025 TutorConnect. All rights reserved.</p>

</body>
</html>
