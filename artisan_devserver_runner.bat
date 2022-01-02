@echo off 
SET EXITCODE=0
echo -LUMEN DEVELOPMENT SERVER RUNNER-
echo Please Wait...
echo Press CTRL+C to Terminate
ver
php -ver
echo ----------
php -S localhost:7020 -t public
echo Bye