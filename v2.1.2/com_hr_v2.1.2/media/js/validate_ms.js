var abstype;


function planentldisablefields() {
    document.getElementById("jform_idcharfrom").disabled = true;
    document.getElementById("jform_idcharto").disabled = true;
}


function planentlenablefields() {
    document.getElementById("jform_idcharfrom").disabled = false;
    document.getElementById("jform_idcharto").disabled = false;
}



function planentlactionOnDates() {
    abstype = document.getElementById("jform_idmeasureunitandid").value;
  //  document.write(abstype);
    
    if ((abstype.indexOf("Days") > -1))
    {
        document.getElementById("jform_id_absence_id").value = abstype.substring(abstype.indexOf("the id is") + 9, (abstype.length));
        document.getElementById("jform_idmeasureunit").value = "Days";
        //    disableCalendarFields();
            planentlenablefields();
            actionOnFromToFields();
    }
    else if ((abstype.indexOf("Hours_minutes") > -1))
    {
        document.getElementById("jform_id_absence_id").value = abstype.substring(abstype.indexOf("the id is") + 9, (abstype.length));
        document.getElementById("jform_idmeasureunit").value = "Hours_minutes";
        //     disableCalendarFields();
      planentlenablefields();
     actionOnFromToFields();
    }
    else
    {
        planentldisablefields();
        //     disableCalendarFields();
    }
}










function assignValidityDates() {  

    refyear = document.getElementById("jform_idref_year_lbl").value;
//insertmethod = document.getElementById("jform_idinsertmethod_lbl").value;
//date1 = document.getElementById("jform_iddatefrom_lbl").value;
//date2 = document.getElementById("jform_iddateto_lbl").value;
nextyear = parseInt(refyear) + 1;

    if (refyear !== '') {
        document.getElementById("jform_idvaliditydatetimefrom_lbl").value = refyear + '-01-01';
        document.getElementById("jform_idvaliditydatetimeto_lbl").value = refyear + '-12-31';
        document.getElementById("jform_iddatetimefrom_lbl").value = refyear + '-01-01';
        document.getElementById("jform_iddatetimeto_lbl").value = refyear + '-12-31';


    }

    if (refyear === '') {
        document.getElementById("jform_idvaliditydatetimefrom_lbl").value = '';
        document.getElementById("jform_idvaliditydatetimeto_lbl").value = '';
        document.getElementById("jform_iddatetimefrom_lbl").value = '';
        document.getElementById("jform_iddatetimeto_lbl").value = '';

    }
  
    /*
        if ((insertmethod.indexOf("1") > -1)) {
            
        if (date1 !== '')  {      
            document.getElementById("jform_idref_year_lbl").value = date1.substring(0,4);         
         }   
          if (date2 !== '')  {      
            document.getElementById("jform_idref_year_lbl").value = date2.substring(0,4);         
         }         
         
        }    
*/

}



function actionOnTimeFromToDateFrom() {

    var timefrom = document.getElementById("jform_idtimefrom_text_lbl").value;
    document.getElementById("jform_idtimefrom_lbl").value = "2015-01-01 ".concat(timefrom);
}
function actionOnTimeToToDateTo() {
    var timeto = document.getElementById("jform_idtimeto_text_lbl").value;
    document.getElementById("jform_idtimeto_lbl").value = "2015-01-01 ".concat(timeto);

}





function actionOnFromToFields() {

    munitofpattern = document.getElementById("jform_idmeasureunitofpattern").value;
    munitofabsence = document.getElementById("jform_idmeasureunit").value;
    
    if ((munitofpattern.indexOf("Years") > -1))
    {
        //   $("#jform_idcharfrom").mask("99");
        //    $("#jform_idcharto").mask("99");    
        //   $("#jform_idcharfrom").value = "";        
        $("#jform_idcharfrom").mask("99", {placeholder: "00"});
        $("#jform_idcharto").mask("99", {placeholder: "00"});
    }

    else if ((munitofpattern.indexOf("Dates") > -1))

    {
        //        $("#jform_idcharfrom").value = "";
     
         if ((munitofabsence.indexOf("Days") > -1)){
        $("#jform_idcharfrom").mask("99-99", {placeholder: "mm-dd"});
        $("#jform_idcharto").mask("99-99", {placeholder: "mm-dd"});
    } else
     if ((munitofabsence.indexOf("Hours_minutes") > -1)){
        $("#jform_idcharfrom").mask("99-99 99:99", {placeholder: "mm-dd hh:mm"});
        $("#jform_idcharto").mask("99-99 99:99", {placeholder: "mm-dd hh:mm"});
    }   
    
    
    }
    else
    {
        $("#jform_idcharfrom").mask(" ", {placeholder: " "});
        $("#jform_idcharto").mask(" ", {placeholder: " "});

    }



}







