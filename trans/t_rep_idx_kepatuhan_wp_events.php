<?php
//BindEvents Method @1-EF623785
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_OnInitializeView @1-787C3C01
function Page_OnInitializeView(& $sender)
{
    $Page_OnInitializeView = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_rep_idx_kepatuhan_wp; //Compatibility
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

//Page_BeforeShow @1-E0AE6D70
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_rep_idx_kepatuhan_wp; //Compatibility
//End Page_BeforeShow
	global $Label1;
	global $t_rep_idx_kepatuhan_wpSearch;
//Custom Code @566-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//End Custom Code
	$doAction = CCGetFromGet('doAction');
	
	$data = array();
	$param_arr = array();
				
	$param_arr['p_year_period_id'] = CCGetFromGet('p_year_period_id');
	$param_arr['p_finance_period_id'] = CCGetFromGet('p_finance_period_id');
		
	$param_arr['tahun_periode'] = CCGetFromGet('tahun_periode');
	$param_arr['pajak_periode'] = CCGetFromGet('pajak_periode');
		
	$t_rep_idx_kepatuhan_wpSearch->p_year_period_id->SetValue($param_arr['p_year_period_id']);
	$t_rep_idx_kepatuhan_wpSearch->p_finance_period_id->SetValue($param_arr['p_finance_period_id']);
	$t_rep_idx_kepatuhan_wpSearch->year_code->SetValue($param_arr['tahun_periode']);
	$t_rep_idx_kepatuhan_wpSearch->code->SetValue($param_arr['pajak_periode']);


	if($doAction == 'view_umum') {
		
		if(!empty($param_arr['p_finance_period_id'])) {
			print_excel_umum($param_arr);
		}else {
			/* Tampilkan Alert */
			echo '<script> alert("Semua Filter Harus Diisi"); </script>';
		}
	} else if($doAction == 'view_detil') {
		
		if(!empty($param_arr['p_finance_period_id'])) {
			print_excel_detil($param_arr);
		}else {
			/* Tampilkan Alert */
			echo '<script> alert("Semua Filter Harus Diisi"); </script>';
		}
	
	}else if($doAction == 'html_general') {
			
		if(!empty($param_arr['p_finance_period_id'])) {
			$Label1->SetText(GetCetakGeneralHTML($param_arr));
		}else {
			/* Tampilkan Alert */
			echo '<script> alert("Semua Filter Harus Diisi"); </script>';
		}		

	}else if($doAction == 'html_detil') {
			
		if(!empty($param_arr['p_finance_period_id'])) {
			$Label1->SetText(GetCetakDetilHTML($param_arr));
		}else {
			/* Tampilkan Alert */
			echo '<script> alert("Semua Filter Harus Diisi"); </script>';
		}		

	}else {
		
		//do nothing 
	}
	
//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function startExcel($filename = "laporan.xls") {
    
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}


function GetCetakGeneralHTML($param_arr) {
	
	$jumlah_total = getGrandTotal($param_arr['p_finance_period_id']);
	
	$total_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],1);
	$total_kurang_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],2);
	$total_tidak_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],3);
	
	$prosentase_patuh = $total_patuh / $jumlah_total * 100;
	$prosentase_kurang_patuh = $total_kurang_patuh / $jumlah_total * 100;
	$prosentase_tidak_patuh = $total_tidak_patuh / $jumlah_total * 100;
	
	$waktu = time();
	$data = array($total_patuh, $total_kurang_patuh, $total_tidak_patuh);
	createPie($waktu, $data);


	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="60%" style="margin-left:10px;padding:5px 5px 5px 5px;">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN KEPATUHAN WAJIB PAJAK</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              	</table>';
	$output .= '<div align="right"><a href="#" onClick="downloadGeneral();"> Download Excel </a></div>';
	$output .= '<h3>TAHUN PAJAK : '.$param_arr['tahun_periode'].'</h3>';
	$output .= '<h3>PERIODE PAJAK : '.$param_arr['pajak_periode'].'</h3> <br/>';
	
	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';

	$output .= '<th width="15">NO</th>';
	$output .= '<th width="150">KRITERIA</th>';
	$output .= '<th width="150">JUMLAH WP</th>';
	$output .= '<th width="150">PRESENTASE (%)</th>';
	$output .= '</tr>';
	
	$output .= '<tr>
				<td align="center"><b>1</b></td>
				<td><b>WP PATUH</b></td>
				<td align="right">'.$total_patuh.'</td>
				<td align="right">'.round($prosentase_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center"><b>2</b></td>
				<td><b>WP KURANG PATUH</b></td>
				<td align="right">'.$total_kurang_patuh.' </td>
				<td align="right">'.round($prosentase_kurang_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center"><b>3</b></td>
				<td><b>WP TIDAK PATUH</b></td>
				<td align="right">'.$total_tidak_patuh.' </td>
				<td align="right">'.round($prosentase_tidak_patuh,2).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><b>JUMLAH</b></td>
				<td align="right">'.$jumlah_total.'</td>
				<td>&nbsp; </td>
			</tr>';
	$output .= '</table>';
	$output .= '<br/> <table>
					<tr>
						<td><img width="400" height="300" src = "'.ServerURL.'/graphfiles/pie_kepatuhan_'.$waktu.'.png"/></td>
					</tr>
				</table>';	

	$output .= '</td></tr>';
	$output .= '</table>';

	return $output;
} 

