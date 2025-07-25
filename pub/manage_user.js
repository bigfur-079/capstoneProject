var url = 'http://163.22.17.136/CI/';

//網頁一開始執行
window.onload = function() {
    onLoad();
}

//判斷上一個介面是什麼
function onLoad() {
    // 從 localStorage 中讀取 showOnload 的值
    var showOnload = localStorage.getItem('showOnload');

    if(showOnload==null || showOnload=='add')
        showAddLeave();
    else if(showOnload=='all')
        showAllLeave();
    else
        showSearchLeave();
}

//顯示新增請假資料
function showAddLeave() {
    // 將 showOnload 設為 add
    localStorage.setItem('showOnload', 'add');

    document.getElementById('addLeave').style.display = 'block';
    document.getElementById('allLeave').style.display = 'none';
    document.getElementById('searchLeave').style.display = 'none';
    
    // addB切換為按下狀態
    document.getElementById('addB').classList.remove("btn-outline-secondary");
    document.getElementById('addB').classList.add("btn-secondary");

    // allB判斷
    if(document.getElementById('allB').classList.contains("btn-secondary")) {
        document.getElementById('allB').classList.remove("btn-secondary");
        document.getElementById('allB').classList.add("btn-outline-secondary");
    }

    // searchB判斷
    if(document.getElementById('searchB').classList.contains("btn-secondary")) {
        document.getElementById('searchB').classList.remove("btn-secondary");
        document.getElementById('searchB').classList.add("btn-outline-secondary");
    }

    return;
}

//顯示全部請假資料
function showAllLeave() {
    // 將 showOnload 設為 all
    localStorage.setItem('showOnload', 'all');

    document.getElementById('allLeave').style.display = 'block';
    document.getElementById('addLeave').style.display = 'none';
    document.getElementById('searchLeave').style.display = 'none';

    // allB切換為按下狀態
    document.getElementById('allB').classList.remove("btn-outline-secondary");
    document.getElementById('allB').classList.add("btn-secondary");

    // addB判斷
    if(document.getElementById('addB').classList.contains("btn-secondary")) {
        document.getElementById('addB').classList.remove("btn-secondary");
        document.getElementById('addB').classList.add("btn-outline-secondary");
    }

    // searchB判斷
    if(document.getElementById('searchB').classList.contains("btn-secondary")) {
        document.getElementById('searchB').classList.remove("btn-secondary");
        document.getElementById('searchB').classList.add("btn-outline-secondary");
    }

    return;
}

//顯示查詢請假資料
function showSearchLeave() {
    // 將 showOnload 設為 search
    localStorage.setItem('showOnload', 'search');

    document.getElementById('searchLeave').style.display = 'block';
    document.getElementById('addLeave').style.display = 'none';
    document.getElementById('allLeave').style.display = 'none';

    // searchB切換為按下狀態
    document.getElementById('searchB').classList.remove("btn-outline-secondary");
    document.getElementById('searchB').classList.add("btn-secondary");

    // addB判斷
    if(document.getElementById('addB').classList.contains("btn-secondary")) {
        document.getElementById('addB').classList.remove("btn-secondary");
        document.getElementById('addB').classList.add("btn-outline-secondary");
    }

    // allB判斷
    if(document.getElementById('allB').classList.contains("btn-secondary")) {
        document.getElementById('allB').classList.remove("btn-secondary");
        document.getElementById('allB').classList.add("btn-outline-secondary");
    }

    return;
}



//確認請假事由不為空
function check_id() {
    
    modify();
   
}
//check empty
function check_empty() {
    
    if(document.getElementById('name').value.trim().length === 0) {
        alert('請輸入姓名');
    }
    else if(document.getElementById('role').value.trim().length === 0) {
        alert('請輸入權限');
    }
    else if(document.getElementById('gender').value.trim().length === 0) {
        alert('請輸入性別');
    }
    else if(document.getElementById('birthday').value.trim().length === 0) {
        alert('請選擇生日');
    }
    else if(document.getElementById('phone_one').value.trim().length === 0) {
        alert('請輸入電話1');
    }
    else if(document.getElementById('phone_two').value.trim().length === 0) {
        alert('請輸入電話2');
    }
    else if(document.getElementById('email').value.trim().length === 0) {
        alert('請輸入信箱');
    }
    else if(document.getElementById('address').value.trim().length === 0) {
        alert('請輸入地址');
    }
    else if(document.getElementById('account').value.trim().length === 0) {
        alert('請輸入帳號');
    }
    else if(document.getElementById('password').value.trim().length === 0) {
        alert('請輸入密碼');
    }
    else {
        //呼叫新增資料函式
        add();
    }
}



