<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_BeforeShow @1-172C17D8
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_laporan_teguran_bphtb; //Compatibility
//End Page_BeforeShow
	global $Label1;
//Custom Code @10-2A29BDB7
// -------------------------
    // Write your own code here.

	$doAction = CCGetFromGet('doAction');
	if($doAction == 'view_html') {
				
		$data = array();
		
		$param_arr = array();
		$param_arr['date_start'] = CCGetFromGet('date_start');
		$param_arr['date_end'] = CCGetFromGet('date_end');
		
		$t_laporan_teguran_bphtb->date_start_laporan->SetValue($param_arr['date_start']);
		$t_laporan_teguran_bphtb->date_end_laporan->SetValue($param_arr['date_end']);

		if(!empty($param_arr['date_start']) and !empty($param_arr['date_end'])) {
			
			$dbConn	= new clsDBConnSIKP();
						
			$whereClause='';
			if(!empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
				$whereClause.=" AND (trunc(reg_bphtb.creation_date) BETWEEN '".$param_arr['date_start']."'";
				$whereClause.=" AND '".$param_arr['date_end']."')";
			}else if(!empty($param_arr['date_start'])&&empty($param_arr['date_end'])){
				$whereClause.=" AND trunc(reg_bphtb.creation_date) >= '".$param_arr['date_start']."'";
			}else if(empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
				$whereClause.=" AND trunc(reg_bphtb.creation_date) <= '".$param_arr['date_end']."'";
			}

			$whereClause.= " AND NOT EXISTS (SELECT 1 FROM t_payment_receipt_bphtb as x WHERE x.t_bphtb_registration_id = reg_bphtb.t_bphtb_registration_id) ";
			$query="SELECT
				reg_bphtb.t_bphtb_registration_id,
				to_char(reg_bphtb.creation_date, 'YYYY-MM-DD') as creation_date,
				registration_no,
				wp_name,
				reg_bphtb.p_bphtb_legal_doc_type_id,
				bphtb_doc.description,
				njop_pbb,
				land_area,
				land_total_price,
				building_area,
				building_total_price,
				market_price,
				bphtb_amt_final
			FROM
				sikp.t_bphtb_registration reg_bphtb
			LEFT JOIN p_bphtb_legal_doc_type bphtb_doc on bphtb_doc.p_bphtb_legal_doc_type_id = reg_bphtb.p_bphtb_legal_doc_type_id
			LEFT JOIN t_customer_order cust_order ON cust_order.t_customer_order_id = reg_bphtb.t_customer_order_id 
			WHERE cust_order.p_order_status_id <> 1 and bphtb_amt_final >0";
			
			$query.= $whereClause;
			$query.= " order by trunc(reg_bphtb.creation_date) ASC,upper(wp_name) ASC";

			$dbConn->query($query);
			while ($dbConn->next_record()) {
				$data['t_bphtb_registration_id'][] = $dbConn->f("t_bphtb_registration_id"); 
				$data['creation_date'][] = $dbConn->f("creation_date"); 	
				$data['registration_no'][] = $dbConn->f("registration_no");
				$data['wp_name'][] = $dbConn->f("wp_name");
				$data['p_bphtb_legal_doc_type_id'][] = $dbConn->f("p_bphtb_legal_doc_type_id");
				$data['description'][] = $dbConn->f("description");
				$data['njop_pbb'][] = $dbConn->f("njop_pbb");
				$data['land_area'][] = $dbConn->f("land_area");
				$data['land_total_price'][] = $dbConn->f("land_total_price");
				$data['building_area'][] = $dbConn->f("building_area");
				$data['building_total_price'][] = $dbConn->f("building_total_price");
				$data['market_price'][] = $dbConn->f("market_price");
				$data['bphtb_amt_final'][] = $dbConn->f("bphtb_amt_final");
				$data['nilai_njop'][] = $dbConn->f("building_total_price") + $dbConn->f("land_total_price");

			}

			$dbConn->close();
			$Label1->SetText(GetCetakHTML($data, $param_arr['date_start'], $param_arr['date_end']));


		}else {
			/* Tampilkan Alert */
			echo '<script> alert("Filter Tanggal Harus Diisi"); </script>';
		}
	}
	else {
		
		//do nothing 
	}
	
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function GetCetakHTML($data, $date_start, $date_end) {
	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0" width="100%">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN TEGURAN BPHTB</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              		</table>';
	
	$output .= '<h2>DAFTAR NOTA VERIFIKASI BPHTB (Belum Bayar) </h2>';
	$output .= '<h2>TANGGAL : '.dateToString($date_start, "-")." s/d ".dateToString($date_end, "-").'</h2> <br/>';

	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px">
                <tr class="Caption">';


		$output.='<th>NO</th>';
		$output.='<th>CETAK <br/> SURAT TEGURAN</th>';
		$output.='<th>TANGGAL</th>';
		$output.='<th>NO REGISTRASI</th>';
		$output.='<th>NAMA WP</th>';
		$output.='<th>JENIS TRANSAKSI</th>';
		$output.='<th>NOP</th>';
		$output.='<th>LT/LB</th>';
		$output.='<th>HARGA TANAH (Rp)</th>';
		$output.='<th>HARGA BANGUNAN (Rp)</th>';
		$output.='<th>TOTAL NJOP (Rp)</th>';
		$output.='<th>HARGA PASAR/<br/>TRANSAKSI/<br/>LELANG (Rp)</th>';
		$output.='<th>NILAI PAJAK YANG <br/>HARUS DIBAYAR(Rp)</th>';
		$output.='</tr>';
	
	$jumlah = 0;
    
	for ($i = 0; $i < count($data['registration_no']); $i++) {

		$output .= '<tr>';
		$output .= '<td align="center">'.($i+1).'</td>';
		$output .= '<td align="center" name="teguran'.$data['t_bphtb_registration_id'][$i].'"> <a href="#teguran'.$data['t_bphtb_registration_id'][$i].'" onClick="cetakSuratTeguran('.$data['t_bphtb_registration_id'][$i].');">CETAK TEGURAN</a></td>';
		$output .= '<td align="center">'.dateToString($data['creation_date'][$i]).'</td>';
		$output .= '<td align="left">'.$data['registration_no'][$i].'</td>';
		$output .= '<td align="left">'.$data['wp_name'][$i].'</td>';
		$output .= '<td align="left">'.$data['description'][$i].'</td>';
		$output .= '<td align="left">'.$data['njop_pbb'][$i].'</td>';
		$output .= '<td align="right">'.number_format($data['land_area'][$i],0,",",".")." / ".number_format($data['building_area'][$i],0,",",".").'</td>';
		$output .= '<td align="right">'.number_format($data['land_total_price'][$i],2,",",".").'</td>';
		$output .= '<td align="right">'.number_format($data['building_total_price'][$i],2,",",".").'</td>';
		$output .= '<td align="right">'.number_format($data['nilai_njop'][$i],2,",",".").'</td>';
		$output .= '<td align="right">'.number_format($data['market_price'][$i],2,",",".").'</td>';
		$output .= '<td align="right">'.number_format($data['bphtb_amt_final'][$i],2,",",".").'</td>';
		$output .= '</tr>';
		$jumlah= $jumlah + $data['bphtb_amt_final'][$i];
	}
	$output .= '<tr>';
		$output .= '<td align="center" colspan = 12>JUMLAH NILAI PAJAK YANG HARUS DIBAYAR</td>';
		$output .= '<td align="right" >'.number_format($jumlah,2,",",".").'</td>';
	$output .= '</tr>';
	
	$output.='</td></tr></table>';
	$output.='</table>';
	
	return $output;
}

