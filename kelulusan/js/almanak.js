browserName=navigator.appName;
browserVer=parseInt(navigator.appVersion);

if ((browserName=="Netscape" && browserVer>=4) || (browserName=="Microsoft Internet Explorer" && browserVer>=4))
version="n3";
else
version="n2";


var now = new Date();
var hours = now.getHours();
var minutes = now.getMinutes();

mydate = new Date();
myday = mydate.getDay();
mymonth = mydate.getMonth();
myweekday= mydate.getDate();
weekday= myweekday;
myyear= mydate.getYear();
year = myyear

if (version=="n3") {

   if(myday == 0) day = " MINGGU, "
   else if(myday == 1) day = " SENIN, "
   else if(myday == 2) day = " SELASA, "
   else if(myday == 3) day = " RABU, "
   else if(myday == 4) day = " KAMIS, "
   else if(myday == 5) day = " JUMAT, "
   else if(myday == 6) day = " SABTU, "

   if(mymonth == 0) month = " JANUARI "
   else if(mymonth ==1) month = " FEBRUARI "
   else if(mymonth ==2) month = " MARET "
   else if(mymonth ==3) month = " APRIL "
   else if(mymonth ==4) month = " MEI "
   else if(mymonth ==5) month = " JUNI "
   else if(mymonth ==6) month = " JULI "
   else if(mymonth ==7) month = " AGUSTUS "
   else if(mymonth ==8) month = " SEPTEMBER "
   else if(mymonth ==9) month = " OKTOBER "
   else if(mymonth ==10) month = " NOPEMBER "
   else if(mymonth ==11) month = " DESEMBER "

if ((navigator.appName == "Microsoft Internet Explorer") && (year < 2000))		
year="19" + year;
if (navigator.appName == "Netscape")
year=1900 + year;

   document.write(day + myweekday);
   document.write(month+ ""+ year );
}