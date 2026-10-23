-- สคริปต์แก้ไขตารางเพิ่มคอลัมน์ description (รันใน phpMyAdmin แท็บ SQL)
USE smart_store;
ALTER TABLE products ADD COLUMN description TEXT NULL AFTER image_url;
