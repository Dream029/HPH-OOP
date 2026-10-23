CREATE DATABASE IF NOT EXISTS eventpulse_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventpulse_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL DEFAULT 'คอนเสิร์ต',
    description TEXT,
    location VARCHAR(200) NOT NULL,
    event_date DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(30) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default Users (Password: 123456)
INSERT INTO users (name, email, password, role) VALUES
('ผู้ดูแลระบบ', 'admin@eventpulse.com', '$2y$10$q.gA5V1u/PqM7S2L4i5eUeX/XN0M/VwYmH.8Jv7g.oY3P3L7r3mG2', 'admin'),
('ลูกค้า ทดสอบ', 'user@eventpulse.com', '$2y$10$q.gA5V1u/PqM7S2L4i5eUeX/XN0M/VwYmH.8Jv7g.oY3P3L7r3mG2', 'customer');

INSERT INTO events (title, category, description, location, event_date, price, total_seats, available_seats, image_url) VALUES
('Neon Night Music Festival 2026', 'คอนเสิร์ต', 'มหกรรมคอนเสิร์ตดนตรีอิเล็กทรอนิกส์สุดยิ่งใหญ่แห่งปี พร้อมแสงสีเสียงระดับโลก จัดเต็มตลอดคืน', 'IMPACT Arena Hall 1', '2026-11-20 18:00:00', 2500.00, 500, 498, 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800'),
('Tech Innovation Summit 2026', 'สัมมนา & เวิร์กช็อป', 'งานสัมมนาเทคโนโลยี AI, Cloud Architecture และ Software Engineering สำหรับนักพัฒนายุคใหม่', 'BITEC Bangna Hall 98', '2026-12-05 09:00:00', 1200.00, 200, 200, 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800'),
('Thailand Gaming Expo & Esports 2026', 'การแข่งขัน & เกม', 'การแข่งขันอีสปอร์ตระดับประเทศ และงานแสดงนวัตกรรมเกมคอมพิวเตอร์และคอนโซลล่าสุด', 'QSNCC Queen Sirikit Center', '2026-11-12 10:00:00', 550.00, 1000, 950, 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800'),
('Bangkok Stand-Up Comedy Special', 'การแสดง & คอมเมดี้', 'โชว์เดี่ยวไมโครโฟนสุดฮาจากคอมเมเดียนชั้นนำ รวมเรื่องขำขันและสแตนด์อัพคอมเมดี้สุดฮิต', 'KBank Siam Pic-Ganesha', '2026-11-28 19:30:00', 850.00, 300, 280, 'https://images.unsplash.com/photo-1585699324551-f6c309eedeca?w=800'),
('International Food & Wine Festival 2026', 'มหกรรม & เทศกาล', 'เทศกาลอาหารและไวน์นานาชาติ ลิ้มลองอาหารจากเชฟมิชลินสตาร์ทั่วโลก', 'CentralWorld Square', '2026-12-15 16:00:00', 1800.00, 400, 385, 'https://images.unsplash.com/photo-1555244162-803834f70033?w=800'),
('Modern Art & Digital Design Exhibition', 'ศิลปะ & นิทรรศการ', 'นิทรรศการศิลปะร่วมสมัยและ Digital Immersive Art ที่ผสานเทคโนโลยีแสงเงาสุดล้ำ', 'MOCA Bangkok', '2026-12-20 10:00:00', 350.00, 600, 600, 'https://images.unsplash.com/photo-1561214115-f2f134cc4912?w=800'),
('Full-Stack Software Architecture Masterclass', 'สัมมนา & เวิร์กช็อป', 'เวิร์กช็อปความรู้เข้มข้น 1 วันเต็มเกี่ยวกับการออกแบบระบบ Microservices และ OOP Design Patterns', 'True Digital Park', '2027-01-10 09:00:00', 3500.00, 80, 75, 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800'),
('Indie Rock Underground Live 2026', 'คอนเสิร์ต', 'คอนเสิร์ตวงดนตรีอินดี้ร็อกนอกกระแสรวม 8 วงที่จะมาสร้างความมันส์บนเวทีเดียว', 'Glowfish Sathorn', '2026-12-18 17:00:00', 990.00, 350, 320, 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800');
