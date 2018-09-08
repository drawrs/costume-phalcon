#!/bin/bash
git pull origin master

sudo rm -rf vendor

composer install
bower install

sudo cp .env.example .env
sudo cp phinx.yml.example phinx.yml

mysql -u "root" -p"Mx4jUA2UCMMFGxNbAvux" -e "DROP DATABASE IF EXISTS stainu_sismik; CREATE DATABASE stainu_sismik;"

php qodr phinx migrate

mysql -u root -p"Mx4jUA2UCMMFGxNbAvux" stainu_sismik  < data_master.sql

php qodr phinx seed:run

php qodr vuko:fix
