$("#frmAcceso").on('submit',function(e)
{
	e.preventDefault();
    logina=$("#logina").val();
    clavea=$("#clavea").val();


     var v1 = $("input#recaptcha_challenge_field").val();
     var v2 = $("input#recaptcha_response_field").val();
                       
     $.ajax({
                            type: "POST",
                            url: "comprobar.php",
                            data: "recaptcha_challenge_field="+v1+"&recaptcha_response_field="+v2,
                            dataType: "html",
                            error: function(){
                                  alert("error petición ajax");
                            },
                            success: function(data){ 

                                 if(data == 0){
                                 //$("#msjform").html("El código de seguridad introducido es incorrecto.");
                                 bootbox.alert("Captcha incorrecto");

                                 Recaptcha.reload(); //recarga Recaptcha.
                                }else if(data == 1){
                                    $.post("../ajax/usuario.php?op=verificar",
                                    {"logina":logina,"clavea":clavea},
                                    function(data)
                                    {
                                        if (data!="null")
                                        {
                                            $(location).attr("href","escritorio.php");            
                                        }
                                        else
                                        {
                                            bootbox.alert("Usuario y/o Password incorrectos");
                                             //$(location).attr("href","escritorio1.php"); 
                                        }
                                });


                                //$("#formulario").submit();    
                                }


                                  //alert(data);
                            }
            });
    
})