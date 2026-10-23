<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventPulse - แพลตฟอร์มจองตั๋วคอนเสิร์ตและอีเวนต์ออนไลน์</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-full flex flex-col antialiased">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-slate-900/80 border-b border-slate-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="index.php?action=events" class="flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-xl shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition duration-300">
                    🎟️
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-white via-indigo-200 to-purple-400 bg-clip-text text-transparent tracking-tight">
                    EventPulse
                </span>
            </a>

            <nav class="flex items-center gap-3 sm:gap-4">
                <a href="index.php?action=events" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/80 transition">
                    📅 อีเวนต์ทั้งหมด
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=my-bookings" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/80 transition">
                        🎫 ตั๋วของฉัน
                    </a>
                    <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                        <a href="index.php?action=create-event" class="px-3 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white shadow-md shadow-purple-500/20 transition flex items-center gap-1">
                            ✨ + เพิ่มอีเวนต์
                        </a>
                    <?php endif; ?>
                    
                    <div class="h-5 w-px bg-slate-700 hidden sm:block"></div>
                    
                    <div class="flex items-center gap-2">
                        <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= ($_SESSION['user_role'] ?? '') === 'admin' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' ?>">
                            <?= ($_SESSION['user_role'] ?? '') === 'admin' ? '👑 Admin' : '👤 ลูกค้า' ?>: <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                        <a href="index.php?action=logout" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 transition">
                            ออกจากระบบ
                        </a>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white transition">
                        เข้าสู่ระบบ
                    </a>
                    <a href="index.php?action=register" class="px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white shadow-md shadow-indigo-500/25 transition">
                        สมัครสมาชิก
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
