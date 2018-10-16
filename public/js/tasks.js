
/*Сохранение данных формы редактирования
задания после предварительной проверки
* */


//Создание новой задачи
function createTask(){

    location.href='/new';
}

function Autorization() {
    if($('#autorizationBtn').html()=='Выйти') {
        location.href = '/exit';
    }else {
        location.href = '/auth';
    }
}

//При изменении значения в форме заданий - разрешаю submit
$('.parameter').on('change', function (e) {
    $('#taskform').submit(true);
})

//Открывает список задач
function TaskList() {
    location.href = '/';
}

//Присваиваю обработчик форме откравки данных
$('#taskform').submit(function () {
    if(!$('#taskform').length)
        return false;
    var save = true;
    if(!$('#username').val()) {//Проверка заполнения имени пользователя
        alert('Введите свое имя');
        save = false;
    }else if(!$('#email').val()){//Проверка заполнения email
        alert('Введите свой e-mail');
        save = false;
    }else if(!$('#email').val()){//Проверка наличия текста задачи
        alert('Введите текст задачи');
        save = false;
    }
    return save;
});

//Предосмотр задачи перед сохранением
function Preview() {
    document.getElementById('username_p').innerHTML = $('#username').val();
    document.getElementById('email_p').innerHTML =  $('#email').val();
    document.getElementById('task_p').innerHTML = $('#task').val();
    console.log($('#picture').val());
    if($('#picture').val())
        $('#previewImg').attr('src', $('#picture').val());

console.log(document.getElementById('username').innerHTML );
    $("#preview").css("visibility", "visible");
}
//Закрываю форму предосмотра
function close_form() {
    $("#preview").css("visibility", "hidden");
}

function onFileSelect(e) {
    var
        f = e.target.files[0], // Первый выбранный файл
        reader = new FileReader,
        place = document.getElementById("previewImg") // Сюда покажем картинку
    ;
    reader.readAsDataURL(f);
    reader.onload = function(e) { // Как только картинка загрузится
        place.src = e.target.result;
    }
    if(place.height>place.width)
        place.height = 320;
    else
        place.width = 240;
}
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        if(document.querySelector("input[type=file]"))
            document.querySelector("input[type=file]").addEventListener("change", onFileSelect, false);
    } else {
        console.warn("Ваш браузер не поддерживает FileAPI")
    }


