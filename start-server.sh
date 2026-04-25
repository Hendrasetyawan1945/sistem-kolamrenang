#!/bin/bash
# Script untuk menjalankan Laravel di background
# Jalankan: bash start-server.sh

APP_DIR="/var/www/html/youth-swimming-club"
LOG_FILE="$APP_DIR/server.log"
PID_FILE="$APP_DIR/server.pid"

# Cek apakah sudah jalan
if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE") 2>/dev/null; then
    echo "✅ Server sudah berjalan (PID: $(cat $PID_FILE))"
    echo "🌐 Akses di: http://$(hostname -I | awk '{print $1}'):8001"
    exit 0
fi

# Jalankan server
cd "$APP_DIR"
nohup php artisan serve --host=0.0.0.0 --port=8001 > "$LOG_FILE" 2>&1 &
echo $! > "$PID_FILE"

sleep 2
if kill -0 $(cat "$PID_FILE") 2>/dev/null; then
    echo "✅ Server berhasil dijalankan (PID: $(cat $PID_FILE))"
    echo "🌐 Akses di: http://$(hostname -I | awk '{print $1}'):8001"
    echo "📋 Log: tail -f $LOG_FILE"
else
    echo "❌ Server gagal dijalankan. Cek log: $LOG_FILE"
fi