function print_excel_umum($param_arr) {
	
	startExcel("index_kepatuhan_all_".$param_arr['tahun_periode']."_".$param_arr['pajak_periode']);
	echo "<div><h3> LAPORAN KEPATUHAN WAJIB PAJAK </h3></div>";	
	echo "<div><b>TAHUN PAJAK : ".$param_arr['tahun_periode']."</b></div>";	
	echo "<div><b>PERIODE PAJAK : ".$param_arr['pajak_periode']."</b></div><br/>";	

	$jumlah_total = getGrandTotal($param_arr['p_finance_period_id']);
	
	$total_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],1);
	$total_kurang_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],2);
	$total_tidak_patuh = getTotalPerJenis('NULL',$param_arr['p_finance_period_id'],3);
	
	$prosentase_patuh = $total_patuh / $jumlah_total * 100;
	$prosentase_kurang_patuh = $total_kurang_patuh / $jumlah_total * 100;
	$prosentase_tidak_patuh = $total_tidak_patuh / $jumlah_total * 100;
	
	$waktu = time();
	$data = array($total_patuh, $total_kurang_patuh, $total_tidak_patuh);
	createPie($waktu, $data);
	
	echo '
		<table border="1">
			<tr>
				<th width="15">NO</th>
				<th width="150">KRITERIA </th>
				<th width="150">JUMLAH WP</th>
				<th width="150">PERSENTASE (%)</th>
			</tr>
			<tr>
				<td align="center">1</td>
				<td>WP PATUH</td>
				<td align="right">'.$total_patuh.'</td>
				<td align="right">'.round($prosentase_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center">2</td>
				<td>WP KURANG PATUH</td>
				<td align="right">'.$total_kurang_patuh.' </td>
				<td align="right">'.round($prosentase_kurang_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center">3</td>
				<td>WP TIDAK PATUH</td>
				<td align="right">'.$total_tidak_patuh.' </td>
				<td align="right">'.round($prosentase_tidak_patuh,2).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>JUMLAH </td>
				<td align="right">'.$jumlah_total.'</td>
				<td>&nbsp; </td>
			</tr>
		</table> <br/><br/>
		<table>
			<tr>
				<td><img width="400" height="300" src = "'.ServerURL.'/graphfiles/pie_kepatuhan_'.$waktu.'.png"/></td>
			</tr>
		</table>
	';

	exit;
}



