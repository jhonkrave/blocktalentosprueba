$(document).ready(function() {
    alert('actualizaciónporzip');
    var img = $('#photo').attr('src');
    var token = img.split('/');
    var newimg = token[0]+"/"+token[1]+"/"+token[2]+"/"+token[3]+"/"+token[4]+"/"+token[5]+"/f2.jpg";
    $('#photo').attr('src',newimg);
    setTimeout(
        function() 
        {
            newimg = token[0]+"/"+token[1]+"/"+token[2]+"/"+token[3]+"/"+token[4]+"/"+token[5]+"/f1.jpg";
            $('#photo').attr('src',newimg);
        }, 1000);
     
    //activar pestañas
    var variables = getVariableGetByName();
    if(variables.ficha == "asistencia"){
        $("#1a").removeClass("active");
        $("#3a").removeClass("hide");
        $("#3a").addClass("active");
        $('#general_li').removeClass("active");
        $('#attendance_li').addClass("active");
    }
    
    
    
    
    //funcion sugerencia inteligente
	$('#codigo').sugerirInteligente({
		src: '../managers/search_suggest.php',
		minChars: 2,
		fillBox: true,
		fillBoxWith: 'codigo',
	});
	
	//se carga la info  del segyuimiento socioeducativo
	$('#pes_socioeducativo').on('click',function() {
	    loadAll_seg();
	    loadAll_seg_grupal();
	    loadAll_primerAcerca();
	    loadAll_AcompaSocio();
	    loadAll_SegSocio();
	});

	$('#socioedu_add_pares').on('click',function() {
	    $('#save_seg').removeClass("hide");
	    $('#div_created').addClass('hide');
	    $('#upd_seg').addClass('hide');
	    $('#myModalLabel').attr('name','PARES');
	    
        initFormSeg();
	});
	
	$('#socioedu_primerAcerca').on('click',function() {
	    $('#myModalPrimerAcerca #save_seg').removeClass("hide");
	    $('#myModalPrimerAcerca #div_created').addClass('hide');
	    $('#myModalPrimerAcerca #upd_seg').addClass('hide');
	    $('#myModalPrimerAcerca #infomonitor').addClass('hide');
	    $('#myModalPrimerAcerca #myModalLabel').attr('name','PARES');
	   
	});
	
	$('#socioedu_add_AcompaSocio').on('click',function() {
	    intiAcompaSocioForm();
	    $('#myModalAcompaSocio #save_seg').removeClass("hide");
	    $('#myModalAcompaSocio #div_created').addClass('hide');
	    $('#myModalAcompaSocio #upd_seg').addClass('hide');
	    $('#myModalAcompaSocio #infoMonitor').addClass('hide');
	    $('#myModalAcompaSocio #myModalLabel').attr('name','PARES');
	});
	
	$('#socioedu_add_segsocio').on('click',function() {
	    $('#myModalSegSocio #save_seg').removeClass("hide");
	    $('#myModalSegSocio #div_created').addClass('hide');
	    $('#myModalSegSocio #infomonitor').addClass('hide');
	    $('#myModalSegSocio #upd_seg').addClass('hide');
	});
	
	
	
	//eventos seccion antecedentes
// 	$('#AcompaSocio').on('click','.antecedentes',function() {
// 	    if($(this).is(':checked') && $(this).val()== 1){
// 	        $('#AcompaSocio #motivo').prop("disabled",false);
// 	    }
// 	    var ant = 0;
// 	    $('#AcompaSocio .antecedentes').each(function() {
// 	         if($(this).is(':checked') && $(this).val() == 0){
// 	         ant += 1;
// 	         }
// 	    });
	  
// 	    if(ant == 3){
// 	        $('#AcompaSocio #motivo').prop("disabled",true);
// 	    }
// 	});
	
	$('#AcompaSocio #mytableRiesgo').on('click','tbody tr',function() {
	   
	   if( $(this).find('td input').eq(0).is(':checked')){
	       $(this).find('td input').eq(1).prop("disabled",false);
	   }
	   
	   if($(this).find('td input').eq(0).prop("checked") == false){
	    $(this).find('td input').eq(1).prop("disabled",true);
	   }
	    
	   // if( $(this).is(':checked') ) ;
	});

	
	$('#AcompaSocio').on('click','#plusingresos',function() {
	    $('#AcompaSocio #mytableIngresos tbody').append('<tr> <td><input id="descripIngreso" name="descripIngreso" size="8" maxlength="15" type="text" /></td> <td><input id="valorIngreso" name="valorIngreso" type="text" size="8" maxlength="8"/></td> <td><a href="#" id="removeEco"><span class="glyphicon glyphicon-remove"></span></a></td> <td class="hide"><input id="idIngreso" name="idIngreso" size="1" value="0" maxlenght="1" type="text" /></td> </tr>');
	});
	
	$('#AcompaSocio').on('click','#plusEgresos',function() {
	    $('#AcompaSocio #mytableEgresos tbody').append('<tr> <td><input id="descripEgreso" name="descripEgreso" size="8" maxlength="15" type="text" /></td> <td><input id="valorEgreso" name="valorEgreso" type="text" size="8" maxlength="8"/></td> <td><a href="#" id="removeEco"><span class="glyphicon glyphicon-remove"></span></a></td> <td class="hide"><input id="idEgreso" name="idEgreso" size="1" value="0" maxlenght="1" type="text" /></td> </tr>');
	});
	
	$('#AcompaSocio').on('click','#plusFamilia',function() {
	    $('#AcompaSocio #mytablefamilia tbody').append(' <tr> <td><input id="nombreFamilia" name="nombreFamilia" size="8" maxlength="8" type="text" /></td> <td><select id="parentescoFamilia"  name="parentescoFamilia"> <option value="MADRE" selected>MADRE</option>  <option value="PADRE">PADRE</option> <option value="HERMANO/A">HERMANO/A</option> <option value="TIO/A">TIO/A</option> <option value="ABUELO/A">ABUELO/A</option> <option value="PRIMO">PRIMO</option> <option value="OTRO">OTRO</option> </select> <td><input id="ocupacionFamilia" name="ocupacionFamilia" size="8" maxlenght="8" type="text" /></td> <td><input id="telefonoFamilia" name="telefonoFamilia" size="8" maxlenght="8" type="text" /></td> <td><a href="#" id="removeFamilia"><span class="glyphicon glyphicon-remove"></span></a></td>  <td class="hide"><input id="idFamilia" name="idFamilia" size="1" value="0" maxlenght="1" type="text" /></td>  </tr>');
	
	    //adicionar entre  por si las moscas </td> <td><input id="edadFamilia" name="edadFamilia" size="8" maxlenght="8" type="text" /></td> <td><input id="estadoCivilfamilia" name="estadoCivilfamilia" size="8" maxlenght="8" type="text" /></td>
	});
	
		 $('#AcompaSocio').on('click', '#removeEco', function () {
        var idEco = $(this).closest('tr').find('td input[id="idIngreso"], td input[id="idEgreso"] ').val();
        var tipo =  $(this).attr('id');
        var ts = $(this).closest('tr');
        
        swal({  title: "Estas seguro/a que desea eliminar esta informacion Información economica?",   
                text: "La información se perderá y no se podrá recurar",   
                type: "warning",  
                showCancelButton: true,   
                confirmButtonColor: "#d51b23",   
                confirmButtonText: "Si!",
                cancelButtonText: "No", 
                closeOnConfirm : true, 
                }, 
                function(isConfirm){ 
                    if(isConfirm) {
                        if (idEco !=0){
                            dropEcono(idEco);
                        }
                        ts.remove();
                        caculateSum('#AcompaSocio #valorIngreso', '#AcompaSocio #totalIngresos');
                        caculateSum('#AcompaSocio #valorEgreso', '#AcompaSocio #totalEgresos');
                        
                    }});
        
        
        
        return false;
    });
    
    
	
	 $('#AcompaSocio').on('click', '#removeFamilia', function () {
        var idFamilia = $(this).closest('tr').find('td input[id="idFamilia"]').val();
        var ts = $(this).closest('tr');
        
        swal({  title: "Estas seguro/a que desea eliminar esta informacion Familiar?",   
                text: "La información se perderá y no se podrá recurar",   
                type: "warning",  
                showCancelButton: true,   
                confirmButtonColor: "#d51b23",   
                confirmButtonText: "Si!",
                cancelButtonText: "No", 
                closeOnConfirm : true, 
                }, 
                function(isConfirm){ 
                    if(isConfirm) {
                        if (idFamilia !=0){
                            dropFamilia(idFamilia);
                        }
                        ts.remove();
                    }});
        
        
        
        return false;
    });
	
	$('#AcompaSocio').on('keydown keyup','#valorIngreso',function() {
	   caculateSum('#AcompaSocio #valorIngreso', '#AcompaSocio #totalIngresos');
	});
	
	$('#AcompaSocio').on('keydown keyup','#valorEgreso',function() {
	   caculateSum('#AcompaSocio #valorEgreso', '#AcompaSocio #totalEgresos');
	});
	
	$('#AcompaSocio').on('keydown keyup','#telefonoFamilia',function() {
	   if (!isNaN(this.value)) {
            $(this).css("background-color", "#FEFFB0");
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        } 
	});
	
	//
	
	$('#myModalSegSocio').on('click','#save_seg',function() {

        var data  = $('#myModalSegSocio #SegSocioForm').serializeArray();
        data.push({name:"function",value:"saveSegSocio"});
        
        var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
        data.push({name:"idtalentos",value:idtalentos});
        
        //console.log(data);
        $.ajax({
                type: "POST",
                data: data,
                url: "../managers/socioeducativo.php",
                success: function(msg)
                {
                    var error = msg.error;
                    if(!error){
                        swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    	$('#myModalSegSocio').modal('toggle');
                    	$('#myModalSegSocio').modal('toggle');
    	    			$('#myModalSegSocio #save_seg').addClass('hide');
    	    			$('.modal-backdrop').remove();
    	    		    loadAll_SegSocio();
    	    		    loadAll_seg();
                    }else{
                        swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                    }
                },
                dataType: "json",
                cache: "false",
                error: function(msg){console.log(msg)},
        });
	});
	
	$('#myModalAcompaSocio').on('click','#save_seg',function() {
	    var validation = validateAcompasocio();
	    
	    if(validation.isvalid){
	        var data  = $('#myModalAcompaSocio #AcompaSocio').serializeArray();
    	    data = addInfoDataAcompaSocio(data);
            data.push({name:"function",value:"newAcompaSocio"});
            //console.log(data);
            $.ajax({
                    type: "POST",
                    data: data,
                    url: "../managers/socioeducativo.php",
                    success: function(msg)
                    {
                        var error = msg.error;
                        if(!error){
                            swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                        	$('#myModalAcompaSocio').modal('toggle');
                        	$('#myModalAcompaSocio').modal('toggle');
        	    			$('#myModalAcompaSocio #save_seg').addClass('hide');
        	    			$('.modal-backdrop').remove();
        	    		     loadAll_AcompaSocio();
                        }else{
                            swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                        }
                    },
                    dataType: "json",
                    cache: "false",
                    error: function(msg){console.log(msg)},
            });
	    }else{
        	swal({title: "Error", html:true, type: "warning",  text: "Detalles del error:<br>"+validation.detalle, confirmButtonColor: "#D3D3D3"});
	    }
	    
	   
	   
	});
	
	$('#myModalAcompaSocio').on('click','#upd_seg',function() {
	     var id_seg = $(this).parent().attr('id');
	    updateAcompaSocio(id_seg);
	});
	
	$('#myModalPrimerAcerca').on('click','#upd_seg',function() {
	     var id_seg = $(this).parent().attr('id');
	    updatePrimerAcerca(id_seg);
	});
	
	$('#myModalSegSocio').on('click','#upd_seg',function() {
	     var id_seg = $(this).parent().attr('id');
	    updateSegsocio(id_seg);
	});
	
	
	$('#myModal #save_seg').on('click',function () {
	   
	   var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
	   var tipo =  $('#myModal #myModalLabel').attr('name');
	   console.log($('#seguimiento #date').val());
	   var id = new Array();
	   id.push(idtalentos);
	   
	   
	   var data = $('#myModal #seguimiento').serializeArray();
	   data.push({name:"function",value:"new"});
	   data.push({name:"idtalentos",value:id});
	   data.push({name:"tipo",value:tipo});
	   var validation = validateModal(data);
	   //$('#seguimiento input[name=optradio]').parent().attr('id')
        if (validation.isvalid){
            $.each(data, function(i, item) {
                if(item.name=="optradio"){ 
                    item.value = $('#myModal #seguimiento input[name=optradio]:checked').parent().attr('id');
                }
            });
    
        	$.ajax({
                type: "POST",
                data: data,
                url: "../managers/seguimiento.php",
                success: function(msg)
                {
                    var error = msg.error;
                    if(!error){
                        swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    	$('#myModal').modal('toggle');
                    	$('#myModal').modal('toggle');
    	    			$('#save_seg').addClass('hide');
    	    			$('.modal-backdrop').remove();
    	    		    loadAll_seg();
                    }else{
                        swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                    }
                },
                dataType: "json",
                cache: "false",
                error: function(msg){console.log( msg)},
            });
        	
        }else{
        	swal({title: "Error", html:true, type: "warning",  text: "Detalles del error:<br>"+validation.detalle, confirmButtonColor: "#D3D3D3"});
        }
        
        
        
         
	});
	
    $('#myModalPrimerAcerca #save_seg').on('click', function() {
        var data = $('#myModalPrimerAcerca #PrimerAcercaForm').serializeArray();
    	var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
        
        data.push({name:"function",value:"saveprimerAcerca"});
        data.push({name:"idtalentos",value:idtalentos});
        //console.log(data);
        // $.each(data, function(i, item) {
        //     if(item.name=="optradio"){ 
        //         item.value = $('#myModalPrimerAcerca #PrimerAcerca input[name=optradio]:checked').parent().attr('id');
        //     }
        // });    
    
    	$.ajax({
            type: "POST",
            data: data,
            url: "../managers/socioeducativo.php",
            success: function(msg)
            {
                var error = msg.error;
                if(!error){
                    swal({title: "Actualizado con exito!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                	$('#myModalPrimerAcerca').modal('toggle');
                	$('#myModalPrimerAcerca').modal('toggle');
        			$('.modal-backdrop').remove();
        		    loadAll_primerAcerca();
                }else{
                    swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){console.log(msg)},
        });
   
	});
	
	
	$('#upd_seg').on('click',function(){
	    var id_seg = $(this).parent().attr('id');
	    update_seg(id_seg);
	});
	
	
	$('#seg_socio_title').on('click',function() {
	  loadAll_SegSocio();
	});
	
	
	$('#seg_grupal_title').on('click',function() {
	   loadAll_seg_grupal();
	});
	
	$('#seg_pares_title').on('click',function() {
	   loadAll_seg();
	});
	
	
});

$(document).on('click','#AcompaSocio .antecedentes',function() {
	    if($(this).is(':checked') && $(this).val()== 1){
	        $('#AcompaSocio #motivo').prop("disabled",false);
	    }
	    var ant = 0;
	    $('#AcompaSocio .antecedentes').each(function() {
	         if($(this).is(':checked') && $(this).val() == 0){
	         ant += 1;
	         }
	    });
	  
	    if(ant == 3){
	        $('#AcompaSocio #motivo').prop("disabled",true);
	    }
});
//para mostrar los titulos
//$( document ).tooltip();


//funciones requeridas

function caculateSum(itemvalues,itemShow){
    var ingresoParcial = 0;
	    
    $(itemvalues).each( function() {
        
         //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            ingresoParcial += parseFloat(this.value);
            $(this).css("background-color", "#FEFFB0");
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
        }   
        
  
    });
    
    $(itemShow).text(ingresoParcial);
}

function loadAll_seg(){
    $('#list_pares').html('');
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val();
    
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"function",value:"load"});
    data.push({name:"tipo",value:"PARES"});
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/seguimiento.php",
        success: function(msg)
        {
            //console.log(msg);
            
            var error = msg.error;
            if(!error){
                
                var isfirst = true;
                var array_semestres_segumientos = msg.semesters_segumientos;
                
                if(array_semestres_segumientos.length > 0){
                    for(y in array_semestres_segumientos){
                    
                        var panel = '<div class="accordion-container"><a id="title'+array_semestres_segumientos[y].id_semester+'" class="accordion-toggle">Semestre '+array_semestres_segumientos[y].name_semester+'<span class="toggle-icon"><i class="glyphicon glyphicon-chevron-left"></i></span></a>';
                        panel += '<div id="panel-body'+array_semestres_segumientos[y].id_semester+'" class="accordion-content ScrollStyle"></div></div>';
                        //console.log(getPromedioString(parseInt(array_semestres_segumientos[y].promedio)));
                        var result = array_semestres_segumientos[y].result;
                        var rows =  array_semestres_segumientos[y].rows;
                        
                        
                        
                        $('#list_pares').append(panel);            
                        
                        if(rows > 0){
                            for (x in result) {
                                $("#list_pares #panel-body"+array_semestres_segumientos[y].id_semester).append("<div class=\"container well col-md-12\"> <div class=\"container-fluid col-md-10\" name=\"info\"><div class=\"row\"><label class=\"col-md-3\" for=\"fecha_des\">Fecha</label><label class=\"col-md-9\" for=\"tema_des\">Tema</label> </div> <div class=\"row\"> <input type=\"text\" class=\"col-md-3\" value=\""+result[x].fecha+"\" id=\"fecha_seg\" name=\"fecha_seg\" disabled> <input type=\"text\" class=\"col-md-9\" value=\""+result[x].tema+"\" id=\"tema_seg\" name=\"tema_seg\" disabled> </div></div> <div id=\""+result[x].id_seg+"\" class=\"col-md-2\" name=\"div_button_seg\"> <button type=\"submit\" id=\"consult_pares\" name=\"consult_pares\" class=\"submit\" data-toggle=\"modal\" data-target=\"#myModal\">Detalle</button> </div></div>");
                            }
                            $('#list_pares').on('click', '#consult_pares', function(){
                                var id_seg = $(this).parent().attr('id');
                                
                                loadJustOneSeg(id_seg, 'PARES');
                            }); 
                            
                        }else{
                            $("#list_pares #panel-body"+array_semestres_segumientos[y].id_semester).append("<label>No registra</label><br>");
                        }
                        
                        if(isfirst){
                        	openAccordionToggle('#list_pares #title'+array_semestres_segumientos[y].id_semester);
                        	isfirst = false;
                        }
                    
                    }
                    
                }else{
                    console.log("no entro");
                     $('#list_pares').append('No hay registros de alguna matricula del estudiante');
                }
                
                
                
                var promedio = getPromedioString(parseInt(msg.promedio));
                //console.log(msg.promedio);

                
                $('#promedio').text(promedio);
                $('#pes_socioeducativo').attr('title','Calificacion global de Riesgo = '+promedio);
                
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"});
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg);},
    });
}

