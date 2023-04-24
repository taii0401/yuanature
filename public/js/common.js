//取得Cookie
function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

//取得Token
function getToken() {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    return csrf_token;
}

//中斷結束
function returnFalseAction() {
    $('.btn_submit').attr('disabled', false);
}

//跳轉頁面
function changeForm(url) {
    if (url != '') {
        location.href = url;
    }
}

//依搜尋條件產生URL
function getSearchUrl(path, is_return = false) {
    var url = path;

    i = 0;
    $('.search_input_data').each(function() {
        input_id = this.id
        input_value = $(this).val();
        if (input_id != "") {
            if (i == 0) {
                url += '?';
            } else {
                url += '&';
            }
            url += input_id + '=' + input_value;
            i++;
        }
    });

    //console.log(url);
    if (is_return) { //回傳URL
        return url;
    } else {
        changeForm(url);
    }
}

//全選
function checkAll() {
    if ($('#check_all').prop('checked')) {
        $('.check_list').prop('checked', true);
    } else {
        $('.check_list').prop('checked', false);
    }

    $('.check_list').each(function(i) {
        //console.log(this.value);
        checkId(this.value);
    });
}

//勾選
var checkboxId = new Array();

function checkId(id) {
    var check = $('#checkbox_' + id).prop('checked');
    if (check) {
        checkboxId.push(id);
    } else {
        removeArray(checkboxId, id);
    }

    $('#check_list').val(checkboxId);
    //console.log("checkboxId:"+checkboxId);

    if (checkboxId != '') {
        $('.check_btn').css('display', '');
    } else {
        $('.check_btn').css('display', 'none');
    }
}

