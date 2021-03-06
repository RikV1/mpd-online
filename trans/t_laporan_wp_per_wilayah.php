<?php
//Include Common Files @1-A7879553
define("RelativePath", "..");
define("PathToCurrentPage", "/trans/");
define("FileName", "t_laporan_wp_per_wilayah.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordt_laporan_piutang_pajak { //t_laporan_piutang_pajak Class @2-4FD102CE

//Variables @2-D6FF3E86

    // Public variables
    var $ComponentType = "Record";
    var $ComponentName;
    var $Parent;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $IsEmpty;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $DataSource;
    var $ValidatingControls;
    var $Controls;
    var $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @2-72168498
    function clsRecordt_laporan_piutang_pajak($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record t_laporan_piutang_pajak/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "t_laporan_piutang_pajak";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button1 = & new clsButton("Button1", $Method, $this);
            $this->Button2 = & new clsButton("Button2", $Method, $this);
            $this->cetak_laporan = & new clsControl(ccsHidden, "cetak_laporan", "cetak_laporan", ccsText, "", CCGetRequestParam("cetak_laporan", $Method, NULL), $this);
            $this->kode_wilayah = & new clsControl(ccsListBox, "kode_wilayah", "kode_wilayah", ccsText, "", CCGetRequestParam("kode_wilayah", $Method, NULL), $this);
            $this->kode_wilayah->DSType = dsSQL;
            $this->kode_wilayah->DataSource = new clsDBConnSIKP();
            $this->kode_wilayah->ds = & $this->kode_wilayah->DataSource;
            list($this->kode_wilayah->BoundColumn, $this->kode_wilayah->TextColumn, $this->kode_wilayah->DBFormat) = array("", "", "");
            $this->kode_wilayah->DataSource->SQL = "select business_area_name, business_area_name\n" .
            "from p_business_area ";
            $this->kode_wilayah->DataSource->Order = "";
            $this->kode_wilayah->Required = true;
            $this->vat_code = & new clsControl(ccsTextBox, "vat_code", "Jenis Pajak", ccsText, "", CCGetRequestParam("vat_code", $Method, NULL), $this);
            $this->p_vat_type_id = & new clsControl(ccsHidden, "p_vat_type_id", "p_vat_type_id", ccsText, "", CCGetRequestParam("p_vat_type_id", $Method, NULL), $this);
            $this->Button3 = & new clsButton("Button3", $Method, $this);
            $this->p_account_status_id = & new clsControl(ccsListBox, "p_account_status_id", "p_account_status_id", ccsText, "", CCGetRequestParam("p_account_status_id", $Method, NULL), $this);
            $this->p_account_status_id->DSType = dsSQL;
            $this->p_account_status_id->DataSource = new clsDBConnSIKP();
            $this->p_account_status_id->ds = & $this->p_account_status_id->DataSource;
            list($this->p_account_status_id->BoundColumn, $this->p_account_status_id->TextColumn, $this->p_account_status_id->DBFormat) = array("", "", "");
            $this->p_account_status_id->DataSource->SQL = "select p_account_status_id,code from p_account_status {SQL_OrderBy}";
            $this->p_account_status_id->DataSource->Order = "p_account_status_id";
        }
    }
//End Class_Initialize Event

//Validate Method @2-DD203AA6
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->cetak_laporan->Validate() && $Validation);
        $Validation = ($this->kode_wilayah->Validate() && $Validation);
        $Validation = ($this->vat_code->Validate() && $Validation);
        $Validation = ($this->p_vat_type_id->Validate() && $Validation);
        $Validation = ($this->p_account_status_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->cetak_laporan->Errors->Count() == 0);
        $Validation =  $Validation && ($this->kode_wilayah->Errors->Count() == 0);
        $Validation =  $Validation && ($this->vat_code->Errors->Count() == 0);
        $Validation =  $Validation && ($this->p_vat_type_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->p_account_status_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-0B2EF381
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->cetak_laporan->Errors->Count());
        $errors = ($errors || $this->kode_wilayah->Errors->Count());
        $errors = ($errors || $this->vat_code->Errors->Count());
        $errors = ($errors || $this->p_vat_type_id->Errors->Count());
        $errors = ($errors || $this->p_account_status_id->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @2-ED598703
function SetPrimaryKeys($keyArray)
{
    $this->PrimaryKeys = $keyArray;
}
function GetPrimaryKeys()
{
    return $this->PrimaryKeys;
}
function GetPrimaryKey($keyName)
{
    return $this->PrimaryKeys[$keyName];
}
//End MasterDetail

//Operation Method @2-C940739D
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button1";
            if($this->Button1->Pressed) {
                $this->PressedButton = "Button1";
            } else if($this->Button2->Pressed) {
                $this->PressedButton = "Button2";
            } else if($this->Button3->Pressed) {
                $this->PressedButton = "Button3";
            }
        }
        $Redirect = "t_laporan_wp_per_wilayah.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button1") {
                if(!CCGetEvent($this->Button1->CCSEvents, "OnClick", $this->Button1)) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button2") {
                if(!CCGetEvent($this->Button2->CCSEvents, "OnClick", $this->Button2)) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button3") {
                if(!CCGetEvent($this->Button3->CCSEvents, "OnClick", $this->Button3)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-58EAF69C
    function Show()
    {
        global $CCSUseAmp;
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->kode_wilayah->Prepare();
        $this->p_account_status_id->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->cetak_laporan->Errors->ToString());
            $Error = ComposeStrings($Error, $this->kode_wilayah->Errors->ToString());
            $Error = ComposeStrings($Error, $this->vat_code->Errors->ToString());
            $Error = ComposeStrings($Error, $this->p_vat_type_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->p_account_status_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button1->Show();
        $this->Button2->Show();
        $this->cetak_laporan->Show();
        $this->kode_wilayah->Show();
        $this->vat_code->Show();
        $this->p_vat_type_id->Show();
        $this->Button3->Show();
        $this->p_account_status_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End t_laporan_piutang_pajak Class @2-FCB6E20C



//Initialize Page @1-D3F6C4AB
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
$TemplateFileName = "t_laporan_wp_per_wilayah.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "../";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-5F4AA257
include_once("./t_laporan_wp_per_wilayah_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-9E07BFC4
$DBConnSIKP = new clsDBConnSIKP();
$MainPage->Connections["ConnSIKP"] = & $DBConnSIKP;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$t_laporan_piutang_pajak = & new clsRecordt_laporan_piutang_pajak("", $MainPage);
$Label1 = & new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet, NULL), $MainPage);
$Label1->HTML = true;
$MainPage->t_laporan_piutang_pajak = & $t_laporan_piutang_pajak;
$MainPage->Label1 = & $Label1;

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

//Execute Components @1-704CC6E8
$t_laporan_piutang_pajak->Operation();
//End Execute Components

//Go to destination page @1-1FE94886
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnSIKP->close();
    header("Location: " . $Redirect);
    unset($t_laporan_piutang_pajak);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-CC4C984F
$t_laporan_piutang_pajak->Show();
$Label1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-7272FD6B
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnSIKP->close();
unset($t_laporan_piutang_pajak);
unset($Tpl);
//End Unload Page


?>