function getPromedioString(val){
    if(val==0){
                    return "Sin Contacto";
                }else if(val > 0 && val<2){
                    return "Bajo";
                }else if(val>=2 && val<3){
                    return "Medio Bajo";
                }else if(val>=3 && val<4){
                    return "Medio";
                }else if(val>=4 && val<5){
                    return "Medio Alto";
                }else if(val == 5){
                    return "Alto";
                }
}

function loadAll_seg_grupal(){
    $('#list_grupal').html('');
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val();
    
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"function",value:"load"});
    data.push({name:"tipo",value:"GRUPAL"});
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/seguimiento.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
                var isfirst = true;
                var array_semestres_segumientos = msg.semesters_segumientos;
                
                for(y in array_semestres_segumientos){
                    
                    var panel = '<div class="accordion-container"><a id="title'+array_semestres_segumientos[y].id_semester+'" class="accordion-toggle">Semestre '+array_semestres_segumientos[y].name_semester+'<span class="toggle-icon"><i class="glyphicon glyphicon-chevron-left"></i></span></a>';
                    panel += '<div id="panel-body'+array_semestres_segumientos[y].id_semester+'" class="accordion-content ScrollStyle"></div></div>';
                    
                    var result = array_semestres_segumientos[y].result;
                    var rows =  array_semestres_segumientos[y].rows;
                    
                    
                    $('#list_grupal').append(panel);            
                    
                    if(rows > 0){
                        for (x in result) {
                            $("#list_grupal #panel-body"+array_semestres_segumientos[y].id_semester).append("<div class=\"container well col-md-12\"> <div class=\"container-fluid col-md-10\" name=\"info\"><div class=\"row\"><label class=\"col-md-3\" for=\"fecha_des\">Fecha</label><label class=\"col-md-9\" for=\"tema_des\">Tema</label> </div> <div class=\"row\"> <input type=\"text\" class=\"col-md-3\" value=\""+result[x].fecha+"\" id=\"fecha_seg\" name=\"fecha_seg\" disabled> <input type=\"text\" class=\"col-md-9\" value=\""+result[x].tema+"\" id=\"tema_seg\" name=\"tema_seg\" disabled> </div></div> <div id=\""+result[x].id_seg+"\" class=\"col-md-2\" name=\"div_button_seg\"> <button type=\"submit\" id=\"consult_grupal\" name=\"consult_grupal\" class=\"submit\" data-toggle=\"modal\" data-target=\"#myModal\">Detalle</button> </div></div>");
                        }
                        $('#list_grupal').on('click', '#consult_grupal', function(){
                            var id_seg = $(this).parent().attr('id');
                            
                            loadJustOneSeg(id_seg, 'PARES');
                        }); 
                           
                        
                    }else{
                        $("#list_grupal #panel-body"+array_semestres_segumientos[y].id_semester).append("<label>No registra</label><br>");
                    }
                    
                    if(isfirst){
                    	openAccordionToggle('#list_grupal #title'+array_semestres_segumientos[y].id_semester);
                    	isfirst = false;

                    }
                    
                    
                }
                
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg);},
    });
}



