$(document).ready( function () {
    $("[class=imagen-like]").on("click", function () {
            likeDislike(this, true);
    });
    $("[class=imagen-dislike]").on("click", function () {
        likeDislike(this, false);
    });
    $("[name=loginRequerido]").one("click",avisarLoginRequerido);
});

function cargarLogin(){
    window.location = "../pages/login.php";
}
function avisarLoginRequerido(){
    $("#requeridoLogearse").show();
    setTimeout(function () {
        $("#requeridoLogearse").fadeOut("slow");
    },2000);
    $("#boton-responder").click(cargarLogin);
}

function likeDislike(img, type){
    if($(img).parent()===$("#contenedor-likes-pregunta")){
        let userId = getUserLogged();
        if (userId === "") {
            window.location.replace("/pages/login.php");
        }
        let idPregunta = $("#contenedor-pregunta").attr("id");
        //Buscar el voto en la base de datos
        let encontrado =likeDislikeExistsP(idPregunta,userId);
        if (!encontrado[0]){
            //No encontrado
            if (setLikeDislikeP(idPregunta,userId,type)){
                setInstantLikeP(img,idPregunta,type);
            }
        }else{
            if(encontrado[1]!== type){
                if(setLikeDislikeP(idPregunta,userId,type+"","TRUE")){
                    setInstantLikeP(img,idPregunta,type,true);
                }
            }
        }

    }else {
        let idRespuesta = $(img).closest(".contenedor-respuesta").attr("id");
        let userId = getUserLogged();
        if (userId === "") {
            window.location.replace("/pages/login.php");
        }
        //Buscar el voto de la base de datos
        let encontrado = likeDislikeExists(idRespuesta, userId);
        if (!encontrado[0]) {
            //No encontrado
            if (setLikeDislike(idRespuesta, userId, type)) {
                setInstantLike(img, idRespuesta, type);
            }
        }
        else {
            if (encontrado[1] !== type) {
                if (setLikeDislike(idRespuesta, userId, type + "", "TRUE")) {
                    setInstantLike(img, idRespuesta, type, true);
                }
            }
        }
    }
}

function getUserLogged() {
    let userId = null;
    $.ajax({
        type: "GET",
        async: false,
        url: "../php/getUserLogged.php"
    }).done(function (data) {
        userId = data;
    });
    return userId;
}

function likeDislikeExists(idRespuestaD, userIdD) {
    let encontrado = "";
    $.ajax({
        type: "GET",
        async: false,
        url: "../php/db/dbUtils.php",
        data: {idRespuesta: idRespuestaD, userId: userIdD}
    }).done(function (data) {
        encontrado = JSON.parse(data);
    });
    return encontrado;
}
function likeDislikeExistsP(idPreguntaD, userIdD) {
    let encontrado = "";
    $.ajax({
        type: "GET",
        async: false,
        url: "../php/db/dbUtils.php",
        data: {idPregunta: idPreguntaD, userId: userIdD}
    }).done(function (data) {
        encontrado = JSON.parse(data);
    });
    return encontrado;
}

function setLikeDislike(idRespuestaD, userIdD, typeD, alterD=false) {
    let encontrado = false;
    debugger;
    $.ajax({
        type: "GET",
        async: false,
        url: "../php/db/dbUtils.php",
        data: {idRespuesta: idRespuestaD, userId: userIdD, type: typeD, alter: alterD}
    }).done(function (data) {
        encontrado = data;
    });
    debugger;
    return encontrado;
}
function setLikeDislikeP(idPreguntaD, userIdD,typeD,alterD=false){
    let encontrado = false;
    $.ajax({
        type: "GET",
        async:false,
        url:"../php/db/dbUtils.php",
        data:{idPregunta:idPreguntaD,userId:userIdD,type:typeD,alter:alterD}
    }).done(function (data){
        encontrado = data;
    });
    return encontrado;
}

function setInstantLike(img, idRespuesta, type, alter){
    debugger;
    $(img).attr("src", "../media/like_blue.png");
    if(!alter){
        let clss;
        if(type)
           clss = ".numero-likes-respuesta";
        else
            clss = ".numero-dislikes-respuesta";

        let span = $("#"+idRespuesta).find(clss);
        let text = parseInt(span.text());
        text++;
        span.html(text);
    }
    else{
        
    }

}
function setInstantLikeP (img,idPregunta,type,alter){
    $(img).attr("src","../media/like_blue.png");
    if(!alter){
        let clss;
        if(type)
            clss = ".numero-likes-pregunta";
        else
            clss = ".numero-dislikes-pregunta";
        let span = $("#"+idRespuesta).find(clss);
        let text = parseInt(span.text());
        text++;
        span.html(text);
    }
    else{
        let sumar = ".numero-dislikes-pregunta";
        let restar = ".numero-likes-pregunta";
        let imagen = ".imagen-like";
        if(type){
            sumar = ".numero-likes-pregunta";
            restar = ".numero-dislikes-pregunta";
            imagen = ".imagen-dislike";
        }
        let htmll = $("#"+idPregunta);
        let span = htmll.find(sumar);
        let text = parseInt(span.text());
        text++;
        span.html(text);

        span = htmll.find(restar);
        text = parseInt(span.text());
        text--;
        span.html(text);
        span.siblings(imagen).attr("src", "../media/like.png");
    }
}