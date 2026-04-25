#!/bin/bash

# VPS Backup Script untuk Youth Swimming Club
# Backup database dan file penting

BACKUP_DIR="backups"
DATE=$(date +%Y%m%d_%H%M%S)
PROJECT_NAME="youth-swimming-club"

echo "💾 VPS BACKUP - Youth Swimming Club"
echo "=" $(printf '=%.0s' {1..50})
echo ""

# Create backup directory
mkdir -p $BACKUP_DIR

echo "📦 Creating backup: $DATE"
echo ""

# 1. Backup Database
if [ -f "database/database.sqlite" ]; then
    cp database/database.sqlite "$BACKUP_DIR/database_$DATE.sqlite"
    echo "✅ Database backed up: $BACKUP_DIR/database_$DATE.sqlite"
    
    # Keep only last 10 database backups
    ls -t $BACKUP_DIR/database_*.sqlite | tail -n +11 | xargs -r rm
    echo "🧹 Cleaned old database backups (kept last 10)"
else
    echo "❌ Database not found!"
fi

# 2. Backup .env file
if [ -f ".env" ]; then
    cp .env "$BACKUP_DIR/env_$DATE.txt"
    echo "✅ Environment backed up: $BACKUP_DIR/env_$DATE.txt"
else
    echo "❌ .env file not found!"
fi

# 3. Backup storage (sessions, logs, cache)
if [ -d "storage" ]; then
    tar -czf "$BACKUP_DIR/storage_$DATE.tar.gz" storage/
    echo "✅ Storage backed up: $BACKUP_DIR/storage_$DATE.tar.gz"
    
    # Keep only last 5 storage backups
    ls -t $BACKUP_DIR/storage_*.tar.gz | tail -n +6 | xargs -r rm
    echo "🧹 Cleaned old storage backups (kept last 5)"
fi

# 4. Create system info snapshot
cat > "$BACKUP_DIR/system_info_$DATE.txt" << EOF
=== SYSTEM INFO - $DATE ===
Hostname: $(hostname)
IP: $(curl -s ifconfig.me 2>/dev/null || echo 'Unknown')
Uptime: $(uptime)
Disk Usage: $(df -h .)
Memory: $(free -h)
PHP Version: $(php --version | head -1)
Laravel Version: $(php artisan --version)

=== DATABASE STATS ===
Total Users: $(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
Total Siswa: $(php artisan tinker --execute="echo App\Models\Siswa::count();" 2>/dev/null | tail -1)
Active Sessions: $(php artisan tinker --execute="echo DB::table('sessions')->count();" 2>/dev/null | tail -1)

=== SERVER STATUS ===
Server PID: $(cat server.pid 2>/dev/null || echo 'Not running')
Port 8001: $(netstat -tuln | grep :8001 | wc -l) connections
EOF

echo "✅ System info saved: $BACKUP_DIR/system_info_$DATE.txt"

# 5. Show backup summary
echo ""
echo "📊 BACKUP SUMMARY:"
echo "   Location: $BACKUP_DIR/"
echo "   Files created:"
ls -la $BACKUP_DIR/*$DATE* | while read line; do
    echo "     $line"
done

echo ""
echo "💡 RESTORE COMMANDS:"
echo "   Database: cp $BACKUP_DIR/database_$DATE.sqlite database/database.sqlite"
echo "   Environment: cp $BACKUP_DIR/env_$DATE.txt .env"
echo "   Storage: tar -xzf $BACKUP_DIR/storage_$DATE.tar.gz"

echo ""
echo "✅ Backup complete!"