function update_seg(id_seg){
	var data = $('#myModal #seguimiento').serializeArray();
    var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
    data.push({name:"id_seg",value:id_seg});
    data.push({name:"function",value:"update"});
    data.push({name:"tipo",value:"PARES"});
    data.push({name:"idtalentos",value:idtalentos});
  
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/seguimiento.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
                 swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    $('#myModal').modal('toggle');
                    $('#myModal').modal('toggle');
    	    		$('#upd_seg').addClass('hide');
    	    		$('.modal-backdrop').remove();
    	    		loadAll_seg();
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function loadJustOneSeg(id_seg,tipo){
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
    data.push({name:"id_seg",value:id_seg});
    data.push({name:"function",value:"loadJustOne"});
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"tipo",value:tipo});
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/seguimiento.php",
        success: function(msg)
        {
            initFormSeg();
            
            var error = msg.error;
            if(!error){
                
                var result = msg.result;
                var rows =  msg.rows;
                if(rows > 0){
                    for (x in result) {
                        $('#seguimiento #date').val(result[x].fecha);
                        $('#seguimiento #place').val(result[x].lugar);
                        $('#seguimiento #h_ini').val(result[x].h_ini);
                        $('#seguimiento #m_ini').val(result[x].m_ini);
                        $('#seguimiento #h_fin').val(result[x].h_fin);
                        $('#seguimiento #m_fin').val(result[x].m_fin);
                        $('#seguimiento #tema').val(result[x].tema);
                        $('#seguimiento #objetivos').val(result[x].objetivos);
                        $('#seguimiento #actividades').val(result[x].actividades);
                        $('#seguimiento #observaciones').val(result[x].observaciones);
                        $('#seguimiento #monitor').text(result[x].infoMonitor);
                        $('#seguimiento #infomonitor').removeClass('hide');
                        //$('#seguimiento #optradio').val(result[x].act_status);
                        $('#seguimiento #'+result[x].act_status+'').children().prop('checked',true);
                        // if ($('#seguimiento').find('label').attr('id') == result[x].act_status){
                        //   alert("funciono");
                        // }else{
                        //     alert("opteradio:"+ $('#seguimiento input[name=optradio]').parent().attr('id')+ " - resut:"+result[x].act_status);
                        // }
                        //se muetra el boton actualizar i se asigina un id al contenedor para identificar el seguimient
                        
                        $('#upd_seg').removeClass('hide');
                        $('#upd_seg').parent().attr('id',id_seg);
                        $('#save_seg').addClass('hide');
                        
                        if (result[x].editable == false || tipo == 'GRUPAL'){
                            $('#upd_seg').attr('disabled',true);
                            $('#upd_seg').attr('title', 'Han trasncurrido más de 24 horas desde su creación por lo tanto no se puede actualizar');
                            $('#seguimiento').find('select, textarea, input').attr('disabled',true);
                            
                            if(tipo == 'GRUPAL'){
                                $('#upd_seg').addClass('hide');
                            }
                        }else{
                            $('#upd_seg').attr('disabled',false);
                            $('#upd_seg').attr('title', '');
                            $('#seguimiento').find('select, textarea, input').attr('disabled',false);
                        }
                        
                        
                        
                        
                        //se muestra los datos de creacion
                        $('#created_date').text("Creado el "+result[x].createdate);
                        $('#div_created').removeClass('hide');
                    }
 
                }else{
                    swal("No se ecnotraron resultados","warning");
                }
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function loadAll_primerAcerca(){
    $('#list_primerA').html('');
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val();
    
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"function",value:"load"});
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
                
                var result = msg.result;
                var rows =  msg.rows;
                if(rows > 0){
                    
                    $('#socioedu_primerAcerca').prop("disabled",true);
                    $('#socioedu_primerAcerca').addClass('hide');
                    $('#socioedu_add_AcompaSocio').removeClass('hide');
                    
                    
                    for (x in result) {
                        
                        $('#list_primerA').append("<div class=\"container well col-md-12\"> <div class=\"container-fluid col-md-10\" name=\"info\"><div class=\"row\"><label class=\"col-md-3\" for=\"fecha_des\">Fecha</label><label class=\"col-md-9\" for=\"tema_des\">Tema</label> </div> <div class=\"row\"> <input type=\"text\" class=\"col-md-3\" value=\""+result[x].fecha+"\" id=\"fecha_seg\" name=\"fecha_seg\" disabled> <input type=\"text\" class=\"col-md-9\" value=\""+result[x].motivo+"\" id=\"tema_seg\" name=\"tema_seg\" disabled> </div></div> <div id=\""+result[x].id+"\" class=\"col-md-2\" name=\"div_button_seg\"> <button type=\"submit\" id=\"consult_primerA\" name=\"consult_primerA\" class=\"submit\" data-toggle=\"modal\" data-target=\"#myModalPrimerAcerca\">Detalle</button> </div></div>");
                    }
                    $('#list_primerA').on('click', '#consult_primerA', function(){
                        var id = $(this).parent().attr('id');
                        $('#update_primerAcerca').removeClass('hide');
                        loadJustOnePrimerAcerca(id);
                    }); 
                       
                    
                }else{
                    $('#list_primerA').append("<label>No registra</label><br>");
                    $('#socioedu_primerAcerca').prop("disabled",false);
                    $('#socioedu_primerAcerca').removeClass('hide');
                    $('#socioedu_add_AcompaSocio').addClass('hide');
                }
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){alert("Error");},
    });
}



