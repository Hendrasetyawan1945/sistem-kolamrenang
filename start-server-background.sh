#!/bin/bash

# Youth Swimming Club - Background Server Starter
# Script ini akan menjalankan server Laravel di background

PROJECT_DIR="/var/www/html/youth-swimming-club"
LOG_FILE="$PROJECT_DIR/server.log"
PID_FILE="$PROJECT_DIR/server.pid"

echo "🏊‍♂️ Starting Youth Swimming Club Server..."

# Masuk ke direktori project
cd "$PROJECT_DIR"

# Cek apakah server sudah jalan
if [ -f "$PID_FILE" ]; then
    PID=$(cat "$PID_FILE")
    if ps -p $PID > /dev/null 2>&1; then
        echo "❌ Server sudah berjalan dengan PID: $PID"
        echo "   Akses: http://127.0.0.1:8001"
        exit 1
    else
        echo "🧹 Membersihkan PID file lama..."
        rm -f "$PID_FILE"
    fi
fi

# Jalankan server di background dengan nohup
echo "🚀 Menjalankan server di background..."
nohup php artisan serve --host=0.0.0.0 --port=8001 > "$LOG_FILE" 2>&1 &

# Simpan PID
SERVER_PID=$!
echo $SERVER_PID > "$PID_FILE"

# Tunggu sebentar untuk memastikan server start
sleep 3

# Cek apakah server berhasil start
if ps -p $SERVER_PID > /dev/null 2>&1; then
    echo "✅ Server berhasil dijalankan!"
    echo "   PID: $SERVER_PID"
    echo "   URL: http://127.0.0.1:8001"
    echo "   Log: $LOG_FILE"
    echo ""
    echo "📋 Akun Demo:"
    echo "   Admin: admin@youthswimming.com / admin123"
    echo "   Coach: budi@youthswimming.com / coach123"
    echo "   Siswa: siswa@youthswimming.com / siswa123"
    echo ""
    echo "🛑 Untuk stop server: ./stop-server.sh"
else
    echo "❌ Gagal menjalankan server!"
    rm -f "$PID_FILE"
    exit 1
fi