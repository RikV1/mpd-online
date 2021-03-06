<?php
//Include Common Files @1-14524943
define("RelativePath", "..");
define("PathToCurrentPage", "/trans/");
define("FileName", "t_target_realisasi_jenis_bulan_view.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridt_target_realisasiGrid { //t_target_realisasiGrid class @2-7DA52549

//Variables @2-AC1EDBB9

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $IsEmpty;
    var $ForceIteration = false;
    var $HasRecord = false;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;
    var $RowNumber;
    var $ControlsVisible = array();

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";
    var $Attributes;

    // Grid Controls
    var $StaticControls;
    var $RowControls;
//End Variables

//Class_Initialize Event @2-B5FF1B9A
    function clsGridt_target_realisasiGrid($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "t_target_realisasiGrid";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid t_target_realisasiGrid";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clst_target_realisasiGridDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 12;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->bulan = & new clsControl(ccsLabel, "bulan", "bulan", ccsText, "", CCGetRequestParam("bulan", ccsGet, NULL), $this);
        $this->target_amount = & new clsControl(ccsLabel, "target_amount", "target_amount", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("target_amount", ccsGet, NULL), $this);
        $this->p_finance_period_id = & new clsControl(ccsHidden, "p_finance_period_id", "p_finance_period_id", ccsText, "", CCGetRequestParam("p_finance_period_id", ccsGet, NULL), $this);
        $this->percentage = & new clsControl(ccsLabel, "percentage", "percentage", ccsFloat, "", CCGetRequestParam("percentage", ccsGet, NULL), $this);
        $this->debt_amt = & new clsControl(ccsLabel, "debt_amt", "debt_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("debt_amt", ccsGet, NULL), $this);
        $this->realisasi_amt = & new clsControl(ccsLabel, "realisasi_amt", "realisasi_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("realisasi_amt", ccsGet, NULL), $this);
        $this->total_amt = & new clsControl(ccsLabel, "total_amt", "total_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total_amt", ccsGet, NULL), $this);
        $this->p_vat_type_id = & new clsControl(ccsHidden, "p_vat_type_id", "p_vat_type_id", ccsText, "", CCGetRequestParam("p_vat_type_id", ccsGet, NULL), $this);
        $this->start_date = & new clsControl(ccsHidden, "start_date", "start_date", ccsText, "", CCGetRequestParam("start_date", ccsGet, NULL), $this);
        $this->end_date = & new clsControl(ccsHidden, "end_date", "end_date", ccsText, "", CCGetRequestParam("end_date", ccsGet, NULL), $this);
        $this->DLink = & new clsControl(ccsLink, "DLink", "DLink", ccsText, "", CCGetRequestParam("DLink", ccsGet, NULL), $this);
        $this->DLink->HTML = true;
        $this->DLink->Page = "t_target_realisasi_jenis_bulan.php";
        $this->denda_pokok = & new clsControl(ccsLabel, "denda_pokok", "denda_pokok", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("denda_pokok", ccsGet, NULL), $this);
        $this->denda_piutang = & new clsControl(ccsLabel, "denda_piutang", "denda_piutang", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("denda_piutang", ccsGet, NULL), $this);
        $this->penalty_amt = & new clsControl(ccsLabel, "penalty_amt", "penalty_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("penalty_amt", ccsGet, NULL), $this);
        $this->denda_pokok2 = & new clsControl(ccsLabel, "denda_pokok2", "denda_pokok2", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("denda_pokok2", ccsGet, NULL), $this);
        $this->denda_piutang1 = & new clsControl(ccsLabel, "denda_piutang1", "denda_piutang1", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("denda_piutang1", ccsGet, NULL), $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->target_amount_sum = & new clsControl(ccsLabel, "target_amount_sum", "target_amount_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("target_amount_sum", ccsGet, NULL), $this);
        $this->realisasi_amt_sum = & new clsControl(ccsLabel, "realisasi_amt_sum", "realisasi_amt_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("realisasi_amt_sum", ccsGet, NULL), $this);
        $this->penalty_amt_sum = & new clsControl(ccsLabel, "penalty_amt_sum", "penalty_amt_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("penalty_amt_sum", ccsGet, NULL), $this);
        $this->penalty_amt_sum1 = & new clsControl(ccsLabel, "penalty_amt_sum1", "penalty_amt_sum1", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("penalty_amt_sum1", ccsGet, NULL), $this);
        $this->debt_amt_sum = & new clsControl(ccsLabel, "debt_amt_sum", "debt_amt_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("debt_amt_sum", ccsGet, NULL), $this);
        $this->total_amt_sum = & new clsControl(ccsLabel, "total_amt_sum", "total_amt_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total_amt_sum", ccsGet, NULL), $this);
        $this->percentage_sum = & new clsControl(ccsLabel, "percentage_sum", "percentage_sum", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("percentage_sum", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-677F86AD
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["sesp_year_period_id"] = CCGetSession("p_year_period_id", NULL);
        $this->DataSource->Parameters["urlp_vat_type_id"] = CCGetFromGet("p_vat_type_id", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["bulan"] = $this->bulan->Visible;
            $this->ControlsVisible["target_amount"] = $this->target_amount->Visible;
            $this->ControlsVisible["p_finance_period_id"] = $this->p_finance_period_id->Visible;
            $this->ControlsVisible["percentage"] = $this->percentage->Visible;
            $this->ControlsVisible["debt_amt"] = $this->debt_amt->Visible;
            $this->ControlsVisible["realisasi_amt"] = $this->realisasi_amt->Visible;
            $this->ControlsVisible["total_amt"] = $this->total_amt->Visible;
            $this->ControlsVisible["p_vat_type_id"] = $this->p_vat_type_id->Visible;
            $this->ControlsVisible["start_date"] = $this->start_date->Visible;
            $this->ControlsVisible["end_date"] = $this->end_date->Visible;
            $this->ControlsVisible["DLink"] = $this->DLink->Visible;
            $this->ControlsVisible["denda_pokok"] = $this->denda_pokok->Visible;
            $this->ControlsVisible["denda_piutang"] = $this->denda_piutang->Visible;
            $this->ControlsVisible["penalty_amt"] = $this->penalty_amt->Visible;
            $this->ControlsVisible["denda_pokok2"] = $this->denda_pokok2->Visible;
            $this->ControlsVisible["denda_piutang1"] = $this->denda_piutang1->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->bulan->SetValue($this->DataSource->bulan->GetValue());
                $this->target_amount->SetValue($this->DataSource->target_amount->GetValue());
                $this->p_finance_period_id->SetValue($this->DataSource->p_finance_period_id->GetValue());
                $this->debt_amt->SetValue($this->DataSource->debt_amt->GetValue());
                $this->realisasi_amt->SetValue($this->DataSource->realisasi_amt->GetValue());
                $this->total_amt->SetValue($this->DataSource->total_amt->GetValue());
                $this->p_vat_type_id->SetValue($this->DataSource->p_vat_type_id->GetValue());
                $this->start_date->SetValue($this->DataSource->start_date->GetValue());
                $this->end_date->SetValue($this->DataSource->end_date->GetValue());
                $this->DLink->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->DLink->Parameters = CCAddParam($this->DLink->Parameters, "p_finance_period_id", $this->DataSource->f("p_finance_period_id"));
                $this->denda_pokok->SetValue($this->DataSource->denda_pokok->GetValue());
                $this->denda_piutang->SetValue($this->DataSource->denda_piutang->GetValue());
                $this->penalty_amt->SetValue($this->DataSource->penalty_amt->GetValue());
                $this->denda_pokok2->SetValue($this->DataSource->denda_pokok2->GetValue());
                $this->denda_piutang1->SetValue($this->DataSource->denda_piutang1->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->bulan->Show();
                $this->target_amount->Show();
                $this->p_finance_period_id->Show();
                $this->percentage->Show();
                $this->debt_amt->Show();
                $this->realisasi_amt->Show();
                $this->total_amt->Show();
                $this->p_vat_type_id->Show();
                $this->start_date->Show();
                $this->end_date->Show();
                $this->DLink->Show();
                $this->denda_pokok->Show();
                $this->denda_piutang->Show();
                $this->penalty_amt->Show();
                $this->denda_pokok2->Show();
                $this->denda_piutang1->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->Navigator->TotalPages <= 1) {
            $this->Navigator->Visible = false;
        }
        $this->Navigator->Show();
        $this->target_amount_sum->Show();
        $this->realisasi_amt_sum->Show();
        $this->penalty_amt_sum->Show();
        $this->penalty_amt_sum1->Show();
        $this->debt_amt_sum->Show();
        $this->total_amt_sum->Show();
        $this->percentage_sum->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-250CA6DC
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->bulan->Errors->ToString());
        $errors = ComposeStrings($errors, $this->target_amount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->p_finance_period_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->percentage->Errors->ToString());
        $errors = ComposeStrings($errors, $this->debt_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->realisasi_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->total_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->p_vat_type_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->start_date->Errors->ToString());
        $errors = ComposeStrings($errors, $this->end_date->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DLink->Errors->ToString());
        $errors = ComposeStrings($errors, $this->denda_pokok->Errors->ToString());
        $errors = ComposeStrings($errors, $this->denda_piutang->Errors->ToString());
        $errors = ComposeStrings($errors, $this->penalty_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->denda_pokok2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->denda_piutang1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End t_target_realisasiGrid Class @2-FCB6E20C

class clst_target_realisasiGridDataSource extends clsDBConnSIKP {  //t_target_realisasiGridDataSource Class @2-9A91A27E

//DataSource Variables @2-DE05F76B
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $bulan;
    var $target_amount;
    var $p_finance_period_id;
    var $debt_amt;
    var $realisasi_amt;
    var $total_amt;
    var $p_vat_type_id;
    var $start_date;
    var $end_date;
    var $denda_pokok;
    var $denda_piutang;
    var $penalty_amt;
    var $denda_pokok2;
    var $denda_piutang1;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-F67B7B44
    function clst_target_realisasiGridDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid t_target_realisasiGrid";
        $this->Initialize();
        $this->bulan = new clsField("bulan", ccsText, "");
        
        $this->target_amount = new clsField("target_amount", ccsFloat, "");
        
        $this->p_finance_period_id = new clsField("p_finance_period_id", ccsText, "");
        
        $this->debt_amt = new clsField("debt_amt", ccsFloat, "");
        
        $this->realisasi_amt = new clsField("realisasi_amt", ccsFloat, "");
        
        $this->total_amt = new clsField("total_amt", ccsFloat, "");
        
        $this->p_vat_type_id = new clsField("p_vat_type_id", ccsText, "");
        
        $this->start_date = new clsField("start_date", ccsText, "");
        
        $this->end_date = new clsField("end_date", ccsText, "");
        
        $this->denda_pokok = new clsField("denda_pokok", ccsFloat, "");
        
        $this->denda_piutang = new clsField("denda_piutang", ccsFloat, "");
        
        $this->penalty_amt = new clsField("penalty_amt", ccsFloat, "");
        
        $this->denda_pokok2 = new clsField("denda_pokok2", ccsFloat, "");
        
        $this->denda_piutang1 = new clsField("denda_piutang1", ccsFloat, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-13FF2B55
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "MAX(start_date) ASC";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-0BE38D2A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "sesp_year_period_id", ccsFloat, "", "", $this->Parameters["sesp_year_period_id"], 0, false);
        $this->wp->AddParameter("2", "urlp_vat_type_id", ccsText, "", "", $this->Parameters["urlp_vat_type_id"], "", false);
    }
//End Prepare Method

//Open Method @2-6A846439
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) FROM (SELECT\n" .
        "	MAX(p_finance_period_id) as p_finance_period_id,\n" .
        "	MAX(p_year_period_id) as p_year_period_id,\n" .
        "	to_char(MAX(start_date),'dd-mm-yyyy') as start_date,\n" .
        "	to_char(MAX(end_date),'dd-mm-yyyy') as end_date,\n" .
        "	MAX(p_vat_type_id) as p_vat_type_id,\n" .
        "	MAX(bulan) as bulan,\n" .
        "	ROUND(SUM (target_amount)) as target_amount,\n" .
        "	ROUND(SUM (realisasi_amt)) as realisasi_amt,\n" .
        "	MAX (penalty_amt) as penalty_amt,\n" .
        "	ROUND(SUM (debt_amt)) as debt_amt,\n" .
        "	MAX (denda_pokok) as denda_pokok,\n" .
        "	MAX (denda_piutang) as denda_piutang\n" .
        "FROM\n" .
        "	f_target_vs_real_monthly_new3_mark_ii(\n" .
        "	" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "\n" .
        "	)\n" .
        "GROUP BY p_finance_period_id) cnt";
        $this->SQL = "SELECT\n" .
        "	MAX(p_finance_period_id) as p_finance_period_id,\n" .
        "	MAX(p_year_period_id) as p_year_period_id,\n" .
        "	to_char(MAX(start_date),'dd-mm-yyyy') as start_date,\n" .
        "	to_char(MAX(end_date),'dd-mm-yyyy') as end_date,\n" .
        "	MAX(p_vat_type_id) as p_vat_type_id,\n" .
        "	MAX(bulan) as bulan,\n" .
        "	ROUND(SUM (target_amount)) as target_amount,\n" .
        "	ROUND(SUM (realisasi_amt)) as realisasi_amt,\n" .
        "	MAX (penalty_amt) as penalty_amt,\n" .
        "	ROUND(SUM (debt_amt)) as debt_amt,\n" .
        "	MAX (denda_pokok) as denda_pokok,\n" .
        "	MAX (denda_piutang) as denda_piutang\n" .
        "FROM\n" .
        "	f_target_vs_real_monthly_new3_mark_ii(\n" .
        "	" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "\n" .
        "	)\n" .
        "GROUP BY p_finance_period_id {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-C6C052CD
    function SetValues()
    {
        $this->bulan->SetDBValue($this->f("bulan"));
        $this->target_amount->SetDBValue(trim($this->f("target_amount")));
        $this->p_finance_period_id->SetDBValue($this->f("p_finance_period_id"));
        $this->debt_amt->SetDBValue(trim($this->f("debt_amt")));
        $this->realisasi_amt->SetDBValue(trim($this->f("realisasi_amt")));
        $this->total_amt->SetDBValue(trim($this->f("target_amount")));
        $this->p_vat_type_id->SetDBValue($this->f("p_vat_type_id"));
        $this->start_date->SetDBValue($this->f("start_date"));
        $this->end_date->SetDBValue($this->f("end_date"));
        $this->denda_pokok->SetDBValue(trim($this->f("denda_pokok")));
        $this->denda_piutang->SetDBValue(trim($this->f("denda_piutang")));
        $this->penalty_amt->SetDBValue(trim($this->f("penalty_amt")));
        $this->denda_pokok2->SetDBValue(trim($this->f("denda_pokok")));
        $this->denda_piutang1->SetDBValue(trim($this->f("denda_piutang")));
    }
//End SetValues Method

} //End t_target_realisasiGridDataSource Class @2-FCB6E20C

class clsGridt_target_realisasiGrid1 { //t_target_realisasiGrid1 class @119-103EBCA7

//Variables @119-AC1EDBB9

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $IsEmpty;
    var $ForceIteration = false;
    var $HasRecord = false;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;
    var $RowNumber;
    var $ControlsVisible = array();

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";
    var $Attributes;

    // Grid Controls
    var $StaticControls;
    var $RowControls;
//End Variables

//Class_Initialize Event @119-08665D2A
    function clsGridt_target_realisasiGrid1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "t_target_realisasiGrid1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid t_target_realisasiGrid1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clst_target_realisasiGrid1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 12;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->ayat = & new clsControl(ccsLabel, "ayat", "ayat", ccsText, "", CCGetRequestParam("ayat", ccsGet, NULL), $this);
        $this->target_amount = & new clsControl(ccsLabel, "target_amount", "target_amount", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("target_amount", ccsGet, NULL), $this);
        $this->p_finance_period_id = & new clsControl(ccsHidden, "p_finance_period_id", "p_finance_period_id", ccsText, "", CCGetRequestParam("p_finance_period_id", ccsGet, NULL), $this);
        $this->percentage = & new clsControl(ccsLabel, "percentage", "percentage", ccsFloat, "", CCGetRequestParam("percentage", ccsGet, NULL), $this);
        $this->debt_amt = & new clsControl(ccsLabel, "debt_amt", "debt_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("debt_amt", ccsGet, NULL), $this);
        $this->realisasi_amt = & new clsControl(ccsLabel, "realisasi_amt", "realisasi_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("realisasi_amt", ccsGet, NULL), $this);
        $this->total_amt = & new clsControl(ccsLabel, "total_amt", "total_amt", ccsFloat, array(False, 2, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total_amt", ccsGet, NULL), $this);
        $this->p_vat_type_id = & new clsControl(ccsHidden, "p_vat_type_id", "p_vat_type_id", ccsText, "", CCGetRequestParam("p_vat_type_id", ccsGet, NULL), $this);
        $this->start_date = & new clsControl(ccsHidden, "start_date", "start_date", ccsText, "", CCGetRequestParam("start_date", ccsGet, NULL), $this);
        $this->end_date = & new clsControl(ccsHidden, "end_date", "end_date", ccsText, "", CCGetRequestParam("end_date", ccsGet, NULL), $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @119-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @119-059F9F1B
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["sesp_year_period_id"] = CCGetSession("p_year_period_id", NULL);
        $this->DataSource->Parameters["urlp_vat_type_id"] = CCGetFromGet("p_vat_type_id", NULL);
        $this->DataSource->Parameters["urlp_finance_period_id"] = CCGetFromGet("p_finance_period_id", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["ayat"] = $this->ayat->Visible;
            $this->ControlsVisible["target_amount"] = $this->target_amount->Visible;
            $this->ControlsVisible["p_finance_period_id"] = $this->p_finance_period_id->Visible;
            $this->ControlsVisible["percentage"] = $this->percentage->Visible;
            $this->ControlsVisible["debt_amt"] = $this->debt_amt->Visible;
            $this->ControlsVisible["realisasi_amt"] = $this->realisasi_amt->Visible;
            $this->ControlsVisible["total_amt"] = $this->total_amt->Visible;
            $this->ControlsVisible["p_vat_type_id"] = $this->p_vat_type_id->Visible;
            $this->ControlsVisible["start_date"] = $this->start_date->Visible;
            $this->ControlsVisible["end_date"] = $this->end_date->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ayat->SetValue($this->DataSource->ayat->GetValue());
                $this->target_amount->SetValue($this->DataSource->target_amount->GetValue());
                $this->p_finance_period_id->SetValue($this->DataSource->p_finance_period_id->GetValue());
                $this->debt_amt->SetValue($this->DataSource->debt_amt->GetValue());
                $this->realisasi_amt->SetValue($this->DataSource->realisasi_amt->GetValue());
                $this->total_amt->SetValue($this->DataSource->total_amt->GetValue());
                $this->p_vat_type_id->SetValue($this->DataSource->p_vat_type_id->GetValue());
                $this->start_date->SetValue($this->DataSource->start_date->GetValue());
                $this->end_date->SetValue($this->DataSource->end_date->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->ayat->Show();
                $this->target_amount->Show();
                $this->p_finance_period_id->Show();
                $this->percentage->Show();
                $this->debt_amt->Show();
                $this->realisasi_amt->Show();
                $this->total_amt->Show();
                $this->p_vat_type_id->Show();
                $this->start_date->Show();
                $this->end_date->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->Navigator->TotalPages <= 1) {
            $this->Navigator->Visible = false;
        }
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @119-3CA2F0B6
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->ayat->Errors->ToString());
        $errors = ComposeStrings($errors, $this->target_amount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->p_finance_period_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->percentage->Errors->ToString());
        $errors = ComposeStrings($errors, $this->debt_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->realisasi_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->total_amt->Errors->ToString());
        $errors = ComposeStrings($errors, $this->p_vat_type_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->start_date->Errors->ToString());
        $errors = ComposeStrings($errors, $this->end_date->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End t_target_realisasiGrid1 Class @119-FCB6E20C

class clst_target_realisasiGrid1DataSource extends clsDBConnSIKP {  //t_target_realisasiGrid1DataSource Class @119-25560BF8

//DataSource Variables @119-94395054
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ayat;
    var $target_amount;
    var $p_finance_period_id;
    var $debt_amt;
    var $realisasi_amt;
    var $total_amt;
    var $p_vat_type_id;
    var $start_date;
    var $end_date;
//End DataSource Variables

//DataSourceClass_Initialize Event @119-424FDFF1
    function clst_target_realisasiGrid1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid t_target_realisasiGrid1";
        $this->Initialize();
        $this->ayat = new clsField("ayat", ccsText, "");
        
        $this->target_amount = new clsField("target_amount", ccsFloat, "");
        
        $this->p_finance_period_id = new clsField("p_finance_period_id", ccsText, "");
        
        $this->debt_amt = new clsField("debt_amt", ccsFloat, "");
        
        $this->realisasi_amt = new clsField("realisasi_amt", ccsFloat, "");
        
        $this->total_amt = new clsField("total_amt", ccsFloat, "");
        
        $this->p_vat_type_id = new clsField("p_vat_type_id", ccsText, "");
        
        $this->start_date = new clsField("start_date", ccsText, "");
        
        $this->end_date = new clsField("end_date", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @119-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @119-9F94EEDB
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "sesp_year_period_id", ccsFloat, "", "", $this->Parameters["sesp_year_period_id"], 0, false);
        $this->wp->AddParameter("2", "urlp_vat_type_id", ccsText, "", "", $this->Parameters["urlp_vat_type_id"], null, false);
        $this->wp->AddParameter("3", "urlp_finance_period_id", ccsText, "", "", $this->Parameters["urlp_finance_period_id"], 'null', false);
    }
//End Prepare Method

//Open Method @119-76927D0C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) FROM ((SELECT\n" .
        "	MAX(target.p_finance_period_id) as p_finance_period_id,\n" .
        "	MAX(target.p_year_period_id) as p_year_period_id,\n" .
        "	to_char(MAX(target.start_date),'dd-mm-yyyy') as start_date,\n" .
        "	to_char(MAX(target.end_date),'dd-mm-yyyy') as end_date,\n" .
        "	MAX(target.p_vat_type_id) as p_vat_type_id,\n" .
        "	MAX(target.bulan) as bulan,\n" .
        "	ROUND(SUM (target.target_amount)) as target_amount,\n" .
        "	ROUND(SUM (target.realisasi_amt)) as realisasi_amt,\n" .
        "	ROUND(SUM (target.penalty_amt)) as penalty_amt,\n" .
        "	ROUND(SUM (target.debt_amt)) as debt_amt,\n" .
        "	MAX(dtl.vat_code) as ayat\n" .
        "FROM\n" .
        "	f_target_vs_real_monthly_new3_mark_ii(" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . ") target,\n" .
        "	p_vat_type_dtl dtl\n" .
        "WHERE\n" .
        "	dtl.p_vat_type_dtl_id = target.p_vat_type_dtl_id\n" .
        "	AND (target.p_finance_period_id = " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . " or " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . " is null)\n" .
        "GROUP BY target.p_vat_type_dtl_id\n" .
        "\n" .
        "ORDER BY MAX(dtl.vat_code) ASC)\n" .
        "UNION\n" .
        "(select 999,\n" .
        "	" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . ",\n" .
        "	'',\n" .
        "	'',\n" .
        "	0,\n" .
        "	'',\n" .
        "	0,\n" .
        "	f_get_total_denda_ayat_mod_1(" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . ") as jumlah,\n" .
        "	0,\n" .
        "	0,\n" .
        "	'DENDA')) cnt";
        $this->SQL = "(SELECT\n" .
        "	MAX(target.p_finance_period_id) as p_finance_period_id,\n" .
        "	MAX(target.p_year_period_id) as p_year_period_id,\n" .
        "	to_char(MAX(target.start_date),'dd-mm-yyyy') as start_date,\n" .
        "	to_char(MAX(target.end_date),'dd-mm-yyyy') as end_date,\n" .
        "	MAX(target.p_vat_type_id) as p_vat_type_id,\n" .
        "	MAX(target.bulan) as bulan,\n" .
        "	ROUND(SUM (target.target_amount)) as target_amount,\n" .
        "	ROUND(SUM (target.realisasi_amt)) as realisasi_amt,\n" .
        "	ROUND(SUM (target.penalty_amt)) as penalty_amt,\n" .
        "	ROUND(SUM (target.debt_amt)) as debt_amt,\n" .
        "	MAX(dtl.vat_code) as ayat\n" .
        "FROM\n" .
        "	f_target_vs_real_monthly_new3_mark_ii(" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . ") target,\n" .
        "	p_vat_type_dtl dtl\n" .
        "WHERE\n" .
        "	dtl.p_vat_type_dtl_id = target.p_vat_type_dtl_id\n" .
        "	AND (target.p_finance_period_id = " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . " or " . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . " is null)\n" .
        "GROUP BY target.p_vat_type_dtl_id\n" .
        "\n" .
        "ORDER BY MAX(dtl.vat_code) ASC)\n" .
        "UNION\n" .
        "(select 999,\n" .
        "	" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . ",\n" .
        "	'',\n" .
        "	'',\n" .
        "	0,\n" .
        "	'',\n" .
        "	0,\n" .
        "	f_get_total_denda_ayat_mod_1(" . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "," . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "," . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . ") as jumlah,\n" .
        "	0,\n" .
        "	0,\n" .
        "	'DENDA')";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @119-4DB832D8
    function SetValues()
    {
        $this->ayat->SetDBValue($this->f("ayat"));
        $this->target_amount->SetDBValue(trim($this->f("target_amount")));
        $this->p_finance_period_id->SetDBValue($this->f("p_finance_period_id"));
        $this->debt_amt->SetDBValue(trim($this->f("debt_amt")));
        $this->realisasi_amt->SetDBValue(trim($this->f("realisasi_amt")));
        $this->total_amt->SetDBValue(trim($this->f("target_amount")));
        $this->p_vat_type_id->SetDBValue($this->f("p_vat_type_id"));
        $this->start_date->SetDBValue($this->f("start_date"));
        $this->end_date->SetDBValue($this->f("end_date"));
    }
//End SetValues Method

} //End t_target_realisasiGrid1DataSource Class @119-FCB6E20C

//Initialize Page @1-2CB2B970
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "t_target_realisasi_jenis_bulan_view.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "../";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-C75CEEAF
include_once("./t_target_realisasi_jenis_bulan_view_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-023ECB5D
$DBConnSIKP = new clsDBConnSIKP();
$MainPage->Connections["ConnSIKP"] = & $DBConnSIKP;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$t_target_realisasiGrid = & new clsGridt_target_realisasiGrid("", $MainPage);
$t_target_realisasiGrid1 = & new clsGridt_target_realisasiGrid1("", $MainPage);
$ayat = & new clsControl(ccsLabel, "ayat", "ayat", ccsText, "", CCGetRequestParam("ayat", ccsGet, NULL), $MainPage);
$ayat->HTML = true;
$p_year_period_id = & new clsControl(ccsLabel, "p_year_period_id", "p_year_period_id", ccsText, "", CCGetRequestParam("p_year_period_id", ccsGet, NULL), $MainPage);
$p_year_period_id->HTML = true;
$MainPage->t_target_realisasiGrid = & $t_target_realisasiGrid;
$MainPage->t_target_realisasiGrid1 = & $t_target_realisasiGrid1;
$MainPage->ayat = & $ayat;
$MainPage->p_year_period_id = & $p_year_period_id;
$t_target_realisasiGrid->Initialize();
$t_target_realisasiGrid1->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-52F9C312
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "../");
$Attributes->Show();
//End Initialize HTML Template

//Go to destination page @1-1C8A63A3
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnSIKP->close();
    header("Location: " . $Redirect);
    unset($t_target_realisasiGrid);
    unset($t_target_realisasiGrid1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-674666C4
$t_target_realisasiGrid->Show();
$t_target_realisasiGrid1->Show();
$ayat->Show();
$p_year_period_id->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-BA9F9BEA
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnSIKP->close();
unset($t_target_realisasiGrid);
unset($t_target_realisasiGrid1);
unset($Tpl);
//End Unload Page


?>
