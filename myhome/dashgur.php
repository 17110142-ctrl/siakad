 <?php
 function youtube($url)
{
$link = str_replace('http://www.youtube.com/watch?v=', '', $url);
$link = str_replace('https://www.youtube.com/watch?v=', '', $link);
$data = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $link . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
return $data;
}
?>
 <div class="row">
                       
<div class="col" style="background-image: url('../images/bgk.jpg');background-size: cover;">
<table>
				<tr>
					<td>
						<img style="margin:5px;height:70px" src="../images/<?= $setting['logo'] ?>">
					</td>
					<td>
						<div><b style="color:#fff;"><?= $setting['sekolah'] ?> </b></div>
						<div><small style="color:yellow;">SISTEM INFORMASI AKADEMIK (SIAKAD)<small></div>
					</td>
				</tr>
</table>

 <div class="row">
<div class="col-md-5"  style="margin-top:10px;">

				<table border="0" width="100%">
				 
				<tr>
				<td colspan="3" style="text-align:center;color:#fff;">MENU AKADEMIK ADMIN<br><?= $setting['sekolah'] ?></td>
				</tr>
				<tr>
				<td style="text-align:center;"><a href="../mykbm" style="text-decoration:none;color:#fff"><img src="../images/icon/belajar.png" style="max-width:70px"><br>KBM</a></td>
				<td style="text-align:center;"><a href="../myakm" style="text-decoration:none;color:#fff"><img src="../images/icon/ujian.png" style="max-width:70px"><br>ASESMEN</a></td>
				<td style="text-align:center;"><a href="../mylearn" style="text-decoration:none;color:#fff"><img src="../images/icon/buku.png" style="max-width:70px"><br>E LEARN</a></td>
				
				</tr>
				<tr>
				<td style="text-align:center;"><a href="../mykurmer" style="text-decoration:none;color:#fff"><img src="../images/icon/kemdikbud.png" style="max-width:70px"><br>RAPOR KURMER</a></td>
				<td style="text-align:center;"><a href="../myproyek" style="text-decoration:none;color:#fff"><img src="../images/icon/p5.png" style="max-width:70px"><br>RAPOR P5</a></td>
				<td style="text-align:center;"><a href="../mykurtilas" style="text-decoration:none;color:#fff"><img src="../images/icon/k13.png" style="max-width:70px"><br>RAPOR K-13</a></td>
				</tr>
				<tr>
				<td style="text-align:center;"><a href="../myskl" style="text-decoration:none;color:#fff"><img src="../images/icon/amplop.png" style="max-width:70px"><br>S K L</a></td>
				<td style="text-align:center;"><a href="../mypres" style="text-decoration:none;color:#fff"><img src="../images/icon/presensi.png" style="max-width:70px"><br>PRESENSI</a></td>
				<td style="text-align:center;"><a href="../mykonsel" style="text-decoration:none;color:#fff"><img src="../images/icon/survey.png" style="max-width:70px"><br>KONSELING</a></td>
				</tr>
				
				</table>
          </div>
		 <div class="col-md-2" style="text-align:center">
		
		 </div>
		  <div class="col-md-5">

          </div>
		  
		  <br><br> <br><br>
       </div> 
    </div>
   </div>