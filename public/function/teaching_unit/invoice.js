function GetPrint()
{
    /*For Print*/
    window.print();
}

function BtnAdd()
{
    /*Add Button*/
    var school = $("#name_school").val();
    var session = $("#name_session").val();
    var cycle = $("#name_cycle").val();
    var classe = $("#name_classe").val();

    if (school == "0" || session == "0" || cycle == "0" || classe == "0") {
        toastr["error"]("Veuiller selectionner les champs ci haut pour configurer la matière", "Erreur");
    }else{
        var v = $("#TRow").clone().appendTo("#TBody") ;
        $(v).find("input").val('');
        $(v).removeClass("d-none");
        $(v).find("th").first().html($('#TBody tr').length - 1);
    }
    
}

function BtnDel(v)
{
    /*Delete Button*/
       $(v).parent().parent().remove(); 
       GetTotal();

        $("#TBody").find("tr").each(
        function(index)
        {
           $(this).find("th").first().html(index);
        }

       );
}

function Calc(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var coff = document.getElementsByClassName("coefficient")[index].value;

    var amt = coff;
    document.getElementsByName("amt")[index].value = amt;

    GetTotal();
}

function GetTotal()
{
    /*Footer Calculation*/   

    var sum=0;
    var amts =  document.getElementsByName("amt");

    for (let index = 0; index < amts.length; index++)
    {
        var amt = amts[index].value;
        sum = +(sum) +  +(amt) ; 
    }

    document.getElementById("FTotal").value = sum;
}
