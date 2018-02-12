#!/bin/bash

cd ~

ssh-keyscan -H github.com >> ~/.ssh/known_hosts

sudo runuser -l root -c 'git clone git@github.com:illinoistech-itm/kkumanan.git kkumanan'

sudo cp /root/kkumanan/ITMO-544/MP3/decoupled-processor.php /home/ubuntu/

echo 'www-data  ALL=(ALL:ALL) ALL' >> /etc/sudoers

echo 'apache  ALL=(ALL:ALL) ALL' >> /etc/sudoers

crontab -e | { cat; echo "*/1 * * * * php /home/ubuntu/decoupled-processor.php >> /home/ubuntu/vimbackup.log 2>&1"; } | crontab -