function  loadJustOnePrimerAcerca(id_pacerca){
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
    data.push({name:"function",value:"load"});
    data.push({name:"idtalentos",value:idtalentos});
    intiAcompaSocioForm();
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            
            var error = msg.error;
            if(!error){
                intiAcompaSocioForm();
                var result = msg.result;
                var rows =  msg.rows;
                
                //console.log(msg.result);
                if(rows > 0){
                    for (x in result) {
                        //se muestra los datos de creacion
                        $('#myModalPrimerAcerca #created_date').text("Creado el "+result[x].created);
                        $('#myModalPrimerAcerca #div_created').removeClass('hide');
                        $('#myModalPrimerAcerca #comp_familiar').val(result[x].comp_familiar);
                        $('#myModalPrimerAcerca #freetime').val(result[x].observaciones);
                        $('#myModalPrimerAcerca #motivo').val(result[x].motivo);
                        $('#myModalPrimerAcerca #'+result[x].act_status+'').children().prop('checked',true);
                        $('#myModalPrimerAcerca #monitor').text(result[x].infoProfesional);
                        $('#myModalPrimerAcerca #infomonitor').removeClass('hide');
                        
                        $('#myModalPrimerAcerca #upd_seg').removeClass('hide');
                        $('#myModalPrimerAcerca #upd_seg').parent().attr('id',result[x].id);
                        $('#myModalPrimerAcerca #save_seg').addClass('hide');
                    }
 
                }else{
                    swal("No se ecnotraron resultados","warning");
                }
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function updatePrimerAcerca(idPA){
        var data = $('#myModalPrimerAcerca #PrimerAcercaForm').serializeArray();
        data.push({name:"function",value:"updatePrimerAcerca"});
        data.push({name:"idPA",value:idPA});
        
        var idtalentos = $('#ficha_estudiante #idtalentos').val();
        data.push({name:"idtalentos",value:idtalentos});
        //console.log(data);
        $.ajax({
                type: "POST",
                data: data,
                url: "../managers/socioeducativo.php",
                success: function(msg)
                {
                    var error = msg.error;
                    if(!error){
                        swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    	$('#myModalPrimerAcerca').modal('toggle');
                    	$('#myModalPrimerAcerca').modal('toggle');
    	    			$('#myModalPrimerAcerca #save_seg').addClass('hide');
    	    			$('.modal-backdrop').remove();
    	    		     loadAll_AcompaSocio();
                    }else{
                        swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                    }
                },
                dataType: "json",
                cache: "false",
                error: function(msg){console.log(msg)},
        });
}

function loadAll_SegSocio(){
     $('#list_SegSocio').html('');
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val();
    
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"function",value:"loadSegSocio"});
    
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {   //console.log(msg);
            var error = msg.error;
            if(!error){
                
                
                var isfirst = true;
                
                var array_semestres_segumientos = msg.semesters_segumientos;
                //console.log(msg.semesters_segumientos);
                for(y in array_semestres_segumientos){
                    
                    var panel = '<div class="accordion-container"><a id="title'+array_semestres_segumientos[y].id_semester+'" class="accordion-toggle">Semestre '+array_semestres_segumientos[y].name_semester+'<span class="toggle-icon"><i class="glyphicon glyphicon-chevron-left"></i></span></a>';
                    panel += '<div id="panel-body'+array_semestres_segumientos[y].id_semester+'" class="accordion-content ScrollStyle"></div></div>';
                    
                    var result = array_semestres_segumientos[y].result;
                    var rows =  array_semestres_segumientos[y].rows;
                    
                    
                    $('#list_SegSocio').append(panel);            
                    
                    if(rows > 0){
                        for (x in result) {
                            $("#list_SegSocio #panel-body"+array_semestres_segumientos[y].id_semester).append("<div class=\"container well col-md-12\"> <div class=\"container-fluid col-md-10\" name=\"info\"><div class=\"row\"><label class=\"col-md-3\" for=\"fecha_des\">Fecha</label><label class=\"col-md-9\" for=\"tema_des\">Tema</label> </div> <div class=\"row\"> <input type=\"text\" class=\"col-md-3\" value=\""+result[x].fecha+"\" id=\"fecha_seg\" name=\"fecha_seg\" disabled> <input type=\"text\" class=\"col-md-9\" value=\""+result[x].tema+"\" id=\"tema_seg\" name=\"tema_seg\" disabled> </div></div> <div id=\""+result[x].id+"\" class=\"col-md-2\" name=\"div_button_seg\"> <button type=\"submit\" id=\"consult_SegSocio\" name=\"consult_SegSocio\" class=\"submit\" data-toggle=\"modal\" data-target=\"#myModal\">Detalle</button> </div></div>");
                        }
                        $('#list_SegSocio').on('click', '#consult_SegSocio', function(){
                            var id_seg = $(this).parent().attr('id');
                            
                            loadJustOneSegSocio(id_seg);
                        }); 
                           
                        
                    }else{
                        $("#list_SegSocio #panel-body"+array_semestres_segumientos[y].id_semester).append("<label>No registra</label><br>");
                    }
                    
                    if(isfirst){
                    	openAccordionToggle('#list_SegSocio #title'+array_semestres_segumientos[y].id_semester); //metodo definido en main
                    	isfirst = false;
                    }
                    
                }
    
                
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg);},
    });
}

