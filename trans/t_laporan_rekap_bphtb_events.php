<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_BeforeShow @1-73FA3533
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_laporan_rekap_bphtb; //Compatibility
//End Page_BeforeShow

//Custom Code @10-2A29BDB7
// -------------------------
    // Write your own code here.
    if($t_laporan_rekap_bphtb->cetak_laporan->GetValue()=='T'){
		$param_arr=array();
		if(empty($param_arr['date_start'])){
			$param_arr['date_start']=CCGetFromGet('date_start');
		}
		if(empty($param_arr['date_end'])){
			$param_arr['date_end']=CCGetFromGet('date_end');
		}
		print_laporan($param_arr);
	}
	
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

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

	$pdf->SetFont('helvetica', '',12);
	$pdf->SetWidths(array(200));
	$pdf->ln(1);
    $pdf->RowMultiBorderWithHeight(array("DAFTAR NOTA VERIFIKASI BPHTB "),array('',''),6);
	//$pdf->ln(8);
	$pdf->SetWidths(array(40,200));
	$pdf->ln(4);
	$pdf->RowMultiBorderWithHeight(array("TANGGAL",": ".dateToString($param_arr['date_start'])." s/d ".dateToString($param_arr['date_end'])),array('',''),6);
	$dbConn = new clsDBConnSIKP();
	$whereClause='';
	if(!empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
		$whereClause.=" AND reg_bphtb.creation_date >= '".$param_arr['date_start']."'";
		$whereClause.=" AND reg_bphtb.creation_date <= '".$param_arr['date_end']."'";
	}else if(!empty($param_arr['date_start'])&&empty($param_arr['date_end'])){
		$whereClause.=" AND reg_bphtb.creation_date >= '".$param_arr['date_start']."'";
	}else if(empty($param_arr['date_start'])&&!empty($param_arr['date_end'])){
		$whereClause.=" AND reg_bphtb.creation_date <= '".$param_arr['date_end']."'";
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
			WHERE cust_order.p_order_status_id <> 1";
	$query.=$whereClause;
	$query.=" order by trunc(reg_bphtb.creation_date) ASC,upper(wp_name) ASC";
	$dbConn->query($query);
	$items=array();
	$pdf->SetFont('helvetica', '',9);
	$pdf->ln(2);
	$pdf->SetWidths(array(10,24,20,15,40,18,22,25,20,61,61,27));
	$pdf->SetAligns(Array('C','C','C','C','C','C','C','C','C','C','C','C'));
	$pdf->SetWidths(array(10,30,25,40,40,25,18,28,25,30,25,40,18,18,18,18,18));
	$pdf->SetFont('arial', '',7);
	$pdf->RowMultiBorderWithHeight(array("NO","TANGGAL","NO.REGISTRASI","NAMA WP","JENIS TRANSAKSI","NJOP","LUAS TANAH(M2)","HARGA TANAH(Rp)","LUAS BANGUNAN(M2)","HARGA BANGUNAN(Rp)","HARGA TAKSIRAN(Rp)","NILAI PAJAK YANG HARUS DIBAYAR(Rp)"),array('LTB','LTB','LBT','LTB','TLB','TLB','TLB','TLB','TLB','TLBR'),5);
	$pdf->SetFont('arial', '',8);
	$no =1;
	$pdf->SetAligns(Array('C','L','L','L','L','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	$jumlah =0;
	$jumlah=0;
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
		$pdf->RowMultiBorderWithHeight(array($no,
											dateToString($item['creation_date']),
											$item['registration_no'],
											$item['wp_name'],
											$item['description'],
											$item['njop_pbb'],
											number_format($item['land_area'],0,",","."),
											number_format($item['land_total_price'],2,",","."),
											number_format($item['building_area'],0,",","."),
											number_format($item['building_total_price'],2,",","."),
											number_format($item['market_price'],2,",","."),
											number_format($item['bphtb_amt_final'],2,",",".")
											),array('LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LBR'),6);
		$jumlah+=$dbConn->f("amount");
	//	$jumlah_wp+=$dbConn->f("jumlah_wp");
		$no++;
	}
	/*print_r($items);
	exit;*/
	$pdf->SetWidths(array(250,70));
	$pdf->ln(8);
	//$pdf->RowMultiBorderWithHeight(array("","KASIE VOP"),array('','','','','','',''),6);
	$pdf->Output("","I");
	exit;	
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

?>
