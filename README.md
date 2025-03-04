<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Supervisor
#### Steps to setup 
##### Step 1: Copy Supervisor Configurations
```shell
  cd ~/dev.trainer-workout.com
  cp -r supervisor ~/supervisor
```
##### Step 2: Restart Supervisor
```shell
  pkill -f supervisord
  rm -f ~/supervisor/supervisor.sock
  supervisord -c ~/supervisor/supervisord.conf
```
##### Step 3: Verify Supervisor is Running
```shell
  supervisorctl -c ~/supervisor/supervisord.conf status
```
💡 If you see “no such file” errors, make sure supervisor.sock exists inside ~/supervisor/.
#### Logs and Debugging
#### Check logs to identify issues:
```shell
  cat ~/supervisor/supervisord.log
```
#### Restart Laravel worker if needed:
```shell
  supervisorctl -c ~/supervisor/supervisord.conf restart laravel-worker
```