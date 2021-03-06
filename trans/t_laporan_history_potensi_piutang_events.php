<?php
//BindEvents Method @1-EF623785
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_OnInitializeView @1-F9F40233
function Page_OnInitializeView(& $sender)
{
    $Page_OnInitializeView = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_laporan_history_potensi_piutang; //Compatibility
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

//Page_BeforeShow @1-561AA48D
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $t_laporan_history_potensi_piutang; //Compatibility
//End Page_BeforeShow

//Custom Code @572-2A29BDB7
// -------------------------
    // Write your own code here.
	$doAction = CCGetFromGet('doAction');
	global $Label1;
	$param_arr['p_year_period_id'] = CCGetFromGet('p_year_period_id');
	$param_arr['p_finance_period_id'] = CCGetFromGet('p_finance_period_id');
	$param_arr['p_finance_period_id1'] = CCGetFromGet('p_finance_period_id1');
	$param_arr['p_vat_type_id'] = CCGetFromGet('p_vat_type_id','');
	$param_arr['status_bayar'] = CCGetFromGet('ListBox1');
	$param_arr['ketetapan'] = CCGetFromGet('ListBox2',4);
	$param_arr['tgl_penerimaan'] = CCGetFromGet('tgl_penerimaan','');
	$param_arr['tgl_penerimaan_last'] = CCGetFromGet('tgl_penerimaan_last','');

	$param_arr['vat_code'] = CCGetFromGet('vat_code');
	$param_arr['year_code'] = CCGetFromGet('year_code');
	$param_arr['code'] = CCGetFromGet('code');
	$param_arr['code1'] = CCGetFromGet('code1');
	if($doAction == 'view_html') {		
		$Label1->SetText(GetCetakHTML($param_arr));
	}
	if($doAction == 'cetak_excel') {		
		CetakExcel($param_arr);
	}
	
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function GetCetakHTML($param_arr) {
	
	$output = '';
	
	$output .='<table id="table-piutang" class="grid-table-container" border="0" cellspacing="0" cellpadding="0">
          		<tr>
            		<td valign="top">';

	$output .='<table class="grid-table" border="0" cellspacing="0" cellpadding="0" width="900">
                	<tr>
                  		<td class="HeaderLeft"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td> 
                  		<td class="th"><strong>LAPORAN REKAP SKPDKB / STPD</strong></td> 
                  		<td class="HeaderRight"><img border="0" alt="" src="../Styles/sikp/Images/Spacer.gif"></td>
                	</tr>
              		</table>';
	
	//$output .= '<h2>JENIS PAJAK : '.$param_arr['vat_code'].' </h2>';
	$output .= '<h2>PERIODE PAJAK : '.$param_arr['code'].' s.d. '.$param_arr['code1'].'</h2>';
	$tanggal = CCGetFromGet('date_end_laporan','31-12-2014');
	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px" width="100%">
                <tr >';

	$output.='<th align="center" >NO</th>';
	$output.='<th align="center" >JENIS PAJAK</th>';
	$output.='<th align="center" >AYAT PAJAK</th>';
	$output.='<th align="center" >NAMA</th>';
	$output.='<th align="center" >NPWPD</th>';
	$output.='<th align="center" >MASA PAJAK</th>';
	$output.='<th align="center" >TGL TAP</th>';
	$output.='<th align="center" >TGL JTH TEMPO</th>';
	$output.='<th align="center" >TOTAL HARUS DIBAYAR</th>';
	$output.='<th align="center" >STATUS BAYAR</th>';
	$output.='<th align="center" >TANGGAL BAYAR</th>';
	$output.='</tr>';
	
	$dbConn	= new clsDBConnSIKP();
	$query="select a.t_vat_setllement_id as set_id,a.npwd as npwpd ,z.code as masa_pajak,
		to_char(due_date,'dd-mm-yyyy')as due_date_char, to_char(settlement_date,'dd-mm-yyyy') as tgl_tap,
		to_char(due_date,'dd-mm-yyyy') as tgl_jth_tempo, p.vat_code as ayat_pajak,q.vat_code as jenis_pajak,
		* from t_vat_setllement a
		left join t_cust_account x on x.t_cust_account_id=a.t_cust_account_id
		left join t_payment_receipt y on y.t_vat_setllement_id=a.t_vat_setllement_id
		left join p_finance_period z on z.p_finance_period_id = a.p_finance_period_id
		left join p_vat_type_dtl p on p.p_vat_type_dtl_id = a.p_vat_type_dtl_id
		left join p_vat_type q on q.p_vat_type_id = p.p_vat_type_id
		where a.p_finance_period_id in(
			select p_finance_period_id 
			from p_finance_period 
			where 
				start_date >= (select start_date from p_finance_period
					where p_finance_period_id = ".$param_arr['p_finance_period_id'].") 
				and end_date <= (select end_date from p_finance_period
					where p_finance_period_id = ".$param_arr['p_finance_period_id1'].") 
		)
		and a.p_vat_type_dtl_id not in (11, 15, 41, 12, 42, 43, 30, 17, 21, 27, 31)
		and x.p_account_status_id = 1";
    if ($param_arr['ketetapan']!=0){
		$query.=" and p_settlement_type_id = ".$param_arr['ketetapan']." ";
	}
	if ($param_arr['tgl_penerimaan']!=''){
		$query.=" and trunc(payment_date) >= to_date('".$param_arr['tgl_penerimaan']."','dd-mm-yyyy') ";
	}
	if ($param_arr['tgl_penerimaan_last']!=''){
		$query.=" and trunc(payment_date) <= to_date('".$param_arr['tgl_penerimaan_last']."','dd-mm-yyyy') ";
	}
	if ($param_arr['p_vat_type_id']!=''){
		$query.="and a.p_vat_type_dtl_id in (select p_vat_type_dtl_id 
				from p_vat_type_dtl where p_vat_type_id =".$param_arr['p_vat_type_id'].")";
	}
	if ($param_arr['status_bayar']==2){
		$query.="and receipt_no is not null ORDER BY q.p_vat_type_id, ayat_pajak, wp_name, start_period";
	}else{
		if ($param_arr['status_bayar']==3){
			$query.="and receipt_no is null ORDER BY q.p_vat_type_id, ayat_pajak, wp_name, start_period";
		}else{
			$query.="ORDER BY q.p_vat_type_id, ayat_pajak, wp_name, start_period";
		}
	}
	//echo $query;exit;
	$data = array();
	$dbConn->query($query);
	while ($dbConn->next_record()) {
		$data[] = $dbConn->Record;
	}
	$dbConn->close();
	$jumlah =0;
	for ($i = 0; $i < count($data); $i++) {
		//$temp = ($data[$i]['total_penalty_amount']+$data[$i]['db_increasing_charge']+$data[$i]['db_interest_charge']+$data[$i]['debt_vat_amt']);
		$temp = $data[$i]['total_vat_amount']+$data[$i]['total_penalty_amount'];
		$jumlah = $jumlah + $temp;
		$output.='<tr><td align="center" >'.($i+1).'</td>';
		$output.='<td align="left" >'.$data[$i]['jenis_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['ayat_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['wp_name'].'</td>';
		$output.='<td align="left" >'.$data[$i]['npwpd'].'</td>';
		$output.='<td align="left" >'.$data[$i]['masa_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['tgl_tap'].'</td>';
		$output.='<td align="left" >'.$data[$i]['tgl_jth_tempo'].'</td>';
		$output.='<td align="right" >'.number_format($temp, 2, ',', '.').'</td>';
		
		if ($data[$i]['payment_date']=='') {
			$output.='<td align="left" >Belum Bayar</td>';
		}else{
			$output.='<td align="left" >Sudah Bayar</td>';
		}
		$output.='<td align="left" >'.$data[$i]['payment_date'].'</td>';
		$output.='</tr>';
	}

	$output.='<tr><td align="center" colspan=8 >Jumlah</td>';
	$output.='<td align="right">'.number_format($jumlah, 2, ',', '.').'</td>';
	$output.='</tr>';

	$output.='</table>';
	
	return $output;
}


function startExcel($filename = "laporan.xls") {
    
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

function CetakExcel($param_arr) {
	
	startExcel("laporan_history_potensi_piutang.xls");
	
	$dbConn	= new clsDBConnSIKP();
	$query="select a.t_vat_setllement_id as set_id,a.npwd as npwpd ,z.code as masa_pajak,
		to_char(due_date,'dd-mm-yyyy')as due_date_char, to_char(settlement_date,'dd-mm-yyyy') as tgl_tap,
		to_char(due_date,'dd-mm-yyyy') as tgl_jth_tempo, p.vat_code as ayat_pajak,q.vat_code as jenis_pajak,
		* from t_vat_setllement a
		left join t_cust_account x on x.t_cust_account_id=a.t_cust_account_id
		left join t_payment_receipt y on y.t_vat_setllement_id=a.t_vat_setllement_id
		left join p_finance_period z on z.p_finance_period_id = a.p_finance_period_id
		left join p_vat_type_dtl p on p.p_vat_type_dtl_id = a.p_vat_type_dtl_id
		left join p_vat_type q on q.p_vat_type_id = p.p_vat_type_id
		where p_settlement_type_id = ".$param_arr['ketetapan']." 
		and a.p_finance_period_id in(
			select p_finance_period_id 
			from p_finance_period 
			where 
				start_date >= (select start_date from p_finance_period
					where p_finance_period_id = ".$param_arr['p_finance_period_id'].") 
				and end_date <= (select end_date from p_finance_period
					where p_finance_period_id = ".$param_arr['p_finance_period_id1'].") 
		)
		and a.p_vat_type_dtl_id not in (11, 15, 41, 12, 42, 43, 30, 17, 21, 27, 31)
		and x.p_account_status_id = 1";
	if ($param_arr['p_vat_type_id']!=''){
		$query.="and a.p_vat_type_dtl_id in (select p_vat_type_dtl_id 
				from p_vat_type_dtl where p_vat_type_id =".$param_arr['p_vat_type_id'].")";
	}
	if ($param_arr['status_bayar']==2){
		$query.="and receipt_no is not null ORDER BY wp_name";
	}else{
		if ($param_arr['status_bayar']==3){
			$query.="and receipt_no is null ORDER BY wp_name";
		}else{
			$query.="ORDER BY q.p_vat_type_id, ayat_pajak, wp_name, start_period";
		}
	}
	//echo $query;exit;
	$data = array();
	$dbConn->query($query);
	while ($dbConn->next_record()) {
		$data[] = $dbConn->Record;
	}
	$dbConn->close();

	$output = '';
	$output .= '<h2>LAPORAN HISTORY POTENSI PIUTANG<h2/>';
	$output .= '<h2>PERIODE PAJAK : '.$param_arr['code'].' s.d. '.$param_arr['code1'].'</h2>';

	$output .='<table id="table-piutang-detil" class="Grid" border="1" cellspacing="0" cellpadding="3px" width="100%">
                <tr >';

	$output.='<th align="center" >NO</th>';
	$output.='<th align="center" >JENIS PAJAK</th>';
	$output.='<th align="center" >AYAT PAJAK</th>';
	$output.='<th align="center" >NAMA</th>';
	$output.='<th align="center" >NPWPD</th>';
	$output.='<th align="center" >MASA PAJAK</th>';
	$output.='<th align="center" >TGL TAP</th>';
	$output.='<th align="center" >TGL JTH TEMPO</th>';
	$output.='<th align="center" >TOTAL HARUS DIBAYAR</th>';
	$output.='<th align="center" >STATUS BAYAR</th>';
	$output.='<th align="center" >TANGGAL BAYAR</th>';
	$output.='</tr>';
	$jumlah = 0;

    for ($i = 0; $i < count($data); $i++) {
		//$temp = ($data[$i]['total_penalty_amount']+$data[$i]['db_increasing_charge']+$data[$i]['db_interest_charge']+$data[$i]['debt_vat_amt']);
		$temp = $data[$i]['total_vat_amount']+$data[$i]['total_penalty_amount'];
		$jumlah = $jumlah + $temp;
		$output.='<tr><td align="center" >'.($i+1).'</td>';
		$output.='<td align="left" >'.$data[$i]['jenis_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['ayat_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['wp_name'].'</td>';
		$output.='<td align="left" >'.$data[$i]['npwpd'].'</td>';
		$output.='<td align="left" >'.$data[$i]['masa_pajak'].'</td>';
		$output.='<td align="left" >'.$data[$i]['tgl_tap'].'</td>';
		$output.='<td align="left" >'.$data[$i]['tgl_jth_tempo'].'</td>';
		$output.='<td align="right" >'.number_format($temp, 2, ',', '.').'</td>';
		
		if ($data[$i]['payment_date']=='') {
			$output.='<td align="left" >Belum Bayar</td>';
		}else{
			$output.='<td align="left" >Sudah Bayar</td>';
		}
		$output.='<td align="left" >'.$data[$i]['payment_date'].'</td>';
		$output.='</tr>';
	}
	$output.='<tr><td align="center" colspan=8 >Jumlah</td>';
	$output.='<td align="right">'.number_format($jumlah, 2, ',', '.').'</td>';
	$output.='</tr>';

	$output.='</table>';

	echo $output;
	exit;
}
?>
