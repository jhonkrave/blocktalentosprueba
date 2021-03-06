$(document).ready(function() {
    $(".assignment_li").css({ display: 'none' });
    $("#form_mon_student").css({ display: 'none' });
    
    $("#search_button").on('click', function() {
        userLoad();
        $("#username_input").attr('readonly', true);
        $(".assignment_li").slideToggle("slow");
    });
    $("#ok-button").on('click', function(){
        var rolchanged = $('#role_select').val()
        userLoad(null,function(msg) {
            if (msg.rol == 'monitor_ps' && msg.rol !=  rolchanged){
            
                var currentUser = new Array();
                currentUser.id =  $('#user_id').val();
                currentUser.username = $('#username_input').val();
                valdateStudentMonitor(currentUser, false);
            }else{
                updateRolUser();
                load_users();
            } 
        });
        
    });
    $("#cancel-button").on('click', function(){
        $(".assignment_li").addClass('hidden');
        $('#boss_li').fadeOut();
        $("#form_mon_student").fadeOut();
        
        $("#username_input").val("");
        $("#username_input").attr('readonly', false);
        $('#name_lastname').val(" ");
        
        $("#form_prof_type").fadeOut();
        
    });
    $("#form_mon_estudiante").css({ display: 'none'});
    $("#form_prof_type").css({ display: 'none'});
    $("#role_select").on('change',function(){
        
        if($("#role_select").val() == "monitor_ps"){
            $("#form_prof_type").fadeOut();
            $("#form_mon_student").fadeIn();
            getProfessionals();
            $('#boss_li').fadeIn();
           
        }
        else if($("#role_select").val() == "profesional_ps"){
            $("#form_prof_type").fadeIn();
            $('#boss_li').fadeOut();
            $("#form_mon_student").fadeOut();
        }
        else{
            $('#boss_li').fadeOut();
            $("#form_mon_student").fadeOut();
            $("#form_prof_type").fadeOut();
        }
    });
    $("#list-users-panel").on('click', function(){
        load_users();
    });
    
    
    $('#div_users').on('click','#delete_user',function(){
        
        var table = $("#div_users #tableUsers").DataTable();
        var td =$(this).parent();
        var childrenid = $(this).children('span').attr('id');
        var colIndex = table.cell(td).index().column;
        
        var username =  table.cell(table.row(td).index(),0).data();
        var firstname = table.cell(table.row(td).index(),1).data();
        var lastname = table.cell(table.row(td).index(),2).data();
        var rol = table.cell(table.row(td).index(),3).data();
        var currentUser = new Array();
        currentUser.id =  childrenid;
        currentUser.username = username;
        
        swal(
            {  
                title: "Estas seguro/a?",   
                text: "Al usuario <strong>"+firstname+" "+lastname+"</strong> con código <strong>"+username+"</strong> se le inhabilitará los permisos del rol <strong>"+rol+"</strong>.<br><strong>¿Estás de acuerdo con los cambios que se efectuarán?</strong>",   
                type: "warning",
                html: true,
                showCancelButton: true,   
                confirmButtonColor: "#d51b23",   
                confirmButtonText: "Si!",
                cancelButtonText: "No", 
                closeOnConfirm : true, 
            }, 
            function(isConfirm){
                if(isConfirm){
                    userLoad(username, function(msg) {
                        currentUser.rol = msg.rol;
                        var rol = msg.rol; 
                        switch(rol){
                            case 'monitor_ps': 
                                valdateStudentMonitor(currentUser, true);
                                break;
                            case 'profesional_ps': 
                                deleteProfesional(currentUser);
                                break;
                            default:
                            deleteOtheruser(currentUser);
                    }
                });
                }
            }
        );

    
    });
    
    
    
    student_asignment();
});

function getObjectUser(objectAjax){
    return objectAjax;
}

