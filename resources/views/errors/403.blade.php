<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Unauthorized Access | TutorConnect</title>
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
        <!-- 403 Lock Icon Badge -->
        <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-4xl mx-auto shadow-sm">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs font-bold font-heading uppercase tracking-widest text-amber-600">Error 403</span>
            <h1 class="text-3xl font-extrabold text-slate-900">Unauthorized Access</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                You do not have administrative or required role permissions to view this resource.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isTutor() ? route('tutor.dashboard') : route('student.dashboard')) }}" 
                   class="w-full sm:w-auto bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-6 py-3 rounded-xl shadow-md shadow-primary-800/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-gauge text-xs"></i>
                    <span>Return to Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-6 py-3 rounded-xl shadow-md transition-all">
                    Sign In with Authorized Account
                </a>
            @endauth
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-5 py-3 rounded-xl transition-colors">
                Go to Homepage
            </a>
        </div>
    </div>

    <p class="mt-8 text-xs text-slate-400">&copy; 2025 TutorConnect. All rights reserved.</p>

</body>
</html>