function setDateTimeFields() {

  $("#jform_iddatetimefrom_lbl").mask("9999-99-99 99:99", {placeholder: "yyyy-mm-dd HH:MM"});
    $("#jform_iddatetimeto_lbl").mask("9999-99-99 99:99", {placeholder: "yyyy-mm-dd HH:MM"});

    
}



function setRetirementDate() {

   idemployeeanddor = document.getElementById("jform_idemployeeanddor").value;
    document.getElementById("jform_iddatetimeto_lbl").value = idemployeeanddor.substring(idemployeeanddor.indexOf("the dor is") + 10, (idemployeeanddor.length));
    lastdigitofidemployee = idemployeeanddor.indexOf("the dor is");
 document.getElementById("jform_idemployee").value =   idemployeeanddor.substring(0, lastdigitofidemployee);
           

  setDateFormat9999();
}






function actionOnDates() {
    abstype = document.getElementById("jform_idmeasureunitandid").value;
    if ((abstype.indexOf("Days") > -1))
    {
        document.getElementById("jform_id_absence_id").value = abstype.substring(abstype.indexOf("the id is") + 9, (abstype.length));
        document.getElementById("jform_idmeasureunit").value = "Days";
        //    disableCalendarFields();
        enableFields();
        setDateFormat9999();
    }
    else if ((abstype.indexOf("Hours_minutes") > -1))
    {
        document.getElementById("jform_id_absence_id").value = abstype.substring(abstype.indexOf("the id is") + 9, (abstype.length));
        document.getElementById("jform_idmeasureunit").value = "Hours_minutes";
        //     disableCalendarFields();
        enableFields();
        setDateTimeFormat9999();
    }
    else
    {
        disableFields();
        //     disableCalendarFields();
    }
}


function enableCalendarFields() {
    document.getElementById("jform_iddatetimefrom_lbl_img").disabled = false;
    document.getElementById("jform_iddatetimeto_lbl_img").disabled = false;
}


function disableCalendarFields() {
    document.getElementById("jform_iddatetimefrom_lbl_img").disabled = true;
    document.getElementById("jform_iddatetimeto_lbl_img").disabled = true;
}

function enableFields() {

    document.getElementById("jform_iddatetimefrom_lbl").disabled = false;
    document.getElementById("jform_iddatetimefrom_lbl-lbl").disabled = false;
    document.getElementById("jform_iddatetimeto_lbl").disabled = false;
    document.getElementById("jform_iddatetimeto_lbl-lbl").disabled = false;
}

function disableFields() {
    document.getElementById("jform_iddatetimefrom_lbl").disabled = true;
    document.getElementById("jform_iddatetimefrom_lbl-lbl").disabled = true;
    document.getElementById("jform_iddatetimeto_lbl").disabled = true;
    document.getElementById("jform_iddatetimeto_lbl-lbl").disabled = true;
  //  document.getElementById("jform_iddatetimeto_lbl_img").disabled = true;
}






function setDateFormat0000() {
    $("#jform_iddatetimefrom_lbl").mask("9999-99-99 00:00", {placeholder: "yyyy-mm-dd HH:MM"});
    $("#jform_iddatetimeto_lbl").mask("9999-99-99 00:00", {placeholder: "yyyy-mm-dd HH:MM"});
          
}


function setDateTimeFormat9999() {
    $("#jform_iddatetimefrom_lbl").mask("9999-99-99 99:99", {placeholder: "yyyy-mm-dd HH:MM"});
    $("#jform_iddatetimeto_lbl").mask("9999-99-99 99:99", {placeholder: "yyyy-mm-dd HH:MM"});
$("#jform_idvaliditydatetimefrom_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
    $("#jform_idvaliditydatetimeto_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});     
}

function setDateFormat9999() {
    $("#jform_iddatetimefrom_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
    $("#jform_iddatetimeto_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
    $("#jform_idvaliditydatetimefrom_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
    $("#jform_idvaliditydatetimeto_lbl").mask("9999-99-99", {placeholder: "yyyy-mm-dd"});    
}


function actionOnTimeFields() {
    $("#jform_idtimefrom_text_lbl").mask("99:99", {placeholder: "HH:MM"});
    $("#jform_idtimeto_text_lbl").mask("99:99", {placeholder: "HH:MM"});
}