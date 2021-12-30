#!/bin/bash
if [[ $EUID -eq 0 ]]; then
  echo "This script must NOT be run as root" 1>&2
  exit 1
fi
sudo -u www-data git reset --hard "HEAD@{1}"
./installAndClearCaches.sh