function loadJustOneSegSocio(idseg){
    //console.log(idseg);
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
    data.push({name:"function",value:"loadJustOneSegSocio"});
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"idSegSocio",value:idseg});
    //console.log(data);
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            
            var error = msg.error;
            if(!error){
                intiAcompaSocioForm();
                var result = msg.result;
                var rows =  msg.rows;
                
                console.log(msg.result);
                if(rows > 0){
                    for (x in result) {
                        //se muestra los datos de creacion
                        $('#myModalSegSocio #created_date').text("Creado el "+result[x].created);
                        $('#myModalSegSocio #div_created').removeClass('hide');
                        
                        $('#myModalSegSocio #date').val(result[x].fecha);
                        $('#myModalSegSocio #seg').val(result[x].seguimiento);
                        $('#myModalSegSocio #motivo').val(result[x].motivo);
                        $('#myModalSegSocio #'+result[x].act_status+'').children().prop('checked',true);
                        
                        $('#myModalSegSocio #upd_seg').removeClass('hide');
                        $('#myModalSegSocio #upd_seg').parent().attr('id',result[x].id);
                        $('#myModalSegSocio #save_seg').addClass('hide');
                        $('#myModalSegSocio #monitor').text(result[x].infoProfesional);
                        $('#myModalSegSocio #infomonitor').removeClass('hide');
                    }
 
                }else{
                    swal("No se ecnotraron resultados","warning");
                }
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function updateSegsocio(idseg){
     var data  = $('#myModalSegSocio #SegSocioForm').serializeArray();
        data.push({name:"function",value:"updateSegSocio"});
        
        var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
        data.push({name:"idtalentos",value:idtalentos});
         data.push({name:"idSegSocio",value:idseg});
        console.log(data);
        $.ajax({
                type: "POST",
                data: data,
                url: "../managers/socioeducativo.php",
                success: function(msg)
                {
                    var error = msg.error;
                    if(!error){
                        swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    	$('#myModalSegSocio').modal('toggle');
                    	$('#myModalSegSocio').modal('toggle');
    	    			$('#myModalSegSocio #save_seg').addClass('hide');
    	    			$('.modal-backdrop').remove();
    	    		    loadAll_SegSocio();
    	    		    loadAll_seg();
                    }else{
                        swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                    }
                },
                dataType: "json",
                cache: "false",
                error: function(msg){console.log(msg)},
        });
}

function loadAll_AcompaSocio(){
    $('#list_AcompaSocio').html('');
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val();
    
    data.push({name:"idtalentos",value:idtalentos});
    data.push({name:"function",value:"load_AcompaSocio"});
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
                var result = msg.result;
                var rows =  msg.rows;
                if(rows > 0){
                    $('#socioedu_add_segsocio').removeClass('hide');
                    $('#socioedu_add_AcompaSocio').addClass('hide');
                    $('#socioedu_add_AcompaSocio').prop("disabled",true);
                    
                    
                    
                    for (x in result) {
                        
                        $('#list_AcompaSocio').append("<div class=\"container well col-md-12\"> <div class=\"container-fluid col-md-10\" name=\"info\"><div class=\"row\"><label class=\"col-md-3\" for=\"fecha_des\">Fecha</label><label class=\"col-md-9\" for=\"tema_des\">Segumiento</label> </div> <div class=\"row\"> <input type=\"text\" class=\"col-md-3\" value=\""+result[x].fecha+"\" id=\"fecha_seg\" name=\"fecha_seg\" disabled> <input type=\"text\" class=\"col-md-9\" value=\""+result[x].seguimiento+"\" id=\"tema_seg\" name=\"tema_seg\" disabled> </div></div> <div id=\""+result[x].id+"\" class=\"col-md-2\" name=\"div_button_seg\"> <button type=\"submit\" id=\"consult_acompasocio\" name=\"consult_acompasocio\" class=\"submit\" data-toggle=\"modal\" data-target=\"#myModalAcompaSocio\">Detalle</button> </div></div>");
                    }
                    $('#list_AcompaSocio').on('click', '#consult_acompasocio', function(){
                        var id = $(this).parent().attr('id');
                       
                        loadJustOneAcompaSocio();
                    }); 
                       
                    
                }else{
                    $('#list_AcompaSocio').append("<label>No registra</label><br>");
                    $('#socioedu_add_AcompaSocio').prop("disabled",false);
                    $('#socioedu_add_segsocio').addClass('hide');
                }
    
                
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg);},
    });
}


