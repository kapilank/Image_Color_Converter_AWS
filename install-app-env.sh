#!/bin/bash

cd ~

ssh-keyscan -H github.com >> ~/.ssh/known_hosts

sudo runuser -l root -c 'git clone git@github.com:illinoistech-itm/kkumanan.git kkumanan'

sudo cp /root/kkumanan/ITMO-544/MP3/*.php /var/www/html/

sudo rm /var/www/html/index.html
