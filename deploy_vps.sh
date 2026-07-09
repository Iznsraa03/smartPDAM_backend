#!/bin/bash

# Script untuk melakukan deploy perubahan dari GitHub ke VPS
# Pastikan Anda sudah menjalankan 'git commit' dan 'git push' di lokal sebelum menjalankan script ini.

echo "Menghubungkan ke server VPS..."

ssh -i /Users/potah/Documents/ssh/bowprime.pem bowprime@103.127.139.156 << 'EOF'
  echo "Berhasil masuk ke VPS!"
  cd /var/www/lombainsancendekia || exit

  echo "Mengambil pembaruan terbaru dari repositori GitHub..."
  sudo git pull origin main

  echo "Memperbarui dependensi (Composer)..."
  sudo -u www-data composer install --no-dev --optimize-autoloader

  echo "Menjalankan migrasi database..."
  sudo -u www-data php artisan migrate --force

  echo "Membersihkan dan membangun ulang cache Laravel..."
  sudo -u www-data php artisan optimize:clear
  sudo -u www-data php artisan config:cache
  sudo -u www-data php artisan route:cache
  sudo -u www-data php artisan view:cache

  echo "Memuat ulang layanan PHP-FPM..."
  sudo systemctl reload php8.4-fpm

  echo "========================================="
  echo "✅ Deploy berhasil diselesaikan!"
  echo "========================================="
EOF
