<?php
//BindEvents Method @1-FA3AC75D
function BindEvents()
{
    global $CCSEvents;
	$CCSEvents["BeforeShow"] = "Page_BeforeShow";
    //$CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
}
//End BindEvents Method

function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_rep_lap_bpps_piutang2; //Compatibility
//End Page_BeforeShow
	global $Label1;
// -------------------------
    // Write your own code here.

	$doAction = CCGetFromGet('doAction');
	if($doAction == 'view_html') {
		
		$p_vat_type_id		= CCGetFromGet("p_vat_type_id", "");
		$p_year_period_id	= CCGetFromGet("p_year_period_id", "");
		$tgl_penerimaan		= CCGetFromGet("tgl_penerimaan", "");
		$i_flag_setoran		= CCGetFromGet("i_flag_setoran", "");
		$tgl_penerimaan_last = CCGetFromGet("tgl_penerimaan_last", "");
		$year_code = CCGetFromGet("year_code", "");

		$tgl_penerimaan = "'".$tgl_penerimaan."'";
		$tgl_penerimaan_last = "'".$tgl_penerimaan_last."'";
		

		// $p_vat_type_id		= 1;
		// $p_year_period_id	= 4;
		// $tgl_penerimaan		= '15-12-2013';
		$date_start=str_replace("'", "",$year_code);
		//$year_date = DateTime::createFromFormat('d-m-Y', $date_start)->format('Y');
		$year_date = $year_code; 		

		$user				= CCGetUserLogin();
		$data				= array();
		$dbConn				= new clsDBConnSIKP();
		$jenis_laporan		= CCGetFromGet("jenis_laporan", "all"); 
		if($jenis_laporan == 'all'){
			$query	= "select *,trunc(payment_date) 
			from f_rep_bpps_piutang2new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) 
			order by kode_ayat, npwpd, masa_pajak";	
			//echo $query;
			//exit;
		}else if($jenis_laporan == 'piutang'){
			$border= $year_date-1;
			$query	= "select *,trunc(payment_date) 
			from f_rep_bpps_piutang2new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) rep
		WHERE
			(	SUBSTRING(rep.masa_pajak,22,4) < $year_date
				AND 
				(NOT (SUBSTRING(rep.masa_pajak,22,4) = $border
				AND SUBSTRING(rep.masa_pajak,19,2) = 12))
			)
			OR
			(
				(SUBSTRING(rep.masa_pajak,22,4) = $year_date
				AND SUBSTRING(rep.masa_pajak,19,2) = 12)
			)
			OR
			(
				SUBSTRING(rep.masa_pajak,22,4) > $year_date
			)
			order by kode_ayat, npwpd, masa_pajak";	
			//echo $query;
			//exit;
		}else if($jenis_laporan == 'murni'){
			$query	= "select *,trunc(payment_date) 
			from f_rep_bpps_piutang3new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) rep
		WHERE
			EXTRACT (YEAR FROM rep.settlement_date) = $year_date
			order by kode_ayat, npwpd, masa_pajak";
		}
		//die($query);
		//echo $query;
		//exit;
		$dbConn->query($query);


		$tgl_penerimaan = str_replace("'", "", $tgl_penerimaan);
		$tgl_penerimaan_last = str_replace("'", "", $tgl_penerimaan_last);
		$tahun = date("Y", strtotime($tgl_penerimaan));
		while ($dbConn->next_record()) {
			$data[]= array(
			"address"	=> $dbConn->f("address"),
			"company_name"	=> $dbConn->f("company_name"),
			"kode_jns_trans"	=> $dbConn->f("kode_jns_trans"),
			"jns_trans"		=> $dbConn->f("jns_trans"),
			"kode_jns_pajak"	=> $dbConn->f("kode_jns_pajak"),
			"kode_ayat"		=> $dbConn->f("kode_ayat"),
			"jns_pajak"		=> $dbConn->f("jns_pajak"),
			"jns_ayat"			=> $dbConn->f("jns_ayat"),
			"nama_ayat"		=> $dbConn->f("nama_ayat"),
			"no_kohir"		=> $dbConn->f("no_kohir"),
			"wp_name"			=> $dbConn->f("wp_name"),
			"wp_address_name"	=> $dbConn->f("wp_address_name"),
			"wp_address_no"		=> $dbConn->f("wp_address_no"),
			"npwpd"			=> $dbConn->f("npwpd"),
			"jumlah_terima"	=> $dbConn->f("jumlah_terima"),
			"masa_pajak"		=> $dbConn->f("masa_pajak"),
			"kd_tap"			=> $dbConn->f("kd_tap"),
			"keterangan"		=> $dbConn->f("keterangan"),
			"payment_date"		=> $dbConn->f("payment_date"),
			"jam"		=> $dbConn->f("jam"));
		}
		$dbConn->close();
				
		$Label1->SetText(GetCetakHTML($data));
	}
	else {
			if(($doAction == 'view_html2')||($doAction == 'view_html3')) {
		
			$p_vat_type_id		= CCGetFromGet("p_vat_type_id", "");
			$p_year_period_id	= CCGetFromGet("p_year_period_id", "");
			$tgl_penerimaan		= CCGetFromGet("tgl_penerimaan", "");
			$i_flag_setoran		= CCGetFromGet("i_flag_setoran", "");
			$tgl_penerimaan_last = CCGetFromGet("tgl_penerimaan_last", "");
			$year_code = CCGetFromGet("year_code", "");

			$tgl_penerimaan = "'".$tgl_penerimaan."'";
			$tgl_penerimaan_last = "'".$tgl_penerimaan_last."'";
		

			// $p_vat_type_id		= 1;
			// $p_year_period_id	= 4;
			// $tgl_penerimaan		= '15-12-2013';
			$date_start=str_replace("'", "",$year_code);
			//$year_date = DateTime::createFromFormat('d-m-Y', $date_start)->format('Y');
			$year_date = $year_code;

			$user				= CCGetUserLogin();
			$data				= array();
			$dbConn				= new clsDBConnSIKP();
			$jenis_laporan		= CCGetFromGet("jenis_laporan", "all"); 
			/*
			if($jenis_laporan == 'all'){
				$query	= "select *
				from f_rep_bpps_list_distinct_semua($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last) order by npwpd";	
				//echo $query;
				//exit;
			}else if($jenis_laporan == 'piutang'){
				$border= $year_date-1;
				$query	= "select *
				from f_rep_bpps_list_distinct_non_murni($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_year_date) rep order by npwpd";	
				//echo $query;
				//exit;
			}else if($jenis_laporan == 'murni'){
				$query	= "select *
				from f_rep_bpps_list_distinct_non_murni($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_year_date) rep order by npwpd";	
			}*/
			if($jenis_laporan == 'all'){
				$query	= "select to_char(active_date,'dd-mm-yyyy') as active_date2,*,trunc(payment_date) 
				from f_rep_bpps_piutang2new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) a
				left join t_cust_account x on a.npwpd = x.npwd 
				order by kode_ayat, npwpd, masa_pajak";	
				//echo $query;
				//exit;
			}else if($jenis_laporan == 'piutang'){
				$border= $year_date-1;
				$query	= "select to_char(active_date,'dd-mm-yyyy') as active_date2,*,trunc(payment_date) 
				from f_rep_bpps_piutang2new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) rep
				left join t_cust_account x on rep.npwpd = x.npwd 
			WHERE
				(	SUBSTRING(rep.masa_pajak,22,4) < $year_date
					AND 
					(NOT (SUBSTRING(rep.masa_pajak,22,4) = $border
					AND SUBSTRING(rep.masa_pajak,19,2) = 12))
				)
				OR
				(
					(SUBSTRING(rep.masa_pajak,22,4) = $year_date
					AND SUBSTRING(rep.masa_pajak,19,2) = 12)
				)
				OR
				(
					SUBSTRING(rep.masa_pajak,22,4) > $year_date
				)
				order by kode_ayat, npwpd, masa_pajak";	
				//echo $query;
				//exit;
			}else if($jenis_laporan == 'murni'){
				$query	= "select to_char(active_date,'dd-mm-yyyy') as active_date2,*,trunc(payment_date) 
				from f_rep_bpps_piutang3new_mod_1($p_vat_type_id, $p_year_period_id, $tgl_penerimaan, $tgl_penerimaan_last, $i_flag_setoran) a
				left join t_cust_account x on a.npwpd = x.npwd 
			WHERE
				EXTRACT (YEAR FROM a.settlement_date) = $year_date
				order by kode_ayat, npwpd, masa_pajak";
			}
			//die($query);
			//echo $query;
			//exit;
			$dbConn->query($query);


			$tgl_penerimaan = str_replace("'", "", $tgl_penerimaan);
			$tgl_penerimaan_last = str_replace("'", "", $tgl_penerimaan_last);
			$tahun = date("Y", strtotime($tgl_penerimaan));
			while ($dbConn->next_record()) {
				$data[]= array(
				"address"	=> $dbConn->f("address"),
				"company_name"	=> $dbConn->f("company_name"),
				"kode_jns_trans"	=> $dbConn->f("kode_jns_trans"),
				"jns_trans"		=> $dbConn->f("jns_trans"),
				"kode_jns_pajak"	=> $dbConn->f("kode_jns_pajak"),
				"kode_ayat"		=> $dbConn->f("kode_ayat"),
				"jns_pajak"		=> $dbConn->f("jns_pajak"),
				"jns_ayat"			=> $dbConn->f("jns_ayat"),
				"nama_ayat"		=> $dbConn->f("nama_ayat"),
				"no_kohir"		=> $dbConn->f("no_kohir"),
				"wp_name"			=> $dbConn->f("company_brand"),
				"wp_address_name"	=> $dbConn->f("brand_address_name"),
				"wp_address_no"		=> $dbConn->f("brand_address_no"),
				"active_date2"		=> $dbConn->f("active_date2"),
				"npwpd"			=> $dbConn->f("npwpd"),
				"jumlah_terima"	=> $dbConn->f("jumlah_terima"),
				"masa_pajak"		=> $dbConn->f("masa_pajak"),
				"kd_tap"			=> $dbConn->f("kd_tap"),
				"keterangan"		=> $dbConn->f("keterangan"),
				"payment_date"		=> $dbConn->f("payment_date"),
				"jam"		=> $dbConn->f("jam"));
			}
			/*
			while ($dbConn->next_record()) {
				$data[]= array(
				"npwpd"			=> $dbConn->f("npwpd"));
			}
			*/
			$dbConn->close();
			if ($doAction == 'view_html2')	{
				$Label1->SetText(GetCetakHTML2($data));
			}else{
				$Label1->SetText(GetCetakHTML3($data));
			}
		}
		else {		
			//do nothing 
		}
	}
	
