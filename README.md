# PPDB SD Muhammadiyah

Sistem Penerimaan Peserta Didik Baru  
Dibangun menggunakan **Laravel 10**

## Cara Install

1. git clone repo ini
2. composer install
3. copy .env.example ke .env
4. php artisan key:generate
5. php artisan migrate

## Catatan Perubahan Terbaru (04 April 2026)

- Export PDF & Excel keuangan siswa:
    - Layout tabel modern, zebra striping, dan font besar.
    - Header berisi judul, kelas, waktu cetak, dan sumber sistem.
    - Footer PDF dihapus, info sumber sistem kini di header.
    - Kolom breakdown kekurangan P, DU, UDP, dan total kekurangan per siswa.
    - Baris total semua kekurangan (P, DU, UDP, total) di bawah tabel.
    - Data Excel kini selaras dengan PDF (kolom breakdown kekurangan, urutan, dan format).
    - Nama kelas pada judul otomatis sesuai filter export.
    - Penamaan jenis biaya sudah konsisten dengan enum migrasi.
    - Perhitungan kekurangan diambil dari sisa tagihan per jenis biaya.

- Redesign total halaman admin/pendaftar agar lebih modern dan konsisten.
- Dropdown filter dan export kini tetap berada di atas tabel/card, tidak terpotong konten.
- Sticky footer/pagination selalu rata dengan card, tidak lagi ikut lebar tabel.
- Penambahan identitas sistem "Sistem Pendaftaran Peserta Didik Baru - SD Muhammadiyah Wonorejo" di bawah tabel.
- Perbaikan z-index dan stacking context pada dropdown, modal, dan overlay.
- Standarisasi tombol aksi (dropdown tiga titik) di tabel pendaftar.
- Perbaikan UX filter, export, dan quick edit.
- Optimalisasi responsif dan tampilan mobile.
