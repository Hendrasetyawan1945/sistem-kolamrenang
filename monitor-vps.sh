#!/bin/bash

# VPS Monitoring Script untuk Youth Swimming Club
# Monitoring session, database, dan server health

echo "🖥️  VPS MONITORING - Youth Swimming Club"
echo "=" $(printf '=%.0s' {1..50})
echo ""

# Server Info
echo "📊 SERVER INFO:"
echo "   Hostname: $(hostname)"
echo "   IP: $(curl -s ifconfig.me 2>/dev/null || echo 'Unknown')"
echo "   Uptime: $(uptime -p)"
echo "   Load: $(uptime | awk -F'load average:' '{print $2}')"
echo ""

# Laravel Server Status
echo "🚀 LARAVEL SERVER:"
if [ -f "server.pid" ]; then
    PID=$(cat server.pid)
    if ps -p $PID > /dev/null 2>&1; then
        echo "   Status: ✅ RUNNING (PID: $PID)"
        echo "   Port: 8001"
        echo "   URL: http://$(curl -s ifconfig.me 2>/dev/null):8001"
    else
        echo "   Status: ❌ STOPPED"
    fi
else
    echo "   Status: ❌ NOT STARTED"
fi
echo ""

# Database Status
echo "💾 DATABASE STATUS:"
if [ -f "database/database.sqlite" ]; then
    SIZE=$(du -h database/database.sqlite | cut -f1)
    echo "   SQLite: ✅ EXISTS ($SIZE)"
    
    # Check active sessions
    SESSIONS=$(php artisan tinker --execute="echo DB::table('sessions')->count();" 2>/dev/null | tail -1)
    echo "   Active Sessions: $SESSIONS"
    
    # Check users count
    USERS=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
    echo "   Total Users: $USERS"
    
    # Check siswa count
    SISWA=$(php artisan tinker --execute="echo App\Models\Siswa::count();" 2>/dev/null | tail -1)
    echo "   Total Siswa: $SISWA"
else
    echo "   SQLite: ❌ MISSING"
fi
echo ""

# Active Sessions Detail
echo "👥 ACTIVE SESSIONS:"
php artisan tinker --execute="
\$sessions = DB::table('sessions')->get();
foreach(\$sessions as \$session) {
    \$data = unserialize(base64_decode(\$session->payload));
    \$userId = \$data['_token'] ?? 'Guest';
    \$lastActivity = date('Y-m-d H:i:s', \$session->last_activity);
    echo '   Session: ' . substr(\$session->id, 0, 8) . '... | Last: ' . \$lastActivity . PHP_EOL;
}
" 2>/dev/null
echo ""

# Disk Usage
echo "💿 DISK USAGE:"
echo "   Project: $(du -sh . | cut -f1)"
echo "   Storage: $(du -sh storage/ | cut -f1)"
echo "   Logs: $(du -sh storage/logs/ 2>/dev/null | cut -f1 || echo '0B')"
echo ""

# Recent Logs
echo "📋 RECENT ACTIVITY (Last 5 lines):"
if [ -f "server.log" ]; then
    tail -5 server.log | while read line; do
        echo "   $line"
    done
else
    echo "   No server logs found"
fi
echo ""

# Network Test
echo "🌐 NETWORK TEST:"
if curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001 | grep -q "200\|302"; then
    echo "   Local Access: ✅ OK"
else
    echo "   Local Access: ❌ FAILED"
fi

if curl -s -o /dev/null -w "%{http_code}" http://$(curl -s ifconfig.me):8001 2>/dev/null | grep -q "200\|302"; then
    echo "   External Access: ✅ OK"
else
    echo "   External Access: ❌ FAILED"
fi
echo ""

# Recommendations
echo "💡 RECOMMENDATIONS:"
echo "   - Clear old sessions: php artisan session:clear"
echo "   - Monitor logs: tail -f server.log"
echo "   - Backup database: cp database/database.sqlite backup/"
echo "   - Check memory: free -h"
echo ""

echo "✅ Monitoring complete!"