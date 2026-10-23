<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-md mx-auto my-12">
    <div class="bg-slate-800/90 backdrop-blur border border-slate-700/80 rounded-2xl p-8 shadow-2xl shadow-indigo-950/50">
        <div class="text-center mb-8">
            <div class="inline-flex p-3 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-3xl mb-3">
                🔐
            </div>
            <h2 class="text-2xl font-bold text-white tracking-tight">เข้าสู่ระบบ EventPulse</h2>
            <p class="text-xs text-slate-400 mt-1">กรอกข้อมูลเพื่อสิทธิ์เข้าใช้งานและจองตั๋วอีเวนต์</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-3.5 rounded-xl mb-6 text-xs flex items-center gap-2">
                <span>✅</span>
                <span>สมัครสมาชิกสำเร็จเรียบร้อย กรุณาเข้าสู่ระบบ</span>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-3.5 rounded-xl mb-6 text-xs flex items-center gap-2">
                <span>⚠️</span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1.5">อีเมล (Email)</label>
                <input type="email" name="email" placeholder="example@domain.com" required 
                       class="w-full bg-slate-900/80 border border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1.5">รหัสผ่าน (Password)</label>
                <input type="password" name="password" placeholder="••••••••" required 
                       class="w-full bg-slate-900/80 border border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none transition">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white font-semibold py-3 rounded-xl shadow-lg shadow-indigo-500/25 transition duration-200">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-700/60 text-center text-xs text-slate-400">
            ยังไม่มีบัญชีใช่ไหม? <a href="index.php?action=register" class="text-indigo-400 font-semibold hover:underline">สมัครสมาชิกที่นี่</a>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