function  loadJustOneAcompaSocio(){
    var data = new Array();
    var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
    data.push({name:"function",value:"load_AcompaSocio"});
    data.push({name:"idtalentos",value:idtalentos});
    intiAcompaSocioForm();
	$.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            
            var error = msg.error;
            if(!error){
                intiAcompaSocioForm();
                var result = msg.result;
                var rows =  msg.rows;
                
                console.log(msg.result);
                if(rows > 0){
                    for (x in result) {
                        
                        //se muestra los datos de creacion
                        $('#created_date').text("Creado el "+result[x].createdate);
                        $('#div_created').removeClass('hide');
                        
                        //se llena el formulario
                    
                        $('#AcompaSocio #date').val(result[x].fecha);
                        
                        $('#AcompaSocio .antecedentes').each(function(i,item) {
                           if (item.name == 'psicologia'){
                               if(item.value == result[x].antecedente_psicosocial){
                                   $(this).prop('checked',true);
                               }
                           }
                           else if (item.name == 'tsocial'){
                               if(item.value == result[x].antecedente_tsocial){
                                   $(this).prop('checked',true);
                               }
                           }
                           else if (item.name == 'teo'){
                               if(item.value == result[x].antecedente_terapiao){
                                   $(this).prop('checked',true);
                               }
                           }
                        });
                        
                        if((result[x].antecedente_terapiao == 1) || (result[x].antecedente_tsocial == 1) || (result[x].antecedente_psicosocial == 1) ){
                            $('#AcompaSocio #motivo').prop('disabled',false);
                        }
                        
                        $('#AcompaSocio #motivo').val(result[x].descripcion_antecedente);
                        
                        var ingresos = result[x].ingresos;
                        $('#AcompaSocio #mytableIngresos tbody').html('');
                        
                        for (y in ingresos){
                            $('#AcompaSocio #mytableIngresos tbody').append('<tr> <td><input id="descripIngreso" name="descripIngreso" size="8" maxlength="15" type="text" value="'+ingresos[y].concepto+'" /></td> <td><input id="valorIngreso" name="valorIngreso" type="text" size="8" maxlength="8" value="'+ingresos[y].monto+'"   /></td> <td><a href="#" id="removeEco"><span class="glyphicon glyphicon-remove"></span></a></td> <td class="hide"><input id="idIngreso" name="idIngreso" size="1" value="'+ingresos[y].id+'" maxlenght="1" type="text" /></td> </tr>');
                        }
                        
                        caculateSum('#AcompaSocio #valorIngreso', '#AcompaSocio #totalIngresos');
                        
                        var egresos = result[x].egresos
                        $('#AcompaSocio #mytableEgresos tbody').html('');
                        for (y in egresos){
                            $('#AcompaSocio #mytableEgresos tbody').append('<tr> <td><input id="descripEgreso" name="descripEgreso" size="8" maxlength="15" type="text" value ="'+egresos[y].concepto+'" /></td> <td><input id="valorEgreso" name="valorEgreso" type="text" size="8" maxlength="8" value ="'+egresos[y].monto+'" /></td> <td><a href="#" id="removeEco"><span class="glyphicon glyphicon-remove"></span></a></td> <td class="hide"><input id="idEgreso" name="idEgreso" size="1" value="'+egresos[y].id+'" maxlenght="1" type="text" /></td> </tr>');
                        }
                        
                        caculateSum('#AcompaSocio #valorEgreso', '#AcompaSocio #totalEgresos');
                        
                        var familia = result[x].familia;
                        $('#AcompaSocio #mytablefamilia tbody').html('');
                        for (y in familia){
                            $('#AcompaSocio #mytablefamilia tbody').append(' <tr> <td><input id="nombreFamilia" name="nombreFamilia" size="8" maxlength="8" type="text" value="'+familia[y].nombre_pariente+'" /></td> <td><select id="parentescoFamilia"  name="parentescoFamilia" > <option value="MADRE" selected>MADRE</option>  <option value="PADRE">PADRE</option> <option value="HERMANO/A">HERMANO/A</option> <option value="TIO/A">TIO/A</option> <option value="ABUELO/A">ABUELO/A</option> <option value="PRIMO">PRIMO</option> <option value="OTRO">OTRO</option> </select></td>  <td><input id="ocupacionFamilia" name="ocupacionFamilia" size="8" maxlenght="8" type="text" value="'+familia[y].ocupacion+'" /></td> <td><input id="telefonoFamilia" name="telefonoFamilia" size="8" maxlenght="8" type="text" value="'+familia[y].telefono+'" /></td> <td><a href="#" id="removeFamilia"><span class="glyphicon glyphicon-remove"></span></a></td>  <td class="hide"><input id="idFamilia" name="idFamilia" size="1" value="'+familia[y].id+'" maxlenght="1" type="text" /></td>  </tr>');
                            //por si se adiciona <td><input id="edadFamilia" name="edadFamilia" size="8" maxlenght="8" type="text" /></td> <td><input id="estadoCivilfamilia" name="estadoCivilfamilia" size="8" maxlenght="8" type="text" /></td>
                            $('#AcompaSocio #mytablefamilia tbody tr').each(function() {
                                if($(this).find('td input[id="idFamilia"]').val() == familia[y].id){
                                    $(this).find('td select[id="parentescoFamilia"]').val(familia[y].parentesco);
                                }
                             });
                            
                        }
                        
                        
                        
                        $('#AcompaSocio #composicionFamiliar').val(result[x].comp_familiar);
                        $('#AcompaSocio #dinamicaFamiliar').val(result[x].dinamica_familiar);
                        $('#AcompaSocio #apoyoFamiliar').val(result[x].red_familiar);
                        $('#AcompaSocio #apoyoEducativo').val(result[x].red_edu);
                        $('#AcompaSocio #apoyoSocial').val(result[x].red_social);
                        $('#AcompaSocio #apoyoLaboral').val(result[x].red_laboral);
                        $('#AcompaSocio #monitor').text(result[x].infoProfesional);
                        $('#AcompaSocio #infomonitor').removeClass('hide');
                        
                        
                        if(result[x].fr_spa == 1) {
                            $('#AcompaSocio #resgo1').prop('checked',true);
                            $('#AcompaSocio #input_r1').prop('disabled',false);
                            $('#AcompaSocio #input_r1').val(result[x].fr_spa_observaciones);
                        }
                        if(result[x].fr_embarazo == 1) {
                            $('#AcompaSocio #resgo2').prop('checked',true);
                            $('#AcompaSocio #input_r2').prop('disabled',false);
                            $('#AcompaSocio #input_r2').val(result[x].fr_embarazo_observaciones);
                        }
                        if(result[x].fr_maltrato == 1) {
                            $('#AcompaSocio #resgo3').prop('checked',true);
                            $('#AcompaSocio #input_r3').prop('disabled',false);
                            $('#AcompaSocio #input_r3').val(result[x].fr_maltrato_observaciones);
                        }
                        if(result[x].fr_abusosexual == 1) {
                            $('#AcompaSocio #resgo4').prop('checked',true);
                            $('#AcompaSocio #input_r4').prop('disabled',false);
                            $('#AcompaSocio #input_r4').val(result[x].fr_abusosexual_observaciones);
                        }
                        if(result[x].fr_otros == 1) {
                            $('#AcompaSocio #resgo5').prop('checked',true);
                            $('#AcompaSocio #input_r5').prop('disabled',false);
                            $('#AcompaSocio #input_r5').val(result[x].fr_otros_observaciones);
                        }
                        
                        
                        $('#AcompaSocio #observacionGeneral').val(result[x].observaciones);
                        $('#AcompaSocio #acuerdos').val(result[x].acuerdos);
                        $('#AcompaSocio #descripSeg').val(result[x].seguimiento);
                        
                        $('#AcompaSocio #'+result[x].act_status+'').children().prop('checked',true);
                        
                        
                        $('#myModalAcompaSocio #upd_seg').removeClass('hide');
                        $('#myModalAcompaSocio #upd_seg').parent().attr('id',result[x].id);
                        $('#myModalAcompaSocio #save_seg').addClass('hide');
                        
                        // if (result[x].editable == false || tipo == 'GRUPAL'){
                        //     $('#upd_seg').attr('disabled',true);
                        //     $('#upd_seg').attr('title', 'Han trasncurrido más de 24 horas desde su creación por lo tanto no se puede actualizar');
                        //     $('#seguimiento').find('select, textarea, input').attr('disabled',true);
                            
                        // }else{
                        //     $('#upd_seg').attr('disabled',false);
                        //     $('#upd_seg').attr('title', '');
                        //     $('#seguimiento').find('select, textarea, input').attr('disabled',false);
                        // }
                        
                        
                        
                        
                        
                    }
 
                }else{
                    swal("No se ecnotraron resultados","warning");
                }
            }else{
                swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            }
            
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function addInfoDataAcompaSocio(data){
    //la informacion económica se va almacenar en los siguientes arreglos
	   var descripIngresos =  new Array();
	   var valorIngresos =  new Array();
	   var idIngresos = new Array();
	   var descripEgresos =  new Array();
	   var valorEgreso =  new Array();
	   var idEgresos = new Array();
	   
	   //información familiar se va almacenar en los siguientes arregñps
	   var nombreFamilia = new Array();
	   var parentescoFamilia =  new Array();
	   var edadFamilia =  new Array();
	   var estadoCivilfamilia =  new Array();
	   var ocupacionFamilia = new Array();
	   var telefonoFamilia =  new Array();
	   var idFamilia =  new Array();
	   
	   
	   $.each(data, function(i, item) {
	       
	       //informacion economica
	       
            if(item.name=="descripIngreso"){ 
                descripIngresos.push(item.value);
            }
            
            else if(item.name=="valorIngreso"){ 
                valorIngresos.push(item.value);
            }
            
            else if(item.name=="idIngreso"){ 
                idIngresos.push(item.value);
            }
            
            else if(item.name == "descripEgreso"){ 
                descripEgresos.push(item.value);
            }
            
            else if(item.name == "valorEgreso"){ 
                valorEgreso.push(item.value);
            }
            
            else if(item.name == "idEgreso"){ 
                idEgresos.push(item.value);
            }
            
            //informacion familiar
            
            else if(item.name == "nombreFamilia"){ 
                nombreFamilia.push(item.value);
            }
            
            else if(item.name == "parentescoFamilia"){ 
                parentescoFamilia.push(item.value);
            }
            
            else if(item.name == "edadFamilia"){ 
                edadFamilia.push(item.value);
            }
            
            else if(item.name == "estadoCivilfamilia"){ 
                estadoCivilfamilia.push(item.value);
            }
            
            else if(item.name == "ocupacionFamilia"){ 
                ocupacionFamilia.push(item.value);
            }
            
            else if(item.name == "telefonoFamilia"){ 
                telefonoFamilia.push(item.value);
            }
            
            else if(item.name == "idFamilia"){ 
                idFamilia.push(item.value);
            }
        });
        
        
        //informacion economica
        data.push({name:"descripIngresos",value:descripIngresos}); //envia por POST el contenido del ultimo item con el name especificado en este caso "descripIngreso"
        data.push({name:"valorIngresos",value:valorIngresos});
        data.push({name:"idIngresos",value:idIngresos});
        data.push({name:"descripEgresos",value:descripEgresos});
        data.push({name:"valorEgresos",value:valorEgreso});
        data.push({name:"idEgresos",value:idEgresos});
        //informacion familiar
        data.push({name:"nombreFamilia",value:nombreFamilia});
        data.push({name:"parentescoFamilia",value:parentescoFamilia});
        data.push({name:"edadFamilia",value:edadFamilia});
        data.push({name:"estadoCivilfamilia",value:estadoCivilfamilia});
        data.push({name:"ocupacionFamilia",value:ocupacionFamilia});
        data.push({name:"telefonoFamilia",value:telefonoFamilia});
        data.push({name:"idFamilia",value:idFamilia});
        
        //se almacena el idtalentos
        var idtalentos = $('#ficha_estudiante #idtalentos').val(); 
        data.push({name:"idtalentos",value:idtalentos});
        
        return data;
}

function updateAcompaSocio(id_acompa){
    var validation = validateAcompasocio();
    if(validation.isvalid){
        var data  = $('#myModalAcompaSocio #AcompaSocio').serializeArray();
	    data = addInfoDataAcompaSocio(data);
        data.push({name:"function",value:"updateAcompaSocio"});
        data.push({name:"idAcompaSocio",value:id_acompa});
        
        //console.log(data);
        $.ajax({
                type: "POST",
                data: data,
                url: "../managers/socioeducativo.php",
                success: function(msg)
                {
                    var error = msg.error;
                    if(!error){
                        swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                    	$('#myModalAcompaSocio').modal('toggle');
                    	$('#myModalAcompaSocio').modal('toggle');
    	    			$('#myModalAcompaSocio #save_seg').addClass('hide');
    	    			$('.modal-backdrop').remove();
    	    		     loadAll_AcompaSocio();
                    }else{
                        swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                    }
                },
                dataType: "json",
                cache: "false",
                error: function(msg){console.log(msg)},
        });
    }else{
        swal({title: "Error", html:true, type: "warning",  text: "Detalles del error:<br>"+validation.detalle, confirmButtonColor: "#D3D3D3"});
    }
        
}

function validateModal(data){
    var isvalid = true;
    var detalle = "";
    
    
	var date,h_ini, m_ini, h_fin, m_fin, tema, objetivos;

	$.each(data, function(i, field){
          
        switch (field.name) {
    	case 'date':
    		date = field.value;
    		break;
    		
    	case 'h_ini':
    		h_ini = field.value;
    		break;
    		
    	case 'm_ini':
    		m_ini = field.value;
    		break;
    	
    	case 'h_fin':
    		h_fin = field.value;
    		break;
    	case 'm_fin':
    		m_fin = field.value;
    		break;
    	case 'tema':
    		tema = field.value;
    		break;
    	case 'objetivos':
    		objetivos = field.value;
    		break;
        }
    });
    if (!date){ 
        isvalid = false;
        detalle +="* Selecciona una Fecha de seguimiento valida: date<br>";
    }
    
    if(h_ini > h_fin){
    	isvalid = false;
        detalle +="* La hora final debe ser mayor a la inicial<br>";
    }else if(h_ini == h_fin){
    	if(m_ini > m_fin){
    		isvalid = false;
            detalle +="* La hora final debe ser mayor a la inicial<br>";
    	}
    }
    
    
    // se valida la seleccion del radio
   var optradio= true;
    
      $.each(data, function(i, item) {
            if(item.name=="optradio"){ 
                    optradio = true;
                    return false;
            }else{
                optradio = false;
            }
    });
    
    
    if(!optradio){
        isvalid = false;
        detalle +="* Califica el seguimiento<br>";
    }
    
    if(tema == ""){
        isvalid = false;
        detalle +="* La informacion de \"observaciones\" es obligatoria :"+ tema+"<br>";
    }
    
    if(objetivos == ""){
        isvalid = false;
        detalle +="* La informacion de \"actividades\" es obligatoria:"+ objetivos+"<br>";
    }
    
    var result= {isvalid:isvalid, detalle:detalle};
    
    
    return result;
}



function getVariableGetByName() {
   var variables = {};
   var arreglos = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
      variables[key] = value;
   });
   return variables;
}

