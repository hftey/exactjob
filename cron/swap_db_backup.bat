REM [at] echo off

FOR /F "skip=1 tokens=1-6" %%G IN ('WMIC Path Win32_LocalTime Get Day^,Hour^,Minute^,Month^,Second^,Year /Format:table') DO (
   IF "%%~L"=="" goto s_done
      Set _yyyy=%%L
      Set _mm=00%%J
      Set _dd=00%%G
      Set _hour=00%%H
      SET _minute=00%%I
)
:s_done

Set _mm=%_mm:~-2%
Set _dd=%_dd:~-2%
Set _hour=%_hour:~-2%
Set _minute=%_minute:~-2%

Set _isodate=%_yyyy%%_mm%%_dd%_%_hour%%_minute%

"C:\wamp\bin\mysql\mysql5.6.17\bin\mysqldump" -u root -pqwe098poi wk_delivery > "D:\WebAppDB\backup\%_isodate%_wk_delivery.sql"
"C:\Program Files\7-zip\7z.exe" u -tzip -pqwe098poi "D:\WebAppDB\backup\%_isodate%_wk_delivery.zip" "D:\WebAppDB\backup\%_isodate%_wk_delivery.sql"
del "D:\WebAppDB\backup\*.sql"