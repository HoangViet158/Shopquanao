-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 06, 2025 at 04:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopaoquan`
--
CREATE DATABASE IF NOT EXISTS `shopaoquan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shopaoquan`;

-- --------------------------------------------------------

--
-- Table structure for table `anh`
--

CREATE TABLE `anh` (
  `MaAnh` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `Url` varchar(255) DEFAULT NULL,
  `TrangThai` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anh`
--

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
(10, 9, '/upload/products/aosomitayngan.jpg', 1),
(11, 10, '/upload/products/68103620a2875_55-556941_sk-telecom-t1.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_quyen_cn`
--

CREATE TABLE `chitiet_quyen_cn` (
  `MaQuyen` int(11) NOT NULL,
  `MaCTQ` int(11) NOT NULL,
  `HanhDong` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiet_quyen_cn`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `chucnang`
--

CREATE TABLE `chucnang` (
  `MaCTQ` int(11) NOT NULL,
  `Chuc nang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chucnang`
--

INSERT INTO `chucnang` (`MaCTQ`, `Chuc nang`) VALUES
(1, 'Quản lý sản phẩm'),
(2, 'Quản lý đơn hàng'),
(3, 'Quản lý nhập hàng'),
(4, 'Quản lý khuyến mãi'),
(5, 'Quản lý tài khoản');

-- --------------------------------------------------------

--
-- Table structure for table `cthoadon`
--

CREATE TABLE `cthoadon` (
  `MaHD` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuongBan` int(11) DEFAULT NULL,
  `DonGia` double DEFAULT NULL,
  `ThanhTien` double DEFAULT NULL,
  `MaSize` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cthoadon`
--

INSERT INTO `cthoadon` (`MaHD`, `MaSP`, `SoLuongBan`, `DonGia`, `ThanhTien`, `MaSize`) VALUES
(1, 1, 2, 150000, 300000, 2),
(1, 3, 1, 350000, 350000, 3),
(1, 4, 1, 180000, 180000, 1),
(2, 2, 1, 250000, 250000, 2),
(2, 5, 1, 280000, 280000, 2),
(3, 1, 1, 150000, 150000, 1),
(3, 2, 1, 250000, 250000, 1),
(3, 4, 1, 180000, 180000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ctphieunhap`
--

CREATE TABLE `ctphieunhap` (
  `MaPN` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `MaSize` int(11) NOT NULL,
  `DonGia` double DEFAULT NULL,
  `SoLuongNhap` int(11) DEFAULT NULL,
  `ThanhTien` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ctphieunhap`
--

INSERT INTO `ctphieunhap` (`MaPN`, `MaSP`, `MaSize`, `DonGia`, `SoLuongNhap`, `ThanhTien`) VALUES
(1, 1, 1, 80000, 50, 4000000),
(1, 1, 2, 80000, 50, 4000000),
(2, 2, 2, 120000, 40, 4800000),
(2, 3, 3, 180000, 30, 5400000),
(3, 4, 1, 90000, 20, 1800000),
(3, 5, 2, 120000, 10, 1200000),
(4, 1, 1, 100000, 1000, 100000000),
(4, 10, 4, 1000, 100, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDM` int(11) NOT NULL,
  `TenDM` varchar(200) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`MaDM`, `TenDM`, `TrangThai`) VALUES
(1, 'Áo thun', 1),
(2, 'Áo sơ mi', 1),
(3, 'Quần jean', 1),
(4, 'Quần short', 1),
(5, 'Đầm/Váy', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `MaHD` int(11) NOT NULL,
  `MaTK` int(11) DEFAULT NULL,
  `ThoiGian` date DEFAULT NULL,
  `ThanhToan` double DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`MaHD`, `MaTK`, `ThoiGian`, `ThanhToan`, `MoTa`, `TrangThai`) VALUES
(1, 3, '2025-04-10', 680000, 'Mua hàng online', 2),
(2, 4, '2025-04-10', 530000, 'Mua tại cửa hàng', 2),
(3, 3, '2025-04-15', 430000, 'Mua hàng online', 1);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `MaKM` int(11) NOT NULL,
  `TenKM` varchar(200) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL,
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  `giaTriKM` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `TenKM`, `TrangThai`, `NgayBatDau`, `NgayKetThuc`, `giaTriKM`) VALUES
(1, 'Giảm giá mùa hè', 1, '2023-05-01 00:00:00', '2023-05-31 23:59:59', 20.00),
(2, 'Khuyến mãi 30/4', 1, '2023-04-25 00:00:00', '2023-05-05 23:59:59', 15.00),
(3, 'Giảm giá cuối năm', 0, '2022-12-15 00:00:00', '2022-12-31 23:59:59', 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `loai`
--

CREATE TABLE `loai` (
  `MaLoai` int(11) NOT NULL,
  `TenLoai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loai`
--

INSERT INTO `loai` (`MaLoai`, `TenLoai`) VALUES
(1, 'Khách hàng'),
(2, 'Nhân viên'),
(3, 'Quản trị viên');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` int(11) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `TrangThai` int(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `MaLoai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `DiaChi`, `TrangThai`, `Email`, `MaLoai`) VALUES
(1, '123 Đường Admin, TP.HCM', 1, 'admin@shop.com', 3),
(2, '456 Đường NV, TP.HCM', 1, 'nhanvien@shop.com', 2),
(3, '789 Đường KH1, Hà Nội', 1, 'khachhang1@gmail.cm', 2),
(4, '101 Đường KH2, Đà Nẵng', 1, 'khachhang2@gmail.com', 1),
(5, '', 1, '', 1),
(6, '123', 1, '123@gmail.com', 1),
(7, '1232', 1, '123@gmail.com', 1),
(8, '', 1, '', 1),
(9, '', 1, '', 1),
(10, '', 1, '', 1),
(11, '', 1, '', 1),
(12, '', 1, '', 1),
(13, '1232', 1, '123@gmail.com', 1),
(14, '123', 1, '123@gmail.com', 1),
(15, '', 1, '', 1),
(16, 'asdsa', 1, 'nhanvien@shop.com', 1),
(17, '123', 1, '123@gmail.com', 1),
(18, '', 1, '', 1),
(19, '123', 1, '123@gmail.com', 1),
(20, '123', 1, '123@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `MaNCC` int(11) NOT NULL,
  `TenNCC` varchar(255) NOT NULL,
  `TrangThai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`MaNCC`, `TenNCC`, `TrangThai`) VALUES
(1, 'Công ty May mặc Việt Nam', 1),
(2, 'Công ty Thời trang Quốc tế', 1),
(3, 'Nhà cung cấp Vải cao cấp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `MaPN` int(11) NOT NULL,
  `MaTK` int(11) NOT NULL,
  `MaNCC` int(11) NOT NULL,
  `ThanhToan` double NOT NULL,
  `ThoiGian` date NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieunhap`
--

INSERT INTO `phieunhap` (`MaPN`, `MaTK`, `MaNCC`, `ThanhToan`, `ThoiGian`, `TrangThai`) VALUES
(1, 2, 1, 5000000, '2023-04-01', 1),
(2, 2, 2, 7500000, '2023-04-05', 1),
(3, 1, 3, 3000000, '2023-04-10', 1),
(4, 1, 1, 200, '2025-04-29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

CREATE TABLE `quyen` (
  `MaQuyen` int(11) NOT NULL,
  `TenQuyen` varchar(255) NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quyen`
--

INSERT INTO `quyen` (`MaQuyen`, `TenQuyen`, `TrangThai`) VALUES
(1, 'Admin', 1),
(2, 'Nhân viên', 1),
(3, 'Khách hàng', 1),
(5, '0', 1),
(12, 'quản lý kho', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `MaKM` int(11) DEFAULT NULL,
  `MaDM` int(11) DEFAULT NULL,
  `TenSP` varchar(200) DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `GiaBan` double DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL,
  `NgayTao` date DEFAULT NULL,
  `SoLuongTong` int(11) DEFAULT NULL,
  `GioiTinh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `MaKM`, `MaDM`, `TenSP`, `MoTa`, `GiaBan`, `TrangThai`, `NgayTao`, `SoLuongTong`, `GioiTinh`) VALUES
(1, 1, 1, 'Áo thun nam cổ tròn', 'Áo thun cotton thoáng mát', 105000, 1, '2023-01-10', 1100, 1),
(2, NULL, 2, 'Áo sơ mi nữ dài tay', 'Áo sơ mi công sở lịch sự', 250000, 1, '2023-01-15', 80, 0),
(3, 2, 3, 'Quần jean nam ống đứng', 'Quần jean co giãn thoải mái', 350000, 1, '2023-02-01', 60, 1),
(4, NULL, 4, 'Quần short nữ thể thao', 'Quần short thoáng mát cho mùa hè', 180000, 1, '2023-02-10', 120, 0),
(5, NULL, 5, 'Đầm suông nữ cổ V', 'Đầm công sở thanh lịch', 280000, 1, '2023-03-05', 50, 0),
(6, 3, 1, 'Áo thun nữ', 'ddđ', 150000, 1, '2025-04-12', 100, 1),
(7, 3, 2, 'vvvv', 'aaa', 122222, 1, '2025-04-12', 300, 1),
(8, 1, 1, '1111', '111', 123000, 1, '2025-04-12', 111, 1),
(9, 2, 1, '1111111', '12544', 11, 1, '2025-04-12', 120, 1),
(10, NULL, 1, 'sddf', 'ddd', 1250, 1, '2025-04-29', 100, 2);

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `MaSize` int(11) NOT NULL,
  `TenSize` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`MaSize`, `TenSize`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL');

-- --------------------------------------------------------

--
-- Table structure for table `size_sanpham`
--

CREATE TABLE `size_sanpham` (
  `MaSP` int(11) NOT NULL,
  `MaSize` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size_sanpham`
--

INSERT INTO `size_sanpham` (`MaSP`, `MaSize`, `SoLuong`) VALUES
(1, 1, 1020),
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
(5, 4, 5),
(10, 4, 100);

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTK` int(11) NOT NULL,
  `MaQuyen` int(11) NOT NULL,
  `TenTK` varchar(200) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `NgayTaoTK` date NOT NULL,
  `TrangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `MaQuyen`, `TenTK`, `MatKhau`, `NgayTaoTK`, `TrangThai`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-01', 0),
(2, 3, '', '', '2023-01-02', 1),
(3, 2, 'khachhang1', 'Array', '2023-01-03', 1),
(4, 3, 'khachhang2', 'e10adc3949ba59abbe56e057f20f883e', '2023-01-04', 1),
(5, 3, '', '$2y$10$SSN1OkqcYr0Q0BAuY7GaSO2/JYDhEmtyx9SCvOKJoyWYpYilPitKK', '2025-04-29', 1),
(6, 1, 'Hoàng Việt', '$2y$10$CiJZCdvAU2dOQYAHPOKTz.OiRHtovs0HU/ykEXlBPSeahSBNxgpLC', '2025-04-29', 1),
(7, 1, 'Hoàng Việt', '$2y$10$cnGMzkkfqCi/lDoWEh24h.2nO6QbfqbOtbmVzp1ljxgVLljif4MPC', '2025-04-29', 1),
(8, 3, '', '$2y$10$Rb2ifQaEkMhvsuoJpjgSvO5lj9uuIiPnAjkLXK0nkzfZjzqPXmKwq', '2025-04-29', 1),
(9, 3, '', '$2y$10$CI.uZWROtOw25x3wCXcdmuGnT0CPXFMJb0Pb8mJ3z6Llm5uZ7Sjbu', '2025-04-29', 1),
(10, 3, '', '$2y$10$JsX/3Pu/fSWcKdjGC1dJ.Op1UrWZWU3Rdq4d8iwAzvNphBsiJx7IO', '2025-04-29', 1),
(11, 3, '', '$2y$10$YvyK9BVFnJeFMir30kfRD.QDfRWYRLgdcD5PjYmQatYsWiM8WFJU6', '2025-04-29', 1),
(12, 3, '', '$2y$10$dwyqEwxY2ZQ5.D23SmeWmuNElRLloD6ergNXyN0yEDA43J.XB01bK', '2025-04-29', 1),
(13, 1, 'Hoàng Việt', '$2y$10$LWNBCLmPG3nSwYydprsmMulrt9npRURZaQgRPXvGeSFVfMtc3QzQi', '2025-04-29', 1),
(14, 1, 'Hoàng Việt', '$2y$10$rtpYUKmP5Bk0Gq1nsMQen.m0cZHVMQ8BetGHwDMOwocywsGKLUoum', '2025-04-29', 1),
(15, 3, '', '$2y$10$GiekoWsfgXMXcpvjvsL1KuUVvqjcVOCBqaXUtUm12XT1TqR.L1fj6', '2025-04-29', 1),
(16, 3, 'Hoang', '$2y$10$7Um4WpLgH4FzRUJ6W314qes7By6oFl1G8jHI8wXbLgW1EHJ/YJT.e', '2025-04-29', 0),
(17, 1, 'Hoàng Việt', '$2y$10$cnKI39WoYvzzLnqONKGX5OLgKZAth4Dqvgauwgf0dv91DdHiOANty', '2025-04-29', 1),
(18, 3, '', '$2y$10$9DVRC02fAwUl6il6XZLFyuOeTK6Ionay5LXY/lY6d.QhLKNRqB4wa', '2025-04-29', 1),
(19, 1, 'Hoàng Việt', '$2y$10$GHNHeRXjU1rItZb4BdBLnu8P3ZGhrgXN5qCoQ/ZYXnFJhigzrAg4u', '2025-05-04', 1),
(20, 1, 'á', '$2y$10$W6c0MNGfGCLPpNoRQJHb2eVtii1DOW6Mn2ouD.9gNPlyxPp4maxVq', '2025-05-04', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anh`
--
ALTER TABLE `anh`
  ADD PRIMARY KEY (`MaAnh`),
  ADD KEY `fk_anh_sp` (`MaSP`);

--
-- Indexes for table `chitiet_quyen_cn`
--
ALTER TABLE `chitiet_quyen_cn`
  ADD PRIMARY KEY (`MaQuyen`,`MaCTQ`),
  ADD KEY `fk_pq_cn` (`MaCTQ`);

--
-- Indexes for table `chucnang`
--
ALTER TABLE `chucnang`
  ADD PRIMARY KEY (`MaCTQ`);

--
-- Indexes for table `cthoadon`
--
ALTER TABLE `cthoadon`
  ADD PRIMARY KEY (`MaHD`,`MaSP`,`MaSize`),
  ADD KEY `fk_cthoadon_sp_size` (`MaSP`,`MaSize`);

--
-- Indexes for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD PRIMARY KEY (`MaPN`,`MaSP`,`MaSize`),
  ADD KEY `fk_ctphieunhap_sp_size` (`MaSP`,`MaSize`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDM`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`MaHD`),
  ADD KEY `fk_hoadon_taikhoan` (`MaTK`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`MaKM`);

--
-- Indexes for table `loai`
--
ALTER TABLE `loai`
  ADD PRIMARY KEY (`MaLoai`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD KEY `fk_loai_nguoidung` (`MaLoai`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`MaNCC`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`MaPN`),
  ADD KEY `fk_phieunhap_ncc` (`MaNCC`),
  ADD KEY `fk_phieunhap_taikhoan` (`MaTK`);

--
-- Indexes for table `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`MaQuyen`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `fk_sanpham_danhmuc` (`MaDM`),
  ADD KEY `fk_sanpham_km` (`MaKM`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`MaSize`);

--
-- Indexes for table `size_sanpham`
--
ALTER TABLE `size_sanpham`
  ADD PRIMARY KEY (`MaSP`,`MaSize`),
  ADD KEY `fk_sizesp_size` (`MaSize`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTK`),
  ADD KEY `fk_taikhoan_quyen` (`MaQuyen`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anh`
--
ALTER TABLE `anh`
  MODIFY `MaAnh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `chucnang`
--
ALTER TABLE `chucnang`
  MODIFY `MaCTQ` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MaHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MaKM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loai`
--
ALTER TABLE `loai`
  MODIFY `MaLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `MaNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `MaNCC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `MaPN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quyen`
--
ALTER TABLE `quyen`
  MODIFY `MaQuyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `MaSize` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `MaTK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anh`
--
ALTER TABLE `anh`
  ADD CONSTRAINT `fk_anh_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitiet_quyen_cn`
--
ALTER TABLE `chitiet_quyen_cn`
  ADD CONSTRAINT `fk_pq_cn` FOREIGN KEY (`MaCTQ`) REFERENCES `chucnang` (`MaCTQ`),
  ADD CONSTRAINT `fk_pq_quyen` FOREIGN KEY (`MaQuyen`) REFERENCES `quyen` (`MaQuyen`);

--
-- Constraints for table `cthoadon`
--
ALTER TABLE `cthoadon`
  ADD CONSTRAINT `fk_cthoadon_hd` FOREIGN KEY (`MaHD`) REFERENCES `hoadon` (`MaHD`),
  ADD CONSTRAINT `fk_cthoadon_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  ADD CONSTRAINT `fk_cthoadon_sp_size` FOREIGN KEY (`MaSP`,`MaSize`) REFERENCES `size_sanpham` (`MaSP`, `MaSize`);

--
-- Constraints for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD CONSTRAINT `fk_ctphieunhap_pn` FOREIGN KEY (`MaPN`) REFERENCES `phieunhap` (`MaPN`),
  ADD CONSTRAINT `fk_ctphieunhap_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  ADD CONSTRAINT `fk_ctphieunhap_sp_size` FOREIGN KEY (`MaSP`,`MaSize`) REFERENCES `size_sanpham` (`MaSP`, `MaSize`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `fk_hoadon_taikhoan` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Constraints for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `fk_loai_nguoidung` FOREIGN KEY (`MaLoai`) REFERENCES `loai` (`MaLoai`),
  ADD CONSTRAINT `fk_nguoidung_taikhoan` FOREIGN KEY (`MaNguoiDung`) REFERENCES `taikhoan` (`MaTK`);

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_phieunhap_ncc` FOREIGN KEY (`MaNCC`) REFERENCES `nhacungcap` (`MaNCC`),
  ADD CONSTRAINT `fk_phieunhap_taikhoan` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`),
  ADD CONSTRAINT `fk_sanpham_km` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`);

--
-- Constraints for table `size_sanpham`
--
ALTER TABLE `size_sanpham`
  ADD CONSTRAINT `fk_sizesp_size` FOREIGN KEY (`MaSize`) REFERENCES `size` (`MaSize`),
  ADD CONSTRAINT `fk_sizesp_sp` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `fk_taikhoan_quyen` FOREIGN KEY (`MaQuyen`) REFERENCES `quyen` (`MaQuyen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
