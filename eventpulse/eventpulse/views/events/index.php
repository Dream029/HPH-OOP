<?php require __DIR__ . '/../layout/header.php'; ?>

<!-- Featured Events Carousel Slider -->
<?php 
$featuredEvents = array_slice($allEventsForSlider, 0, 5); 
?>

<?php if (!empty($featuredEvents)): ?>
<div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 mb-10 shadow-2xl group" id="hero-carousel">
    <!-- Slides Container -->
    <div class="relative h-[380px] sm:h-[440px] w-full overflow-hidden">
        <?php foreach ($featuredEvents as $index => $item): ?>
            <div class="carousel-slide absolute inset-0 transition-all duration-700 ease-in-out transform <?= $index === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-95 z-0 pointer-events-none' ?>" 
                 data-slide-index="<?= $index ?>">
                <!-- Background Image with Overlay -->
                <img src="<?= htmlspecialchars($item['image_url']) ?>" class="w-full h-full object-cover object-center" alt="<?= htmlspecialchars($item['title']) ?>">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-slate-900/30"></div>
                
                <!-- Content Overlay -->
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-12 z-20 max-w-3xl">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-500/30 text-purple-200 border border-purple-500/40 backdrop-blur-md">
                            🏷️ <?= htmlspecialchars($item['category']) ?>
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 backdrop-blur-md">
                            🔥 อีเวนต์ไฮไลต์แนะนำ
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-md">
                            ฿<?= number_format($item['price'], 2) ?>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-3 drop-shadow-md">
                        <?= htmlspecialchars($item['title']) ?>
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm line-clamp-2 leading-relaxed mb-5 max-w-2xl">
                        <?= htmlspecialchars($item['description']) ?>
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 mb-6">
                        <span class="flex items-center gap-1.5 bg-slate-900/60 backdrop-blur-md px-3 py-1.5 rounded-lg border border-slate-700/50">
                            <span class="text-indigo-400">📍</span> <?= htmlspecialchars($item['location']) ?>
                        </span>
                        <span class="flex items-center gap-1.5 bg-slate-900/60 backdrop-blur-md px-3 py-1.5 rounded-lg border border-slate-700/50">
                            <span class="text-purple-400">📅</span> <?= date('d/m/Y H:i', strtotime($item['event_date'])) ?> น.
                        </span>
                    </div>

                    <a href="index.php?action=event-detail&id=<?= $item['id'] ?>" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 hover:from-indigo-400 hover:to-pink-400 text-white font-bold text-xs sm:text-sm shadow-xl shadow-indigo-500/25 transition-all hover:scale-105 active:scale-95">
                        🎟️ ดูรายละเอียด & จองตั๋ว
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Navigation Arrows -->
    <button id="carousel-prev" 
            class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full bg-slate-900/60 hover:bg-slate-900 backdrop-blur-md border border-slate-700/80 text-white flex items-center justify-center transition hover:scale-110 active:scale-95 shadow-lg">
        ❮
    </button>
    <button id="carousel-next" 
            class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full bg-slate-900/60 hover:bg-slate-900 backdrop-blur-md border border-slate-700/80 text-white flex items-center justify-center transition hover:scale-110 active:scale-95 shadow-lg">
        ❯
    </button>

    <!-- Indicators -->
    <div class="absolute bottom-4 right-6 z-30 flex items-center gap-2 bg-slate-900/50 backdrop-blur-md px-3 py-1.5 rounded-full border border-slate-800">
        <?php foreach ($featuredEvents as $index => $item): ?>
            <button class="carousel-dot w-2.5 h-2.5 rounded-full transition-all duration-300 <?= $index === 0 ? 'bg-indigo-400 w-6' : 'bg-slate-600 hover:bg-slate-400' ?>" 
                    data-slide-target="<?= $index ?>"></button>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    let currentIndex = 0;
    let autoSlideInterval;

    function showSlide(index) {
        if (slides.length === 0) return;
        currentIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            if (i === currentIndex) {
                slide.classList.remove('opacity-0', 'scale-95', 'pointer-events-none', 'z-0');
                slide.classList.add('opacity-100', 'scale-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'scale-100', 'z-10');
                slide.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'z-0');
            }
        });

        dots.forEach((dot, i) => {
            if (i === currentIndex) {
                dot.classList.remove('bg-slate-600', 'w-2.5');
                dot.classList.add('bg-indigo-400', 'w-6');
            } else {
                dot.classList.remove('bg-indigo-400', 'w-6');
                dot.classList.add('bg-slate-600', 'w-2.5');
            }
        });
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(() => {
            showSlide(currentIndex + 1);
        }, 5000);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) clearInterval(autoSlideInterval);
    }

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            showSlide(currentIndex - 1);
            startAutoSlide();
        });

        nextBtn.addEventListener('click', () => {
            showSlide(currentIndex + 1);
            startAutoSlide();
        });
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-slide-target'));
            showSlide(index);
            startAutoSlide();
        });
    });

    const carousel = document.getElementById('hero-carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoSlide);
        carousel.addEventListener('mouseleave', startAutoSlide);
    }

    startAutoSlide();
});
</script>
<?php endif; ?>