//ID、姓名查詢
function searchName() {
    if(document.getElementById('searchName').value.trim().length === 0) {
        alert('請選擇查詢會員');
    }
    else {
        var id = document.getElementById('searchName').value.split('、');

        //整理輸入資料至data
        var data = {
            id: id[0]
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/manage_user/searchName",
            type: "POST",
            data: data,
            success: function(response) {
                console.log(response);
                if(response === 'success') {
                    location.reload();
                }
                else {
                    alert('查無資料！')
                    location.reload();
                }
            },
            error: function() {
                alert('查詢失敗！');
            }
        });
    }
}



//修改資料
function modify() {
    

    //整理輸入資料至data
    var data = {
        user_id: document.getElementById('modify_id').value,
        name: document.getElementById('modify_name').value,
        role: document.getElementById('modify_role').value,
        gender: document.getElementById('modify_gender').value,
        birthday: document.getElementById('modify_birthday').value,
        phone_one: document.getElementById('modify_phone_one').value,
        phone_two: document.getElementById('modify_phone_two').value,
        email: document.getElementById('modify_email').value,
        address: document.getElementById('modify_address').value,
        account: document.getElementById('modify_account').value,

    };

    //向後端發送修改資料請求
    $.ajax({
        url: url + "index.php/manage_user/modify",
        type: "POST",
        data: data,
        success: function(response) {
            console.log(response);
            location.reload();
            alert('修改成功！');
        },
        error: function() {
            alert('修改失敗！');
        }
    });
}

//刪除資料
function Delete(user_id) {
    if(confirm('確認要刪除嗎？')) {
        //整理輸入資料至data
        var data = {
            user_id: user_id,
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/manage_user/delete",
            type: "POST",
            data: data,
            success: function(response) {
                console.log(response);
                location.reload();
                alert('刪除成功！');
            },
            error: function() {
                alert('刪除失敗！');
            }
        });
    }
}

//顯示瀏覽資料
function showScan(user_id, name, role, gender, birthday, phone_one, phone_two,email,address,account) {
    // 設置彈出視窗的內容
    document.getElementById('scan_id').innerHTML = user_id;
    document.getElementById('scan_name').innerHTML = name;
    document.getElementById('scan_role').innerHTML = role;
    document.getElementById('scan_gender').innerHTML = gender;
    document.getElementById('scan_birthday').innerHTML = birthday;
    document.getElementById('scan_phone_one').innerHTML = phone_one;
    document.getElementById('scan_phone_two').innerHTML = phone_two;
    document.getElementById('scan_email').innerHTML = email;
    document.getElementById('scan_address').innerHTML = address;
    document.getElementById('scan_account').innerHTML = account;
    return;
}

//顯示修改資料
function showModify(user_id, name, role, gender, birthday, phone_one, phone_two,email,address,account) {
    // 設置彈出視窗的內容
    document.getElementById('modifyID').value = user_id;
    document.getElementById('modify_id').value = user_id;
    document.getElementById('modify_name').value = name;
    document.getElementById('modify_role').value = role;
    document.getElementById('modify_gender').value = gender;
    document.getElementById('modify_birthday').value = birthday;
    document.getElementById('modify_phone_one').value = phone_one;
    document.getElementById('modify_phone_two').value = phone_two;
    document.getElementById('modify_email').value = email;
    document.getElementById('modify_address').value = address;
    document.getElementById('modify_account').value = account;
    

   
    return;
}


//新增會員
function add() {
    

    //整理輸入資料至data
    var data = {
        name: document.getElementById('name').value,
        role: document.getElementById('role').value,
        gender: document.getElementById('gender').value,
        birthday: document.getElementById('birthday').value,
        phone_one: document.getElementById('phone_one').value,
        phone_two: document.getElementById('phone_two').value,
        email: document.getElementById('email').value,
        address: document.getElementById('address').value,
        account: document.getElementById('account').value,

    };

    //向後端發送修改資料請求
    $.ajax({
        url: url + "index.php/manage_user/add",
        type: "POST",
        data: data,
        success: function(response) {
            if (response.status === 'error') {
                alert(response.message); // 顯示帳號重複的錯誤訊息
            } else {
                alert('註冊成功'); // 或執行其他成功的操作
            }
        },
        error: function() {
            alert('發生錯誤'); // 處理 AJAX 錯誤
        }
    });
}