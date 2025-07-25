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

//檢查結束時間要比開始時間晚
function checkTime() {
    // 取得開始時間和結束時間的值
    const startValue = document.getElementById('startDatetime').value;
    const endValue = document.getElementById('endDatetime').value;

    // 將值轉換為 Date 物件
    const startDate = new Date(startValue);
    const endDate = new Date(endValue);

    //輸入不可為空
    if(document.getElementById('name').value.trim().length === 0) {
        alert('請選擇照服員');
    }
    else if(document.getElementById('startDatetime').value.trim().length === 0) {
        alert('請選擇開始日期及時間');
    }
    else if(document.getElementById('endDatetime').value.trim().length === 0) {
        alert('請選擇結束日期及時間');
    }
    else if(document.getElementById('category').value.trim().length === 0) {
        alert('請選擇請假類別');
    }
    else if(document.getElementById('reason').value.trim().length === 0) {
        alert('請輸入請假事由');
    }
    else if (endDate <= startDate) {
        alert('結束時間必須晚於開始時間');
    }
    else {
        //呼叫新增資料函式
        add();
    }
}

//確認請假事由不為空
function checkReason() {
    if (document.getElementById('modifyReason').value.trim().length === 0) {
        alert('請輸入請假事由');
    }
    else {
        //呼叫修改資料函式
        modify();
    }
}

//日期查詢
function searchDate() {
    if(document.getElementById('searchDate').value.trim().length === 0) {
        alert('請選擇查詢日期');
    }
    else {
        //整理輸入資料至data
        var data = {
            date: document.getElementById('searchDate').value
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/checkLeave/searchDate",
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

//ID、姓名查詢
function searchName() {
    if(document.getElementById('searchName').value.trim().length === 0) {
        alert('請選擇查詢照服員');
    }
    else {
        var id = document.getElementById('searchName').value.split('、');

        //整理輸入資料至data
        var data = {
            id: id[0]
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/checkLeave/searchName",
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

//新增資料
async function add() {
    //前端資料判斷
    var id = document.getElementById('name').value.split('、');
    if(document.getElementById('check').checked)
        var checked = '通過';
    else
        var checked = '未通過';

    //整理輸入資料至data
    var data = {
        uid: id[0],
        startDatetime: document.getElementById('startDatetime').value,
        endDatetime: document.getElementById('endDatetime').value,
        category: document.getElementById('category').value,
        reason: document.getElementById('reason').value,
        check:  checked
    };

    //向後端發送新增資料請求
    $.ajax({
        url: url + "index.php/checkLeave/add",
        type: "POST",
        data: data,
        success: function(response) {
            console.log(response);
            if(response === "success") {
                location.reload();
                alert('新增成功！');
            }
            else {
                alert('查無此照服員！');
            }
        },
        error: function() {
            alert('新增失敗！');
        }
    });
}

//修改資料
function modify() {
    //前端資料判斷
    if(document.getElementById('modifyCheck').checked)
        var checked = '通過';
    else
        var checked = '未通過';

    //整理輸入資料至data
    var data = {
        id: document.getElementById('modifyID').value,
        startDatetime: document.getElementById('modifyStartTime').value,
        endDatetime: document.getElementById('modifyEndTime').value,
        category: document.getElementById('modifyCategory').value,
        reason: document.getElementById('modifyReason').value,
        check:  checked
    };

    //向後端發送修改資料請求
    $.ajax({
        url: url + "index.php/checkLeave/modify",
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
function Delete(id) {
    if(confirm('確認要刪除嗎？')) {
        //整理輸入資料至data
        var data = {
            id: id,
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/checkLeave/delete",
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
function showScan(uid, name, startTime, endTime, category, reason, check) {
    // 設置彈出視窗的內容
    document.getElementById('scanUid').innerHTML = uid;
    document.getElementById('scanName').innerHTML = name;
    document.getElementById('scanStartTime').innerHTML = startTime;
    document.getElementById('scanEndTime').innerHTML = endTime;
    document.getElementById('scanCategory').innerHTML = category;
    document.getElementById('scanReason').innerHTML = reason;
    document.getElementById('scanCheck').innerHTML = check;

    return;
}

//顯示修改資料
function showModify(leave_id, uid, name, startTime, endTime, category, reason, check) {
    // 設置彈出視窗的內容
    document.getElementById('modifyID').value = leave_id;
    document.getElementById('modifyUid').value = uid;
    document.getElementById('modifyName').value = name;

    // 將日期時間轉換成 JavaScript 的 Date 物件
    var startDate = new Date(startTime);
    var endDate = new Date(endTime);

    // 取得本地時區偏移量（分鐘）
    var timezoneOffset = startDate.getTimezoneOffset();

    // 加上本地時區偏移量來調整時間
    startDate.setMinutes(startDate.getMinutes() - timezoneOffset);
    endDate.setMinutes(endDate.getMinutes() - timezoneOffset);

    // 將 Date 物件轉換成特定格式的字串（例如 'Y-m-d\TH:i'）
    var start = startDate.toISOString().slice(0, 16); // 以 'Y-m-d\TH:i' 格式顯示，例如 '2023-07-05T12:30'
    var end = endDate.toISOString().slice(0, 16);     // 以 'Y-m-d\TH:i' 格式顯示，例如 '2023-07-05T14:30'

    // 將結果設定到指定的 HTML 元素中
    document.getElementById('modifyStartTime').value = start;
    document.getElementById('modifyEndTime').value = end;

    document.getElementById('modifyCategory').value = category;
    document.getElementById('modifyReason').value = reason;

    if(check == "通過")
        document.getElementById('modifyCheck').checked = true;
    else
        document.getElementById('modifyCheck').checked =false;

    return;
}