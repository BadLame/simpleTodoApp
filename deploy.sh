#!/bin/bash

set -e

git init
git clone https://github.com/BadLame/simpleTodoApp.git
cd ./simpleTodoApp
composer install
npm install
read -n 1 -p "Make sure to create database for app then press enter"
cp .env.example .env


dataIsCorrect=""

while [ "$dataIsCorrect" = "" ]; do
  echo -e "\n"

  read -p "DB_CONNECTION [mysql]: " db_conn
  if [ "$db_conn" = "" ]; then
    db_conn="mysql"
  fi
  sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=$db_conn/" .env

  read -p "DB_HOST [127.0.0.1]: " db_host
  if [ "$db_host" = "" ]; then
    db_host="127.0.0.1"
  fi
  sed -i "s/DB_HOST=.*/DB_HOST=$db_host/" .env

  read -p "DB_PORT [3306]: " db_port
  if [ "$db_port" = "" ]; then
    db_port="3306"
  fi
  sed -i "s/DB_PORT=.*/DB_PORT=$db_port/" .env

  while [ "$db_database" = "" ]; do
    read -p "DB_DATABASE: " db_database
    if [ "$db_database" = "" ]; then
      echo -e "\nPlease type db name"
    fi
  done
  sed -i "s/DB_DATABASE=.*/DB_DATABASE=$db_database/" .env

  while [ "$db_user" = "" ]; do
    read -p "DB_USERNAME: " db_user
    if [ "$db_user" = "" ]; then
      echo -e "\nPlease type db username"
    fi
  done
  sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_user/" .env

  while [ "$db_password" = "" ]; do
    read -p "DB_PASSWORD: " db_password
    if [ "$db_password" = "" ]; then
      echo -e "\nPlease type db password"
    fi
  done
  sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_password/" .env

  echo -e "\n"
  echo -e "DB_CONNECTION=$db_conn"
  echo -e "DB_HOST=$db_host"
  echo -e "DB_PORT=$db_port"
  echo -e "DB_DATABASE=$db_database"
  echo -e "DB_USERNAME=$db_user"
  echo -e "DB_PASSWORD=$db_password"

  read -N 1 -p "Is data correct? [Y/n]: " dataIsCorrect

  if [ "$dataIsCorrect" = "n" ]; then
    dataIsCorrect=""
    db_conn="mysql"
    db_host="127.0.0.1"
    db_port="3306"
    db_database=""
    db_user=""
    db_password=""
  fi
done


php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