function GetCetakDetilHTML($param_arr) {
	
	
	$grand_total_hotel = getGrandTotalPerJenisPajak(1, $param_arr['p_finance_period_id']);
	$grand_total_restoran = getGrandTotalPerJenisPajak(2, $param_arr['p_finance_period_id']);
	$grand_total_hiburan = getGrandTotalPerJenisPajak(3, $param_arr['p_finance_period_id']);
	$grand_total_parkir = getGrandTotalPerJenisPajak(4, $param_arr['p_finance_period_id']);
	
	// ---- start hotel ---
	$hotel_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],1);
	$hotel_kurang_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],2);
	$hotel_tidak_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],3);
	
	$hotel_persen_patuh = $hotel_patuh / $grand_total_hotel * 100;
	$hotel_persen_kurang_patuh = $hotel_kurang_patuh / $grand_total_hotel * 100;
	$hotel_persen_tidak_patuh = $hotel_tidak_patuh / $grand_total_hotel * 100;
	// ---- end hotel ---
	

	// -- start restoran --
	$restoran_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],1);
	$restoran_kurang_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],2);
	$restoran_tidak_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],3);
	
	$restoran_persen_patuh = $restoran_patuh / $grand_total_restoran * 100;
	$restoran_persen_kurang_patuh = $restoran_kurang_patuh / $grand_total_restoran * 100;
	$restoran_persen_tidak_patuh = $restoran_tidak_patuh / $grand_total_restoran * 100;

	// -- end restoran -- 
	

	//-- start hiburan  --
	$hiburan_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],1);
	$hiburan_kurang_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],2);
	$hiburan_tidak_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],3);
	
	$hiburan_persen_patuh = $hiburan_patuh / $grand_total_hiburan * 100;
	$hiburan_persen_kurang_patuh = $hiburan_kurang_patuh / $grand_total_hiburan * 100;
	$hiburan_persen_tidak_patuh = $hiburan_tidak_patuh / $grand_total_hiburan * 100;

	// -- end hiburan -- 
	
	//-- start parkir --
	$parkir_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],1);
	$parkir_kurang_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],2);
	$parkir_tidak_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],3);
	
	$parkir_persen_patuh = $parkir_patuh / $grand_total_parkir * 100;
	$parkir_persen_kurang_patuh = $parkir_kurang_patuh / $grand_total_parkir * 100;
	$parkir_persen_tidak_patuh = $parkir_tidak_patuh / $grand_total_parkir * 100;
	
	//-- end parkir --
	$waktu = time();
	$data = array('patuh' => array($hotel_patuh, $restoran_patuh, $hiburan_patuh, $parkir_patuh),
				  'kurang_patuh' => array($hotel_kurang_patuh, $restoran_kurang_patuh, $hiburan_kurang_patuh, $parkir_kurang_patuh),
				  'tidak_patuh' => array($hotel_tidak_patuh, $restoran_tidak_patuh, $hiburan_tidak_patuh, $parkir_tidak_patuh) 
				  );
	createGroupBar($waktu, $data);


	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="80%" style="margin-left:10px;padding:5px 5px 5px 5px;">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN KEPATUHAN WAJIB PAJAK</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              	</table>';
	$output .= '<div align="right"><a href="#" onClick="downloadDetil();"> Download Excel </a></div>';
	$output .= '<h3>TAHUN PAJAK : '.$param_arr['tahun_periode'].'</h3>';
	$output .= '<h3>PERIODE PAJAK : '.$param_arr['pajak_periode'].'</h3> <br/>';
	
	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';
	
	$output .= '			
				<th width="15" rowspan="2">NO</th>
				<th width="150" rowspan="2">KRITERIA </th>
				<th width="150" colspan="2">HOTEL</th>
				<th width="150" colspan="2">RESTORAN</th>
				<th width="150" colspan="2">HIBURAN</th>
				<th width="150" colspan="2">PARKIR</th>
			</tr>
			<tr class="Caption">
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
			</tr>
			<tr>
				<td align="center"><b>1</b></td>
				<td><b>WP PATUH</b></td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',1,1)">'.$hotel_patuh.'</a></td>
				<td align="right">'.round($hotel_persen_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',2,1)">'.$restoran_patuh.'</a></td>
				<td align="right">'.round($restoran_persen_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',3,1)">'.$hiburan_patuh.'</a></td>
				<td align="right">'.round($hiburan_persen_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',4,1)">'.$parkir_patuh.'</a></td>
				<td align="right">'.round($parkir_persen_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center"><b>2</b></td>
				<td><b>WP KURANG PATUH</b></td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',1,2)">'.$hotel_kurang_patuh.'</a></td>
				<td align="right">'.round($hotel_persen_kurang_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',2,2)">'.$restoran_kurang_patuh.'</a></td>
				<td align="right">'.round($restoran_persen_kurang_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',3,2)">'.$hiburan_kurang_patuh.'</a></td>
				<td align="right">'.round($hiburan_persen_kurang_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',4,2)">'.$parkir_kurang_patuh.'</a></td>
				<td align="right">'.round($parkir_persen_kurang_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center"><b>3</b></td>
				<td><b>WP TIDAK PATUH</b></td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',1,3)">'.$hotel_tidak_patuh.'</a></td>
				<td align="right">'.round($hotel_persen_tidak_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',2,3)">'.$restoran_tidak_patuh.'</a></td>
				<td align="right">'.round($restoran_persen_tidak_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',3,3)">'.$hiburan_tidak_patuh.'</a></td>
				<td align="right">'.round($hiburan_persen_tidak_patuh,2).'</td>
				<td align="right"><a href="#" onclick="OpenPdf('.$param_arr['p_finance_period_id'].','.$param_arr['p_year_period_id'].',4,3)">'.$parkir_tidak_patuh.'</a></td>
				<td align="right">'.round($parkir_persen_tidak_patuh,2).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><b>JUMLAH</b></td>
				<td align="right"><b>'.$grand_total_hotel.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_restoran.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_hiburan.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_parkir.'</b></td>
				<td>&nbsp;</td>
			</tr>';	
	$output .= '</table>';
	$output .= '<br/><br/>
		<table>
		<tr>
			<td><img width="400" height="300" src = "'.ServerURL.'/graphfiles/bar_kepatuhan_'.$waktu.'.png"/></td>
		</tr>
		</table>';
	$output .= '</td></tr>';
	$output .= '</table>';

	return $output;
}

