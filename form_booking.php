<style>
th{
	text-align:center !important;
}
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 350px;
      }
	  .img_kapasitas_{
		  width:100%;
	  }
	  .img_kapasitas_disabled{
		  width:100%;
		  opacity:0.3;
	  }
		label > input{ /* HIDE RADIO */
		  visibility: hidden; /* Makes input not-clickable */
		  position: absolute; /* Remove input from document flow */
		}
		label > input + img{ /* IMAGE STYLES */
		  cursor:pointer;
		  border:2px solid transparent;
		}
		label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
		  border:2px solid #f00;
		}
    </style>
<?php
	if(isset($_GET['ruangan'])){
		$_SESSION['ruangan'] = $_GET['ruangan'];
		$data = array('ql' => "select * where name='".$_GET['ruangan']."'");		
		//reading data ruangan
		$ruangans = $client->get_collection('ruangans',$data);
		//do something with the data
		while ($ruangans->has_next_entity()) {
			$ruangan = $ruangans->get_next_entity();
			$_SESSION['get_ruangan'] = $ruangan;
			$_SESSION['ruanganUUID'] = $ruangan->get('uuid');
		}
	}else{
		echo '<meta http-equiv="refresh" content="0; url=?page=dashboard">';
	}
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-body">
			<div class="col-md-5">
				<h5 class=" text-bold"><?php echo $ruangan->get('name'); ?></h5>
				<hr>
				<img class="thumbnail" src="<?php echo $ruangan->get('foto'); ?>" style="width:100%" alt="...">
				<table class="table table-bordered table-striped" style="font-size: small;">
					<tr>
						<th>KAPASITAS RATA-RATA</th><th>FASILITAS</th>
					</tr>
					<tr>
						<td>+- <?php echo $ruangan->get('kapasitas')['rata-rata'];?> orang</td>
						<td> <?php echo $ruangan->get('fasilitas'); ?></td>
					</tr>
				</table>				
					<div id="map"></div>
						<script>
						  var map;
						  function initMap() {
							var myLatLng = {lat: -6.873189, lng: 107.586871};

							var map = new google.maps.Map(document.getElementById('map'), {
							  zoom: 16,
							  center: myLatLng
							});
							 var contentString = '<div id="content">'+
							'<div id="siteNotice">'+
							'</div>'+
							'<h5 id="firstHeading" class="firstHeading">Alamat Lengkap</h5>'+
							'<div id="bodyContent">'+
							'<p><?php echo $ruangan->get('alamat'); ?></p>'+
							'</div>'+
							'</div>';
							var infowindow = new google.maps.InfoWindow({
							  content: contentString,
							  maxWidth: 300
							});
							var marker = new google.maps.Marker({
							  position: myLatLng,
							  map: map,
							  title: '<?php echo $ruangan->get('name');?>'
							});
							marker.addListener('click', function() {
							  infowindow.open(map, marker);
							});
						  }
						</script>
						<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrOoiHmLAVvbfMP1ho5S0Y8oS0K8w-K0A&callback=initMap"
						async defer></script>
			</div>
			<div class="col-md-7">
				<h5 class=" text-bold">DATA PEMINJAMAN RUANGAN</h5>
				<hr>
				<form class="form-vertical" action="" method="post">
					<div class="form-group">
						<label class="form-label text-bold">Nama Meeting/Rapat : </label>
						<input type="text" name="nama" class="form-control" placeholder="Nama meeting/rapat anda" required>
						<input type="hidden" name="harga" value="0">
					</div>
					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">Data Tamu/Peserta</a></li>
						<li role="presentation"><a href="#waktu" aria-controls="waktu" role="tab" data-toggle="tab">Tanggal & Waktu</a></li>
					  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="data">
						<div class="form-group" >
							<br>
							<label class="form-label text-bold">Pilih Bentuk Pemakaian Ruangan : ( <span class="glyphicon glyphicon-user" aria-hidden="true"></span> = orang )</label>
							<table class="table">
								<?php
									$banquet = ($ruangan->get('kapasitas')['banquet'] == 0 ? "disabled" : "");
									$boardroom = ($ruangan->get('kapasitas')['boardroom'] == 0 ? "disabled" : "");
									$classroom = ($ruangan->get('kapasitas')['classroom'] == 0 ? "disabled" : "");
									$doubleushape = ($ruangan->get('kapasitas')['doubleushape'] == 0 ? "disabled" : "");
									$roundtable = ($ruangan->get('kapasitas')['roundtable'] == 0 ? "disabled" : "");
									$theatre = ($ruangan->get('kapasitas')['theatre'] == 0 ? "disabled" : "");
									$ushape = ($ruangan->get('kapasitas')['ushape'] == 0 ? "disabled" : "");
									
								?>
								<tr style="font-size:small;font-weight:bold;text-align:center;color: #9E9E9E;">
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="banquet" <?php echo $banquet; ?>/>
											<img class="img_kapasitas_<?php echo $banquet; ?>" src="img/kapasitas/venue-layout-banquet.png"/><br><?php echo $ruangan->get('kapasitas')['banquet']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="boardroom" <?php echo $boardroom; ?>/>
											<img class="img_kapasitas_<?php echo $boardroom; ?>" src="img/kapasitas/venue-layout-boardroom.png" /><br><?php echo $ruangan->get('kapasitas')['boardroom']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="classroom" <?php echo $classroom; ?> />
											<img class="img_kapasitas_<?php echo $classroom; ?>" src="img/kapasitas/venue-layout-classroom.png" /><br><?php echo $ruangan->get('kapasitas')['classroom']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></td>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="doubleushape" <?php echo $doubleushape; ?>/>
											<img class="img_kapasitas_<?php echo $doubleushape; ?>" src="img/kapasitas/venue-layout-doubleushape.png" /><br><?php echo $ruangan->get('kapasitas')['doubleushape']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></td>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="roundtable" <?php echo $roundtable; ?>/>
											<img class="img_kapasitas_<?php echo $roundtable; ?>" src="img/kapasitas/venue-layout-roundtable.png" /><br><?php echo $ruangan->get('kapasitas')['roundtable']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></td>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="theatre" <?php echo $theatre; ?>/>
											<img class="img_kapasitas_<?php echo $theatre; ?>" src="img/kapasitas/venue-layout-theatre.png" /><br><?php echo $ruangan->get('kapasitas')['theatre']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></td>
										</label>
									</td>
									<td>
										<label>
											<input type="radio" name="bentuk_ruangan" value="ushape" <?php echo $ushape; ?>/>
											<img class="img_kapasitas_<?php echo $ushape; ?>" src="img/kapasitas/venue-layout-ushape.png" /><br><?php echo $ruangan->get('kapasitas')['ushape']; ?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></td>
										</label>
									</td>
								</tr>
							</table>
						</div>	
						<div class="form-group" >
							<div class="col-md-3" style="padding:0px 10px 0px 0px">
									<?php
										echo'<label class="form-label text-bold">Jumlah Orang :</label>';
										echo'<input class="form-control" type="number" value="1" min="0" name="jumlah" required/>';
									?>							
								
							</div>
							<div class="col-md-4" style="padding:0px;">
								<label class="form-label text-bold">Nama Tamu<small>(Jika Ada)</small> : </label>
								<input type="text" name="tamu" id="tamu" class="form-control" placeholder="Nama Tamu">
							</div>
							<div class="col-md-4" style="padding:0px;">
								<label class="form-label text-bold"><small>Jabatan Tamu(optional)</small></label>
								<input type="text" name="tamu_jabatan" id="tamu_jabatan" class="form-control" placeholder="Jabatan Tamu">
							</div>
							<div class="col-md-1" style="padding:0px;">
								<label class="form-label text-bold">&nbsp &nbsp </label>
								<button onclick="addTamu()" class="btn btn-default" style="padding: 11px 9px;"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
							</div>
							<div class="col-md-9 col-md-offset-3" style="padding-left:0px;display:block;" id="daftar_tamu">
								<?php
									if(count($_SESSION['daftar_tamu']) > 0){
										echo "
										<table class='table table-bordered table-condensed'>
											<thead>
												<tr>
													<th style='width: 46.3%;'><font size='1'>NAMA</font></th>
													<th><font size='1'>JABATAN</font></th>
													<th width='1%'><font size='1'></font></th>
												</tr>
											</thead>
											<tbody>
												";
												$daftar_tamu = explode(",",$_SESSION['daftar_tamu']);
												for($i=1;$i<count($daftar_tamu);$i++){												
													$tamu = explode("?",$daftar_tamu[$i]);
													echo "
														<tr>
															<td><font size='2'>".$tamu[0]."</font></td>
															<td><font size='2'>".$tamu[1]."</font></td>
															<td><font size='1' style='cursor:pointer;'><a title='Hapus Tamu' onclick=deleteTamu('".$tamu[0]."')>[HAPUS]</a></font></td>
														</tr>
													";				
												}
											echo "
											</tbody>
										</table>
										";
									}
								?>
							</div>
						</div>							
						<br><br><br><br>
						<div class="form-group col-md-12" style="padding:0px;">
							<div class="col-md-12" style="padding:0px 10px 0px 0px;">
								<div class="col-md-12" style="padding:0px 10px 0px 0px;">
								<label class="form-label text-bold">Konnsumsi Yang Dipesan : <small>(optional)</small></label>
								</div>
								<div class="col-md-6" style="padding:0px 5px 0px 0px;">
									<button type="button" style="width:100%;" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_makanan">Pilih Makanan</button>
									<!-- <select class="selectpicker" data-size="4" name="makanan[]" multiple title="Tidak ada yang dipilih" data-live-search="true" data-width="100%">
										<?php
									/**		if($ruangan->get('makanan') != 'Tidak Tersedia'){
												$makanan = explode(",",$ruangan->get('makanan'));
												for($i=0;$i<count($makanan);$i++){
													echo"<option>".$makanan[$i]."</option>";
												}
											}else{
												echo"<option>Tidak Tersedia</option>";
											}
									**/	?>
									</select>
									-->
									<textarea name="makanan_keterangan" class="form-control" rows="2" placeholder="Catatan Pesanan Makanan"></textarea>
								</div>
								<div class="col-md-6" style="padding:0px 0px 0px 5px;">
									<button type="button" style="width:100%;" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_minuman">Pilih Minuman</button>
									<!-- <select class="selectpicker" data-size="4" name="minuman[]" multiple title="Tidak ada yang dipilih" data-live-search="true" data-width="100%">
									  <?php
									/**		if($ruangan->get('minuman') != 'Tidak Tersedia'){
												$minuman = explode(",",$ruangan->get('minuman'));
												for($i=0;$i<count($minuman);$i++){
													echo"<option>".$minuman[$i]."</option>";
												}
											}else{
												echo"<option>Tidak Tersedia</option>";
											}
									**/	?>
									</select>
									-->
									<textarea name="minuman_keterangan" class="form-control" rows="2" placeholder="Catatan Pesanan Minuman"></textarea>
								</div>
							</div>
							<div class="col-md-6" style="padding:0px 10px 0px 0px;">
								<br>
								<label class="form-label text-bold">Fasilitas Tambahan :</label>
								<textarea name="fasilitas_tambahan" class="form-control" rows="4" placeholder="Deskripsikan kebutuhan tambahan anda disini"></textarea>
							</div>
							<div class="col-md-6" style="padding:0px 10px 0px 0px;">
								<br>
								<label class="form-label text-bold">Metode Pembayaran :</label>
								<div class="col-md-12" >
									<label>
										<input type="radio" name="pembayaran" value="gratis" checked>
										<img class="img_kapasitas" src="img/pembayaran/free.png" style="width:75px" />
									</label>
									<label>
										<input type="radio" name="pembayaran" value="tmoney">
										<img class="img_kapasitas" src="img/pembayaran/tmoney.jpg" style="width:75px" />
									</label>
								</div>
							</div>							
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="waktu">
						<br>
						<div class="form-group form-group-sm">
							<div class="col-md-5" style="padding:0px 10px 0px 0px;">
								<label class="form-label text-bold">Tanggal :</label>
								<div class='input-group date' id='datepicker'>
									<input type='text' class="form-control" id="tanggal" name="tanggal" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>

							</div>
							<label class="form-label text-bold">Waktu Pemakaian :</label>
							<div class="col-md-7" style="padding:0px">
								<div class="col-md-5" style="padding:0px">
									<select class="form-control" name="jam_mulai" required>
										<?php
											for($i=8;$i<=16;$i++){
												echo"<option value='$i'> ".sprintf('%02d', $i).":00 </option>";
											}
										?>							
									</select>
								</div>
								<div class="col-md-2" style="padding-top:1%">
									<small style="">s/d</small>
								</div>
								<div class="col-md-5" style="padding:0px">
									<select class="form-control" name="jam_selesai" required>
										<?php
											for($i=9;$i<=17;$i++){
												echo"<option value='$i'> ".sprintf('%02d', $i).":00 </option>";
											}
										?>							
									</select>
								</div>
							</div>
						</div>	
						<div class="form-group form-group-sm">
							<br>
							<div class="table-responsive">
							<table class="table table-bordered" style="font-size:15px;" id="available_waktu">
								<thead>
								<tr>
									<th colspan="2">Ket : </th>
									<td style="background-color:#00964a"></td><td colspan="2">= Tersedia</td>
									<td style="background-color:#cc2b36"></td><td colspan="4">= Tidak Tersedia</td>
								</tr>
								<tr style="border-top:2px solid grey;">
									<th>#</th>
									<?php
									$day =  date('l',strtotime("-1 day"));										
									$tanggal = date('d/m/Y',strtotime("-1 day"));	
									switch($day){
										case "Sunday" : $day = strtoupper("Minggu");break;
										case "Monday" : $day = strtoupper("Senin");break;
										case "Tuesday" : $day = strtoupper("Selasa");break;
										case "Wednesday" : $day = strtoupper("Rabu");break;
										case "Thursday" : $day = strtoupper("Kamis");break;
										case "Friday" : $day = strtoupper("Jumat");break;
										case "Saturday" : $day = strtoupper("Sabtu");break;
									}
									echo "<th><small>$day<br><font size='1'>$tanggal</font></small></th>";
									for($i=0;$i<=7;$i++){
										$day =  date('l',strtotime("+$i day"));										
										$tanggal = date('d/m/Y',strtotime("+$i day"));	
										switch($day){
											case "Sunday" : $day = strtoupper("Minggu");break;
											case "Monday" : $day = strtoupper("Senin");break;
											case "Tuesday" : $day = strtoupper("Selasa");break;
											case "Wednesday" : $day = strtoupper("Rabu");break;
											case "Thursday" : $day = strtoupper("Kamis");break;
											case "Friday" : $day = strtoupper("Jumat");break;
											case "Saturday" : $day = strtoupper("Sabtu");break;
										}
										echo "<th><small>$day<br><font size='1'>$tanggal</font></small></th>";
									}
									?>
								</tr>
								</thead>
								<tbody style="font-size:12px;">
									<?php
										for($i=8;$i<17;$i++){
											$row="";
											if(strlen($i) == 1){
												$jam = "0".$i;
												$jam_akhir = $i+1;
												if(strlen($jam_akhir) == 1){
													$jam_akhir = "0".$jam_akhir;
												}
											}else{
												$jam = $i;
												$jam_akhir = $i+1;
											}
											$row = "
											<tr>
												<th>$jam:00</th>";
												$tanggal = date('d/m/Y',strtotime("-1 day"));																						
												
												$data = array('ql' => "select * where ruangan='".$ruangan->get('uuid')."' AND tanggal='".$tanggal."' AND (jammulai<=".$i." AND jamselesai>=".($i+1).")");		
												//reading data ruangan
												$pinjams = $client->get_collection('pinjams',$data);
												//do something with the data
												if($pinjams->has_next_entity()){
													$pinjam = $pinjams->get_next_entity();
													$row .= "<td style='tidak tersedia'><small>".strtoupper($pinjam->get('name'))."</small></td>";														
												}else{
													$row .= "<td style='tersedia'></td>";														
												}	
											for($x=0;$x<=7;$x++){
												$tanggal = date('d/m/Y',strtotime("+$x day"));																						
												
												$data = array('ql' => "select * where ruangan='".$ruangan->get('uuid')."' AND tanggal='".$tanggal."' AND (jammulai<=".$i." AND jamselesai>=".($i+1).")");		
												//reading data ruangan
												$pinjams = $client->get_collection('pinjams',$data);
												//do something with the data
												if($pinjams->has_next_entity()){
													$pinjam = $pinjams->get_next_entity();
													$row .= "<td style='tidak tersedia'><small>".strtoupper($pinjam->get('name'))."</small></td>";														
												}else{
													$row .= "<td style='tersedia'></td>";														
												}												
											}
											$row = $row."</tr>";
											$row = str_replace("style='tersedia'","style='background-color:white;color:black; '",$row);
											$row = str_replace("style='tidak tersedia'","style='background-color:#cc2b36;color:white;vertical-align: middle;text-align: center;'",$row);
											echo $row."</tr>";
										}
										
									?>
								</tbody>
							</table>
							</div>
						</div>	
					</div>
				  </div>
					<div class="form-group" >
						<input type="submit" name="selesai" class="btn btn-success pull-right" value="BOOKING SEKARANG">
					</div>
					<!-- ModalMakanan -->
					<div id="modal_makanan" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- konten modal-->
							<div class="modal-content">
								<!-- heading modal -->
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h5 class="modal-title">Menu Makanan <?php echo ucfirst($_GET['ruangan']); ?></h5>
								</div>
								<!-- body modal -->
								<div class="modal-body" style="max-height: 450px;overflow-y: auto;">
									<div>
									<table class="table table-condensed">
										<tbody>										
										<?php
											for($lmk=0;$lmk<4;$lmk++){
										?>
										<tr>
											<td width="20%"><img width="100%" class="thumbnail" src="https://pbs.twimg.com/profile_images/651265569031745537/jOGHunFi.jpg" ></td>
											<td width="65%"><input type="hidden" name="menu_makanan[<?php echo $lmk;?>]" value="ayam<?php echo $lmk;?>">
												<strong>Nasi Ayam Bakar</strong><br>
												<strong><small>Harga :</strong><br><input type="hidden" name="harga_makanan[<?php echo $lmk;?>]" value="13000">Rp 13.000 per porsi</small>
											</td>
											<td width="15%"><strong><small>Qty :</small></strong><br><input type="number" class="form-control" value="0" min="0" name="qty_makanan[<?php echo $lmk;?>]"></td>
										</tr>
										<?php	
											}
										?>
										</tbody>
									</table>
									</div>
								</div>
								<!-- footer modal -->
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">SELESAI</button>
								</div>
							</div>
						</div>
					</div>
					<!-- ModalMinuman -->
					<div id="modal_minuman" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- konten modal-->
							<div class="modal-content">
								<!-- heading modal -->
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h5 class="modal-title">Menu Minuman <?php echo ucfirst($_GET['ruangan']); ?></h5>
								</div>
								<!-- body modal -->
								<div class="modal-body" style="max-height: 450px;overflow-y: auto;">
									<div>
									<table class="table table-condensed">
										<tbody>										
										<?php
											for($lmn=0;$lmn<2;$lmn++){
										?>
										<tr>
											<td width="20%"><img width="100%" class="thumbnail" src="https://photos.bigoven.com/recipe/hero/resep-balado-telur-pedas-enak-1653998.jpg?h=256&w=256" ></td>
											<td width="65%"><input type="hidden" name="menu_minuman[<?php echo $lmn;?>]" value="telur<?php echo $lmn;?>">
												<strong>Nasi Telur Balado</strong><br>
												<strong><small>Harga :</strong><br><input type="hidden" name="harga_minuman[<?php echo $lmn;?>]" value="10000">Rp 10.000 per porsi</small>
											</td>
											<td width="15%"><strong><small>Qty :</small></strong><br><input type="number" class="form-control" value="0" min="0" name="qty_minuman[<?php echo $lmn;?>]"></td>
										</tr>
										<?php	
											}
										?>
										</tbody>
									</table>
									</div>
								</div>
								<!-- footer modal -->
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">SELESAI</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
