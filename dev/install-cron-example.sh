#!/bin/bash

ssh root@127.0.0.1 <<'ENDSSH'
sudo crontab -l > backup-cron
echo "*/6 * * * * php -f /opt/wikipedia-analyse/src/cron/save-missing-csfd-in-inbox.php" >> backup-cron
sudo crontab backup-cron
rm backup-cron
ENDSSH
