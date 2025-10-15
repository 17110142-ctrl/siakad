<div class="row">
<div class="col-md-12">
   <div class="card">
       <div class="card-header">
          <a href="." class="btn btn-secondary btn-sm"> <i class="material-icons">west</i><strong class="card-title"> Back</strong></a>       
		<div class="kanan">
		Saldo Rp<?= number_format($siswa['saldo']) ?>
		</div>
		</div>
         <div class="card-body">
		 <table id="tablesaldo" style="width:100%">
		 <tr>
           
		     <td style="text-align:left; vertical-align:center;">Tanggal</td>
			   <td style="text-align:right; vertical-align:center;">Debet</td>
			     <td style="text-align:right; vertical-align:center;">Kredit</td>
			</tr>
		 <?php
			$no=0;
			$query = mysqli_query($koneksi, "SELECT * FROM saldo WHERE idsiswa='$id_siswa' ORDER BY id DESC LIMIT 10"); 
			while ($data = mysqli_fetch_array($query)) :
			
			$no++;
			?>
             <tr>
               
			    <td style="text-align:left; vertical-align:center;"><?= date('d-m-Y',strtotime($data['tanggal'])) ?><br><?= $data['jam'] ?></td>
				 <td style="text-align:right; vertical-align:center;">Rp<?= number_format($data['debet']) ?></td>
				  <td style="text-align:right; vertical-align:center;">Rp<?= number_format($data['kredit']) ?></td>
			</tr>
			<?php endwhile; ?>
			</table>
			
		 </div>
             </div>
                 </div>
                   </div>
				  
				   