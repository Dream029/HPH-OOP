<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <a href="index.php?action=events" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white mb-6 transition">
        ← กลับไปหน้าอีเวนต์ทั้งหมด
    </a>

    <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl overflow-hidden shadow-2xl shadow-indigo-950/50">
        <div class="relative h-80 sm:h-96 w-full overflow-hidden">
            <img src="<?= htmlspecialchars($event->getImageUrl()) ?>" class="w-full h-full object-cover" alt="Cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-6 left-6 right-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/30 text-purple-200 border border-purple-500/40 backdrop-blur-md">
                            🏷️ <?= htmlspecialchars($event->getCategory()) ?>
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 backdrop-blur-md">
                            📍 <?= htmlspecialchars($event->getLocation()) ?>
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                        <?= htmlspecialchars($event->getTitle()) ?>
                    </h1>
                </div>

                <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                    <a href="index.php?action=delete-event&id=<?= $event->getId() ?>" 
                       onclick="return confirm('คุณต้องการลบอีเวนต์นี้ใช่หรือไม่?');"
                       class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold shadow-lg transition">
                        🗑️ ลบอีเวนต์นี้ (Admin)
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">รายละเอียดอีเวนต์</h3>
                    <p class="text-slate-300 leading-relaxed text-sm whitespace-pre-line">
                        <?= htmlspecialchars($event->getDescription()) ?>
                    </p>
                </div>

                <div class="bg-slate-900/80 border border-slate-700/60 p-5 rounded-2xl space-y-3 text-xs text-slate-300">
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-lg text-lg">📅</span>
                        <div>
                            <p class="text-slate-400">วันและเวลาแสดง</p>
                            <p class="font-semibold text-white text-sm"><?= date('d/m/Y H:i', strtotime($event->getEventDate())) ?> น.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-purple-500/10 text-purple-400 rounded-lg text-lg">📍</span>
                        <div>
                            <p class="text-slate-400">สถานที่จัดงาน</p>
                            <p class="font-semibold text-white text-sm"><?= htmlspecialchars($event->getLocation()) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Card -->
            <div class="bg-slate-900/90 border border-slate-700/80 p-6 rounded-2xl flex flex-col justify-between">
                <div>
                    <span class="text-xs text-slate-400">ราคาบัตรเข้างาน</span>
                    <div class="text-3xl font-extrabold text-emerald-400 my-1">
                        ฿<?= number_format($event->getPrice(), 2) ?>
                    </div>
                    <p class="text-xs text-slate-400 mb-6">
                        ที่นั่งว่างคงเหลือ: <strong class="text-indigo-400 font-bold"><?= $event->getAvailableSeats() ?></strong> ใบ
                    </p>

                    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_qty'): ?>
                        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-3 rounded-xl mb-4 text-xs">
                            ⚠️ จำนวนตั๋วไม่ถูกต้อง หรือเกินจำนวนสิทธิ์คงเหลือ
                        </div>
                    <?php endif; ?>

                    <?php if ($event->getAvailableSeats() > 0): ?>
                        <form action="index.php?action=book" method="POST" class="space-y-4">
                            <input type="hidden" name="event_id" value="<?= $event->getId() ?>">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">จำนวนตั๋วที่ต้องการ</label>
                                <input type="number" name="quantity" value="1" min="1" max="<?= $event->getAvailableSeats() ?>" 
                                       class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl p-2.5 text-center font-bold outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">ช่องทางชำระเงิน</label>
                                <select name="payment_type" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl p-2.5 text-xs outline-none focus:border-indigo-500">
                                    <option value="promptpay">📱 PromptPay QR Code</option>
                                    <option value="credit_card">💳 บัตรเครดิต / เดบิต</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/25 transition">
                                ยืนยันจองตั๋วทันที
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled class="w-full bg-slate-800 text-slate-500 font-bold py-3 rounded-xl cursor-not-allowed border border-slate-700">
                            บัตรจำหน่ายหมดแล้ว (Sold Out)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