<!-- Alerts -->
<?php if (isset($_GET['created'])): ?>
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl mb-8 text-sm flex items-center justify-between">
        <span class="flex items-center gap-2">🎉 เพิ่มข้อมูลอีเวนต์เรียบร้อยแล้ว</span>
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl mb-8 text-sm flex items-center justify-between">
        <span class="flex items-center gap-2">🗑️ ลบรายการอีเวนต์ออกจากระบบเรียบร้อยแล้ว</span>
    </div>
<?php endif; ?>

<!-- Category Filter Pills Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
            🏷️ หมวดหมู่อีเวนต์และรายการทั้งหมด
        </h2>
        <p class="text-xs text-slate-400 mt-1">
            หมวดหมู่ปัจจุบัน: <strong class="text-indigo-400"><?= htmlspecialchars($selectedCategory) ?></strong> (พบ <?= count($events) ?> รายการ)
        </p>
    </div>

    <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
        <a href="index.php?action=create-event" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shadow-md transition self-start md:self-auto">
            + สร้างอีเวนต์ใหม่
        </a>
    <?php endif; ?>
</div>

<!-- Category Tabs Navigation -->
<div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-none">
    <?php 
    $allCats = array_merge(['ทั้งหมด'], $categories);
    $allCats = array_unique($allCats);
    foreach ($allCats as $cat): 
        $isActive = ($selectedCategory === $cat);
    ?>
        <a href="index.php?action=events&category=<?= urlencode($cat) ?>" 
           class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 <?= $isActive ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/25 scale-105' : 'bg-slate-800/80 hover:bg-slate-700 text-slate-300 border border-slate-700/80' ?>">
            <?= htmlspecialchars($cat) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Event Cards Grid -->
<?php if (empty($events)): ?>
    <div class="bg-slate-800/80 border border-slate-700/80 p-12 text-center rounded-3xl my-6">
        <div class="text-4xl mb-3">🔍</div>
        <h3 class="text-lg font-bold text-white mb-1">ไม่พบอีเวนต์ในหมวดหมู่นี้</h3>
        <p class="text-xs text-slate-400 mb-6">ลองเลือกดูหมวดหมู่อื่นๆ หรือเรียกดูอีเวนต์ทั้งหมด</p>
        <a href="index.php?action=events" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition">
            แสดงอีเวนต์ทั้งหมด
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($events as $item): ?>
            <div class="group bg-slate-800/80 border border-slate-700/80 hover:border-indigo-500/50 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-indigo-950/50 transition-all duration-300 flex flex-col justify-between">
                <div class="relative overflow-hidden">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" class="h-52 w-full object-cover group-hover:scale-105 transition duration-500" alt="Event Cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
                    
                    <span class="absolute top-3 left-3 bg-purple-900/80 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-purple-200 border border-purple-500/30">
                        <?= htmlspecialchars($item['category']) ?>
                    </span>

                    <span class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-emerald-400 border border-emerald-500/30">
                        ฿<?= number_format($item['price'], 2) ?>
                    </span>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-white group-hover:text-indigo-300 transition line-clamp-1 mb-2">
                            <?= htmlspecialchars($item['title']) ?>
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed line-clamp-2 mb-4">
                            <?= htmlspecialchars($item['description']) ?>
                        </p>
                    </div>

                    <div class="space-y-2 text-xs text-slate-300 border-t border-slate-700/60 pt-4">
                        <p class="flex items-center gap-2">
                            <span class="text-indigo-400">📍</span>
                            <span class="truncate"><?= htmlspecialchars($item['location']) ?></span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-purple-400">📅</span>
                            <span><?= date('d/m/Y H:i', strtotime($item['event_date'])) ?> น.</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-amber-400">🎟️</span>
                            <span>ที่นั่งคงเหลือ: <strong class="<?= $item['available_seats'] > 0 ? 'text-emerald-400' : 'text-rose-400' ?>"><?= $item['available_seats'] ?></strong> / <?= $item['total_seats'] ?></span>
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-2 flex items-center gap-2">
                    <a href="index.php?action=event-detail&id=<?= $item['id'] ?>" 
                       class="flex-grow text-center bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-xl text-xs shadow-md transition">
                        รายละเอียด & จองตั๋ว
                    </a>

                    <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                        <a href="index.php?action=delete-event&id=<?= $item['id'] ?>" 
                           onclick="return confirm('คุณต้องการลบอีเวนต์ <?= htmlspecialchars(addslashes($item['title'])) ?> หรือไม่?');"
                           class="px-3 py-2.5 bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 rounded-xl text-xs font-semibold transition"
                           title="ลบอีเวนต์นี้">
                            🗑️ ลบ
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