//移除陣列元素
function removeArray(arr) {
    var what, a = arguments,
        L = a.length,
        ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax = arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

//上傳檔案
function uploadFile() {
    $('#drag-and-drop-zone').dmUploader({
        url: '/ajax/upload_file',
        maxFileSize: 3000000, //檔案限制大小-3Megs
        extFilter: ['jpg', 'jpeg', 'png'], //允許的檔案字尾名
        onDragEnter: function() {
            this.addClass('active');
        },
        onDragLeave: function() {
            this.removeClass('active');
        },
        onUploadCanceled: function(id) { //取消
            ui_multi_update_file_status(id, 'warning', '取消');
            ui_multi_update_file_progress(id, 0, 'warning', false);
        },
        onUploadSuccess: function(id, file, data) { //完成
            if (checkFileCount() != false) { //檢查上傳數量
                jsdata = JSON.stringify(data);
                jsdata = JSON.parse(jsdata);

                if (!jsdata.error) {
                    id = jsdata.file_id;

                    var template = $('#files-template').text();
                    template = template.replaceAll('%%file_name%%', file.name);
                    template = template.replaceAll('%%file_id%%', id);

                    template = $(template);
                    template.prop('id', 'uploaderFile' + id);
                    template.data('file-id', id);

                    $('#files').prepend(template);

                    ui_multi_update_file_status(id, 'success', '完成');
                    ui_multi_update_file_progress(id, 100, 'success', false);
                } else {
                    alert(jsdata.message);
                    returnFalseAction();
                    return false;
                }
            }
        },
        onUploadError: function(id, xhr, status, message) { //錯誤訊息
            ui_multi_update_file_status(id, 'danger', message);
            ui_multi_update_file_progress(id, 0, 'danger', false);
        },
        onFileSizeError: function(file) { //檢查檔案大小
            alert('\'' + file.name + '\' 無法上傳: 檔案超過限制大小');
            returnFalseAction();
            return false;
        },
        onFileExtError: function(file) { //檢查檔案格式
            alert('僅支援上傳圖檔');
            returnFalseAction();
            return false;
        }
    });
}

//刪除上傳檔案
function deleteFile(id, type) {
    var yes = confirm("你確定要刪除嗎？");
    if (!yes) {
        returnFalseAction();
        return false;
    }

    //取得csrf_token
    var csrf_token = getToken();
    //刪除上傳顯示檔案block
    $('#uploaderFile' + id).remove();
    if (type == 'add') { //新增時，尚未存至資料表，因此直接刪除
        //刪除實際路徑
        $.ajax({
            type: 'POST',
            url: '/ajax/upload_file_delete',
            dataType: 'json',
            data: { '_token': csrf_token, file_id: id },
            error: function(xhr) {
                //console.log(xhr);
                alert('傳送錯誤！');
                returnFalseAction();
                return false;
            },
            success: function(response) {
                //console.log(response);
                if (response.error == false) {

                } else if (response.error == true) {
                    alert(response.message);
                    returnFalseAction();
                    return false;
                } else {
                    alert('傳送錯誤！');
                    returnFalseAction();
                    return false;
                }
            }
        });
    }
}

//上傳-限制筆數
function checkFileCount() {
    count = 0;
    $("input[name='file_id[]']").each(function() {
        count++;
    });

    if (count >= 6) {
        alert('上傳不可超過6張圖片！');
        returnFalseAction();
        return false;
    }
}

//開啟關閉按鈕-顯示文字
function changeSwitch(id) {
    var check_switch = $('#' + id).prop('checked');
    if (check_switch) {
        $('#input_switch_text_' + id).html('是');
    } else {
        $('#input_switch_text_' + id).html('否');
    }
}

//切換選項欄位，判斷是否顯示其他輸入欄位
function changeDataDisplay(type, id_name, display_name, check_value, isRequire) {
    if(type == 'checked') {
        var val = $('input[name*="'+id_name+'"]:checked').val();
    } else {
        var val = $('#'+id_name).val();
    }
    
    if(val == check_value) { //顯示
        $('#div_'+display_name).css('display','');
        if(isRequire) {
            $('#'+display_name).addClass('require');
        }
    } else { //隱藏
        $('#div_'+display_name).css('display','none');
        if(isRequire) {
            $('#'+display_name).removeClass('require');
        }
    }
}

//檢查必填欄位(class對應的欄位,是否顯示錯誤訊息區)
function checkRequiredClass(classStr, isShowMsg) {
    var class_arr = classStr.split(',');
    var required_num = 0;

    for (var i = 0; i < class_arr.length; i++) {
        var class_name = class_arr[i];

        $('.' + class_name).each(function() {
            var data = $(this).val();

            if (!data) {
                $(this).css('background-color', '#FFCCCC');
                required_num++;
            } else {
                $(this).css('background-color', '');
            }
        });
    }

    if (required_num > 0) {
        message = '您有' + required_num + '個必填欄位未選填！';
        showMsg('msg_error', message, isShowMsg);
        returnFalseAction();
        return false;
    }
}

//檢查格式是否正確-英文字或數字、登入密碼、確認密碼、電子郵件、手機號碼
function checkFormat(type, data, strLength, isShowMsg) {
    //去除空白
    data = data.trim();
    //回傳訊息
    var message = '';
    if (type == 'en_number') { //英文字或數字
        var regRule = /^[\d|a-zA-Z]+$/;
        if (!regRule.test(data)) {
            message = '請確認是否為英文字或數字！';
        }
    } else if (type == 'confirm_password') { //確認密碼
        var password = $('#password').val();
        if (data != password.trim()) {
            message = '請確認密碼是否一樣！';
        }
    } else if (type == 'email') { //電子郵件
        var emailRule = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
        if (data.search(emailRule) == -1) {
            message = '請檢查電子郵件格式！';
        }
    } else if (type == 'phone') { //手機號碼
        var phoneRule = /^(09)[0-9]{8}$/;
        if (!data.match(phoneRule)) {
            message = '請檢查手機號碼格式！';
        }
    }

    //檢查長度
    if (parseInt(strLength) > 0) {
        if (data.length > parseInt(strLength)) {
            if (message != '') {
                message += '、';
            }
            message += '請檢查長度限制！';
        }
    }


    if (message != '') {
        showMsg('msg_error', message, isShowMsg);
        returnFalseAction();
        return false;
    }
}

//顯示訊息
function showMsg(div_msg, message, isShowMsg) {
    if (message != '') {
        //顯示錯誤訊息
        if (isShowMsg) {
            $('#' + div_msg).css('display', '');
            $('#' + div_msg).html(message);
        } else {
            $('#' + div_msg).css('display', 'none');
            alert(message);
        }
    }
}

//Modal-設定輸入值
function setModalInput(modal_values, input_modal_keys, select_modal_keys) {
    if (input_modal_keys.length > 0) {
        for (var i = 0; i < input_modal_keys.length; i++) {
            var input_modal_key = input_modal_keys[i];
            var value = modal_values.split(',')[i];

            if (select_modal_keys.length > 0 && select_modal_keys.includes(input_modal_key)) { //switch、select
                if (value == "1") {
                    $('#input_modal_' + input_modal_key).prop('checked', true);
                } else {
                    $('#input_modal_' + input_modal_key).prop('checked', false);
                }
                changeSwitch('input_modal_' + input_modal_key);
            } else {
                $('#input_modal_' + input_modal_key).val(value);
            }

            if (input_modal_key == 'action_type') {
                if (value == 'add') {
                    $('#modal_title_name').text('新增');
                } else if (value == 'edit') {
                    $('#modal_title_name').text('編輯');
                } else if (value == 'delete') {
                    $('#modal_title_name').text('刪除');
                } else if (value == 'cancel') {
                    $('#modal_title_name').text('取消');
                }
            }
        }
    }
}

//檢查帳號是否存在
function userExist(account) {
    //取得csrf_token
    var csrf_token = getToken();
    isSuccess = false;
    $.ajax({
        type: 'POST',
        url: '/ajax/user_exist',
        dataType: 'json',
        async: false,
        data: { '_token': csrf_token, account: account },
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                showMsg('msg_success', response.message, true);
                isSuccess = true;
            } else if (response.error == true) {
                showMsg('msg_error', response.message, true);
            } else {
                alert('傳送錯誤！');
            }
        }
    });

    return isSuccess;
}

