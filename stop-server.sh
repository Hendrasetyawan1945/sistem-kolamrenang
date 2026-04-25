#!/bin/bash

# Youth Swimming Club - Server Stopper
# Script ini akan menghentikan server Laravel yang berjalan di background

PROJECT_DIR="/var/www/html/youth-swimming-club"
PID_FILE="$PROJECT_DIR/server.pid"

echo "🛑 Stopping Youth Swimming Club Server..."

# Cek apakah ada PID file
if [ ! -f "$PID_FILE" ]; then
    echo "❌ Server tidak berjalan (PID file tidak ditemukan)"
    exit 1
fi

# Baca PID
PID=$(cat "$PID_FILE")

# Cek apakah process masih berjalan
if ! ps -p $PID > /dev/null 2>&1; then
    echo "❌ Server tidak berjalan (PID $PID tidak aktif)"
    rm -f "$PID_FILE"
    exit 1
fi

# Kill process
echo "🔪 Menghentikan server dengan PID: $PID"
kill $PID

# Tunggu sebentar
sleep 2

# Cek apakah berhasil dihentikan
if ps -p $PID > /dev/null 2>&1; then
    echo "⚠️  Server masih berjalan, menggunakan force kill..."
    kill -9 $PID
    sleep 1
fi

# Hapus PID file
rm -f "$PID_FILE"

# Konfirmasi
if ps -p $PID > /dev/null 2>&1; then
    echo "❌ Gagal menghentikan server!"
    exit 1
else
    echo "✅ Server berhasil dihentikan!"
fi