function userLoad(username, callback){
    var dataString = username;
    if(!dataString){
        dataString = $('#role_man_form #username_input').val();
    }
    
    var objectAjax = $.ajax({
        type: "POST",
        data: {dat: dataString},
        url: "../managers/search_user.php",
        success: function(msg){
            
            if(callback){
                    callback(msg);
            }else{
                if(!msg.error){
                    
                    
                        if (msg.firstname == ""){
                            swal("Error", "El usuario no existe en la base de datos", "error");
                            $("#username_input").attr('readonly', false);
                        }
                        else{
                            $('.assignment_li').removeClass('hidden');
                            $('#name_lastname').val(msg.firstname + " " + msg.lastname);
                            $('#user_id').val(msg.id);
                            if(msg.rol == ""){
                                $('#role_select').val("no_asignado");
                            }
                            else{
                                $('#role_select').val(msg.rol);
                                if(msg.rol == "profesional_ps"){
                                    $('#select_prof_type').val(msg.profesion);
                                    $("#form_prof_type").fadeIn();
                                    $('#boss_li').fadeOut();
                                    $("#form_mon_student").fadeOut();
                                }
                                else if(msg.rol == "monitor_ps"){
                                    loadStudents();
                                    getProfessionals(msg.boss);
                                   
                                    $('#boss_li').fadeIn();
                                    $("#form_mon_student").fadeIn();
                                    $("#form_prof_type").fadeOut();
                                    
                                }else{
                                    $('#boss_li').fadeOut();
                                    $("#form_mon_student").fadeOut();
                                    $("#form_prof_type").fadeOut();
                                }
                            }
                        }
                    
                }else{
                    swal("Error", msg.error, "error");
                    $(".assignment_li").addClass('hidden');
                    $("#form_mon_student").css({display: 'none' });
                    
                    $("#username_input").val("");
                    $("#username_input").attr('readonly', false);
                    $('#name_lastname').val(" ");
                    
                }
            }
            
        },
        dataType: "json",
        error: function(msg){
            console.log(msg);
            swal("Error", "El usuario no existe en la base de datos", "error");
            $(".assignment_li").addClass('hidden');
            $("#form_mon_student").css({display: 'none' });
            
            $("#username_input").val("");
            $("#username_input").attr('readonly', false);
            $('#name_lastname').val(" ");
        }
        });
}

function updateRolUser(){
    var dataRole = $('#role_select').val();
    var dataUsername = $('#username_input').val();
    var dataStudents = new Array();

    $('input[name="array_students[]"]').each(function() {
    	dataStudents.push($(this).val());
    });

    if(dataRole == "profesional_ps"){
        
        var dataProfessional = $('#select_prof_type').val();
        
        if(dataProfessional == "no_asignado"){
            swal("Error", "El usuario no tiene un \"tipo de profesional\" asignado, debe asignar un \"tipo de profesional\".", "error")
        }else{
            $.ajax({
            type: "POST",
            data: {role: dataRole, username: dataUsername, professional: dataProfessional},
            url: "../managers/update_role_user.php",
            success: function(msg)
            {
                //console.log(msg);
                swal("Información!", msg, "info");
                userLoad(dataUsername);
            },
            dataType: "text",
            cache: "false",
            error: function(msg){swal("Error", "Ha ocurrido un error", "error")},
            });
        }
    }
    else if(dataRole == "monitor_ps"){
        var boss_id = $('#boss_select').val();
        
        $.ajax({
            type: "POST",
            data: {role: dataRole, username: dataUsername, students: dataStudents, boss:boss_id},
            url: "../managers/update_role_user.php",
            success: function(msg)
            {
                swal({  title: "Información!",   
                    text: msg,   
                    type: "info",
                    html: true,
                    confirmButtonColor: "#d51b23",   
                    confirmButtonText: "Ok!",
                    closeOnConfirm : true
                });
                userLoad(dataUsername);
              
            },
            dataType: "text",
            cache: "false",
            error: function(msg){swal("Error", "Ha ocurrido un error", "error")},
            });
    }
    else{
        $.ajax({
            type: "POST",
            data: {role: dataRole, username: dataUsername},
            url: "../managers/update_role_user.php",
            success: function(msg)
            {
               swal("Información!", msg, "info");
            },
            dataType: "text",
            cache: "false",
            error: function(msg){swal("Error", "Ha ocurrido un error", "error")},
            });
    }
    
    
}