function initFormSeg(){

    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    var minutes = date.getMinutes();
    var hour = date.getHours();
    
//   // inicializar fecha
    // if (month < 10) month = "0" + month;
    // if (day < 10) day = "0" + day;
    
    // var today = year + "-" + month + "-" + day;
    // $("#seguimiento #date").prop("value",today);
    
    //incializar hora
    var hora="";
    for (var i = 0; i<24;i++) {
        if(i==hour){
            if(hour<10) hour= "0"+hour;
            hora += "<option value=\""+hour+"\" selected>"+hour+"</option>";
        }else if (i<10){
            hora += "<option value=\"0"+i+"\">0"+i+"</option>";
        }else{
        hora += "<option value=\""+i+"\">"+i+"</option>";
        }
    }
    
    var min = "";
    for (var i = 0; i<60;i++) {
        
        if(i== minutes){
            if (minutes < 10 ) minutes = "0"+minutes;
            min += "<option value=\""+minutes+"\" selected>"+minutes+"</option>";
        }else if (i<10){
            min += "<option value=\"0"+i+"\">0"+i+"</option>";
        }else{
        min += "<option value=\""+i+"\">"+i+"</option>";
        }
    }
    

    $('#seguimiento #h_ini').append(hora);
    $('#seguimiento #m_ini').append(min);
    
    $('#seguimiento #h_fin').append(hora);
    $('#seguimiento #m_fin').append(min);
    $("#seguimiento #infomonitor").addClass('hide');
    $("#seguimiento").find("input, textarea").val('');
    $('#upd_seg').attr('disabled',false);
    $('#upd_seg').attr('title', '');
    $('#seguimiento').find('select, textarea, input').attr('disabled',false);
   
}