//忘記密碼
function userForget() {
    //檢查必填
    if (checkRequiredClass('require', true) == false) {
        returnFalseAction();
        return false;
    }

    isSuccess = false;
    $.ajax({
        type: 'POST',
        url: '/ajax/user_forget',
        dataType: 'json',
        async: false,
        data: $('#form_data').serialize(),
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                showMsg('msg_success', response.message, true);
                isSuccess = true;
            } else if (response.error == true) {
                showMsg('msg_error', response.message, true);
            } else {
                alert('傳送錯誤！');
            }
        }
    });

    return isSuccess;
}

//送出-會員資料
function userSubmit(action_type) {
    $('.btn_submit').attr('disabled', true);
    $('#action_type').val(action_type);
    //檢查必填
    if (checkRequiredClass('require', true) == false) {
        returnFalseAction();
        return false;
    }
    if (action_type == 'add') { //新增
        //檢查登入帳號(電子郵件)
        if (checkFormat('email', $('#account').val(), 0, true) == false) {
            returnFalseAction();
            return false;
        }
        //檢查登入帳號(電子郵件)是否存在
        if (userExist($('#account').val()) == false) {
            returnFalseAction();
            return false;
        }
    }
    if (action_type == 'add' || action_type == 'edit_password') { //新增、編輯密碼
        //檢查密碼
        if (checkFormat('en_number', $('#password').val(), 30, true) == false) {
            returnFalseAction();
            return false;
        }
        //檢查確認密碼
        if (checkFormat('confirm_password', $('#confirm_password').val(), 30, true) == false) {
            returnFalseAction();
            return false;
        }
    }
    if (action_type == 'add' || action_type == 'edit') { //新增、編輯
        //檢查手機號碼
        if ($('#phone').val() != '') {
            if (checkFormat('phone', $('#phone').val(), 0, true) == false) {
                returnFalseAction();
                return false;
            }
        }
    }

    if (action_type == 'delete') { //刪除
        var yes = confirm("你確定要刪除嗎？");
        if (!yes) {
            returnFalseAction();
            return false;
        }
    }

    $('.form-control').attr('disabled', false);

    $.ajax({
        type: 'POST',
        url: '/ajax/user_data',
        dataType: 'json',
        async: false,
        data: $('#form_data').serialize(),
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
            returnFalseAction();
            return false;
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                if (action_type == 'add') { //新增
                    alert("申請成功！");
                    changeForm('/users/verify/add/' + response.message);
                } else if (action_type == 'edit') { //編輯-會員資料
                    alert("修改成功！");
                    changeForm('/users/edit');
                } else if (action_type == 'edit_password') { //編輯-密碼
                    alert("修改成功，請重新登入！");
                    changeForm('/users/logout');
                } else if (action_type == 'delete') { //刪除
                    alert("刪除成功！");
                    changeForm('/users/logout');
                }
            } else if (response.error == true) {
                showMsg('msg_error', response.message, true);
                returnFalseAction();
                return false;
            } else {
                alert('傳送錯誤！');
                returnFalseAction();
                return false;
            }
        }
    });
}

//購物車-新增、編輯、刪除
function cartSubmit(action_type) {
    $('.btn_submit').attr('disabled', true);
    $('#action_type').val(action_type);

    if (action_type == 'delete') { //刪除
        var yes = confirm("你確定要刪除嗎？");
        if (!yes) {
            returnFalseAction();
            return false;
        }
    }

    $.ajax({
        type: 'POST',
        url: '/ajax/cart_data',
        dataType: 'json',
        async: false,
        data: $('#form_data').serialize(),
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
            returnFalseAction();
            return false;
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                if (action_type == 'add') { //新增
                    alert("新增成功！");
                    changeForm('/orders/cart');
                } else if (action_type == 'delete') { //刪除
                    alert("刪除成功！");
                    changeForm('/orders/cart');
                }
            } else if (response.error == true) {
                if (action_type == 'add') { //新增
                    alert(response.message);
                } else {
                    showMsg('msg_error', response.message, false);
                }
                returnFalseAction();
                return false;
            } else {
                alert('傳送錯誤！');
                returnFalseAction();
                return false;
            }
        }
    });
}

