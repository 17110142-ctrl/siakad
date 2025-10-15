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

				
          </div>
		 <div class="col-md-2" style="text-align:center">
		
		 </div>
		  <div class="col-md-5">
<table border="0" width="100%">
				 
				<tr>
				<td colspan="3" style="text-align:center;color:#fff;">MENU NON AKADEMIK ADMIN<br><?= $setting['sekolah'] ?></td>
				</tr>
				
				<tr>
				<td style="text-align:center;"><a href="../mypras" style="text-decoration:none;color:#fff"><img src="../images/icon/sapras.png" style="max-width:70px"><br>SAPRAS</a></td>
				<td style="text-align:center;"><a href="#" style="text-decoration:none;color:#fff"><img src="../images/icon/pustaka.png" style="max-width:70px"><br>PERPUS</a></td>
				<td style="text-align:center;"><a href="../myperpus" style="text-decoration:none;color:#fff"><img src="../images/icon/payment.png" style="max-width:70px"><br>PAYMENT</a></td>
				</tr>
				<tr>
			    <td style="text-align:center;"><a href="../mypres" style="text-decoration:none;color:#fff"><img src="../images/icon/presensi.png" style="max-width:70px"><br>PRESENSI</a></td>
			    <td style="text-align:center;"><a href="../myskl" style="text-decoration:none;color:#fff"><img src="../images/icon/amplop.png" style="max-width:70px"><br>S K L</a></td>
				<td style="text-align:center;"><a href="#" style="text-decoration:none;color:#fff"><img src="../images/icon/arsip.png" style="max-width:70px"><br>ARSIP</a></td>
				<td style="text-align:center;"><a href="#" style="text-decoration:none;color:#fff"><img src="../images/icon/kantin.png" style="max-width:70px"><br>KANTIN</a></td>
				<td style="text-align:center;"><a href="#" style="text-decoration:none;color:#fff"><img src="../images/icon/vote.png" style="max-width:70px"><br>VOTE</a></td>
				</tr>
				
				</table>
				
          </div>
		  
		  <br><br> <br><br>
       </div> 
    </div>
   </div>