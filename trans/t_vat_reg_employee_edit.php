<?php
//Include Common Files @1-3F936517
define("RelativePath", "..");
define("PathToCurrentPage", "/trans/");
define("FileName", "t_vat_reg_employee_edit.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordt_vat_reg_employeeForm { //t_vat_reg_employeeForm Class @94-BDCD4624

//Variables @94-D6FF3E86

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

//Class_Initialize Event @94-871BF11B
    function clsRecordt_vat_reg_employeeForm($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record t_vat_reg_employeeForm/Error";
        $this->DataSource = new clst_vat_reg_employeeFormDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "t_vat_reg_employeeForm";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            $this->t_vat_reg_employee_id = & new clsControl(ccsHidden, "t_vat_reg_employee_id", "Id", ccsFloat, "", CCGetRequestParam("t_vat_reg_employee_id", $Method, NULL), $this);
            $this->description = & new clsControl(ccsTextBox, "description", "Description", ccsText, "", CCGetRequestParam("description", $Method, NULL), $this);
            $this->created_by = & new clsControl(ccsTextBox, "created_by", "Created By", ccsText, "", CCGetRequestParam("created_by", $Method, NULL), $this);
            $this->updated_by = & new clsControl(ccsTextBox, "updated_by", "Updated By", ccsText, "", CCGetRequestParam("updated_by", $Method, NULL), $this);
            $this->creation_date = & new clsControl(ccsTextBox, "creation_date", "Creation Date", ccsText, "", CCGetRequestParam("creation_date", $Method, NULL), $this);
            $this->updated_date = & new clsControl(ccsTextBox, "updated_date", "Updated Date", ccsText, "", CCGetRequestParam("updated_date", $Method, NULL), $this);
            $this->rqst_type_code = & new clsControl(ccsHidden, "rqst_type_code", "rqst_type_code", ccsText, "", CCGetRequestParam("rqst_type_code", $Method, NULL), $this);
            $this->p_rqst_type_id = & new clsControl(ccsHidden, "p_rqst_type_id", "p_rqst_type_id", ccsFloat, "", CCGetRequestParam("p_rqst_type_id", $Method, NULL), $this);
            $this->t_customer_order_id = & new clsControl(ccsHidden, "t_customer_order_id", "t_customer_order_id", ccsFloat, "", CCGetRequestParam("t_customer_order_id", $Method, NULL), $this);
            $this->employee_qty = & new clsControl(ccsTextBox, "employee_qty", "Jumlah Pegawai", ccsFloat, array(False, 0, Null, "", False, "", "", 1, True, ""), CCGetRequestParam("employee_qty", $Method, NULL), $this);
            $this->employee_qty->Required = true;
            $this->employee_salery = & new clsControl(ccsTextBox, "employee_salery", "Gaji Pegawai", ccsFloat, array(False, 0, Null, "", False, "", "", 1, True, ""), CCGetRequestParam("employee_salery", $Method, NULL), $this);
            $this->employee_salery->Required = true;
            $this->t_vat_registration_id = & new clsControl(ccsHidden, "t_vat_registration_id", "t_vat_registration_id", ccsFloat, "", CCGetRequestParam("t_vat_registration_id", $Method, NULL), $this);
            $this->jabatan = & new clsControl(ccsTextBox, "jabatan", "Jabatan", ccsText, "", CCGetRequestParam("jabatan", $Method, NULL), $this);
            $this->jabatan->Required = true;
            $this->p_job_position_id = & new clsControl(ccsHidden, "p_job_position_id", "p_job_position_id", ccsFloat, "", CCGetRequestParam("p_job_position_id", $Method, NULL), $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->created_by->Value) && !strlen($this->created_by->Value) && $this->created_by->Value !== false)
                    $this->created_by->SetText(CCGetUserLogin());
                if(!is_array($this->updated_by->Value) && !strlen($this->updated_by->Value) && $this->updated_by->Value !== false)
                    $this->updated_by->SetText(CCGetUserLogin());
                if(!is_array($this->creation_date->Value) && !strlen($this->creation_date->Value) && $this->creation_date->Value !== false)
                    $this->creation_date->SetText(date("d-M-Y"));
                if(!is_array($this->updated_date->Value) && !strlen($this->updated_date->Value) && $this->updated_date->Value !== false)
                    $this->updated_date->SetText(date("d-M-Y"));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @94-6CA713FF
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlt_vat_reg_employee_id"] = CCGetFromGet("t_vat_reg_employee_id", NULL);
    }
