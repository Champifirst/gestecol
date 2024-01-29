// variable
let effectifs_totale = [];
let filles = [];
let redoublants = [];

// start
let id_school = 0;
if (localStorage.getItem('id_school') != null) {
    id_school = localStorage.getItem('id_school');
}
diagramme_effectif(id_school);
// change diagramme effectif
function onChangeDiagramme(){
    let id_ecole = $("#ecole_diagramme").val();
    diagramme_effectif(id_ecole);
}


function diagramme_effectif(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/statistique/diagramme_effectif/"+id_school+"";
    $("#chartContainer2").html("");

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            if (json !== undefined) {
                console.log(json);
                data_effectif = json.data_effectif; 
                // success
                for (let i = 0; i < data_effectif.length; i++) {
                    let objet_effectif = { 
                        x: (i+1),
                        label: data_effectif[i].class,
                        y: data_effectif[i].count,
                        indexLabel: data_effectif[i].class
                    }
                    effectifs_totale.push(objet_effectif);
                    //--
                    let object_redoublants = {
                        x: (i+1),
                        label: data_effectif[i].class,
                        y: data_effectif[i].redouble,
                        indexLabel: data_effectif[i].class
                    };
                    redoublants.push(object_redoublants);
                    //--
                    let objet_filles = {
                        x: data_effectif[i].class,
                        y: data_effectif[i].redouble 
                    };
                    filles.push(objet_filles);
                }
                //--
                printChartEffectif(effectifs_totale);
                printChartRedoublant(redoublants);

            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

console.log(effectifs_totale);
console.log(filles);
console.log(redoublants);

function printChartRedoublant(data){
    var options = {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: ""
        },
        axisX: {
            // valueFormatString: "MMM",
            itle: "Effectif",
            interval: 1
        },
        axisY: {
            prefix: "",
        },
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [
            {
                type: "bar",
                name: "Effectif totale",
                showInLegend: true,
                dataPoints: data
            }
        ]
    };

    $("#chartContainer2").CanvasJSChart(options); 
}

function printChartEffectif(data){
    var options = {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: ""
        },
        axisX: {
            // valueFormatString: "MMM",
            itle: "Effectif",
            interval: 1
        },
        axisY: {
            prefix: "",
        },
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [
            {
                type: "bar",
                name: "Effectif totale",
                showInLegend: true,
                dataPoints: data
            }
        ]
    };

    $("#chartContainer").CanvasJSChart(options);
}

function toggleDataSeries(e) {
    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else {
        e.dataSeries.visible = true;
    }
    e.chart.render();
} 
    