function print_excel_detil($param_arr) {

	startExcel("index_kepatuhan_detil_".$param_arr['tahun_periode']."_".$param_arr['pajak_periode']);
	echo "<div><h3> LAPORAN KEPATUHAN WAJIB PAJAK </h3></div>";	
	echo "<div><b>TAHUN PAJAK : ".$param_arr['tahun_periode']."</b></div>";	
	echo "<div><b>PERIODE PAJAK : ".$param_arr['pajak_periode']."</b></div> <br/>";
	
	
	$grand_total_hotel = getGrandTotalPerJenisPajak(1, $param_arr['p_finance_period_id']);
	$grand_total_restoran = getGrandTotalPerJenisPajak(2, $param_arr['p_finance_period_id']);
	$grand_total_hiburan = getGrandTotalPerJenisPajak(3, $param_arr['p_finance_period_id']);
	$grand_total_parkir = getGrandTotalPerJenisPajak(4, $param_arr['p_finance_period_id']);
	
	// ---- start hotel ---
	$hotel_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],1);
	$hotel_kurang_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],2);
	$hotel_tidak_patuh = getTotalPerJenis(1,$param_arr['p_finance_period_id'],3);
	
	$hotel_persen_patuh = $hotel_patuh / $grand_total_hotel * 100;
	$hotel_persen_kurang_patuh = $hotel_kurang_patuh / $grand_total_hotel * 100;
	$hotel_persen_tidak_patuh = $hotel_tidak_patuh / $grand_total_hotel * 100;
	// ---- end hotel ---
	

	// -- start restoran --
	$restoran_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],1);
	$restoran_kurang_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],2);
	$restoran_tidak_patuh = getTotalPerJenis(2,$param_arr['p_finance_period_id'],3);
	
	$restoran_persen_patuh = $restoran_patuh / $grand_total_restoran * 100;
	$restoran_persen_kurang_patuh = $restoran_kurang_patuh / $grand_total_restoran * 100;
	$restoran_persen_tidak_patuh = $restoran_tidak_patuh / $grand_total_restoran * 100;

	// -- end restoran -- 
	

	//-- start hiburan  --
	$hiburan_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],1);
	$hiburan_kurang_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],2);
	$hiburan_tidak_patuh = getTotalPerJenis(3,$param_arr['p_finance_period_id'],3);
	
	$hiburan_persen_patuh = $hiburan_patuh / $grand_total_hiburan * 100;
	$hiburan_persen_kurang_patuh = $hiburan_kurang_patuh / $grand_total_hiburan * 100;
	$hiburan_persen_tidak_patuh = $hiburan_tidak_patuh / $grand_total_hiburan * 100;

	// -- end hiburan -- 
	
	//-- start parkir --
	$parkir_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],1);
	$parkir_kurang_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],2);
	$parkir_tidak_patuh = getTotalPerJenis(4,$param_arr['p_finance_period_id'],3);
	
	$parkir_persen_patuh = $parkir_patuh / $grand_total_parkir * 100;
	$parkir_persen_kurang_patuh = $parkir_kurang_patuh / $grand_total_parkir * 100;
	$parkir_persen_tidak_patuh = $parkir_tidak_patuh / $grand_total_parkir * 100;
	
	//-- end parkir --
	$waktu = time();
	$data = array('patuh' => array($hotel_patuh, $restoran_patuh, $hiburan_patuh, $parkir_patuh),
				  'kurang_patuh' => array($hotel_kurang_patuh, $restoran_kurang_patuh, $hiburan_kurang_patuh, $parkir_kurang_patuh),
				  'tidak_patuh' => array($hotel_tidak_patuh, $restoran_tidak_patuh, $hiburan_tidak_patuh, $parkir_tidak_patuh) 
				  );
	createGroupBar($waktu, $data);
	
	echo '
		<table border="1">
			<tr>
				<th width="15" rowspan="2">NO</th>
				<th width="150" rowspan="2">KRITERIA </th>
				<th width="150" colspan="2">HOTEL</th>
				<th width="150" colspan="2">RESTORAN</th>
				<th width="150" colspan="2">HIBURAN</th>
				<th width="150" colspan="2">PARKIR</th>
			</tr>
			<tr>
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
				<th>JUMLAH</th>
				<th>%</th>
			</tr>
			<tr>
				<td align="center">1</td>
				<td>WP PATUH</td>
				<td align="right">'.$hotel_patuh.'</td>
				<td align="right">'.round($hotel_persen_patuh,2).'</td>
				<td align="right">'.$restoran_patuh.'</td>
				<td align="right">'.round($restoran_persen_patuh,2).'</td>
				<td align="right">'.$hiburan_patuh.'</td>
				<td align="right">'.round($hiburan_persen_patuh,2).'</td>
				<td align="right">'.$parkir_patuh.'</td>
				<td align="right">'.round($parkir_persen_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center">2</td>
				<td>WP KURANG PATUH</td>
				<td align="right">'.$hotel_kurang_patuh.'</td>
				<td align="right">'.round($hotel_persen_kurang_patuh,2).'</td>
				<td align="right">'.$restoran_kurang_patuh.'</td>
				<td align="right">'.round($restoran_persen_kurang_patuh,2).'</td>
				<td align="right">'.$hiburan_kurang_patuh.'</td>
				<td align="right">'.round($hiburan_persen_kurang_patuh,2).'</td>
				<td align="right">'.$parkir_kurang_patuh.'</td>
				<td align="right">'.round($parkir_persen_kurang_patuh,2).'</td>
			</tr>
			<tr>
				<td align="center">3</td>
				<td>WP TIDAK PATUH</td>
				<td align="right">'.$hotel_tidak_patuh.'</td>
				<td align="right">'.round($hotel_persen_tidak_patuh,2).'</td>
				<td align="right">'.$restoran_tidak_patuh.'</td>
				<td align="right">'.round($restoran_persen_tidak_patuh,2).'</td>
				<td align="right">'.$hiburan_tidak_patuh.'</td>
				<td align="right">'.round($hiburan_persen_tidak_patuh,2).'</td>
				<td align="right">'.$parkir_tidak_patuh.'</td>
				<td align="right">'.round($parkir_persen_tidak_patuh,2).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>JUMLAH</td>
				<td align="right"><b>'.$grand_total_hotel.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_restoran.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_hiburan.'</b></td>
				<td>&nbsp;</td>
				<td align="right"><b>'.$grand_total_parkir.'</b></td>
				<td>&nbsp;</td>
			</tr>
		</table>

		<br/><br/>
		<table>
		<tr>
			<td><img width="400" height="300" src = "'.ServerURL.'/graphfiles/bar_kepatuhan_'.$waktu.'.png"/></td>
		</tr>
		</table>
	';
	exit;
}

