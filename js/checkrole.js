//url[array]  contiene el valor de los parametros de la url se accede a cada uno de la forma url["nombreParametro"]  
var url = QueryString();

$(window).load(function() {
  
  var pag = window.location.href;
  
  
  if (pag.includes("talentos_profile.php")){
    verificarPermisosFicha();
    writejson1();
  }else if(pag.includes("index.php")){
    verificarPermisosIndex();
  }else if(pag.includes("user_management.php")){
    verificarPermisosRoles();
  }else if(pag.includes("upload_files_form.php")){
    verificarPermisosArchivos();
  }
});


function verificarPermisosFicha(){
  $.ajax({
            type: "POST",
            data:{page:"ficha",block:url["instanceid"]},
            url: "../managers/checkrole.php",
            success: function(msg)
            {
              var error = msg.error;
              if(!error){
                //console.log(msg);
                  for (x in msg) {
                     var fun = msg[x].funid;
                     var permiso = msg[x].permiso;
                    switch (parseInt(fun)) {
                        case 3://General
                          gestionarPermisosGeneral(permiso);
                          break;
                          
                        case 4: //academica
                          gestionarPermisosAcademica(permiso);
                          break;
                          
                        case 5: //asistenacia
                          gestionarPermisosAsistencia(permiso);  
                          break;
                         
                        case 6: // psicosocial
                          gestionarPermisospsicosocial(permiso);
                          break;
                        
                        case 7: // psicosocial monitor 
                          gestionarPermisospsicosocial_monitor(permiso);
                          //console.log("entro");
                          break;
                          
                        default :
                          console.log("Error al consultar permiso "+ fun);
                    }
                  }
              }else{
                var men = msg.msg;
                swal({title: error, html:true, type: "error",  text: men, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  window.history.back();} else {     swal("Cancelled", "Your imaginary file is safe :)", "error");   } });
                //alert(error); window.history.back()
                
              }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){alert("Error " + msg)},
            });
}

function verificarPermisosIndex(){

    $.ajax({
            type: "POST",
            data:{page:"index",block:url["instanceid"]},
            url: "../managers/checkrole.php",
            success: function(msg)
            {
              var error = msg.error;
              if(!error){
                  for (x in msg) {
                    var fun = msg[x].funid;
                    var permiso = msg[x].permiso;
                      switch (permiso) {
                        case 'C':
                          // CODE
                          break;
                        
                        case 'R':
                          $('#btn-send-indexform').prop("disabled",false);
                          break;
                        
                        case 'U':
                          // code
                          break;
                          
                        case 'D':
                          // code
                          break;
                      }
                  }
              }else{
                var men = msg.msg;
                swal({title: error, html:true, type: "error",  text: men, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  window.history.back();} else {     swal("Cancelled", "Your imaginary file is safe :)", "error");   } });
              }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){console.log(msg)},
            });
}

function verificarPermisosArchivos(){
      $.ajax({
            type: "POST",
            data:{page:"archivos",block:url["instanceid"]},
            url: "../managers/checkrole.php",
            success: function(msg)
            {
              var error = msg.error;
              if(!error){
                  for (x in msg) {
                    var fun = msg[x].funid;
                    var permiso = msg[x].permiso;
                    // alert("Su permiso es "+permiso);
                      switch (permiso) {
                        case 'C':
                          // CODE
                          break;
                        
                        case 'R':
                         
                          break;
                        
                        case 'U':
                          $('#archivo').prop("disabled",false);
                          $('#boton_subir').prop("disabled",false);
                          break;
                          
                        case 'D':
                          $('#archivos_subidos').removeClass("hide");
                          break;
                        
                        default:
                          alert("el permiso es "+permiso);
                          break;
                      }
                  }
              }else{
                var men = msg.msg;
                swal({title: error, html:true, type: "error",  text: men, confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  window.history.back();} else {     swal("Cancelled", "Your imaginary file is safe :)", "error");   } });
              }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){alert("Error " + msg)},
            })
}