// -------------------------


//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}


function GetCetakHTML($data) {
	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="100%">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN REALISASI HARIAN MURNI DAN NON MURNI</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              		</table>';
	
	$output .= '<h2>LAPORAN REALISASI HARIAN PER JENIS PAJAK </h2>';
	//$output .= '<h2>TANGGAL : '.dateToString($date_start, "-")." s/d ".dateToString($date_end, "-").'</h2> <br/>';

	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';


	$output.='<th>NO</th>';
	$output.='<th>NO AYAT</th>';
	$output.='<th>NAMA AYAT</th>';
	$output.='<th>NO KOHIR</th>';
	$output.='<th>NAMA WP</th>';
	$output.='<th>NPWPD</th>';
	$output.='<th>JUMLAH</th>';
	$output.='<th>MASA PAJAK</th>';
	$output.='<th>TGL TAP</th>';
	$output.='<th>TGL BAYAR</th>';
	$output.='</tr>';
	
	$jumlahtemp=0;
	$jumlahperayat=0;
	$i=0;
	foreach($data as $item) {
		$output .= '<tr>';
		$output .= '<td align="center">'.($i+1).'</td>';
		$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
		$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
		$output .= '<td align="left">'.$item['no_kohir'].'</td>';
		$output .= '<td align="left">'.$item['wp_name'].'</td>';
		$output .= '<td align="left">'.$item['npwpd'].'</td>';
		$output .= '<td align="right">Rp. '.number_format($item["jumlah_terima"], 2, ',', '.').'</td>';
		$output .= '<td align="left">'.$item['masa_pajak'].'</td>';
		$output .= '<td align="left">'.$item['kd_tap'].'</td>';
		$output .= '<td align="left">'.date("d-m-Y", strtotime($item['payment_date'])).'</td>';
		$output .= '</tr>';
		

		
		//hitung jumlahperayat sampai baris ini
		$jumlahtemp += $item["jumlah_terima"];
		//$total+= $item["jumlah_terima"];
		//cek apakah perlu bikin baris jumlah
		//jika iya, simpan jumlahtemp ke jumlahperayat, print jumlahtemp, reset jumlahtemp
		$ayat = $item["kode_ayat"];
		$ayatsesudah = $data[$i+1]["kode_ayat"];
		if(($ayat != $ayatsesudah&&count($data)>1)||empty($data[$i+1])){
			$jumlahperayat += $jumlahtemp;
			$output .= '<tr>';
				$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$item["nama_ayat"].'</td>';
				$output .= '<td align="right">Rp. '.number_format($jumlahtemp, 2, ',', '.').'</td>';
			$output .= '</tr>';
			$jumlahtemp = 0;
			
		}
		$i=$i+1;
	}
	$output .= '<tr>';
		$output .= '<td align="CENTER" colspan=6>TOTAL PAJAK</td>';
		$output .= '<td align="right">Rp. '.number_format($jumlahperayat, 2, ',', '.').'</td>';
	$output .= '</tr>';
	
	$output.='</td></tr></table>';
	$output.='</table>';
	
	return $output;
}