function createGroupBar($waktu, $data_bar) {
	require_once ('../jpgraph/jpgraph.php');
	require_once ('../jpgraph/jpgraph_bar.php');

	$patuh = $data_bar['patuh'];
	$kurang_patuh = $data_bar['kurang_patuh'];
	$tidak_patuh = $data_bar['tidak_patuh'];


	// Create the graph. These two calls are always required
	$graph = new Graph(400,300,'auto');
	$graph->SetScale("textlin");

	$theme_class = new UniversalTheme;
	$graph->SetTheme($theme_class);

	
	$graph->ygrid->SetFill(false);
	$graph->xaxis->SetTickLabels(array('HOTEL','RESTORAN','HIBURAN','PARKIR'));
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);

	// Create the bar plots
	$b1plot = new BarPlot($patuh);
	$b2plot = new BarPlot($kurang_patuh);
	$b3plot = new BarPlot($tidak_patuh);

	// Create the grouped bar plot
	$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot));
	// ...and add it to the graPH
	$graph->Add($gbplot);


	$b1plot->SetColor("white");
	$b1plot->SetFillColor("#8AE952");
	$b1plot->SetLegend('PATUH');

	$b2plot->SetColor("white");
	$b2plot->SetFillColor("#056FD8");
	$b2plot->SetLegend('KURANG PATUH');

	$b3plot->SetColor("white");
	$b3plot->SetFillColor("#AE0000");
	$b3plot->SetLegend('TIDAK PATUH');

	$graph->title->Set("GRAFIK KEPATUHAN WP");
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);

	// Display the graph
	$graph->Stroke("../graphfiles/bar_kepatuhan_".$waktu.".png");
}

