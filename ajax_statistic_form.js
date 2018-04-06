/* to use script*/
/*
 * 1 - set  form_config file
 * 2 - set onclick in btn update, delete items
 * 3 - include script file at the end of page
 * */

var error_msg = 'Помилка при збережнені!';
var save_success_msg = "Запис збережнен успішно! Результат буде видно після оновлення стрінки";
var validate_error_msg = 'Заповніть всі поля!';
var config = {};

/******************** Custom Page Events **************************/

$(document).ready(function(){
    var btn_class_form_ajax='form_statistic_show';

    $('#visit_date').on('click ', function() {
        $('#visit,#visitIconsPlush,#visitIconsMinus').toggle()
    });
    
    $('#onePurchase').on('click ', function() {
        $('#onePurchaseList,#onePurchasePlush,#onePurchaseMinus').toggle()
    });

    $('#errorLogin').on('click ', function() {
        $('#listErrorLogin,#listErrorLoginPlush,#listErrorLoginMinus').toggle()
    });

    $('#noVisitsUsersInfo').on('click ', function() {
        $('#noVisitsUsersList,#noVisitsUsersInfoPlush,#noVisitsUsersInfoMinus').toggle()
    });

    $('#usersInBucketInfo').on('click ', function() {
        $('#usersInBucketList,#usersInBucketInfoPlush,#usersInBucketInfoMinus').toggle()
    });
    
    $('#usersOnlineInfo').on('click ', function() {
        $('#usersOnlineList,#usersOnlineInfoPlush,#usersOnlineInfoMinus').toggle()
    });

    $('#visitTodayInfo').on('click ', function() {
        $('#visitTodayList,#visitTodayInfoPlush,#visitTodayInfoMinus').toggle()
    });
    
    $('#unorderedInfo').on('click ', function() {
        $('#unorderedInfoList,#unorderedInfoPlush,#unorderedInfoMinus').toggle()
    });


    
    $('.'+btn_class_form_ajax).click(function(event){
        event.preventDefault();
        var el = $(this);
        getAjaxFormItems(el);
    });

    $('#close').click(function(event){
        event.preventDefault();
        closeEditForm(0);

    });



});

/******************************************************************/
/******************** Main functions *****************************/

/* ajax get config form settings */
getConfigSettings=function(){
    $.ajax({
        type: 'GET',
        url: document.location.href+'&action=get_config',
        dataType: 'json',
        success: function(json){
            config = json;

            return false;
        }
    });
}
getConfigSettings();

/* ajax edit item */
function getAjaxFormItems(element){

    config.url= $(element).attr("href");
    $.ajax({
        type: 'GET',
        url: $(element).attr("href"),
        dataType: 'json',
        success: function(json){

            var productList='';
            var visitList='';
            var searchList='';

            json[1].forEach(function(item) {
                visitList += item+'<br>'
            });

            json[2].forEach(function(item) {
                productList += item+'<br><br>'
            });

            json[3].forEach(function(item) {
                searchList += item+'<br>'
            });

            $('#userName').val(json[0].UserName);
            $('#FirstName').val(json[0].FirstName);
            $('#email').val(json[0].Email);
            $('#userIdSend').val(json[0].id);
            $('#last_visit').html('Останнiй раз заходив : '+json[0].visit_date);
            $('#visit').html(visitList);
            $('#productSearch').html(productList);
            $('#searchString').html(searchList);

            return false;
        }
    });
}
