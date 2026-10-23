<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">ตั๋วของฉัน (My Tickets)</h1>
            <p class="text-xs text-slate-400 mt-1">ประวัติรายการจองและหลักฐานการชำระเงินทั้งหมดของคุณ</p>
        </div>
        <a href="index.php?action=events" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-xs font-semibold transition">
            + จองอีเวนต์เพิ่ม
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl mb-6 text-xs flex items-center gap-2">
            🎉 ยืนยันการจองตั๋วสำเร็จเรียบร้อย! ข้อมูลตั๋วของคุณถูกบันทึกในระบบเรียบร้อยแล้ว
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl mb-6 text-xs flex items-center gap-2">
            ❌ ยกเลิกตั๋วเรียบร้อยแล้ว คืนที่นั่งเข้าสู่ระบบเรียบร้อย
        </div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="bg-slate-800/80 border border-slate-700/80 p-12 text-center rounded-3xl shadow-xl">
            <div class="text-4xl mb-3">🎟️</div>
            <h3 class="text-lg font-bold text-white mb-1">ยังไม่มีรายการจองตั๋ว</h3>
            <p class="text-xs text-slate-400 mb-6">คุณยังไม่ได้ทำรายการจองอีเวนต์ใดๆ ในระบบขณะนี้</p>
            <a href="index.php?action=events" class="inline-block px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition shadow-lg">
                เรียกดูรายการอีเวนต์ทั้งหมด
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($bookings as $b): ?>
                <?php $isConfirmed = ($b['status'] === 'confirmed'); ?>
                <div class="bg-slate-800/90 border-l-4 <?= $isConfirmed ? 'border-l-emerald-500' : 'border-l-rose-500' ?> border-y border-r border-slate-700/80 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <?php if ($isConfirmed): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    ✓ ยืนยันแล้ว
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    ❌ ยกเลิกแล้ว
                                </span>
                            <?php endif; ?>

                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-300 border border-purple-500/20">
                                🏷️ <?= htmlspecialchars($b['category'] ?? 'ทั่วไป') ?>
                            </span>

                            <span class="text-xs text-slate-500 font-mono">
                                CODE: <strong class="text-slate-300"><?= htmlspecialchars($b['booking_code']) ?></strong>
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-white <?= !$isConfirmed ? 'line-through text-slate-400' : '' ?>">
                            <?= htmlspecialchars($b['event_title']) ?>
                        </h3>

                        <div class="text-xs text-slate-300 space-y-1">
                            <p class="flex items-center gap-2">
                                <span class="text-indigo-400">📍</span> <?= htmlspecialchars($b['location']) ?>
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="text-purple-400">📅</span> <?= date('d/m/Y H:i', strtotime($b['event_date'])) ?> น.
                            </p>
                            <p class="text-slate-400 text-xs">
                                ช่องทางชำระเงิน: <span class="text-slate-200 font-medium"><?= htmlspecialchars($b['payment_method']) ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right border-t md:border-t-0 pt-4 md:pt-0 w-full md:w-auto flex md:flex-col justify-between md:justify-center items-end gap-3">
                        <div>
                            <div class="text-xs text-slate-400">
                                จำนวน: <strong class="text-white text-sm"><?= $b['quantity'] ?></strong> ใบ
                            </div>
                            <div class="text-2xl font-extrabold <?= $isConfirmed ? 'text-emerald-400' : 'text-slate-500 line-through' ?> mt-1">
                                ฿<?= number_format($b['total_price'], 2) ?>
                            </div>
                        </div>

                        <?php if ($isConfirmed): ?>
                            <a href="index.php?action=cancel-booking&id=<?= $b['id'] ?>" 
                               onclick="return confirm('คุณแน่ใจหรือว่าต้องการยกเลิกการจองตั๋วนี้? (จำนวนที่นั่งจะถูกคืนเข้าสู่ระบบ)');"
                               class="px-4 py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 rounded-xl text-xs font-semibold transition">
                                🚫 ยกเลิกตั๋ว
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