function student_asignment(){
    var MaxInputs       =  10; //Número Maximo de Campos
        var contenedor       = $("#contenedor_add_fields"); //ID del contenedor
        var AddButton       =  $("#agregarCampo"); //ID del Botón Agregar
    
        var count = $("#contenedor_add_fields div").length + 1;
        var FieldCount = count - 1; //para el seguimiento de los campos
    
        $(AddButton).click(function (e) {
            if(count <= MaxInputs) //max input box allowed
            {
                FieldCount++;
                $("#contenedor_add_fields").append('<div><input type="text" class="inputs_students" name="array_students[]" id="campo_'+ FieldCount +'" placeholder="Estudiante '+ FieldCount +'"/></div>');
                count++; 
            }
            return false;
        });
    
        $("body").on("click",".eliminar_add_fields", function(e){ //click en eliminar campo
            
            var student = $(this).parent('div').children('input').val();
            var parent = $(this).parent('div');
            if(student){
                swal({  title: "Estas seguro/a?",   
                text: "Se desvinculará el prensente monitor del estudiante con codigo "+student,   
                type: "warning",  
                showCancelButton: true,   
                confirmButtonColor: "#d51b23",   
                confirmButtonText: "Si!",
                cancelButtonText: "No", 
                closeOnConfirm : true, 
                }, 
                function(isConfirm){ 
                    if(isConfirm) {
                        deleteStudent(student);
                        parent.remove(); //eliminar el campo
                        count--;
                    }
                });
            }
            
            // if( count > 1 ) {
            //     $(this).parent('div').remove(); //eliminar el campo
            //     count--;
            // }
            return false;
        });
}

function loadStudents(){
    var data =  new Array();
  
    var user_id   =  $('#user_id').val();
 
    data.push({name:"function",value:"load_grupal"});
    data.push({name:"user_management",value:user_id});
   
    $.ajax({
            type: "POST",
            data: data,
            url: "../managers/seguimiento.php",
            success: function(msg)
            {
                $('#contenedor_add_fields').html('');
                if(msg.rows != 0){
                    
                    var content =  msg.content;
                    for (x in content){
                            $('#contenedor_add_fields').append('<div id="contenedor_add_fields"> <div class="added_add_fields"> <input type="text"  class="inputs_students" name="array_students[]" id="campo_1" value="'+content[x].username+'" readonly/> <a href="#" class="eliminar_add_fields"><img src="../icon/ico_wrong.png"></a> </div> </div>');
                    }
                
                }else{
                    $('#contenedor_add_fields').append('<div id="contenedor_add_fields"> <div class="added_add_fields"> <input type="text"  class="inputs_students" name="array_students[]" id="campo_1" placeholder="Estudiante 1"/> <a href="#" class="eliminar_add_fields"><img src="../icon/ico_wrong.png"></a> </div> </div> ');
                }
            },
            dataType: "json",
            cache: "false",
            error: function(msg){console.log(msg)},
            });
}

