-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for shopaoquan
CREATE DATABASE IF NOT EXISTS `shopaoquan` ;
USE `shopaoquan`;

-- Dumping structure for table shopaoquan.anh
CREATE TABLE IF NOT EXISTS `anh` (
  `MaAnh` int NOT NULL AUTO_INCREMENT,
  `MaSP` int NOT NULL,
  `Url` varchar(255) DEFAULT NULL,
  `TrangThai` tinyint DEFAULT NULL,
  PRIMARY KEY (`MaAnh`),
  KEY `fk_anh_sp` (`MaSP`),
  CONSTRAINT `fk_anh_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.anh: ~0 rows (approximately)
DELETE FROM `anh`;
INSERT INTO `anh` (`MaAnh`, `MaSP`, `Url`, `TrangThai`) VALUES
	(1, 1, '/upload/products/aokhoacdu.jpg', 1),
	(2, 1, '/upload/products/aokhoacgio.jpg', 1),
	(3, 2, '/upload/products/aokhoacjean.jpg', 1),
	(4, 2, '/upload/products/aokhoackaki.jpg', 1),
	(5, 3, '/upload/products/aopolonam.jpg', 1),
	(6, 4, '/upload/products/aopolonam-den.jpg', 1),
	(7, 5, '/upload/products/aosomitayngan.jpg', 1),
	(8, 8, '/upload/products/aosomitayngan.jpg', 1),
	(9, 6, '/upload/products/aosomitayngan.jpg', 1),
	(10, 9, '/upload/products/aosomitayngan.jpg', 1);

-- Dumping structure for table shopaoquan.chitiet_quyen_cn
CREATE TABLE IF NOT EXISTS `chitiet_quyen_cn` (
  `MaQuyen` int NOT NULL,
  `MaCTQ` int NOT NULL ,
  `HanhDong` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MaQuyen`,`MaCTQ`),
  KEY `fk_pq_cn` (`MaCTQ`),
  CONSTRAINT `fk_pq_cn` FOREIGN KEY (`MaCTQ`) REFERENCES `chucnang` (`MaCTQ`),
  CONSTRAINT `fk_pq_quyen` FOREIGN KEY (`MaQuyen`) REFERENCES `quyen` (`MaQuyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.chitiet_quyen_cn: ~0 rows (approximately)
DELETE FROM `chitiet_quyen_cn`;
INSERT INTO `chitiet_quyen_cn` (`MaQuyen`, `MaCTQ`, `HanhDong`) VALUES
	(1, 1, 'full'),
	(1, 2, 'full'),
	(1, 3, 'full'),
	(1, 4, 'full'),
	(1, 5, 'full'),
	(2, 1, 'view,edit'),
	(2, 2, 'full'),
	(2, 3, 'view,edit'),
	(3, 1, 'view');

-- Dumping structure for table shopaoquan.chucnang
CREATE TABLE IF NOT EXISTS `chucnang` (
  `MaCTQ` int NOT NULL AUTO_INCREMENT,
  `Chuc nang` varchar(255) NOT NULL,
  PRIMARY KEY (`MaCTQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.chucnang: ~0 rows (approximately)
DELETE FROM `chucnang`;
INSERT INTO `chucnang` (`MaCTQ`, `Chuc nang`) VALUES
	(1, 'Quản lý sản phẩm'),
	(2, 'Quản lý đơn hàng'),
	(3, 'Quản lý nhập hàng'),
	(4, 'Quản lý khuyến mãi'),
	(5, 'Quản lý tài khoản');

-- Dumping structure for table shopaoquan.cthoadon
CREATE TABLE IF NOT EXISTS `cthoadon` (
  `MaHD` int NOT NULL ,
  `MaSP` int NOT NULL,
  `SoLuongBan` int DEFAULT NULL,
  `DonGia` double DEFAULT NULL,
  `ThanhTien` double DEFAULT NULL,
  `MaSize` int NOT NULL,
  PRIMARY KEY (`MaHD`,`MaSP`,`MaSize`),
  KEY `fk_cthoadon_sp_size` (`MaSP`,`MaSize`),
  CONSTRAINT `fk_cthoadon_hd` FOREIGN KEY (`MaHD`) REFERENCES `hoadon` (`MaHD`),
  CONSTRAINT `fk_cthoadon_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  CONSTRAINT `fk_cthoadon_sp_size` FOREIGN KEY (`MaSP`, `MaSize`) REFERENCES `size_sanpham` (`MaSP`, `MaSize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.cthoadon: ~0 rows (approximately)
DELETE FROM `cthoadon`;
INSERT INTO `cthoadon` (`MaHD`, `MaSP`, `SoLuongBan`, `DonGia`, `ThanhTien`, `MaSize`) VALUES
	(1, 1, 2, 150000, 300000, 2),
	(1, 3, 1, 350000, 350000, 3),
	(1, 4, 1, 180000, 180000, 1),
	(2, 2, 1, 250000, 250000, 2),
	(2, 5, 1, 280000, 280000, 2),
	(3, 1, 1, 150000, 150000, 1),
	(3, 2, 1, 250000, 250000, 1),
	(3, 4, 1, 180000, 180000, 2);

-- Dumping structure for table shopaoquan.ctphieunhap
CREATE TABLE IF NOT EXISTS `ctphieunhap` (
  `MaPN` int NOT NULL,
  `MaSP` int NOT NULL,
  `MaSize` int NOT NULL,
  `DonGia` double DEFAULT NULL,
  `SoLuongNhap` int DEFAULT NULL,
  `ThanhTien` double DEFAULT NULL,
  PRIMARY KEY (`MaPN`,`MaSP`,`MaSize`),
  KEY `fk_ctphieunhap_sp_size` (`MaSP`,`MaSize`),
  CONSTRAINT `fk_ctphieunhap_pn` FOREIGN KEY (`MaPN`) REFERENCES `phieunhap` (`MaPN`),
  CONSTRAINT `fk_ctphieunhap_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  CONSTRAINT `fk_ctphieunhap_sp_size` FOREIGN KEY (`MaSP`, `MaSize`) REFERENCES `size_sanpham` (`MaSP`, `MaSize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.ctphieunhap: ~0 rows (approximately)
DELETE FROM `ctphieunhap`;
INSERT INTO `ctphieunhap` (`MaPN`, `MaSP`, `MaSize`, `DonGia`, `SoLuongNhap`, `ThanhTien`) VALUES
	(1, 1, 1, 80000, 50, 4000000),
	(1, 1, 2, 80000, 50, 4000000),
	(2, 2, 2, 120000, 40, 4800000),
	(2, 3, 3, 180000, 30, 5400000),
	(3, 4, 1, 90000, 20, 1800000),
	(3, 5, 2, 120000, 10, 1200000);

-- Dumping structure for table shopaoquan.danhmuc
CREATE TABLE IF NOT EXISTS `danhmuc` (
  `MaDM` int NOT NULL AUTO_INCREMENT,
  `TenDM` varchar(200) DEFAULT NULL,
  `TrangThai` int DEFAULT NULL,
  PRIMARY KEY (`MaDM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.danhmuc: ~0 rows (approximately)
DELETE FROM `danhmuc`;
INSERT INTO `danhmuc` (`MaDM`, `TenDM`, `TrangThai`) VALUES
	(1, 'Áo thun', 1),
	(2, 'Áo sơ mi', 1),
	(3, 'Quần jean', 1),
	(4, 'Quần short', 1),
	(5, 'Đầm/Váy', 1);

-- Dumping structure for table shopaoquan.hoadon
CREATE TABLE IF NOT EXISTS `hoadon` (
  `MaHD` int NOT NULL AUTO_INCREMENT,
  `MaTK` int DEFAULT NULL,
  `ThoiGian` date DEFAULT NULL,
  `ThanhToan` double DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `TrangThai` int DEFAULT NULL,
  PRIMARY KEY (`MaHD`),
  KEY `fk_hoadon_taikhoan` (`MaTK`),
  CONSTRAINT `fk_hoadon_taikhoan` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.hoadon: ~0 rows (approximately)
DELETE FROM `hoadon`;
INSERT INTO `hoadon` (`MaHD`, `MaTK`, `ThoiGian`, `ThanhToan`, `MoTa`, `TrangThai`) VALUES
	(1, 3, '2023-05-10', 680000, 'Mua hàng online', 2),
	(2, 4, '2023-05-12', 530000, 'Mua tại cửa hàng', 2),
	(3, 3, '2023-05-15', 430000, 'Mua hàng online', 1);

-- Dumping structure for table shopaoquan.khuyenmai
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `MaKM` int NOT NULL AUTO_INCREMENT,
  `TenKM` varchar(200) DEFAULT NULL,
  `TrangThai` int DEFAULT NULL,
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  `giaTriKM` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaKM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.khuyenmai: ~0 rows (approximately)
DELETE FROM `khuyenmai`;
INSERT INTO `khuyenmai` (`MaKM`, `TenKM`, `TrangThai`, `NgayBatDau`, `NgayKetThuc`, `giaTriKM`) VALUES
	(1, 'Giảm giá mùa hè', 1, '2023-05-01 00:00:00', '2023-05-31 23:59:59', 20.00),
	(2, 'Khuyến mãi 30/4', 1, '2023-04-25 00:00:00', '2023-05-05 23:59:59', 15.00),
	(3, 'Giảm giá cuối năm', 0, '2022-12-15 00:00:00', '2022-12-31 23:59:59', 25.00);

-- Dumping structure for table shopaoquan.loai
CREATE TABLE IF NOT EXISTS `loai` (
  `MaLoai` int NOT NULL AUTO_INCREMENT,
  `TenLoai` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MaLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.loai: ~0 rows (approximately)
DELETE FROM `loai`;
INSERT INTO `loai` (`MaLoai`, `TenLoai`) VALUES
	(1, 'Khách hàng'),
	(2, 'Nhân viên'),
	(3, 'Quản trị viên');

-- Dumping structure for table shopaoquan.nguoidung
CREATE TABLE IF NOT EXISTS `nguoidung` (
  `MaNguoiDung` int NOT NULL AUTO_INCREMENT,
  `DiaChi` varchar(255) DEFAULT NULL,
  `TrangThai` int NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `MaLoai` int DEFAULT NULL,
  PRIMARY KEY (`MaNguoiDung`),
  KEY `fk_loai_nguoidung` (`MaLoai`),
  CONSTRAINT `fk_loai_nguoidung` FOREIGN KEY (`MaLoai`) REFERENCES `loai` (`MaLoai`),
  CONSTRAINT `fk_nguoidung_taikhoan` FOREIGN KEY (`MaNguoiDung`) REFERENCES `taikhoan` (`MaTK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.nguoidung: ~0 rows (approximately)
DELETE FROM `nguoidung`;
INSERT INTO `nguoidung` (`MaNguoiDung`, `DiaChi`, `TrangThai`, `Email`, `MaLoai`) VALUES
	(1, '123 Đường Admin, TP.HCM', 1, 'admin@shop.com', 3),
	(2, '456 Đường NV, TP.HCM', 1, 'nhanvien@shop.com', 2),
	(3, '789 Đường KH1, Hà Nội', 1, 'khachhang1@gmail.com', 1),
	(4, '101 Đường KH2, Đà Nẵng', 1, 'khachhang2@gmail.com', 1);

-- Dumping structure for table shopaoquan.nhacungcap
CREATE TABLE IF NOT EXISTS `nhacungcap` (
  `MaNCC` int NOT NULL AUTO_INCREMENT,
  `TenNCC` varchar(255) NOT NULL,
  `TrangThai` int DEFAULT NULL,
  PRIMARY KEY (`MaNCC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.nhacungcap: ~0 rows (approximately)
DELETE FROM `nhacungcap`;
INSERT INTO `nhacungcap` (`MaNCC`, `TenNCC`, `TrangThai`) VALUES
	(1, 'Công ty May mặc Việt Nam', 1),
	(2, 'Công ty Thời trang Quốc tế', 1),
	(3, 'Nhà cung cấp Vải cao cấp', 1);

-- Dumping structure for table shopaoquan.phieunhap
CREATE TABLE IF NOT EXISTS `phieunhap` (
  `MaPN` int NOT NULL AUTO_INCREMENT,
  `MaTK` int NOT NULL,
  `MaNCC` int NOT NULL,
  `ThanhToan` double NOT NULL,
  `ThoiGian` date NOT NULL,
  `TrangThai` int NOT NULL,
  PRIMARY KEY (`MaPN`),
  KEY `fk_phieunhap_ncc` (`MaNCC`),
  KEY `fk_phieunhap_taikhoan` (`MaTK`),
  CONSTRAINT `fk_phieunhap_ncc` FOREIGN KEY (`MaNCC`) REFERENCES `nhacungcap` (`MaNCC`),
  CONSTRAINT `fk_phieunhap_taikhoan` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.phieunhap: ~0 rows (approximately)
DELETE FROM `phieunhap`;
INSERT INTO `phieunhap` (`MaPN`, `MaTK`, `MaNCC`, `ThanhToan`, `ThoiGian`, `TrangThai`) VALUES
	(1, 2, 1, 5000000, '2023-04-01', 1),
	(2, 2, 2, 7500000, '2023-04-05', 1),
	(3, 1, 3, 3000000, '2023-04-10', 1);

-- Dumping structure for table shopaoquan.quyen
CREATE TABLE IF NOT EXISTS `quyen` (
  `MaQuyen` int NOT NULL AUTO_INCREMENT,
  `TenQuyen` varchar(255) NOT NULL,
  `TrangThai` int NOT NULL,
  PRIMARY KEY (`MaQuyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.quyen: ~0 rows (approximately)
DELETE FROM `quyen`;
INSERT INTO `quyen` (`MaQuyen`, `TenQuyen`, `TrangThai`) VALUES
	(1, 'Admin', 1),
	(2, 'Nhân viên', 1),
	(3, 'Khách hàng', 1);

-- Dumping structure for table shopaoquan.sanpham
CREATE TABLE IF NOT EXISTS `sanpham` (
  `MaSP` int NOT NULL AUTO_INCREMENT,
  `MaKM` int DEFAULT NULL,
  `MaDM` int DEFAULT NULL,
  `TenSP` varchar(200) DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `GiaBan` double DEFAULT NULL,
  `TrangThai` int DEFAULT NULL,
  `NgayTao` date DEFAULT NULL,
  `SoLuongTong` int DEFAULT NULL,
  `GioiTinh` int DEFAULT NULL,
  PRIMARY KEY (`MaSP`),
  KEY `fk_sanpham_danhmuc` (`MaDM`),
  KEY `fk_sanpham_km` (`MaKM`),
  CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`),
  CONSTRAINT `fk_sanpham_km` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.sanpham: ~0 rows (approximately)
DELETE FROM `sanpham`;
INSERT INTO `sanpham` (`MaSP`, `MaKM`, `MaDM`, `TenSP`, `MoTa`, `GiaBan`, `TrangThai`, `NgayTao`, `SoLuongTong`, `GioiTinh`) VALUES
	(1, 1, 1, 'Áo thun nam cổ tròn', 'Áo thun cotton thoáng mát', 150000, 1, '2023-01-10', 100, 1),
	(2, NULL, 2, 'Áo sơ mi nữ dài tay', 'Áo sơ mi công sở lịch sự', 250000, 1, '2023-01-15', 80, 0),
	(3, 2, 3, 'Quần jean nam ống đứng', 'Quần jean co giãn thoải mái', 350000, 1, '2023-02-01', 60, 1),
	(4, NULL, 4, 'Quần short nữ thể thao', 'Quần short thoáng mát cho mùa hè', 180000, 1, '2023-02-10', 120, 0),
	(5, NULL, 5, 'Đầm suông nữ cổ V', 'Đầm công sở thanh lịch', 280000, 1, '2023-03-05', 50, 0),
	(6, 3, 1, 'Áo thun nữ', 'ddđ', 150000, 1, '2025-04-12', 100, 1),
	(7, 3, 2, 'vvvv', 'aaa', 122222, 1, '2025-04-12', 300, 1),
	(8, 1, 1, '1111', '111', 123000, 1, '2025-04-12', 111, 1),
	(9, 2, 1, '1111111', '12544', 11, 1, '2025-04-12', 120, 1);

-- Dumping structure for table shopaoquan.size
CREATE TABLE IF NOT EXISTS `size` (
  `MaSize` int NOT NULL AUTO_INCREMENT,
  `TenSize` varchar(50) NOT NULL,
  PRIMARY KEY (`MaSize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.size: ~0 rows (approximately)
DELETE FROM `size`;
INSERT INTO `size` (`MaSize`, `TenSize`) VALUES
	(1, 'S'),
	(2, 'M'),
	(3, 'L'),
	(4, 'XL'),
	(5, 'XXL');

-- Dumping structure for table shopaoquan.size_sanpham
CREATE TABLE IF NOT EXISTS `size_sanpham` (
  `MaSP` int NOT NULL,
  `MaSize` int NOT NULL,
  `SoLuong` int NOT NULL,
  PRIMARY KEY (`MaSP`,`MaSize`),
  KEY `fk_sizesp_size` (`MaSize`),
  CONSTRAINT `fk_sizesp_size` FOREIGN KEY (`MaSize`) REFERENCES `size` (`MaSize`),
  CONSTRAINT `fk_sizesp_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.size_sanpham: ~0 rows (approximately)
DELETE FROM `size_sanpham`;
INSERT INTO `size_sanpham` (`MaSP`, `MaSize`, `SoLuong`) VALUES
	(1, 1, 20),
	(1, 2, 30),
	(1, 3, 30),
	(1, 4, 15),
	(1, 5, 5),
	(2, 1, 15),
	(2, 2, 25),
	(2, 3, 25),
	(2, 4, 10),
	(2, 5, 5),
	(3, 2, 20),
	(3, 3, 25),
	(3, 4, 10),
	(3, 5, 5),
	(4, 1, 30),
	(4, 2, 40),
	(4, 3, 30),
	(4, 4, 20),
	(5, 1, 10),
	(5, 2, 20),
	(5, 3, 15),
	(5, 4, 5);

-- Dumping structure for table shopaoquan.taikhoan
CREATE TABLE IF NOT EXISTS `taikhoan` (
  `MaTK` int NOT NULL AUTO_INCREMENT,
  `MaQuyen` int NOT NULL,
  `TenTK` varchar(200) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `NgayTaoTK` date NOT NULL,
  `TrangThai` int NOT NULL,
  PRIMARY KEY (`MaTK`),
  KEY `fk_taikhoan_quyen` (`MaQuyen`),
  CONSTRAINT `fk_taikhoan_quyen` FOREIGN KEY (`MaQuyen`) REFERENCES `quyen` (`MaQuyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopaoquan.taikhoan: ~0 rows (approximately)
DELETE FROM `taikhoan`;
INSERT INTO `taikhoan` (`MaTK`, `MaQuyen`, `TenTK`, `MatKhau`, `NgayTaoTK`, `TrangThai`) VALUES
	(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-01', 1),
	(2, 2, 'nhanvien1', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-02', 1),
	(3, 3, 'khachhang1', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-03', 1),
	(4, 3, 'khachhang2', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-04', 1);
ALTER TABLE sanpham MODIFY COLUMN MaKM INT NULL;
/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