//End Initialize Method

//Validate Method @94-599D5704
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->t_vat_reg_employee_id->Validate() && $Validation);
        $Validation = ($this->description->Validate() && $Validation);
        $Validation = ($this->created_by->Validate() && $Validation);
        $Validation = ($this->updated_by->Validate() && $Validation);
        $Validation = ($this->creation_date->Validate() && $Validation);
        $Validation = ($this->updated_date->Validate() && $Validation);
        $Validation = ($this->rqst_type_code->Validate() && $Validation);
        $Validation = ($this->p_rqst_type_id->Validate() && $Validation);
        $Validation = ($this->t_customer_order_id->Validate() && $Validation);
        $Validation = ($this->employee_qty->Validate() && $Validation);
        $Validation = ($this->employee_salery->Validate() && $Validation);
        $Validation = ($this->t_vat_registration_id->Validate() && $Validation);
        $Validation = ($this->jabatan->Validate() && $Validation);
        $Validation = ($this->p_job_position_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->t_vat_reg_employee_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->description->Errors->Count() == 0);
        $Validation =  $Validation && ($this->created_by->Errors->Count() == 0);
        $Validation =  $Validation && ($this->updated_by->Errors->Count() == 0);
        $Validation =  $Validation && ($this->creation_date->Errors->Count() == 0);
        $Validation =  $Validation && ($this->updated_date->Errors->Count() == 0);
        $Validation =  $Validation && ($this->rqst_type_code->Errors->Count() == 0);
        $Validation =  $Validation && ($this->p_rqst_type_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->t_customer_order_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->employee_qty->Errors->Count() == 0);
        $Validation =  $Validation && ($this->employee_salery->Errors->Count() == 0);
        $Validation =  $Validation && ($this->t_vat_registration_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jabatan->Errors->Count() == 0);
        $Validation =  $Validation && ($this->p_job_position_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @94-7F65CE47
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->t_vat_reg_employee_id->Errors->Count());
        $errors = ($errors || $this->description->Errors->Count());
        $errors = ($errors || $this->created_by->Errors->Count());
        $errors = ($errors || $this->updated_by->Errors->Count());
        $errors = ($errors || $this->creation_date->Errors->Count());
        $errors = ($errors || $this->updated_date->Errors->Count());
        $errors = ($errors || $this->rqst_type_code->Errors->Count());
        $errors = ($errors || $this->p_rqst_type_id->Errors->Count());
        $errors = ($errors || $this->t_customer_order_id->Errors->Count());
        $errors = ($errors || $this->employee_qty->Errors->Count());
        $errors = ($errors || $this->employee_salery->Errors->Count());
        $errors = ($errors || $this->t_vat_registration_id->Errors->Count());
        $errors = ($errors || $this->jabatan->Errors->Count());
        $errors = ($errors || $this->p_job_position_id->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @94-ED598703
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

//Operation Method @94-7DE29AD7
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm", "FLAG", "t_vat_reg_dtl_hotel_id"));
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm", "FLAG", "t_vat_reg_dtl_hotel_id"));
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm", "FLAG"));
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm", "FLAG"));
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @94-9615BE8E
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->description->SetValue($this->description->GetValue(true));
        $this->DataSource->t_vat_registration_id->SetValue($this->t_vat_registration_id->GetValue(true));
        $this->DataSource->p_job_position_id->SetValue($this->p_job_position_id->GetValue(true));
        $this->DataSource->employee_qty->SetValue($this->employee_qty->GetValue(true));
        $this->DataSource->employee_salery->SetValue($this->employee_salery->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @94-AE0A6CE7
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->t_vat_reg_employee_id->SetValue($this->t_vat_reg_employee_id->GetValue(true));
        $this->DataSource->description->SetValue($this->description->GetValue(true));
        $this->DataSource->t_vat_registration_id->SetValue($this->t_vat_registration_id->GetValue(true));
        $this->DataSource->employee_qty->SetValue($this->employee_qty->GetValue(true));
        $this->DataSource->p_job_position_id->SetValue($this->p_job_position_id->GetValue(true));
        $this->DataSource->employee_salery->SetValue($this->employee_salery->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @94-C382F3D5
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->t_vat_reg_employee_id->SetValue($this->t_vat_reg_employee_id->GetValue(true));
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @94-69E2D16A
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


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->t_vat_reg_employee_id->SetValue($this->DataSource->t_vat_reg_employee_id->GetValue());
                    $this->description->SetValue($this->DataSource->description->GetValue());
                    $this->created_by->SetValue($this->DataSource->created_by->GetValue());
                    $this->updated_by->SetValue($this->DataSource->updated_by->GetValue());
                    $this->creation_date->SetValue($this->DataSource->creation_date->GetValue());
                    $this->updated_date->SetValue($this->DataSource->updated_date->GetValue());
                    $this->employee_qty->SetValue($this->DataSource->employee_qty->GetValue());
                    $this->employee_salery->SetValue($this->DataSource->employee_salery->GetValue());
                    $this->t_vat_registration_id->SetValue($this->DataSource->t_vat_registration_id->GetValue());
                    $this->jabatan->SetValue($this->DataSource->jabatan->GetValue());
                    $this->p_job_position_id->SetValue($this->DataSource->p_job_position_id->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->t_vat_reg_employee_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->description->Errors->ToString());
            $Error = ComposeStrings($Error, $this->created_by->Errors->ToString());
            $Error = ComposeStrings($Error, $this->updated_by->Errors->ToString());
            $Error = ComposeStrings($Error, $this->creation_date->Errors->ToString());
            $Error = ComposeStrings($Error, $this->updated_date->Errors->ToString());
            $Error = ComposeStrings($Error, $this->rqst_type_code->Errors->ToString());
            $Error = ComposeStrings($Error, $this->p_rqst_type_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->t_customer_order_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->employee_qty->Errors->ToString());
            $Error = ComposeStrings($Error, $this->employee_salery->Errors->ToString());
            $Error = ComposeStrings($Error, $this->t_vat_registration_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jabatan->Errors->ToString());
            $Error = ComposeStrings($Error, $this->p_job_position_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->t_vat_reg_employee_id->Show();
        $this->description->Show();
        $this->created_by->Show();
        $this->updated_by->Show();
        $this->creation_date->Show();
        $this->updated_date->Show();
        $this->rqst_type_code->Show();
        $this->p_rqst_type_id->Show();
        $this->t_customer_order_id->Show();
        $this->employee_qty->Show();
        $this->employee_salery->Show();
        $this->t_vat_registration_id->Show();
        $this->jabatan->Show();
        $this->p_job_position_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End t_vat_reg_employeeForm Class @94-FCB6E20C

class clst_vat_reg_employeeFormDataSource extends clsDBConnSIKP {  //t_vat_reg_employeeFormDataSource Class @94-276C0FE2

//DataSource Variables @94-250739C8
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $t_vat_reg_employee_id;
    var $description;
    var $created_by;
    var $updated_by;
    var $creation_date;
    var $updated_date;
    var $rqst_type_code;
    var $p_rqst_type_id;
    var $t_customer_order_id;
    var $employee_qty;
    var $employee_salery;
    var $t_vat_registration_id;
    var $jabatan;
    var $p_job_position_id;
//End DataSource Variables

//DataSourceClass_Initialize Event @94-D0E6AA0B
    function clst_vat_reg_employeeFormDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record t_vat_reg_employeeForm/Error";
        $this->Initialize();
        $this->t_vat_reg_employee_id = new clsField("t_vat_reg_employee_id", ccsFloat, "");
        
        $this->description = new clsField("description", ccsText, "");
        
        $this->created_by = new clsField("created_by", ccsText, "");
        
        $this->updated_by = new clsField("updated_by", ccsText, "");
        
        $this->creation_date = new clsField("creation_date", ccsText, "");
        
        $this->updated_date = new clsField("updated_date", ccsText, "");
        
        $this->rqst_type_code = new clsField("rqst_type_code", ccsText, "");
        
        $this->p_rqst_type_id = new clsField("p_rqst_type_id", ccsFloat, "");
        
        $this->t_customer_order_id = new clsField("t_customer_order_id", ccsFloat, "");
        
        $this->employee_qty = new clsField("employee_qty", ccsFloat, "");
        
        $this->employee_salery = new clsField("employee_salery", ccsFloat, "");
        
        $this->t_vat_registration_id = new clsField("t_vat_registration_id", ccsFloat, "");
        
        $this->jabatan = new clsField("jabatan", ccsText, "");
        
        $this->p_job_position_id = new clsField("p_job_position_id", ccsFloat, "");
        

    }
//End DataSourceClass_Initialize Event

//Prepare Method @94-C4909B3D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlt_vat_reg_employee_id", ccsFloat, "", "", $this->Parameters["urlt_vat_reg_employee_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "t_vat_reg_employee_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @94-4C166B6C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM v_vat_reg_employee {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @94-522163B3
    function SetValues()
    {
        $this->t_vat_reg_employee_id->SetDBValue(trim($this->f("t_vat_reg_employee_id")));
        $this->description->SetDBValue($this->f("description"));
        $this->created_by->SetDBValue($this->f("created_by"));
        $this->updated_by->SetDBValue($this->f("updated_by"));
        $this->creation_date->SetDBValue($this->f("creation_date"));
        $this->updated_date->SetDBValue($this->f("updated_date"));
        $this->employee_qty->SetDBValue(trim($this->f("employee_qty")));
        $this->employee_salery->SetDBValue(trim($this->f("employee_salery")));
        $this->t_vat_registration_id->SetDBValue(trim($this->f("t_vat_registration_id")));
        $this->jabatan->SetDBValue($this->f("jabatan"));
        $this->p_job_position_id->SetDBValue(trim($this->f("p_job_position_id")));
    }
//End SetValues Method

//Insert Method @94-6AACC112
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->cp["description"] = new clsSQLParameter("ctrldescription", ccsText, "", "", $this->description->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["created_by"] = new clsSQLParameter("expr712", ccsText, "", "", CCGetUserLogin(), "", false, $this->ErrorBlock);
        $this->cp["updated_by"] = new clsSQLParameter("expr713", ccsText, "", "", CCGetUserLogin(), "", false, $this->ErrorBlock);
        $this->cp["t_vat_registration_id"] = new clsSQLParameter("ctrlt_vat_registration_id", ccsFloat, "", "", $this->t_vat_registration_id->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["p_job_position_id"] = new clsSQLParameter("ctrlp_job_position_id", ccsFloat, "", "", $this->p_job_position_id->GetValue(true), 0, false, $this->ErrorBlock);
        $this->cp["employee_qty"] = new clsSQLParameter("ctrlemployee_qty", ccsFloat, "", "", $this->employee_qty->GetValue(true), 0, false, $this->ErrorBlock);
        $this->cp["employee_salery"] = new clsSQLParameter("ctrlemployee_salery", ccsFloat, "", "", $this->employee_salery->GetValue(true), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        if (!is_null($this->cp["description"]->GetValue()) and !strlen($this->cp["description"]->GetText()) and !is_bool($this->cp["description"]->GetValue())) 
            $this->cp["description"]->SetValue($this->description->GetValue(true));
        if (!is_null($this->cp["created_by"]->GetValue()) and !strlen($this->cp["created_by"]->GetText()) and !is_bool($this->cp["created_by"]->GetValue())) 
            $this->cp["created_by"]->SetValue(CCGetUserLogin());
        if (!is_null($this->cp["updated_by"]->GetValue()) and !strlen($this->cp["updated_by"]->GetText()) and !is_bool($this->cp["updated_by"]->GetValue())) 
            $this->cp["updated_by"]->SetValue(CCGetUserLogin());
        if (!is_null($this->cp["t_vat_registration_id"]->GetValue()) and !strlen($this->cp["t_vat_registration_id"]->GetText()) and !is_bool($this->cp["t_vat_registration_id"]->GetValue())) 
            $this->cp["t_vat_registration_id"]->SetValue($this->t_vat_registration_id->GetValue(true));
        if (!is_null($this->cp["p_job_position_id"]->GetValue()) and !strlen($this->cp["p_job_position_id"]->GetText()) and !is_bool($this->cp["p_job_position_id"]->GetValue())) 
            $this->cp["p_job_position_id"]->SetValue($this->p_job_position_id->GetValue(true));
        if (!strlen($this->cp["p_job_position_id"]->GetText()) and !is_bool($this->cp["p_job_position_id"]->GetValue(true))) 
            $this->cp["p_job_position_id"]->SetText(0);
        if (!is_null($this->cp["employee_qty"]->GetValue()) and !strlen($this->cp["employee_qty"]->GetText()) and !is_bool($this->cp["employee_qty"]->GetValue())) 
            $this->cp["employee_qty"]->SetValue($this->employee_qty->GetValue(true));
        if (!strlen($this->cp["employee_qty"]->GetText()) and !is_bool($this->cp["employee_qty"]->GetValue(true))) 
            $this->cp["employee_qty"]->SetText(0);
        if (!is_null($this->cp["employee_salery"]->GetValue()) and !strlen($this->cp["employee_salery"]->GetText()) and !is_bool($this->cp["employee_salery"]->GetValue())) 
            $this->cp["employee_salery"]->SetValue($this->employee_salery->GetValue(true));
        if (!strlen($this->cp["employee_salery"]->GetText()) and !is_bool($this->cp["employee_salery"]->GetValue(true))) 
            $this->cp["employee_salery"]->SetText(0);
        $this->SQL = "INSERT INTO t_vat_reg_employee(t_vat_reg_employee_id, description, created_by, updated_by, creation_date, updated_date, t_vat_registration_id, p_job_position_id, employee_qty, employee_salery) \n" .
        "VALUES(generate_id('sikp','t_vat_reg_employee','t_vat_reg_employee_id'), '" . $this->SQLValue($this->cp["description"]->GetDBValue(), ccsText) . "', '" . $this->SQLValue($this->cp["created_by"]->GetDBValue(), ccsText) . "', '" . $this->SQLValue($this->cp["updated_by"]->GetDBValue(), ccsText) . "', sysdate, sysdate, " . $this->SQLValue($this->cp["t_vat_registration_id"]->GetDBValue(), ccsFloat) . ", " . $this->SQLValue($this->cp["p_job_position_id"]->GetDBValue(), ccsFloat) . ", " . $this->SQLValue($this->cp["employee_qty"]->GetDBValue(), ccsFloat) . ", " . $this->SQLValue($this->cp["employee_salery"]->GetDBValue(), ccsFloat) . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @94-1C7588FF
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->cp["t_vat_reg_employee_id"] = new clsSQLParameter("ctrlt_vat_reg_employee_id", ccsFloat, "", "", $this->t_vat_reg_employee_id->GetValue(true), 0, false, $this->ErrorBlock);
        $this->cp["description"] = new clsSQLParameter("ctrldescription", ccsText, "", "", $this->description->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["updated_by"] = new clsSQLParameter("expr741", ccsText, "", "", CCGetUserLogin(), "", false, $this->ErrorBlock);
        $this->cp["t_vat_registration_id"] = new clsSQLParameter("ctrlt_vat_registration_id", ccsFloat, "", "", $this->t_vat_registration_id->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["employee_qty"] = new clsSQLParameter("ctrlemployee_qty", ccsFloat, "", "", $this->employee_qty->GetValue(true), 0, false, $this->ErrorBlock);
        $this->cp["p_job_position_id"] = new clsSQLParameter("ctrlp_job_position_id", ccsFloat, "", "", $this->p_job_position_id->GetValue(true), 0, false, $this->ErrorBlock);
        $this->cp["employee_salery"] = new clsSQLParameter("ctrlemployee_salery", ccsFloat, "", "", $this->employee_salery->GetValue(true), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        if (!is_null($this->cp["t_vat_reg_employee_id"]->GetValue()) and !strlen($this->cp["t_vat_reg_employee_id"]->GetText()) and !is_bool($this->cp["t_vat_reg_employee_id"]->GetValue())) 
            $this->cp["t_vat_reg_employee_id"]->SetValue($this->t_vat_reg_employee_id->GetValue(true));
        if (!strlen($this->cp["t_vat_reg_employee_id"]->GetText()) and !is_bool($this->cp["t_vat_reg_employee_id"]->GetValue(true))) 
            $this->cp["t_vat_reg_employee_id"]->SetText(0);
        if (!is_null($this->cp["description"]->GetValue()) and !strlen($this->cp["description"]->GetText()) and !is_bool($this->cp["description"]->GetValue())) 
            $this->cp["description"]->SetValue($this->description->GetValue(true));
        if (!is_null($this->cp["updated_by"]->GetValue()) and !strlen($this->cp["updated_by"]->GetText()) and !is_bool($this->cp["updated_by"]->GetValue())) 
            $this->cp["updated_by"]->SetValue(CCGetUserLogin());
        if (!is_null($this->cp["t_vat_registration_id"]->GetValue()) and !strlen($this->cp["t_vat_registration_id"]->GetText()) and !is_bool($this->cp["t_vat_registration_id"]->GetValue())) 
            $this->cp["t_vat_registration_id"]->SetValue($this->t_vat_registration_id->GetValue(true));
        if (!is_null($this->cp["employee_qty"]->GetValue()) and !strlen($this->cp["employee_qty"]->GetText()) and !is_bool($this->cp["employee_qty"]->GetValue())) 
            $this->cp["employee_qty"]->SetValue($this->employee_qty->GetValue(true));
        if (!strlen($this->cp["employee_qty"]->GetText()) and !is_bool($this->cp["employee_qty"]->GetValue(true))) 
            $this->cp["employee_qty"]->SetText(0);
        if (!is_null($this->cp["p_job_position_id"]->GetValue()) and !strlen($this->cp["p_job_position_id"]->GetText()) and !is_bool($this->cp["p_job_position_id"]->GetValue())) 
            $this->cp["p_job_position_id"]->SetValue($this->p_job_position_id->GetValue(true));
        if (!strlen($this->cp["p_job_position_id"]->GetText()) and !is_bool($this->cp["p_job_position_id"]->GetValue(true))) 
            $this->cp["p_job_position_id"]->SetText(0);
        if (!is_null($this->cp["employee_salery"]->GetValue()) and !strlen($this->cp["employee_salery"]->GetText()) and !is_bool($this->cp["employee_salery"]->GetValue())) 
            $this->cp["employee_salery"]->SetValue($this->employee_salery->GetValue(true));
        if (!strlen($this->cp["employee_salery"]->GetText()) and !is_bool($this->cp["employee_salery"]->GetValue(true))) 
            $this->cp["employee_salery"]->SetText(0);
        $this->SQL = "UPDATE t_vat_reg_employee SET \n" .
        "description='" . $this->SQLValue($this->cp["description"]->GetDBValue(), ccsText) . "', \n" .
        "updated_by='" . $this->SQLValue($this->cp["updated_by"]->GetDBValue(), ccsText) . "', \n" .
        "updated_date=sysdate, \n" .
        "p_job_position_id=" . $this->SQLValue($this->cp["p_job_position_id"]->GetDBValue(), ccsFloat) . ", \n" .
        "employee_qty=" . $this->SQLValue($this->cp["employee_qty"]->GetDBValue(), ccsFloat) . ", \n" .
        "employee_salery=" . $this->SQLValue($this->cp["employee_salery"]->GetDBValue(), ccsFloat) . "\n" .
        "WHERE t_vat_reg_employee_id=" . $this->SQLValue($this->cp["t_vat_reg_employee_id"]->GetDBValue(), ccsFloat) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @94-1D290402
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->cp["t_vat_reg_employee_id"] = new clsSQLParameter("ctrlt_vat_reg_employee_id", ccsFloat, "", "", $this->t_vat_reg_employee_id->GetValue(true), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        if (!is_null($this->cp["t_vat_reg_employee_id"]->GetValue()) and !strlen($this->cp["t_vat_reg_employee_id"]->GetText()) and !is_bool($this->cp["t_vat_reg_employee_id"]->GetValue())) 
            $this->cp["t_vat_reg_employee_id"]->SetValue($this->t_vat_reg_employee_id->GetValue(true));
        if (!strlen($this->cp["t_vat_reg_employee_id"]->GetText()) and !is_bool($this->cp["t_vat_reg_employee_id"]->GetValue(true))) 
            $this->cp["t_vat_reg_employee_id"]->SetText(0);
        $this->SQL = "DELETE FROM t_vat_reg_employee\n" .
        "WHERE t_vat_reg_employee_id = " . $this->SQLValue($this->cp["t_vat_reg_employee_id"]->GetDBValue(), ccsFloat) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End t_vat_reg_employeeFormDataSource Class @94-FCB6E20C

//Initialize Page @1-AB042761
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
$TemplateFileName = "t_vat_reg_employee_edit.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "../";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-B39922A4
include_once("./t_vat_reg_employee_edit_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-A34C2068
$DBConnSIKP = new clsDBConnSIKP();
$MainPage->Connections["ConnSIKP"] = & $DBConnSIKP;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$t_vat_reg_employeeForm = & new clsRecordt_vat_reg_employeeForm("", $MainPage);
$MainPage->t_vat_reg_employeeForm = & $t_vat_reg_employeeForm;
$t_vat_reg_employeeForm->Initialize();

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

//Execute Components @1-E7E57601
$t_vat_reg_employeeForm->Operation();
//End Execute Components

//Go to destination page @1-40A7E65C
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnSIKP->close();
    header("Location: " . $Redirect);
    unset($t_vat_reg_employeeForm);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-DDA96665
$t_vat_reg_employeeForm->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-6B5F2526
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnSIKP->close();
unset($t_vat_reg_employeeForm);
unset($Tpl);
//End Unload Page


?>