function getProfessionals(selected){
    var data =  new Array();
    data.push({name:"function",value:"cargar"});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/search_user.php",
        success: function(msg)
        {
            $('#boss_select').html('');
            $('#boss_select').append('<option value="ninguno">Ninguno</option>');
            for (x in msg){
                var firstnamearray = msg[x].firstname.split(" ");
                var lastnamearray = msg[x].lastname.split(" ");
                var isequal = false;
                if (selected == msg[x].id){
                    $('#boss_select').append('<option value="'+msg[x].id+'" selected>'+msg[x].username+'-'+firstnamearray[0]+' '+lastnamearray[0]+'-'+msg[x].nombre_profesional+'</option>');
                    isequal = true;
                }else{
                    $('#boss_select').append('<option value="'+msg[x].id+'">'+msg[x].username+'-'+firstnamearray[0]+' '+lastnamearray[0]+'-'+msg[x].nombre_profesional+'</option>');
            
                }
            }
            if(!isequal) $('#boss_select').val('ninguno');
            
            $('#boss_li').removeClass('hide');
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
        });
}

function deleteStudent(student){
    var data =  new Array();
    var user_id =  $('#user_id').val();
    var dataUsername = $('#username_input').val();
    data.push({name:"deleteStudent",value:"delete"});
    data.push({name:"student",value:student});
    data.push({name:"username",value:dataUsername});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/update_role_user.php",
        success: function(msg)
        {
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
        });
}

function load_users(){
    $.ajax({
        type: "POST",
        url: "../managers/load_role_users.php",
        success: function(msg){
            $("#div_users").empty();
            $("#div_users").append('<table id="tableUsers" class="display" cellspacing="0" width="100%"><thead><thead></table>');
            var table = $("#tableUsers").DataTable(msg);
            $('#div_users #delete_user').css('cursor','pointer');
        },
        dataType: "json",
        cache: "false",
        error: function(msg){
            console.log(msg)
        },
    })
}

// function deleteUser(username){
//     var data =  new Array();
//     var user_id =  $('#user_id').val();
//     var dataUsername = $('#username_input').val();
//     data.push({name:"deleteUser",value:"delete"});
//     data.push({name:"username",value:username});
//     $.ajax({
//         type: "POST",
//         data: data,
//         url: "../managers/update_role_user.php",
//         success: function(msg)
//         {
            
//         },
//         dataType: "json",
//         cache: "false",
//         error: function(msg){console.log(msg)},
//         });
// }

function valdateStudentMonitor(currentUser, isdelete){
    //console.log(currentUser);
    var data =  new Array();
    data.push({name:"function",value:"load_grupal"});
    data.push({name:"user_management",value:currentUser.id});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/seguimiento.php",
        success: function(msg)
        {
            //console.log(msg);
            //deleteMonitor(username,msg);
            if (msg.rows !=0){
                var data = msg.content;
                
                var message = '<p align=justify">Se ha encontrado que el usuario tiene asignado el rol MONITOR y tiene a cargo los siguientes estudiantes:</p><br><br> ';
                message+='<div class="pre-scrollable" style="max-height:200px"><table id="tableStudent" class="table table-striped"> <thead> <tr> <th>Código</th> <th>Nombre</th> <th>Apellido</th></tr> </thead><tbody>';
                
                for (x in data){
                        message += '<tr> <td>'+data[x].username+'</td> <td>'+data[x].firstname+'</td> <td>'+data[x].lastname+'</td> </tr>';        
                }
                message +='</tbody></table></div><br><br><p align=justify">Para continuar se creará un nuevo usuario con rol monitor quien se hará a cargo de los anteriores estudiante(s).<br> Por favor escribe el código del nuevo usuario:</p>';
                var title = "";
                if(isdelete){
                    title = "Antes de eliminar!";
                }else{
                     title = "Antes de Actualizar!";
                }
                swal({
                    title: title,
                    html: true,
                    text: message,
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonColor: "#d51b23",
                    confirmButtonText: "Continuar",
                    cancelButtonText: "Cancelar!",
                    animation: "slide-from-top",
                    inputPlaceholder: "Código"
                },
                function(inputValue){
                
               
                  if (inputValue === false) return false;
                  
                  if (inputValue === "") {
                    swal.showInputError("Escibe el código del nuevo monitor!");
                    return false;
                  }
                  
                    userLoad(inputValue, function (msg){
                        if(msg.error) {swal.showInputError(msg.error)}else{
    
                            confirmNewMonitor(msg,currentUser, isdelete);
                        };
                       
                        return false;
                    });
                  
                  //swal("Nice!", "You wrote: " + inputValue, "success");
                });
            }
            
            
          
            
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
        });
}