function GetCetakHTML2($data) {
	$tgl_penerimaan		= CCGetFromGet("tgl_penerimaan", "");
	$year_code = CCGetFromGet("year_code", "");
	$date_start=str_replace("'", "",$year_code);
	//$year_date = DateTime::createFromFormat('d-m-Y', $date_start)->format('Y');
	$year_date = $year_code;
	
	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="100%">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN REALISASI HARIAN MURNI DAN NON MURNI</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              		</table>';
	
	$output .= '<h2>LAPORAN REALISASI HARIAN PER JENIS PAJAK </h2>';
	//$output .= '<h2>TANGGAL : '.dateToString($date_start, "-")." s/d ".dateToString($date_end, "-").'</h2> <br/>';

	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';


	$output.='<th rowspan = 2>NO</th>';
	$output.='<th rowspan = 2>NO AYAT</th>';
	$output.='<th rowspan = 2>NAMA AYAT</th>';
	//$output.='<th>NO KOHIR</th>';
	$output.='<th rowspan = 2>MERK DAGANG</th>';
	$output.='<th rowspan = 2>ALAMAT MERK DAGANG</th>';
	$output.='<th rowspan = 2>TANGGAL PENGUKUHAN</th>';
	$output.='<th rowspan = 2>NPWPD</th>';
	$jenis_laporan		= CCGetFromGet("jenis_laporan", "all");
	if($jenis_laporan == 'murni'){ 
		$output.='<th colspan = 13 align=center>REALISASI DAN TANGGAL BAYAR</th>';
		$output.='</tr>';
			$output.='<tr class="Caption">';
				$output.='<th align="center">DESEMBER '.($year_date-1).'</th>';
				$output.='<th align="center">JANUARI '.$year_date.'</th>';
				$output.='<th align="center">FEBRUARI '.$year_date.'</th>';
				$output.='<th align="center">MARET '.$year_date.'</th>';
				$output.='<th align="center">APRIL '.$year_date.'</th>';
				$output.='<th align="center">MEI '.$year_date.'</th>';
				$output.='<th align="center">JUNI '.$year_date.'</th>';
				$output.='<th align="center">JULI '.$year_date.'</th>';
				$output.='<th align="center">AGUSTUS '.$year_date.'</th>';
				$output.='<th align="center">SEPTEMBER '.$year_date.'</th>';
				$output.='<th align="center">OKTOBER '.$year_date.'</th>';
				$output.='<th align="center">NOVEMBER '.$year_date.'</th>';
				$output.='<th align="center">JUMLAH</th>';
			$output.='</tr>';
		$output.='</tr>';

		$jumlahtemp=0;
		$jumlahperayat=0;
		$i=0;
		$i2=0;
		$before=0;
		$new=0;
		$jan=0;
		$feb=0;
		$mar=0;
		$apr=0;
		$mei=0;
		$jun=0;
		$jul=0;
		$agu=0;
		$sep=0;
		$okt=0;
		$nov=0;
		$des=0;
		foreach($data as $item) {
			$bln = substr($item["masa_pajak"],-7,2);
			$thn = substr($item["masa_pajak"],-4,4);
			if ($new == 0){
				$output .= '<tr>';
				$output .= '<td align="center">'.($i+1).'</td>';
				$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
				$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
				//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
				$output .= '<td align="left">'.$item['wp_name'].'</td>';
				$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
				$output .= '<td align="left">'.$item['active_date2'].'</td>';
				$output .= '<td align="left">'.$item['npwpd'].'</td>';
				//$before = $item;
				//if ($thn == $year_date && $bln != 12){
					switch ($bln) {
					    case "01":
							$jan=$jan+$item["jumlah_terima"];
					        break;
					    case "02":
					        $feb=$feb+$item["jumlah_terima"];
					        break;
					    case "03":
					        $mar=$mar+$item["jumlah_terima"];
					        break;
					    case "04":
					        $apr=$apr+$item["jumlah_terima"];
					        break;
					    case "05":
					        $mei=$mei+$item["jumlah_terima"];
					        break;
					    case "06":
					        $jun=$jun+$item["jumlah_terima"];
					        break;
					    case "07":
					        $jul=$jul+$item["jumlah_terima"];
					        break;
					    case "08":
					        $agu=$agu+$item["jumlah_terima"];
					        break;
					    case "09":
					        $sep=$sep+$item["jumlah_terima"];
					        break;
					    case "10":
					        $okt=$okt+$item["jumlah_terima"];
					        break;
					    case "11":
					        $nov=$nov+$item["jumlah_terima"];
					        break;
						case "12":
					        $des=$des+$item["jumlah_terima"];
					        break;
					}
				/*}else{
					if ($thn == ($year_date - 1) && $bln == 12){
						$des=$des+$item["jumlah_terima"];
					}
					else{
						if ($thn < $year_date){
							$xdes=$xdes+$item["jumlah_terima"];
						}
						else{
							if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
									$xnov=$xnov+$item["jumlah_terima"];
							}
						}
					}
				}*/
				//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
				//$output .= '<td align="left">'.$item['masa_pajak'].'</td>';
				//$output .= '<td align="left">'.$item['kd_tap'].'</td>';
				//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
				//$output .= '</tr>';
				$jumlahtemp += $item["jumlah_terima"];
				$new =1;
			}else{
				if ($before['npwpd']==$item['npwpd']){				
					//if ($thn == $year_date && $bln != 12){
						switch ($bln) {
						    case "01":
								$jan=$jan+$item["jumlah_terima"];
						        break;
						    case "02":
						        $feb=$feb+$item["jumlah_terima"];
						        break;
						    case "03":
						        $mar=$mar+$item["jumlah_terima"];
						        break;
						    case "04":
						        $apr=$apr+$item["jumlah_terima"];
						        break;
						    case "05":
						        $mei=$mei+$item["jumlah_terima"];
						        break;
						    case "06":
						        $jun=$jun+$item["jumlah_terima"];
						        break;
						    case "07":
						        $jul=$jul+$item["jumlah_terima"];
						        break;
						    case "08":
						        $agu=$agu+$item["jumlah_terima"];
						        break;
						    case "09":
						        $sep=$sep+$item["jumlah_terima"];
						        break;
						    case "10":
						        $okt=$okt+$item["jumlah_terima"];
						        break;
						    case "11":
						        $nov=$nov+$item["jumlah_terima"];
						        break;
							case "12":
						        $des=$des+$item["jumlah_terima"];
						        break;
						}
					/*}else{
						if ($thn == ($year_date - 1) && $bln == 12){
							$des=$des+$item["jumlah_terima"];
						}
						else{
							if ($thn < $year_date){
								$xdes=$xdes+$item["jumlah_terima"];
							}
							else{
								if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
										$xnov=$xnov+$item["jumlah_terima"];
								}
							}
						}
					}*/

					$jumlahtemp += $item["jumlah_terima"];
					$ayat = $item["kode_ayat"];
				}else{
					$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
			
					//$new=0;
					//$output .= '<tr>';
					$jumlahperayat += $jumlahtemp;
			
					//$output .= '<tr>';
						//$output .= '<td align="CENTER" colspan=5>JUMLAH PAJAK '.$before["wp_name"].'</td>';
						$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
					$output .= '</tr>';
					$jumlahtemp=0;
					$jan=0;
					$feb=0;
					$mar=0;
					$apr=0;
					$mei=0;
					$jun=0;
					$jul=0;
					$agu=0;
					$sep=0;
					$okt=0;
					$nov=0;
					$des=0;
					$xdes=0;
					$xnov=0;
					$output .= '<td align="center">'.($i+1).'</td>';
					$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
					$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
					//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
					$output .= '<td align="left">'.$item['wp_name'].'</td>';
					$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
					$output .= '<td align="left">'.$item['active_date2'].'</td>';
					$output .= '<td align="left">'.$item['npwpd'].'</td>';
					//$before = $item;
					//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
										//if ($thn == $year_date && $bln != 12){
						switch ($bln) {
						    case "01":
								$jan=$jan+$item["jumlah_terima"];
						        break;
						    case "02":
						        $feb=$feb+$item["jumlah_terima"];
						        break;
						    case "03":
						        $mar=$mar+$item["jumlah_terima"];
						        break;
						    case "04":
						        $apr=$apr+$item["jumlah_terima"];
						        break;
						    case "05":
						        $mei=$mei+$item["jumlah_terima"];
						        break;
						    case "06":
						        $jun=$jun+$item["jumlah_terima"];
						        break;
						    case "07":
						        $jul=$jul+$item["jumlah_terima"];
						        break;
						    case "08":
						        $agu=$agu+$item["jumlah_terima"];
						        break;
						    case "09":
						        $sep=$sep+$item["jumlah_terima"];
						        break;
						    case "10":
						        $okt=$okt+$item["jumlah_terima"];
						        break;
						    case "11":
						        $nov=$nov+$item["jumlah_terima"];
						        break;
							case "12":
						        $des=$des+$item["jumlah_terima"];
						        break;
						}
					/*}else{
						if ($thn == ($year_date - 1) && $bln == 12){
							$des=$des+$item["jumlah_terima"];
						}
						else{
							if ($thn < $year_date){
								$xdes=$xdes+$item["jumlah_terima"];
							}
							else{
								if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
										$xnov=$xnov+$item["jumlah_terima"];
								}
							}
						}
					}*/

					$jumlahtemp += $item["jumlah_terima"];
					$i=$i+1;
			
				}
			}
			
			$before = $item;
			$i2=$i2+1;
			if(empty($data[$i2]))
			{
				$jumlahperayat += $jumlahtemp;
				$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
				$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
				$output .= '</tr>';
			}
		}
		$output .= '<tr>';
			$output .= '<td align="CENTER" colspan=19>TOTAL PAJAK</td>';
			$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
		$output .= '</tr>';

		$output.='</td></tr></table>';
		$output.='</table>';
	}
	else{
		if(($jenis_laporan == 'all')){
			$output.='<th colspan = 15 align=center>REALISASI DAN TANGGAL BAYAR</th>';
			$output.='</tr>';
				$output.='<tr class="Caption">';
					$output.='<th align="center">SEBELUM DESEMBER '.($year_date-1).'</th>';
					$output.='<th align="center">DESEMBER '.($year_date-1).'</th>';
					$output.='<th align="center">JANUARI '.$year_date.'</th>';
					$output.='<th align="center">FEBRUARI '.$year_date.'</th>';
					$output.='<th align="center">MARET '.$year_date.'</th>';
					$output.='<th align="center">APRIL '.$year_date.'</th>';
					$output.='<th align="center">MEI '.$year_date.'</th>';
					$output.='<th align="center">JUNI '.$year_date.'</th>';
					$output.='<th align="center">JULI '.$year_date.'</th>';
					$output.='<th align="center">AGUSTUS '.$year_date.'</th>';
					$output.='<th align="center">SEPTEMBER '.$year_date.'</th>';
					$output.='<th align="center">OKTOBER '.$year_date.'</th>';
					$output.='<th align="center">NOVEMBER '.$year_date.'</th>';
					$output.='<th align="center">SETELAH NOVEMBER '.$year_date.'</th>';
					$output.='<th align="center">JUMLAH</th>';
				$output.='</tr>';
			$output.='</tr>';
	
			$jumlahtemp=0;
			$jumlahperayat=0;
			$i=0;
			$i2=0;
			$before=0;
			$new=0;
			$jan=0;
			$feb=0;
			$mar=0;
			$apr=0;
			$mei=0;
			$jun=0;
			$jul=0;
			$agu=0;
			$sep=0;
			$okt=0;
			$nov=0;
			$des=0;
			$xdes=0;
			$xnov=0;
			foreach($data as $item) {
				$bln = substr($item["masa_pajak"],-7,2);
				$thn = substr($item["masa_pajak"],-4,4);
				if ($new == 0){
					$output .= '<tr>';
					$output .= '<td align="center">'.($i+1).'</td>';
					$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
					$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
					//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
					$output .= '<td align="left">'.$item['wp_name'].'</td>';
					$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
					$output .= '<td align="left">'.$item['active_date2'].'</td>';
					$output .= '<td align="left">'.$item['npwpd'].'</td>';
					//$before = $item;
					if ($thn == $year_date && $bln != 12){
						switch ($bln) {
						    case "01":
								$jan=$jan+$item["jumlah_terima"];
						        break;
						    case "02":
						        $feb=$feb+$item["jumlah_terima"];
						        break;
						    case "03":
						        $mar=$mar+$item["jumlah_terima"];
						        break;
						    case "04":
						        $apr=$apr+$item["jumlah_terima"];
						        break;
						    case "05":
						        $mei=$mei+$item["jumlah_terima"];
						        break;
						    case "06":
						        $jun=$jun+$item["jumlah_terima"];
						        break;
						    case "07":
						        $jul=$jul+$item["jumlah_terima"];
						        break;
						    case "08":
						        $agu=$agu+$item["jumlah_terima"];
						        break;
						    case "09":
						        $sep=$sep+$item["jumlah_terima"];
						        break;
						    case "10":
						        $okt=$okt+$item["jumlah_terima"];
						        break;
						    case "11":
						        $nov=$nov+$item["jumlah_terima"];
						        break;
						}
					}else{
						if ($thn == ($year_date - 1) && $bln == 12){
							$des=$des+$item["jumlah_terima"];
						}
						else{
							if ($thn < $year_date){
								$xdes=$xdes+$item["jumlah_terima"];
							}
							else{
								if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
										$xnov=$xnov+$item["jumlah_terima"];
								}
							}
						}
					}
					//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
					//$output .= '<td align="left">'.$item['masa_pajak'].'</td>';
					//$output .= '<td align="left">'.$item['kd_tap'].'</td>';
					//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
					//$output .= '</tr>';
					$jumlahtemp += $item["jumlah_terima"];
					$new =1;
				}else{
					if ($before['npwpd']==$item['npwpd']){				
						if ($thn == $year_date && $bln != 12){
							switch ($bln) {
							    case "01":
									$jan=$jan+$item["jumlah_terima"];
							        break;
							    case "02":
							        $feb=$feb+$item["jumlah_terima"];
							        break;
							    case "03":
							        $mar=$mar+$item["jumlah_terima"];
							        break;
							    case "04":
							        $apr=$apr+$item["jumlah_terima"];
							        break;
							    case "05":
							        $mei=$mei+$item["jumlah_terima"];
							        break;
							    case "06":
							        $jun=$jun+$item["jumlah_terima"];
							        break;
							    case "07":
							        $jul=$jul+$item["jumlah_terima"];
							        break;
							    case "08":
							        $agu=$agu+$item["jumlah_terima"];
							        break;
							    case "09":
							        $sep=$sep+$item["jumlah_terima"];
							        break;
							    case "10":
							        $okt=$okt+$item["jumlah_terima"];
							        break;
							    case "11":
							        $nov=$nov+$item["jumlah_terima"];
							        break;
							}
						}else{
							if ($thn == ($year_date - 1) && $bln == 12){
								$des=$des+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$xdes=$xdes+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
											$xnov=$xnov+$item["jumlah_terima"];
									}
								}	
							}
						}

						$jumlahtemp += $item["jumlah_terima"];
						$ayat = $item["kode_ayat"];
					}else{
						$output .= '<td align="right">'.number_format($xdes, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($xnov, 2, ',', '.').'</td>';
				
						//$new=0;
						//$output .= '<tr>';
						$jumlahperayat += $jumlahtemp;
				
						//$output .= '<tr>';
							//$output .= '<td align="CENTER" colspan=5>JUMLAH PAJAK '.$before["wp_name"].'</td>';
							$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
						$output .= '</tr>';
						$jumlahtemp=0;
						$jan=0;
						$feb=0;
						$mar=0;
						$apr=0;
						$mei=0;
						$jun=0;
						$jul=0;
						$agu=0;
						$sep=0;
						$okt=0;
						$nov=0;
						$des=0;
						$xdes=0;
						$xnov=0;
						$output .= '<td align="center">'.($i+1).'</td>';
						$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
						$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
						//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
						$output .= '<td align="left">'.$item['wp_name'].'</td>';
						$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
						$output .= '<td align="left">'.$item['active_date2'].'</td>';
						$output .= '<td align="left">'.$item['npwpd'].'</td>';
						//$before = $item;
						//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
						if ($thn == $year_date && $bln != 12){
							switch ($bln) {
							    case "01":
									$jan=$jan+$item["jumlah_terima"];
							        break;
							    case "02":
							        $feb=$feb+$item["jumlah_terima"];
							        break;
							    case "03":
							        $mar=$mar+$item["jumlah_terima"];
							        break;
							    case "04":
							        $apr=$apr+$item["jumlah_terima"];
							        break;
							    case "05":
							        $mei=$mei+$item["jumlah_terima"];
							        break;
							    case "06":
							        $jun=$jun+$item["jumlah_terima"];
							        break;
							    case "07":
							        $jul=$jul+$item["jumlah_terima"];
							        break;
							    case "08":
							        $agu=$agu+$item["jumlah_terima"];
							        break;
							    case "09":
							        $sep=$sep+$item["jumlah_terima"];
							        break;
							    case "10":
							        $okt=$okt+$item["jumlah_terima"];
							        break;
							    case "11":
							        $nov=$nov+$item["jumlah_terima"];
							        break;
							}
						}else{
							if ($thn == ($year_date - 1) && $bln == 12){
								$des=$des+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$xdes=$xdes+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
											$xnov=$xnov+$item["jumlah_terima"];
									}
								}	
							}
						}
						$jumlahtemp += $item["jumlah_terima"];
						$i=$i+1;
				
					}
				}
				
				$before = $item;
				$i2=$i2+1;
				if(empty($data[$i2]))
				{
					$jumlahperayat += $jumlahtemp;
					$output .= '<td align="right">'.number_format($xdes, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($xnov, 2, ',', '.').'</td>';
					$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
					$output .= '</tr>';
				}
			}
			$output .= '<tr>';
				$output .= '<td align="CENTER" colspan=21>TOTAL PAJAK</td>';
				$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
			$output .= '</tr>';
	
			$output.='</td></tr></table>';
			$output.='</table>';
		}else{
			if($jenis_laporan == 'piutang'){
				$output.='<th colspan = 16 align=center>REALISASI DAN TANGGAL BAYAR</th>';
				$output.='</tr>';
					$output.='<tr class="Caption">';
						$output.='<th align="center">SEBELUM DESEMBER '.($year_date-2).'</th>';
						$output.='<th align="center">DESEMBER '.($year_date-2).'</th>';
						$output.='<th align="center">JANUARI '.($year_date-1).'</th>';
						$output.='<th align="center">FEBRUARI '.($year_date-1).'</th>';
						$output.='<th align="center">MARET '.($year_date-1).'</th>';
						$output.='<th align="center">APRIL '.($year_date-1).'</th>';
						$output.='<th align="center">MEI '.($year_date-1).'</th>';
						$output.='<th align="center">JUNI '.($year_date-1).'</th>';
						$output.='<th align="center">JULI '.($year_date-1).'</th>';
						$output.='<th align="center">AGUSTUS '.($year_date-1).'</th>';
						$output.='<th align="center">SEPTEMBER '.($year_date-1).'</th>';
						$output.='<th align="center">OKTOBER '.($year_date-1).'</th>';
						$output.='<th align="center">NOVEMBER '.($year_date-1).'</th>';
						$output.='<th align="center">DESEMBER '.$year_date.'</th>';
						//$output.='<th align="center">SESUDAH DESEMBER '.$year_date.'</th>';
						$output.='<th align="center">JUMLAH</th>';
					$output.='</tr>';
				$output.='</tr>';

				$jumlahtemp=0;
				$jumlahperayat=0;
				$i=0;
				$i2=0;
				$before=0;
				$new=0;
				$jan=0;
				$feb=0;
				$mar=0;
				$apr=0;
				$mei=0;
				$jun=0;
				$jul=0;
				$agu=0;
				$sep=0;
				$okt=0;
				$nov=0;
				$des=0;
				$xdes=0;
				$xxdes=0;
				//$ydes=0;
				foreach($data as $item) {
					$bln = substr($item["masa_pajak"],-7,2);
					$thn = substr($item["masa_pajak"],-4,4);
					if ($new == 0){
						$output .= '<tr>';
						$output .= '<td align="center">'.($i+1).'</td>';
						$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
						$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
						//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
						$output .= '<td align="left">'.$item['wp_name'].'</td>';
						$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
						$output .= '<td align="left">'.$item['active_date2'].'</td>';
						$output .= '<td align="left">'.$item['npwpd'].'</td>';
						//$before = $item;

						if ($thn == ($year_date-1) && $bln != 12){
							switch ($bln) {
							    case "01":
									$jan=$jan+$item["jumlah_terima"];
							        break;
							    case "02":
							        $feb=$feb+$item["jumlah_terima"];
							        break;
							    case "03":
							        $mar=$mar+$item["jumlah_terima"];
							        break;
							    case "04":
							        $apr=$apr+$item["jumlah_terima"];
							        break;
							    case "05":
							        $mei=$mei+$item["jumlah_terima"];
							        break;
							    case "06":
							        $jun=$jun+$item["jumlah_terima"];
							        break;
							    case "07":
							        $jul=$jul+$item["jumlah_terima"];
							        break;
							    case "08":
							        $agu=$agu+$item["jumlah_terima"];
							        break;
							    case "09":
							        $sep=$sep+$item["jumlah_terima"];
							        break;
							    case "10":
							        $okt=$okt+$item["jumlah_terima"];
							        break;
							    case "11":
							        $nov=$nov+$item["jumlah_terima"];
							        break;
							}
						}else{
							if ($thn == ($year_date - 2) && $bln == 12){
								$xdes=$xdes+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$xxdes=$xxdes+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)){
											$des=$des+$item["jumlah_terima"];
									}
									/*else{
										if ($thn > $year_date){
											$ydes=$ydes+$item["jumlah_terima"];
										}
									}*/
								}
							}
						}
						//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
						//$output .= '<td align="left">'.$item['masa_pajak'].'</td>';
						//$output .= '<td align="left">'.$item['kd_tap'].'</td>';
						//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
						//$output .= '</tr>';
						$jumlahtemp += $item["jumlah_terima"];
						$new =1;
					}else{
						if ($before['npwpd']==$item['npwpd']){				
							if ($thn == ($year_date-1) && $bln != 12){
								switch ($bln) {
								    case "01":
										$jan=$jan+$item["jumlah_terima"];
								        break;
								    case "02":
								        $feb=$feb+$item["jumlah_terima"];
								        break;
								    case "03":
								        $mar=$mar+$item["jumlah_terima"];
								        break;
								    case "04":
								        $apr=$apr+$item["jumlah_terima"];
								        break;
								    case "05":
								        $mei=$mei+$item["jumlah_terima"];
								        break;
								    case "06":
								        $jun=$jun+$item["jumlah_terima"];
								        break;
								    case "07":
								        $jul=$jul+$item["jumlah_terima"];
								        break;
								    case "08":
								        $agu=$agu+$item["jumlah_terima"];
								        break;
								    case "09":
								        $sep=$sep+$item["jumlah_terima"];
								        break;
								    case "10":
								        $okt=$okt+$item["jumlah_terima"];
								        break;
								    case "11":
								        $nov=$nov+$item["jumlah_terima"];
								        break;
								}
							}else{
								if ($thn == ($year_date - 2) && $bln == 12){
									$xdes=$xdes+$item["jumlah_terima"];
								}
								else{
									if ($thn < $year_date){
										$xxdes=$xxdes+$item["jumlah_terima"];
									}
									else{
										if (($thn == $year_date && $bln == 12)){
												$des=$des+$item["jumlah_terima"];
										}
										/*else{
											if ($thn > $year_date){
												$ydes=$ydes+$item["jumlah_terima"];
											}
										}*/
									}
								}
							}

							$jumlahtemp += $item["jumlah_terima"];
							$ayat = $item["kode_ayat"];
						}else{
							$output .= '<td align="right">'.number_format($xxdes, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($xdes, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
							$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
							//$output .= '<td align="right">'.number_format($ydes, 2, ',', '.').'</td>';
		
							//$new=0;
							//$output .= '<tr>';
							$jumlahperayat += $jumlahtemp;
		
							//$output .= '<tr>';
								//$output .= '<td align="CENTER" colspan=5>JUMLAH PAJAK '.$before["wp_name"].'</td>';
								$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
							$output .= '</tr>';
							$jumlahtemp=0;
							$jan=0;
							$feb=0;
							$mar=0;
							$apr=0;
							$mei=0;
							$jun=0;
							$jul=0;
							$agu=0;
							$sep=0;
							$okt=0;
							$nov=0;
							$des=0;
							$xdes=0;
							$xxdes=0;
							$ydes=0;
							$output .= '<td align="center">'.($i+1).'</td>';
							$output .= '<td align="center">'.$item["kode_jns_pajak"]." ".$item["kode_ayat"].'</td>';
							$output .= '<td align="center">'.$item["nama_ayat"].'</td>';
							//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
							$output .= '<td align="left">'.$item['wp_name'].'</td>';
							$output .= '<td align="left">'.$item['wp_address_name'].' '.$item['wp_address_no'].'</td>';
							$output .= '<td align="left">'.$item['active_date2'].'</td>';
							$output .= '<td align="left">'.$item['npwpd'].'</td>';
							//$before = $item;
							//$output .= '<td align="right">'.number_format($item["jumlah_terima"], 2, ',', '.').'<br></br>'.$item['kd_tap'].'</td>';
							if ($thn == ($year_date-1) && $bln != 12){
								switch ($bln) {
								    case "01":
										$jan=$jan+$item["jumlah_terima"];
								        break;
								    case "02":
								        $feb=$feb+$item["jumlah_terima"];
								        break;
								    case "03":
								        $mar=$mar+$item["jumlah_terima"];
								        break;
								    case "04":
								        $apr=$apr+$item["jumlah_terima"];
								        break;
								    case "05":
								        $mei=$mei+$item["jumlah_terima"];
								        break;
								    case "06":
								        $jun=$jun+$item["jumlah_terima"];
								        break;
								    case "07":
								        $jul=$jul+$item["jumlah_terima"];
								        break;
								    case "08":
								        $agu=$agu+$item["jumlah_terima"];
								        break;
								    case "09":
								        $sep=$sep+$item["jumlah_terima"];
								        break;
								    case "10":
								        $okt=$okt+$item["jumlah_terima"];
								        break;
								    case "11":
								        $nov=$nov+$item["jumlah_terima"];
								        break;
								}
							}else{
								if ($thn == ($year_date - 2) && $bln == 12){
									$xdes=$xdes+$item["jumlah_terima"];
								}
								else{
									if ($thn < $year_date){
										$xxdes=$xxdes+$item["jumlah_terima"];
									}
									else{
										if (($thn == $year_date && $bln == 12)){
												$des=$des+$item["jumlah_terima"];
										}
										//else{
											//if ($thn > $year_date){
											//	$ydes=$ydes+$item["jumlah_terima"];
											//}
										//}
									}
								}
							}
							$jumlahtemp += $item["jumlah_terima"];
							$i=$i+1;
		
						}
					}
		
					$before = $item;
					$i2=$i2+1;
					if(empty($data[$i2]))
					{
						$jumlahperayat += $jumlahtemp;
						$output .= '<td align="right">'.number_format($xxdes, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($xdes, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jan, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($feb, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($mar, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($apr, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($mei, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jun, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jul, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($agu, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($sep, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($okt, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($nov, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($des, 2, ',', '.').'</td>';
						//$output .= '<td align="right">'.number_format($ydes, 2, ',', '.').'</td>';
						$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
						$output .= '</tr>';
					}
				}
				$output .= '<tr>';
					$output .= '<td align="CENTER" colspan=21>TOTAL PAJAK</td>';
					$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
				$output .= '</tr>';

				$output.='</td></tr></table>';
				$output.='</table>';
			}
		}
	}
		
	return $output;
}


function GetCetakHTML3($data) {
	$tgl_penerimaan		= CCGetFromGet("tgl_penerimaan", "");
	$year_code = CCGetFromGet("year_code", "");
	$date_start=str_replace("'", "",$year_code);
	//$year_date = DateTime::createFromFormat('d-m-Y', $date_start)->format('Y');
	$year_date = $year_code;
	
	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="100%">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN REALISASI HARIAN MURNI DAN NON MURNI</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              		</table>';
	
	$output .= '<h2>LAPORAN REALISASI HARIAN PER JENIS PAJAK </h2>';
	//$output .= '<h2>TANGGAL : '.dateToString($date_start, "-")." s/d ".dateToString($date_end, "-").'</h2> <br/>';

	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';


	$output.='<th rowspan = 1>NO</th>';
	$output.='<th rowspan = 1>NO AYAT</th>';
	$output.='<th rowspan = 1>JENIS PAJAK</th>';
	$output.='<th rowspan = 1>NAMA AYAT</th>';
	//$output.='<th>NO KOHIR</th>';
	$output.='<th rowspan = 1>NAMA WP</th>';
	$output.='<th rowspan = 1>NPWPD</th>';
	$output .= '<td align="left">ALAMAT</td>';
	$output.='<th rowspan = 1>MASA PAJAK</th>';
	$output.='<th rowspan = 1>JUMLAH</th>';
	$output.='</tr>';
	$jenis_laporan		= CCGetFromGet("jenis_laporan", "all");
	
	$kode='';
	$nama='';
	$wp='';
	$npwpd='';
	
	if($jenis_laporan == 'murni'){ 
		$jumlahtemp=0;
		$jumlahperayat=0;
		$i=0;
		$i2=0;
		$before=0;
		$new=0;
		$bulan2				= array();
		$bulan2[0]['nama']='DESEMBER'.' '.($year_date-1);
		$bulan2[0]['jumlah']=0;
		$bulan2[1]['nama']='JANUARI'.' '.$year_date;
		$bulan2[1]['jumlah']=0;
		$bulan2[2]['nama']='FEBRUARI'.' '.$year_date;
		$bulan2[2]['jumlah']=0;
		$bulan2[3]['nama']='MARET'.' '.$year_date;
		$bulan2[3]['jumlah']=0;
		$bulan2[4]['nama']='APRIL'.' '.$year_date;
		$bulan2[4]['jumlah']=0;
		$bulan2[5]['nama']='MEI'.' '.$year_date;
		$bulan2[5]['jumlah']=0;
		$bulan2[6]['nama']='JUNI'.' '.$year_date;
		$bulan2[6]['jumlah']=0;
		$bulan2[7]['nama']='JULI'.' '.$year_date;
		$bulan2[7]['jumlah']=0;
		$bulan2[8]['nama']='AGUSTUS'.' '.$year_date;
		$bulan2[8]['jumlah']=0;
		$bulan2[9]['nama']='SEPTEMBER'.' '.$year_date;
		$bulan2[9]['jumlah']=0;
		$bulan2[10]['nama']='OKTOBER'.' '.$year_date;
		$bulan2[10]['jumlah']=0;
		$bulan2[11]['nama']='NOVEMBER'.' '.$year_date;
		$bulan2[11]['jumlah']=0;
		
		foreach($data as $item) {
			$bln = substr($item["masa_pajak"],-7,2);
			$thn = substr($item["masa_pajak"],-4,4);
			if ($new == 0){
				$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
				$jenis=$item["jns_pajak"];
				$nama=$item["nama_ayat"];
				$wp=$item["wp_name"];
				$npwpd=$item["npwpd"];
				$address=$item["address"];
				
				switch ($bln) {
					case "01":
						$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
						break;
					case "02":
						$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
						break;
					case "03":
						$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
						break;
					case "04":
						$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
						break;
					case "05":
						$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
						break;
					case "06":
						$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
						break;
					case "07":
						$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
						break;
					case "08":
						$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
						break;
					case "09":
						$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
						break;
					case "10":
						$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
						break;
					case "11":
						$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
						break;
					case "12":
						$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
						break;
				}
				$jumlahtemp += $item["jumlah_terima"];
				$new =1;
			}else{
				if ($before['npwpd']==$item['npwpd']){	
					switch ($bln) {
						case "01":
							$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
							break;
						case "02":
							$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
							break;
						case "03":
							$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
							break;
						case "04":
							$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
							break;
						case "05":
							$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
							break;
						case "06":
							$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
							break;
						case "07":
							$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
							break;
						case "08":
							$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
							break;
						case "09":
							$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
							break;
						case "10":
							$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
							break;
						case "11":
							$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
							break;
						case "12":
							$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
							break;
					}

					$jumlahtemp += $item["jumlah_terima"];
					$ayat = $item["kode_ayat"];
				}else{
					//$new=0;
					//$output .= '<tr>';
					$jumlahperayat += $jumlahtemp;
					foreach($bulan2 as $bulan) {
						$output .= '<tr>';
						$output .= '<td align="center">'.($i+1).'</td>';
						$output .= '<td align="center">'.$kode.'</td>';
						$output .= '<td align="center">'.$jenis.'</td>';
						$output .= '<td align="center">'.$nama.'</td>';
						//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
						$output .= '<td align="left">'.$wp.'</td>';
						$output .= '<td align="left">'.$npwpd.'</td>';
						$output .= '<td align="left">'.$address.'</td>';
						$output .= '<td align="left">'.$bulan['nama'].'</td>';
						$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
						$output .= '</tr>';
					}
					$output .= '<tr>';
						$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
						$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
					$output .= '</tr>';
					
					$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
					$nama=$item["nama_ayat"];
					$jenis=$item["jns_pajak"];
					$wp=$item["wp_name"];
					$npwpd=$item["npwpd"];
					$address=$item["address"];
					$jumlahtemp=0;
					$bulan2[1]['jumlah']=0;
					$bulan2[2]['jumlah']=0;
					$bulan2[3]['jumlah']=0;
					$bulan2[4]['jumlah']=0;
					$bulan2[5]['jumlah']=0;
					$bulan2[6]['jumlah']=0;
					$bulan2[7]['jumlah']=0;
					$bulan2[8]['jumlah']=0;
					$bulan2[9]['jumlah']=0;
					$bulan2[10]['jumlah']=0;
					$bulan2[11]['jumlah']=0;
					$bulan2[0]['jumlah']=0;
					
					switch ($bln) {
						case "01":
							$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
							break;
						case "02":
							$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
							break;
						case "03":
							$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
							break;
						case "04":
							$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
							break;
						case "05":
							$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
							break;
						case "06":
							$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
							break;
						case "07":
							$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
							break;
						case "08":
							$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
							break;
						case "09":
							$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
							break;
						case "10":
							$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
							break;
						case "11":
							$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
							break;
						case "12":
							$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
							break;
					}

					$jumlahtemp += $item["jumlah_terima"];
					$i=$i+1;
			
				}
			}
			
			$before = $item;
			$i2=$i2+1;
			if(empty($data[$i2]))
			{
				$jumlahperayat += $jumlahtemp;
				foreach($bulan2 as $bulan) {
					$output .= '<tr>';
					$output .= '<td align="center">'.($i+1).'</td>';
					$output .= '<td align="center">'.$kode.'</td>';
					$output .= '<td align="center">'.$jenis.'</td>';
					$output .= '<td align="center">'.$nama.'</td>';
					//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
					$output .= '<td align="left">'.$wp.'</td>';
					$output .= '<td align="left">'.$npwpd.'</td>';
					$output .= '<td align="left">'.$address.'</td>';
					$output .= '<td align="left">'.$bulan['nama'].'</td>';
					$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
					$output .= '</tr>';
				}
				$output .= '<tr>';
					$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
					$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
				$output .= '</tr>';
			}
		}
		$output .= '<tr>';
			$output .= '<td align="CENTER" colspan=6>TOTAL PAJAK</td>';
			$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
		$output .= '</tr>';

		$output.='</td></tr></table>';
		$output.='</table>';
	}
	else{
		if(($jenis_laporan == 'all')){
			$bulan2				= array();
			$bulan2[12]['nama']='SEBELUM DESEMBER'.' '.($year_date-1);
			$bulan2[12]['jumlah']=0;
			$bulan2[0]['nama']='DESEMBER'.' '.($year_date-1);
			$bulan2[0]['jumlah']=0;
			$bulan2[1]['nama']='JANUARI'.' '.$year_date;
			$bulan2[1]['jumlah']=0;
			$bulan2[2]['nama']='FEBRUARI'.' '.$year_date;
			$bulan2[2]['jumlah']=0;
			$bulan2[3]['nama']='MARET'.' '.$year_date;
			$bulan2[3]['jumlah']=0;
			$bulan2[4]['nama']='APRIL'.' '.$year_date;
			$bulan2[4]['jumlah']=0;
			$bulan2[5]['nama']='MEI'.' '.$year_date;
			$bulan2[5]['jumlah']=0;
			$bulan2[6]['nama']='JUNI'.' '.$year_date;
			$bulan2[6]['jumlah']=0;
			$bulan2[7]['nama']='JULI'.' '.$year_date;
			$bulan2[7]['jumlah']=0;
			$bulan2[8]['nama']='AGUSTUS'.' '.$year_date;
			$bulan2[8]['jumlah']=0;
			$bulan2[9]['nama']='SEPTEMBER'.' '.$year_date;
			$bulan2[9]['jumlah']=0;
			$bulan2[10]['nama']='OKTOBER'.' '.$year_date;
			$bulan2[10]['jumlah']=0;
			$bulan2[11]['nama']='NOVEMBER'.' '.$year_date;
			$bulan2[11]['jumlah']=0;
			$bulan2[13]['nama']='SETELAH NOVEMBER'.' '.$year_date;
			$bulan2[13]['jumlah']=0;
	
			$jumlahtemp=0;
			$jumlahperayat=0;
			$i=0;
			$i2=0;
			$before=0;
			foreach($data as $item) {
				$bln = substr($item["masa_pajak"],-7,2);
				$thn = substr($item["masa_pajak"],-4,4);
				if ($new == 0){
					$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
					$nama=$item["nama_ayat"];
					$jenis=$item["jns_pajak"];
					$wp=$item["wp_name"];
					$npwpd=$item["npwpd"];
					$address=$item["address"];
					if ($thn == $year_date && $bln != 12){
						switch ($bln) {
							case "01":
								$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
								break;
							case "02":
								$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
								break;
							case "03":
								$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
								break;
							case "04":
								$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
								break;
							case "05":
								$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
								break;
							case "06":
								$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
								break;
							case "07":
								$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
								break;
							case "08":
								$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
								break;
							case "09":
								$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
								break;
							case "10":
								$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
								break;
							case "11":
								$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
								break;
						}
					}else{
						if ($thn == ($year_date - 1) && $bln == 12){
							$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
						}
						else{
							if ($thn < $year_date){
								$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
							}
							else{
								if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
										$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
								}
							}
						}
					}
					
					$jumlahtemp += $item["jumlah_terima"];
					$new =1;
				}else{
					if ($before['npwpd']==$item['npwpd']){				
						if ($thn == $year_date && $bln != 12){
							switch ($bln) {
								case "01":
									$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
									break;
								case "02":
									$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
									break;
								case "03":
									$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
									break;
								case "04":
									$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
									break;
								case "05":
									$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
									break;
								case "06":
									$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
									break;
								case "07":
									$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
									break;
								case "08":
									$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
									break;
								case "09":
									$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
									break;
								case "10":
									$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
									break;
								case "11":
									$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
									break;
							}
						}else{
							if ($thn == ($year_date - 1) && $bln == 12){
								$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
											$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
									}
								}
							}
						}

						$jumlahtemp += $item["jumlah_terima"];
						$ayat = $item["kode_ayat"];
					}else{
						foreach($bulan2 as $bulan) {
							$output .= '<tr>';
							$output .= '<td align="center">'.($i+1).'</td>';
							$output .= '<td align="center">'.$kode.'</td>';
							$output .= '<td align="center">'.$jenis.'</td>';
							$output .= '<td align="center">'.$nama.'</td>';
							//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
							$output .= '<td align="left">'.$wp.'</td>';
							$output .= '<td align="left">'.$npwpd.'</td>';
							$output .= '<td align="left">'.$address.'</td>';
							$output .= '<td align="left">'.$bulan['nama'].'</td>';
							$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
							$output .= '</tr>';
						}
						$output .= '<tr>';
							$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
							$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
						$output .= '</tr>';
				
						
						$jumlahperayat += $jumlahtemp;
				
						$jumlahtemp=0;
						$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
						$nama=$item["nama_ayat"];
						$jenis=$item["jns_pajak"];
						$wp=$item["wp_name"];
						$npwpd=$item["npwpd"];
						$address=$item["address"];
						$bulan2[1]['jumlah']=0;
						$bulan2[2]['jumlah']=0;
						$bulan2[3]['jumlah']=0;
						$bulan2[4]['jumlah']=0;
						$bulan2[5]['jumlah']=0;
						$bulan2[6]['jumlah']=0;
						$bulan2[7]['jumlah']=0;
						$bulan2[8]['jumlah']=0;
						$bulan2[9]['jumlah']=0;
						$bulan2[10]['jumlah']=0;
						$bulan2[11]['jumlah']=0;
						$bulan2[0]['jumlah']=0;
						$bulan2[12]['jumlah']=0;
						$bulan2[13]['jumlah']=0;
						
						
						if ($thn == $year_date && $bln != 12){
							switch ($bln) {
								case "01":
									$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
									break;
								case "02":
									$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
									break;
								case "03":
									$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
									break;
								case "04":
									$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
									break;
								case "05":
									$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
									break;
								case "06":
									$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
									break;
								case "07":
									$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
									break;
								case "08":
									$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
									break;
								case "09":
									$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
									break;
								case "10":
									$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
									break;
								case "11":
									$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
									break;
							}
						}else{
							if ($thn == ($year_date - 1) && $bln == 12){
								$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)||($thn > $year_date)){
											$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
									}
								}
							}
						}
						$jumlahtemp += $item["jumlah_terima"];
						$i=$i+1;
				
					}
				}
				
				$before = $item;
				$i2=$i2+1;
				if(empty($data[$i2]))
				{
					$jumlahperayat += $jumlahtemp;
					foreach($bulan2 as $bulan) {
						$output .= '<tr>';
						$output .= '<td align="center">'.($i+1).'</td>';
						$output .= '<td align="center">'.$kode.'</td>';
						$output .= '<td align="center">'.$jenis.'</td>';
						$output .= '<td align="center">'.$nama.'</td>';
						//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
						$output .= '<td align="left">'.$wp.'</td>';
						$output .= '<td align="left">'.$npwpd.'</td>';
						$output .= '<td align="left">'.$address.'</td>';
						$output .= '<td align="left">'.$bulan['nama'].'</td>';
						$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
						$output .= '</tr>';
					}
					$output .= '<tr>';
						$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
						$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
					$output .= '</tr>';
				}
			}
			$output .= '<tr>';
				$output .= '<td align="CENTER" colspan=6>TOTAL PAJAK</td>';
				$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
			$output .= '</tr>';
	
			$output.='</td></tr></table>';
			$output.='</table>';
		}else{
			if($jenis_laporan == 'piutang'){
				$bulan2				= array();
				$bulan2[12]['nama']='SEBELUM DESEMBER'.' '.($year_date-2);
				$bulan2[12]['jumlah']=0;
				$bulan2[0]['nama']='DESEMBER'.' '.($year_date-2);
				$bulan2[0]['jumlah']=0;
				$bulan2[1]['nama']='JANUARI'.' '.($year_date-1);
				$bulan2[1]['jumlah']=0;
				$bulan2[2]['nama']='FEBRUARI'.' '.($year_date-1);
				$bulan2[2]['jumlah']=0;
				$bulan2[3]['nama']='MARET'.' '.($year_date-1);
				$bulan2[3]['jumlah']=0;
				$bulan2[4]['nama']='APRIL'.' '.($year_date-1);
				$bulan2[4]['jumlah']=0;
				$bulan2[5]['nama']='MEI'.' '.($year_date-1);
				$bulan2[5]['jumlah']=0;
				$bulan2[6]['nama']='JUNI'.' '.($year_date-1);
				$bulan2[6]['jumlah']=0;
				$bulan2[7]['nama']='JULI'.' '.($year_date-1);
				$bulan2[7]['jumlah']=0;
				$bulan2[8]['nama']='AGUSTUS'.' '.($year_date-1);
				$bulan2[8]['jumlah']=0;
				$bulan2[9]['nama']='SEPTEMBER'.' '.($year_date-1);
				$bulan2[9]['jumlah']=0;
				$bulan2[10]['nama']='OKTOBER'.' '.($year_date-1);
				$bulan2[10]['jumlah']=0;
				$bulan2[11]['nama']='NOVEMBER'.' '.($year_date-1);
				$bulan2[11]['jumlah']=0;
				$bulan2[13]['nama']='DESEMBER'.' '.$year_date;
				$bulan2[13]['jumlah']=0;
				
				$jumlahtemp=0;
				$jumlahperayat=0;
				$i=0;
				$i2=0;
				$before=0;
				$new=0;
				
				foreach($data as $item) {
					$bln = substr($item["masa_pajak"],-7,2);
					$thn = substr($item["masa_pajak"],-4,4);
					if ($new == 0){
						$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
						$nama=$item["nama_ayat"];
						$jenis=$item["jns_pajak"];
						$wp=$item["wp_name"];
						$npwpd=$item["npwpd"];
						$address=$item["address"];
						if ($thn == ($year_date-1) && $bln != 12){
							switch ($bln) {
								case "01":
									$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
									break;
								case "02":
									$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
									break;
								case "03":
									$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
									break;
								case "04":
									$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
									break;
								case "05":
									$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
									break;
								case "06":
									$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
									break;
								case "07":
									$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
									break;
								case "08":
									$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
									break;
								case "09":
									$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
									break;
								case "10":
									$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
									break;
								case "11":
									$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
									break;
							}
						}else{
							if ($thn == ($year_date - 2) && $bln == 12){
								$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
							}
							else{
								if ($thn < $year_date){
									$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
								}
								else{
									if (($thn == $year_date && $bln == 12)){
											$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
									}
									/*else{
										if ($thn > $year_date){
											$ydes=$ydes+$item["jumlah_terima"];
										}
									}*/
								}
							}
						}
						
						$jumlahtemp += $item["jumlah_terima"];
						$new =1;
					}else{
						if ($before['npwpd']==$item['npwpd']){				
							if ($thn == ($year_date-1) && $bln != 12){
								switch ($bln) {
									case "01":
										$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
										break;
									case "02":
										$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
										break;
									case "03":
										$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
										break;
									case "04":
										$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
										break;
									case "05":
										$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
										break;
									case "06":
										$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
										break;
									case "07":
										$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
										break;
									case "08":
										$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
										break;
									case "09":
										$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
										break;
									case "10":
										$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
										break;
									case "11":
										$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
										break;
								}
							}else{
								if ($thn == ($year_date - 2) && $bln == 12){
									$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
								}
								else{
									if ($thn < $year_date){
										$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
									}
									else{
										if (($thn == $year_date && $bln == 12)){
												$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
										}
										/*else{
											if ($thn > $year_date){
												$ydes=$ydes+$item["jumlah_terima"];
											}
										}*/
									}
								}
							}

							$jumlahtemp += $item["jumlah_terima"];
							$ayat = $item["kode_ayat"];
						}else{
							foreach($bulan2 as $bulan) {
								$output .= '<tr>';
								$output .= '<td align="center">'.($i+1).'</td>';
								$output .= '<td align="center">'.$kode.'</td>';
								$output .= '<td align="center">'.$jenis.'</td>';
								$output .= '<td align="center">'.$nama.'</td>';
								//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
								$output .= '<td align="left">'.$wp.'</td>';
								$output .= '<td align="left">'.$npwpd.'</td>';
								$output .= '<td align="left">'.$address.'</td>';
								$output .= '<td align="left">'.$bulan['nama'].'</td>';
								$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
								$output .= '</tr>';
							}
							$output .= '<tr>';
								$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
								$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
							$output .= '</tr>';
							$jumlahperayat += $jumlahtemp;
							$jumlahtemp=0;
							$kode=$item["kode_jns_pajak"]." ".$item["kode_ayat"];
							$nama=$item["nama_ayat"];
							$jenis=$item["jns_pajak"];
							$wp=$item["wp_name"];
							$npwpd=$item["npwpd"];
							$address=$item["address"];
							$bulan2[1]['jumlah']=0;
							$bulan2[2]['jumlah']=0;
							$bulan2[3]['jumlah']=0;
							$bulan2[4]['jumlah']=0;
							$bulan2[5]['jumlah']=0;
							$bulan2[6]['jumlah']=0;
							$bulan2[7]['jumlah']=0;
							$bulan2[8]['jumlah']=0;
							$bulan2[9]['jumlah']=0;
							$bulan2[10]['jumlah']=0;
							$bulan2[11]['jumlah']=0;
							$bulan2[0]['jumlah']=0;
							$bulan2[12]['jumlah']=0;
							$bulan2[13]['jumlah']=0;
							
							if ($thn == ($year_date-1) && $bln != 12){
								switch ($bln) {
									case "01":
										$bulan2[1]['jumlah']=$bulan2[1]['jumlah']+$item["jumlah_terima"];
										break;
									case "02":
										$bulan2[2]['jumlah']=$bulan2[2]['jumlah']+$item["jumlah_terima"];
										break;
									case "03":
										$bulan2[3]['jumlah']=$bulan2[3]['jumlah']+$item["jumlah_terima"];
										break;
									case "04":
										$bulan2[4]['jumlah']=$bulan2[4]['jumlah']+$item["jumlah_terima"];
										break;
									case "05":
										$bulan2[5]['jumlah']=$bulan2[5]['jumlah']+$item["jumlah_terima"];
										break;
									case "06":
										$bulan2[6]['jumlah']=$bulan2[6]['jumlah']+$item["jumlah_terima"];
										break;
									case "07":
										$bulan2[7]['jumlah']=$bulan2[7]['jumlah']+$item["jumlah_terima"];
										break;
									case "08":
										$bulan2[8]['jumlah']=$bulan2[8]['jumlah']+$item["jumlah_terima"];
										break;
									case "09":
										$bulan2[9]['jumlah']=$bulan2[9]['jumlah']+$item["jumlah_terima"];
										break;
									case "10":
										$bulan2[10]['jumlah']=$bulan2[10]['jumlah']+$item["jumlah_terima"];
										break;
									case "11":
										$bulan2[11]['jumlah']=$bulan2[11]['jumlah']+$item["jumlah_terima"];
										break;
								}
							}else{
								if ($thn == ($year_date - 2) && $bln == 12){
									$bulan2[0]['jumlah']=$bulan2[0]['jumlah']+$item["jumlah_terima"];
								}
								else{
									if ($thn < $year_date){
										$bulan2[12]['jumlah']=$bulan2[12]['jumlah']+$item["jumlah_terima"];
									}
									else{
										if (($thn == $year_date && $bln == 12)){
												$bulan2[13]['jumlah']=$bulan2[13]['jumlah']+$item["jumlah_terima"];
										}
										/*else{
											if ($thn > $year_date){
												$ydes=$ydes+$item["jumlah_terima"];
											}
										}*/
									}
								}
							}
							$jumlahtemp += $item["jumlah_terima"];
							$i=$i+1;
		
						}
					}
		
					$before = $item;
					$i2=$i2+1;
					if(empty($data[$i2]))
					{
						$jumlahperayat += $jumlahtemp;
						foreach($bulan2 as $bulan) {
							$output .= '<tr>';
							$output .= '<td align="center">'.($i+1).'</td>';
							$output .= '<td align="center">'.$kode.'</td>';
							$output .= '<td align="center">'.$jenis.'</td>';
							$output .= '<td align="center">'.$nama.'</td>';
							//$output .= '<td align="left">'.$item['no_kohir'].'</td>';
							$output .= '<td align="left">'.$wp.'</td>';
							$output .= '<td align="left">'.$npwpd.'</td>';
							$output .= '<td align="left">'.$address.'</td>';
							$output .= '<td align="left">'.$bulan['nama'].'</td>';
							$output .= '<td align="right">'.number_format($bulan['jumlah'], 2, ',', '.').'</td>';
							$output .= '</tr>';
						}
						$output .= '<tr>';
							$output .= '<td align="CENTER" colspan=6>JUMLAH PAJAK '.$before["wp_name"].'</td>';
							$output .= '<td align="right">'.number_format($jumlahtemp, 2, ',', '.').'</td>';
						$output .= '</tr>';
					}
				}
				$output .= '<tr>';
					$output .= '<td align="CENTER" colspan=6>TOTAL PAJAK</td>';
					$output .= '<td align="right">'.number_format($jumlahperayat, 2, ',', '.').'</td>';
				$output .= '</tr>';

				$output.='</td></tr></table>';
				$output.='</table>';
			}
		}
		
	}
	return $output;
}


//Page_OnInitializeView @1-16619CEA
function Page_OnInitializeView(& $sender)
{
    $Page_OnInitializeView = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_rep_lap_bpps_piutang2; //Compatibility
//End Page_OnInitializeView

//Custom Code @66-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Page_OnInitializeView @1-81DF8332
    return $Page_OnInitializeView;
}
//End Close Page_OnInitializeView


?>
