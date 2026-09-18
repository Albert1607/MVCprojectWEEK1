@echo off
title MediCare Clinic - Pure PHP MVC
echo ====================================================
echo       MediCare Clinic Appointment Portal
echo             Pure PHP MVC Architecture
echo ====================================================
echo.

:: Detect PHP executable
set PHP_BIN=php
if exist "C:\xampp\php\php.exe" (
    set PHP_BIN="C:\xampp\php\php.exe"
)

echo [1/2] Checking database and seeding sample data...
%PHP_BIN% database\seed.php
if %ERRORLEVEL% neq 0 (
    echo [ERROR] Database initialization failed.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [2/2] Starting PHP Development Server at http://localhost:8000
echo Document Root: public/
echo.
echo Press Ctrl+C in this window to stop the server anytime.
echo.

:: Open browser automatically
start http://localhost:8000

:: Start server
%PHP_BIN% -S localhost:8000 -t public
pause