function confirmNewMonitor(newUser, currentUser, isdelete){
    var message = "";
    if(newUser.rol == 'ninguno'){
        message = 'El usuario <strong>'+newUser.firstname+' '+newUser.lastname+'</strong> con código <strong>'+newUser.username+'</strong> se le asiganara el rol monitor y tendrá a cargo los estudiantes del anterior monitor<br>¿Está de acuerdo con los cambios que se efectuarán?';
    }else{
        message = 'El usuario <strong>'+newUser.firstname+' '+newUser.lastname+'</strong> con código <strong>'+newUser.username+'</strong> ya tiene el rol <strong>'+newUser.rol+'</strong>  en el sistema.<br>Perderá el presente rol y se le asiganará el rol monitor.<br> Tendrá a cargo los estudiantes del anterior monitor.<br><br><strong>¿Estás de acuerdo con los cambios que se efectuarán?</strong>';
    }
    
    
    swal({
      title: "Estás seguro/a?",
      text: message,
      type: "warning",
      html: true,
      showCancelButton: true,
      confirmButtonColor: "#d51b23",
      confirmButtonText: "Si!",
      cancelButtonText: "Atras!",
      closeOnConfirm: false,
      closeOnCancel: false,
      animation: "slide-from-top",
    },
    function(isConfirm){
      if (isConfirm) {
        
        changeMonitor(newUser,currentUser, isdelete);
        
      } else {
        valdateStudentMonitor(currentUser, isdelete);
      }
});
}

function changeMonitor(newUser,currentUser, isdelete){
    var data =  new Array();
    data.push({name:'changeMonitor',value:'changeMonitor'});
    data.push({name:'oldUser',value:JSON.stringify( [currentUser.id,currentUser.username] )});
    data.push({name:'newUser',value:JSON.stringify( [newUser.id, newUser.username] )});
    data.push({name:'isdelete',value:isdelete});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/update_role_user.php",
        success: function(msg)
        {   
            if(msg == 1){
                if(isdelete) {
                    swal("Eliminado!", "El proceso se eliminación de completó satisfactoriamente.", "success")}
                else{
                    swal("Hecho!", "El proceso de reasignación de estudiantes se completó satisfactoriamente. Por favor actualiza y guarda el nuevo rol", "success");
                    updateRolUser();
                    load_users();
                }
                
                
                load_users();
            }else{
                 swal("Error!", msg, "error");
            }
            
           
        },
        dataType: "text",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function deleteProfesional(currentUser){
    var data =  new Array();
    data.push({name:'deleteProfesional',value:'deleteProfesional'});
    data.push({name:'user',value:JSON.stringify( [currentUser.id,currentUser.username] )});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/update_role_user.php",
        success: function(msg)
        {
            if(msg == 1){
                swal("Eliminado!", "El proceso se eliminación de completó satisfactoriamente.", "success");
                load_users();
            }else{
                swal("Error!", msg, "error");
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}

function deleteOtheruser(currentUser){
    var data =  new Array();
    data.push({name:'deleteOtheruser',value:'deleteOtheruser'});
    data.push({name:'user',value:JSON.stringify( [currentUser.id,currentUser.username, currentUser.rol] )});
    $.ajax({
        type: "POST",
        data: data,
        url: "../managers/update_role_user.php",
        success: function(msg)
        {
            if(msg == 1){
                swal("Eliminado!", "El proceso se eliminación de completó satisfactoriamente.", "success");
                load_users();
            }else{
                swal("Error!", msg, "error");
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log(msg)},
    });
}