function createPie($waktu, $data_pie) {
	
	require_once ('../jpgraph/jpgraph.php');
	require_once ('../jpgraph/jpgraph_pie.php');
	require_once ('../jpgraph/jpgraph_pie3d.php');

	// Some data
	$data = $data_pie;

	// Create the Pie Graph. 
	$graph = new PieGraph(400,300);

	$theme_class= new VividTheme;
	$graph->SetTheme($theme_class);

	// Set A title for the plot
	$graph->title->Set("GRAFIK KEPATUHAN WP");
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
	
	// Create
	$p1 = new PiePlot3D($data);
	$p1->SetLegends(array('PATUH', 'KURANG PATUH', 'TIDAK PATUH'));
	$p1->SetCenter(0.5,0.45);
	$p1->ShowBorder();
	$p1->SetColor('black');
	
	$graph->Add($p1);
	
	//unlink('../graphfiles/pie_kepatuhan.png');
	$graph->Stroke("../graphfiles/pie_kepatuhan_".$waktu.".png");
}



function dateToString($date){
	if(empty($date)) return "";
	
	$monthname = array(0  => '-',
	                   1  => 'Januari',
	                   2  => 'Februari',
	                   3  => 'Maret',
	                   4  => 'April',
	                   5  => 'Mei',
	                   6  => 'Juni',
	                   7  => 'Juli',
	                   8  => 'Agustus',
	                   9  => 'September',
	                   10 => 'Oktober',
	                   11 => 'November',
	                   12 => 'Desember');    
	
	$pieces = explode('-', $date);
	
	return $pieces[2].' '.$monthname[(int)$pieces[1]].' '.$pieces[0];
}

function getTotalPerJenis($jenis_pajak, $p_finance_period_id, $tipe_patuh) {
	$dbConn = new clsDBConnSIKP();
	$jumlah_total = 0;

	$query = "SELECT COUNT(*) AS jumlah FROM f_rep_bpps_patuh(".$jenis_pajak.",".$p_finance_period_id.",".$tipe_patuh.")";
	$dbConn->query($query);
	while ($dbConn->next_record()) {
		$jumlah_total = $dbConn->f('jumlah');
	}

	$dbConn->close();
	return $jumlah_total;
}

function getGrandTotal($p_finance_period_id) {
	
	$dbConn = new clsDBConnSIKP();
	$jumlah_total = 0;
	$query = "SELECT SUM( JML) AS jumlah FROM (
				SELECT count(*) AS JML from f_rep_bpps_patuh(null,".$p_finance_period_id.",1)
				UNION ALL
				SELECT count(*) AS JML from f_rep_bpps_patuh(null,".$p_finance_period_id.",2)
				UNION ALL
				SELECT count(*) AS JML from f_rep_bpps_patuh(null,".$p_finance_period_id.",3)
			 )";
	$dbConn->query($query);
	while ($dbConn->next_record()) {
		$jumlah_total = $dbConn->f('jumlah');
	}
			
	$dbConn->close();
	return $jumlah_total;
}


function getGrandTotalPerJenisPajak($jenis_pajak, $p_finance_period_id) {
	
	$dbConn = new clsDBConnSIKP();
	$jumlah_total = 0;
	$query = "SELECT SUM( JML) AS jumlah FROM (
				SELECT count(*) AS JML from f_rep_bpps_patuh(".$jenis_pajak.",".$p_finance_period_id.",1)
				UNION ALL
				SELECT count(*) AS JML from f_rep_bpps_patuh(".$jenis_pajak.",".$p_finance_period_id.",2)
				UNION ALL
				SELECT count(*) AS JML from f_rep_bpps_patuh(".$jenis_pajak.",".$p_finance_period_id.",3)
			 )";
	$dbConn->query($query);
	while ($dbConn->next_record()) {
		$jumlah_total = $dbConn->f('jumlah');
	}
			
	$dbConn->close();
	return $jumlah_total;
}
?>
