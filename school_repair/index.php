
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแจ้งซ่อมภายในโรงเรียน</title>

    <style>
        body {
            font-family: Tahoma, sans-serif;
            background: #f1f5f9;
            margin: 0;
            color: #1e293b;
        }

        header {
            background: #173b70;
            color: white;
            padding: 20px 8%;
        }

        nav {
            margin-top: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .hero {
            text-align: center;
            padding: 70px 20px;
            background: #dbeafe;
        }

        .hero h1 {
            color: #173b70;
            font-size: 32px;
        }

        .hero p {
            line-height: 1.8;
        }

        .button {
            display: inline-block;
            background: #173b70;
            color: white;
            padding: 12px 22px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 15px;
        }

        .services {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 40px 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 230px;
            text-align: center;
            box-shadow: 0 3px 10px #00000010;
        }

        footer {
            background: #173b70;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

<header>
    <h2>🔧 ระบบแจ้งซ่อมภายในโรงเรียน</h2>

    <nav>
        <a href="index.php">หน้าแรก</a>
        <a href="#services">บริการ</a>
        <a href="login.php">เข้าสู่ระบบ</a>
    </nav>
</header>

<section class="hero">
    <h1>ยินดีต้อนรับสู่ระบบแจ้งซ่อม</h1>

    <p>
        แจ้งปัญหาอุปกรณ์ภายในโรงเรียน<br>
        ติดตามสถานะและตรวจสอบความคืบหน้าของงานซ่อม
    </p>

    <a href="login.php" class="button">
        เข้าสู่ระบบเพื่อแจ้งซ่อม
    </a>
</section>

<section class="services" id="services">

    <div class="card">
        <h3>📝 แจ้งซ่อม</h3>
        <p>บันทึกรายละเอียดอุปกรณ์ที่ชำรุดและสถานที่</p>
    </div>

    <div class="card">
        <h3>🔍 ติดตามสถานะ</h3>
        <p>ตรวจสอบความคืบหน้าของงานแจ้งซ่อม</p>
    </div>

    <div class="card">
        <h3>🛠️ งานช่าง</h3>
        <p>จัดการงานซ่อมและบันทึกผลการดำเนินงาน</p>
    </div>

</section>

<footer>
    <p>Final Project: PHP OOP, MySQL และ OOAD</p>
    <p>ระบบแจ้งซ่อมภายในโรงเรียน</p>
</footer>

</body>
</html>
