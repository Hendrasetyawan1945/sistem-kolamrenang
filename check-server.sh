#!/bin/bash

# Youth Swimming Club - Server Status Checker
# Script ini akan mengecek status server

PROJECT_DIR="/var/www/html/youth-swimming-club"
PID_FILE="$PROJECT_DIR/server.pid"
LOG_FILE="$PROJECT_DIR/server.log"

echo "🔍 Checking Youth Swimming Club Server Status..."
echo ""

# Cek PID file
if [ ! -f "$PID_FILE" ]; then
    echo "❌ Server tidak berjalan (PID file tidak ditemukan)"
    echo ""
    echo "🚀 Untuk menjalankan server: ./start-server-background.sh"
    exit 1
fi

# Baca PID
PID=$(cat "$PID_FILE")

# Cek apakah process berjalan
if ps -p $PID > /dev/null 2>&1; then
    echo "✅ Server BERJALAN"
    echo "   PID: $PID"
    echo "   URL: http://127.0.0.1:8001"
    echo "   Log: $LOG_FILE"
    echo ""
    
    # Cek port
    if netstat -tuln | grep -q ":8001 "; then
        echo "🌐 Port 8001: AKTIF"
    else
        echo "⚠️  Port 8001: TIDAK AKTIF"
    fi
    
    # Tampilkan log terakhir
    if [ -f "$LOG_FILE" ]; then
        echo ""
        echo "📋 Log terakhir (5 baris):"
        tail -5 "$LOG_FILE"
    fi
    
    echo ""
    echo "📋 Akun Demo:"
    echo "   Admin: admin@youthswimming.com / admin123"
    echo "   Coach: budi@youthswimming.com / coach123"
    echo "   Siswa: siswa@youthswimming.com / siswa123"
    
else
    echo "❌ Server tidak berjalan (PID $PID tidak aktif)"
    rm -f "$PID_FILE"
    echo ""
    echo "🚀 Untuk menjalankan server: ./start-server-background.sh"
fi