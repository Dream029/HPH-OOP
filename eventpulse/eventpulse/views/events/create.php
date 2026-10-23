<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-2xl mx-auto my-6">
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-700/60 pb-4">
            <span class="p-3 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-xl text-2xl">✨</span>
            <div>
                <h2 class="text-xl font-bold text-white">สร้างงานอีเวนต์ใหม่ (Admin Panel)</h2>
                <p class="text-xs text-slate-400">กรอกข้อมูลรายละเอียดอีเวนต์เพื่อเปิดให้จองตั๋วบนแพลตฟอร์ม</p>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-3.5 rounded-xl mb-6 text-xs flex items-center gap-2">
                <span>⚠️</span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?action=create-event" method="POST" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">ชื่ออีเวนต์ / คอนเสิร์ต</label>
                    <input type="text" name="title" required placeholder="เช่น Neon Night Music Festival 2026"
                           class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">หมวดหมู่</label>
                    <select name="category" class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                        <option value="คอนเสิร์ต">🎵 คอนเสิร์ต</option>
                        <option value="สัมมนา & เวิร์กช็อป">💻 สัมมนา & เวิร์กช็อป</option>
                        <option value="การแข่งขัน & เกม">🎮 การแข่งขัน & เกม</option>
                        <option value="ศิลปะ & นิทรรศการ">🎨 ศิลปะ & นิทรรศการ</option>
                        <option value="มหกรรม & เทศกาล">🍷 มหกรรม & เทศกาล</option>
                        <option value="การแสดง & คอมเมดี้">🎭 การแสดง & คอมเมดี้</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1.5">รายละเอียดงาน</label>
                <textarea name="description" rows="4" placeholder="อธิบายบรรยากาศ ศิลปิน หรือกำหนดการงาน..."
                          class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">สถานที่จัดงาน</label>
                    <input type="text" name="location" required placeholder="เช่น IMPACT Arena Hall 1"
                           class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">วันและเวลา</label>
                    <input type="datetime-local" name="event_date" required 
                           class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">ราคาตั๋ว (บาท)</label>
                    <input type="number" step="0.01" name="price" required placeholder="2500.00"
                           class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">จำนวนที่นั่งทั้งหมด</label>
                    <input type="number" name="total_seats" required placeholder="500"
                           class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1.5">URL รูปภาพหน้าปก</label>
                <input type="url" name="image_url" placeholder="https://images.unsplash.com/..."
                       class="w-full bg-slate-900/80 border border-slate-700 focus:border-purple-500 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none">
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="flex-grow bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold py-3 rounded-xl shadow-lg transition">
                    บันทึกและเปิดขายตั๋ว
                </button>
                <a href="index.php?action=events" class="px-5 py-3 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-xl text-xs font-semibold transition">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