function verificarPermisosRoles(){
      $.ajax({
            type: "POST",
            data:{page:"user",block:url["instanceid"]},
            url: "../managers/checkrole.php",
            success: function(msg)
            {
              var error = msg.error;
              if(!error){
                  for (x in msg) {
                    var fun = msg[x].funid;
                    var permiso = msg[x].permiso;
                      switch (permiso) {
                        case 'C':
                          // CODE
                          break;
                        
                        case 'R':
                         
                          break;
                        
                        case 'U':
                          $('#search_button').prop("disabled",false);
                          break;
                          
                        case 'D':
                          // code
                          break;
                      }
                  }
              }else{
                var men = msg.msg;
                swal({title: "ÁREA RESTRINGIDA", html:true, type: "warning",  text: "Esta sección está restringida solo para el personal del Área de Sistemas del Plan Talentos Pilos", confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) {  window.history.back();} });
                
              }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){alert("Error " + msg)},
            })  
}



function gestionarPermisosGeneral(permiso){
  switch (permiso) {

    case 'R':
      $('#1a').removeClass("hide");
      break;
    
    case 'U':
      $('#editar_ficha').removeClass("hide");
      if(!url["talento_id"]){
        $('#editar_ficha').addClass("hide");
      }
      break;

  }
}

function gestionarPermisosAcademica(permiso){
  switch (permiso) {
    case 'C':
      // CODE
      break;
    
    case 'R':
      $('#2a').removeClass("hide");
      break;
    
    case 'U':
      // code
      break;
      
    case 'D':
      // code
      break;
  }
}

function gestionarPermisosAsistencia(permiso){
    switch (permiso) {
    case 'C':
      // CODE
      break;
    
    case 'R':
      $('#3a').removeClass("hide");
      break;
    
    case 'U':
      // code
      break;
      
    case 'D':
      // code
      break;
  }
}

function gestionarPermisospsicosocial(permiso){
      switch (permiso) {
    case 'C':
      // CODE
      break;
    
    case 'R':
      $('#4a').removeClass("hide");
      $('#panelPares').removeClass("hide");
      $('#panelGrupal').removeClass("hide");
      $('#panelAcompSocio').removeClass("hide");
      $('#panelSegSocio').removeClass("hide");
      $('#panelPrimerAcerca').removeClass("hide");
      break;
    
    case 'U':
      // code
      break;
      
    case 'D':
      // code
      break;
  }
}

function gestionarPermisospsicosocial_monitor(permiso){
  var idtalentos = $('#ficha_estudiante #idtalentos').val();
  canSeeStudent(idtalentos, function(canSee){
    
    //console.log(canSee);
    if (canSee.result){
      switch (permiso) {
        case 'C':
          // CODE
          break;
        
        case 'R':
          $('#4a').removeClass("hide");
          $('#panelPares').removeClass("hide");
          $('#panelGrupal').removeClass("hide");
          break;
        
        case 'U':
          // code
          break;
          
        case 'D':
          // code
          break;
      }
      
    }else{
      swal({title: "ÁREA RESTRINGIDA", html:true, type: "warning",  text: "No tienes permisos para ver la información de este estudiante.<br> Dirigete a la oficina de Sistemas del plan talentos pilos para gestionar tu situación", confirmButtonColor: "#d51b23"}, function(isConfirm){   if (isConfirm) { 
        window.history.back();
      } });
    }
  });
  
}




function QueryString () {
  // This function is anonymous, is executed immediately and 
  // the return value is assigned to QueryString!
  var query_string = [];
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    query_string[pair[0]] = pair[1];
  } 
  return query_string;
}

function writejson1 (){
  $.ajax({
            type: "POST",
            data:{page:"archivos",block:url["instanceid"]},
            url: "../managers/writeJsonFile.php",
            success: function(msg)
            {
              console.log(msg);
            },
            dataType: "text",
            cache: "false",
            error: function(msg){alert("Error " + msg)},
            });
}

function canSeeStudent(idtalentos, callback){ // se consulta mediane un funcion callbak la cual recibe como parametro resultado del ajax
  var pagina = location.href;
  var data = new Array();
  
  if(idtalentos){
    data.push({name:"estudiante_monitor",value:idtalentos});
    
    //console.log(data);
    $.ajax({
          type: "POST",
          data:data,
          url: "../managers/checkrole.php",
          success: function(msg)
          {
            //console.log({result:result,pagina:pagina});
            msg.pagina = pagina;
            callback(msg);
          },
          dataType: "json",
          cache: "false",
          error: function(msg){
            console.log("Error ");
            console.log(msg);
            //return {result:false,pagina:pagina};
          }
          
    });
    
  }

}