function print_laporan($param_arr){
	include "../include/fpdf17/mc_table.php";
	$_BORDER = 0;
	$_FONT = 'Times';
	$_FONTSIZE = 10;
    $pdf = new PDF_MC_Table();
	$size = $pdf->_getpagesize('Legal');
	$pdf->DefPageSize = $size;
	$pdf->CurPageSize = $size;
    $pdf->AddPage('Landscape', 'Legal');
    $pdf->SetFont('helvetica', '', $_FONTSIZE);
	$pdf->SetRightMargin(5);
	$pdf->SetLeftMargin(9);
	$pdf->SetAutoPageBreak(false,0);
	

	$textFilter = '';
	if(!empty($param_arr['filter_lap'])) {
		if($param_arr['filter_lap'] == 1) //sudah bayar
			$textFilter = '(Sudah Bayar)';
		if($param_arr['filter_lap'] == 2) //belum bayar
			$textFilter = '(Belum Bayar)';
	}


	$pdf->SetFont('helvetica', '',12);
	$pdf->SetWidths(array(200));
	$pdf->ln(1);
    $pdf->RowMultiBorderWithHeight(array("DAFTAR NOTA VERIFIKASI BPHTB ".$textFilter),array('',''),6);
	//$pdf->ln(8);
	$pdf->SetWidths(array(40,200));
	$pdf->ln(4);
	$pdf->RowMultiBorderWithHeight(array("TANGGAL",": ".dateToString($param_arr['date_start'],"-")." s/d ".dateToString($param_arr['date_end']),"-"),array('',''),6);
	$dbConn = new clsDBConnSIKP();
	$whereClause='';
	if(!empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
		$whereClause.=" AND (trunc(reg_bphtb.creation_date) BETWEEN '".$param_arr['date_start']."'";
		$whereClause.=" AND '".$param_arr['date_end']."')";
	}else if(!empty($param_arr['date_start'])&&empty($param_arr['date_end'])){
		$whereClause.=" AND trunc(reg_bphtb.creation_date) >= '".$param_arr['date_start']."'";
	}else if(empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
		$whereClause.=" AND trunc(reg_bphtb.creation_date) <= '".$param_arr['date_end']."'";
	}

	if(!empty($param_arr['filter_lap'])) {
		
		if($param_arr['filter_lap'] == 1) //sudah bayar
			$whereClause.= " AND (payment.receipt_no is not null or payment.receipt_no <> '') ";
		if($param_arr['filter_lap'] == 2) //belum bayar
			$whereClause.= " AND ( payment.receipt_no is null or payment.receipt_no = '') ";
	}

	$query="SELECT
				to_char(reg_bphtb.creation_date, 'YYYY-MM-DD') as creation_date,
				registration_no,
				wp_name,
				reg_bphtb.p_bphtb_legal_doc_type_id,
				bphtb_doc.description,
				njop_pbb,
				land_area,
				land_total_price,
				building_area,
				building_total_price,
				market_price,
				bphtb_amt_final
			FROM
				sikp.t_bphtb_registration reg_bphtb
			LEFT JOIN p_bphtb_legal_doc_type bphtb_doc on bphtb_doc.p_bphtb_legal_doc_type_id = reg_bphtb.p_bphtb_legal_doc_type_id
			LEFT JOIN t_customer_order cust_order ON cust_order.t_customer_order_id = reg_bphtb.t_customer_order_id 
			LEFT JOIN t_payment_receipt_bphtb payment ON reg_bphtb.t_bphtb_registration_id = payment.t_bphtb_registration_id 
			WHERE cust_order.p_order_status_id <> 1";
	$query.=$whereClause;
	$query.=" order by trunc(reg_bphtb.creation_date) ASC,upper(wp_name) ASC";
	
	$dbConn->query($query);
	$items=array();
	$pdf->SetFont('helvetica', '',9);
	$pdf->ln(2);
	$pdf->SetWidths(array(10,24,20,15,40,18,22,25,20,61,61,27));
	$pdf->SetAligns(Array('C','C','C','C','C','C','C','C','C','C','C','C'));
	$pdf->SetWidths(array(10,24,23,37,30,35,18,28,24,28,25,40,18,18,18,18,18));
	$pdf->SetFont('arial', '',7);
	$pdf->RowMultiBorderWithHeight(array("NO","TANGGAL","NO.REGISTRASI","NAMA WP","JENIS TRANSAKSI","NOP","LT / LB","HARGA TANAH(Rp)","HARGA BANGUNAN(Rp)","TOTAL NJOP (Rp)","HARGA PASAR / TRANSAKSI / LELANG (Rp)","NILAI PAJAK YANG HARUS DIBAYAR(Rp)"),array('LTB','LTB','LBT','LTB','TLB','TLB','TLB','TLB','TLB','TLBR'),5);
	$pdf->SetFont('arial', '',8);
	$no =1;
	$pdf->SetAligns(Array('C','L','L','L','L','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	$jumlah =0;
	$jumlah=0;
	$total_nilai_pajak = 0;
	$nilai_njop = 0;
	while($dbConn->next_record()){
		$items[]= $item = array(
					   'creation_date' => $dbConn->f("creation_date"), 	
					   'registration_no' => $dbConn->f("registration_no"),
					   'wp_name' => $dbConn->f("wp_name"),
					   'p_bphtb_legal_doc_type_id' => $dbConn->f("p_bphtb_legal_doc_type_id"),
					   'description' => $dbConn->f("description"),
					   'njop_pbb' => $dbConn->f("njop_pbb"),
					   'land_area' => $dbConn->f("land_area"),
					   'land_total_price' => $dbConn->f("land_total_price"),
					   'building_area' => $dbConn->f("building_area"),
					   'building_total_price' => $dbConn->f("building_total_price"),
					   'market_price' => $dbConn->f("market_price"),
					   'bphtb_amt_final' => $dbConn->f("bphtb_amt_final")
						);
		
		$nilai_njop = $dbConn->f("building_total_price") + $dbConn->f("land_total_price");
		
		$pdf->RowMultiBorderWithHeight(array($no,
											dateToString($item['creation_date']),
											$item['registration_no'],
											$item['wp_name'],
											$item['description'],
											$item['njop_pbb'],
											number_format($item['land_area'],0,",",".")." / ".number_format($item['building_area'],0,",","."),
											number_format($item['land_total_price'],2,",","."),
											number_format($item['building_total_price'],2,",","."),
											number_format($nilai_njop,2,",","."),
											number_format($item['market_price'],2,",","."),
											number_format($item['bphtb_amt_final'],2,",",".")
											),array('LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LBR'),6);
		$jumlah+=$dbConn->f("amount");
	//	$jumlah_wp+=$dbConn->f("jumlah_wp");
		$total_nilai_pajak += $item['bphtb_amt_final'];
		$no++;
	}
	$pdf->SetWidths(array(282,40));
	$pdf->SetAligns(Array('C','R'));
	$pdf->SetFont('arial', 'B',8);
	$pdf->RowMultiBorderWithHeight(array("TOTAL", number_format($total_nilai_pajak,2,",",".")), array('LB','LBR'), 6);
	/*print_r($items);
	exit;*/
	//$pdf->SetWidths(array(250,70));
	$pdf->ln(12);
	
	$pdf->SetAligns(array("C", "C"));
	$pdf->SetWidths(array(169, 163));
	$pdf->RowMultiBorderWithHeight( array("Mengetahui, \n Kepala Seksi Penyelesaian Piutang \n\n\n\n\n\n\n\n RACHMAT SATIADI, SIP, M.Si. \n  ŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻ ","\n Koordinator BPHTB"."\n\n\n\n\n\n\n\n INDRA WISNU, SE. \n ŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻŻ "), array("",""), 4 );
	$pdf->RowMultiBorderWithHeight( array("NIP : 19691104.1998.03.1.007","NIP : 19731031.2009.1.1001"), array("",""), 1 );
	
	    
	//$pdf->RowMultiBorderWithHeight(array("","KASIE VOP"),array('','','','','','',''),6);
	$pdf->Output("","I");
	exit;	
}
function dateToString($date, $strip = "/"){
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
	
	return $pieces[2].$strip.$monthname[(int)$pieces[1]].$strip.$pieces[0];
}

?>
