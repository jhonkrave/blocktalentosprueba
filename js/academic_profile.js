$(document).ready(function(){
    var userid =  $('#ficha_estudiante #iduser').val();
    create_semesters_panel(userid);
    
    $('#pes_academica').on('click',function(){
        create_semesters_panel(userid);
    });
});

/* Formatting function for row details - modify as you need */
function format (dataRow) {
    
    if(dataRow.descriptions  == null){
        return 'El curso no registra calificaciones';
    }
    else{
        return dataRow.descriptions ;
    }
}

function create_semesters_panel(userid){
        $.ajax({
        type: "POST",
        data: {dat: 'semesters', user:userid},
        url: "../managers/academic_processing_profile.php",
        success: function(msg)
        {   
            //console.log(msg);
            $('#academic_report').html('');
            if(msg){
                console.log(msg);
                var isfirst = true;
                
                for(x in msg){
                    
                    var panel = '<div class="accordion-container"><a id="title'+x.id_semester+'" class="accordion-toggle">Semestre '+msg[x].name_semester+'<span class="toggle-icon"><i class="glyphicon glyphicon-chevron-left"></i></span></a>';
                    panel += '<div id="panel-body'+msg[x].id_semester+'" class="accordion-content ScrollStyle"></div></div>';
                    
                    $('#academic_report').append(panel);
                    
                    var courses = msg[x].courses;

                    if(courses.length != 0){
                        
                        $("#panel-body"+msg[x].id_semester).empty();
                        $("#panel-body"+msg[x].id_semester).append('<table id="tableResultCourse'+msg[x].id_semester+'" class="display" cellspacing="0" width="100%"><thead><thead></table>');
                        var table = $("#tableResultCourse"+msg[x].id_semester).DataTable({
                            "bsort": false,
                            "bPaginate": false,
                            "searching": false,
                            "columns": [
                                {
                                    "className":      'details-control',
                                    "orderable":      false,
                                    "data":           null,
                                    "defaultContent": ''
                                },
                                { "title": "Curso", "data": "name_course"},
                                { "title": "Calificación Final", "data": "grade" },
                            ],
                            "data": msg[x].courses,
                            "order": [[1, 'asc']],
                            
                        });
                        
                        // table.rows().every( function (index, value) {
                        //     this.child( );
                        // } );
                        
                        
                        $('#tableResultCourse'+msg[x].id_semester+' tbody').on('click', 'td.details-control', function () {
                            var tr = $(this).closest('tr');
                            var row = table.row( tr );
                     
                            if ( row.child.isShown() ) {
                                // This row is already open - close it
                                row.child.hide();
                                tr.removeClass('shown');
                            }
                            else {
                                // Open this row
                                row.child( format(row.data()) ).show();
                                tr.addClass('shown');
                            }
                        });
                    }
                    if(isfirst){
                    	openAccordionToggle('#academic_report #title'+msg[x].id_semester);
                    	isfirst = false;
                    }
                }
                
                var riesgo = 'bajo';
                
                for(x in msg[0].courses){
                    if(courses[x].grade < 3.0){
                        riesgo = 'alto';
                        break;
                    }
                    else if(courses[x].grade > 3.0 && courses[x].grade < 3.5){
                        riesgo = 'medio';
                    }
                }
                $('#pes_academica').html('');
                $('#pes_academica').append('ACADÉMICA <i>Riesgo '+riesgo+'</i>');
                
            }else{
               $("#panel-body"+msg[x].id_semester).append("No se encontraron registros");
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
        });
}

function prueba(userid){
    
            $.ajax({
        type: "POST",
        data: {dat: 'semesters', user:userid},
        url: "../managers/academic_processing_profile.php",
        success: function(msg)
        {   
            //console.log(msg);
            $('#academic_report').html('');
            console.log(msg);

        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
        });
}