function intiAcompaSocioForm(){
    
    $('#AcompaSocio .antecedentes').each(function() {
	         if($(this).val()== 0){
    	         $(this).prop("checked","checked");
	         }
	});
    $('#AcompaSocio #mytableIngresos tbody').html('');
    $('#AcompaSocio #mytableEgresos tbody').html('');
    $('#AcompaSocio #mytablefamilia tbody').html('');
    $('#AcompaSocio #motivo').prop('disabled',true);
    
    $('#AcompaSocio #input_r1').prop('disabled',true);
    $('#AcompaSocio #input_r2').prop('disabled',true);
    $('#AcompaSocio #input_r3').prop('disabled',true);
    $('#AcompaSocio #input_r4').prop('disabled',true);
    $('#AcompaSocio #input_r5').prop('disabled',true);
    
    for( var i = 1 ; i<=5; i++){
        $('#AcompaSocio #resgo'+i).prop("checked", false );
        $('#AcompaSocio #input_r'+i).val('');
    }
    
    
    
    //$("#AcompaSocio").find("input, textarea").val('');
    
    $('#AcompaSocio').each(function() {
        
        var name = $(this).attr("name");
        if(name != 'psicologia' &&  name != 'tsocial' && name != 'teo'){
             $(this).val('');
        }
	});
    
    
    
    $('#AcompaSocio  #upd_seg').attr('disabled',false);
    $('#AcompaSocio  #upd_seg').attr('title', '');
    
    
}

function validateAcompasocio(){
    var detalle = "";
    var isvalid = true;
    $('#AcompaSocio #valorIngreso').each( function() {
        
         //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            $(this).css("background-color", "#FEFFB0");
        }
        else {
            $(this).css("background-color", "red");
            isvalid = false;
            detalle+="* Asegúrate de que los valores en los montos de los ingresos sean numéricos y NO sean nulos.<br>";
        }   
    });
    
    $('#AcompaSocio #valorEgreso').each( function() {
         //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            $(this).css("background-color", "#FEFFB0");
        }
        else {
            $(this).css("background-color", "red");
            isvalid = false;
            detalle+="* Asegúrate de que los valores en los montos de los egresos sean numéricos y NO sean nulos.<br>";
        }   
    });
    
    $('#AcompaSocio #telefonoFamilia').each( function() {
         //add only if the value is number
        if (!isNaN(this.value)) {
            $(this).css("background-color", "#FEFFB0");
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "red");
            isvalid = false;
            detalle+="* Asegúrate de que los valores de los telefonos de la familia sean numéricos.<br>";
        }   
    });
    
    return {isvalid:isvalid,detalle:detalle};
}

function dropEcono(idEcono){
    var data = new Array();
    data.push({name:"function",value:"deleteEconomica"});
    data.push({name:"idEco",value:idEcono});
    
   // var idtalentos = $('#ficha_estudiante #idtalentos').val();
    //data.push({name:"idtalentos",value:idtalentos});
    console.log(data);
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
               // swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
            	console.log(msg.msg);
            }else{
                //swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                console.log(msg.msg);
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function dropFamilia(idfamilia){
     var data = new Array();
    data.push({name:"function",value:"deleteFamilia"});
    data.push({name:"idFamilia",value:idfamilia});
    
   // var idtalentos = $('#ficha_estudiante #idtalentos').val();
    //data.push({name:"idtalentos",value:idtalentos});
    console.log(data);
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/socioeducativo.php",
        success: function(msg)
        {
            var error = msg.error;
            if(!error){
                //swal({title: "Actualizado con exito!!", html:true, type: "success",  text: msg.msg, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  blockall(this);} });
                console.log(msg.msg);
                
            }else{
                //swal({title: error, html:true, type: "error",  text: msg.msg, confirmButtonColor: "#D3D3D3" });
                console.log(msg.msg);
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function validatePrimerAcerca(){
     var isvalid = true;
     var detalle = "";
    if(!$('#myModalPrimerAcerca').find('input[name="optradio"]').is('unchecked')){
        
        
    }
    
}

