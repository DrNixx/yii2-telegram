@echo off

rem -------------------------------------------------------------
rem  Yii command line init script for Windows.
rem
rem  @author Qiang Xue <qiang.xue@gmail.com>
rem  @link http://www.yiiframework.com/
rem  @copyright Copyright (c) 2008 Yii Software LLC
rem  @license http://www.yiiframework.com/license/
rem -------------------------------------------------------------

@setlocal

set YII_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=d:\Workspace\OpenServer\modules\php\PHP_7.4\php.exe

"%PHP_COMMAND%" "%YII_PATH%yii" %*

@endlocal