//更新購物車小計、合計
function cartChangeTotal(id) {
    amount = parseInt($('#amount_' + id).val());
    price = parseInt($('#price_' + id).val());

    //更新購物車數量
    $('#amount').val(amount);
    $('#product_id').val(id);
    cartSubmit('edit');

    //計算小計
    subtotal = amount * price;
    $('#subtotal_' + id).html(subtotal);
    $('#subtotal_col_' + id).val(subtotal);

    //計算合計
    total = 0;
    $("input[name='subtotal[]']").each(function() {
        if (parseInt(this.value) > 0) {
            total += parseInt(this.value);
        }
    });
    //console.log(total);
    $('#total').html(total);
}

//訂單-新增、編輯、刪除、取消
function orderSubmit(action_type) {
    $('.btn_submit').attr('disabled', true);

    if (action_type == 'add') { //新增
        //檢查必填
        if (checkRequiredClass('require', true) == false) {
            returnFalseAction();
            return false;
        }
        //檢查手機號碼
        if ($('#phone').val() != '') {
            if (checkFormat('phone', $('#phone').val(), 0, true) == false) {
                returnFalseAction();
                return false;
            }
        }
        //檢查電子郵件
        if ($('#email').val() != '') {
            if (checkFormat('email', $('#email').val(), 0, true) == false) {
                returnFalseAction();
                return false;
            }
        }
    }

    var form_name = 'form_data';
    if(action_type == 'cancel') {
        form_name = 'form_data_cancel';
    }

    $('.form-control').attr('disabled', false);

    $.ajax({
        type: 'POST',
        url: '/ajax/orders_data',
        dataType: 'json',
        async: false,
        data: $('#'+form_name).serialize(),
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
            returnFalseAction();
            return false;
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                if (action_type == 'add') { //新增
                    uuid = response.message;
                    //changeForm('/orders/pay_check?orders_uuid=' + uuid);

                    alert("購買成功！");
                    changeForm('/orders/detail?orders_uuid='+uuid);
                } else if (action_type == 'cancel') { //取消
                    alert("取消成功！");
                    changeForm('/orders');
                }
            } else if (response.error == true) {
                showMsg('msg_error', response.message, false);
                returnFalseAction();
                return false;
            } else {
                alert('傳送錯誤！');
                returnFalseAction();
                return false;
            }
        }
    });
}


/* =================================後台================================= */
//送出-管理員資料、會員資料、訂單資料
function adminSubmit(type) {
    $('.btn_submit').attr('disabled', true);

    if (type == 'admin') {
        change_url = '/admin/list';
    } else if (type == 'user') {
        change_url = '/admin/user/';
    } else if (type == 'orders') {
        change_url = '/admin/orders/';
    }

    var action_type = $('#input_modal_action_type').val();

    var form_name = 'form_data';
    if(action_type == 'cancel' && type == 'orders') {
        form_name = 'form_data_cancel';
    }

    if (action_type == 'delete') { //刪除
        var yes = confirm("你確定要刪除嗎？");
        if (!yes) {
            returnFalseAction();
            return false;
        }
    } else {
        //檢查必填
        if (checkRequiredClass('require', true) == false) {
            returnFalseAction();
            return false;
        }
    }

    $('.form-control').attr('disabled', false);

    $.ajax({
        type: 'POST',
        url: '/ajax/admin/' + type + '_data',
        dataType: 'json',
        async: false,
        data: $('#'+form_name).serialize(),
        error: function(xhr) {
            //console.log(xhr);
            alert('傳送錯誤！');
            returnFalseAction();
            return false;
        },
        success: function(response) {
            //console.log(response);
            if (response.error == false) {
                if (action_type == 'add') { //新增
                    alert("新增成功！");
                } else if (action_type == 'edit') { //編輯
                    alert("編輯成功！");
                } else if (action_type == 'delete') { //刪除
                    alert("刪除成功！");
                } else if (action_type == 'cancel') { //取消
                    alert("取消成功！");
                }
                changeForm(change_url);
            } else if (response.error == true) {
                showMsg('msg_error', response.message, true);
                returnFalseAction();
                return false;
            } else {
                alert('傳送錯誤！');
                returnFalseAction();
                return false;
            }
        }
    });
}