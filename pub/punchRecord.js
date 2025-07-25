var mapT;
var mapP;
var markersArray;
var url = 'http://localhost/CI/';

window.onload = function() {
    onLoad();
    showSearchToday();
}

function onLoad() {
    //定義全域地圖物件
    mapT = L.map('todayMap').setView([51.505, -0.09], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenCurrentMap</a> contributors'
    }).addTo(mapT);
    //將中點移到台灣地理中心碑位置
    mapT.setView([23.558725740711726, 120.87524162210006], 8);

    mapP = L.map('punchMap').setView([51.505, -0.09], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenRecordMap</a> contributors'
    }).addTo(mapP);
    //將中點移到台灣地理中心碑位置
    mapP.setView([23.558725740711726, 120.87524162210006], 8);

    //定義全域位置陣列
    markersArray = [];

    return;
}

//顯示今日打卡紀錄頁面
function showSearchToday() {
    document.getElementById('todayPunch').style.display = 'block';
    document.getElementById('punchRecord').style.display = 'none';
    
    // todayB切換為按下狀態
    document.getElementById('todayB').classList.remove("btn-outline-secondary");
    document.getElementById('todayB').classList.add("btn-secondary");

    // recordB判斷
    if(document.getElementById('recordB').classList.contains("btn-secondary")) {
        document.getElementById('recordB').classList.remove("btn-secondary");
        document.getElementById('recordB').classList.add("btn-outline-secondary");
    }

    return;
}

//顯示查詢打卡紀錄頁面
function showSearchPunch() {
    document.getElementById('punchRecord').style.display = 'block';
    document.getElementById('todayPunch').style.display = 'none';

    // recordB切換為按下狀態
    document.getElementById('recordB').classList.remove("btn-outline-secondary");
    document.getElementById('recordB').classList.add("btn-secondary");

    // todayB判斷
    if(document.getElementById('todayB').classList.contains("btn-secondary")) {
        document.getElementById('todayB').classList.remove("btn-secondary");
        document.getElementById('todayB').classList.add("btn-outline-secondary");
    }

    return;
}

//今日打卡紀錄
function searchToday() {
    if(document.getElementById('todayName').value.trim().length === 0) {
        alert('請選擇查詢照服員');
    }
    else {
        var id = document.getElementById('todayName').value.split('、');

        //整理輸入資料至data
        var data = {
            id: id[0]
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/punchRecord/searchToday",
            type: "POST",
            data: data,
            success: function(response) {
                console.log(response);
                if(response === 'error') {
                    alert('查無紀錄！');
                }
                else {
                    //在地圖上標點
                    todayMarker(response);
                }
            },
            error: function() {
                alert('查詢失敗！');
            }
        });
    }
}

//查詢打卡紀錄
function searchRecord() {
    if(document.getElementById('punchDate').value.trim().length === 0) {
        alert('請選擇查詢日期');
    }
    else if(document.getElementById('punchName').value.trim().length === 0) {
        alert('請選擇查詢照服員');
    }
    else {
        var id = document.getElementById('punchName').value.split('、');

        //整理輸入資料至data
        var data = {
            date: document.getElementById('punchDate').value,
            id: id[0]
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/punchRecord/searchRecord",
            type: "POST",
            data: data,
            success: function(response) {
                if(response === 'error') {
                    alert('查無紀錄！');
                }
                else {
                    //畫紀錄線
                    punchMarker(response);
                }
            },
            error: function() {
                alert('查詢失敗！');
            }
        });
    }
}

//將今日紀錄標註於地圖
function todayMarker(data) {
    var record = JSON.parse(data);

    //刪除標記圖層
    mapT.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
            mapT.removeLayer(layer);
        }
    });

    //標註打卡點
    for(let marker of record) {
        //抵達marker
        if(marker.clock_in === "arrive") {
            L.marker([Number(marker.lat), Number(marker.lng)])
            .addTo(mapT)
            .bindPopup(
                "<h5>抵達</h5>" +
                "姓名：" + marker.name + "<br>" +
                "時間：" + marker.time
            );
        }
        //離開marker
        else {
            L.marker([Number(marker.lat), Number(marker.lng)])
            .addTo(mapT)
            .bindPopup(
                "<h5>離開</h5>" +
                "姓名：" + marker.name + "<br>" +
                "時間：" + marker.time
            );
        }
    }

    //將中點移到台灣地理中心碑位置
    mapT.setView([23.558725740711726, 120.87524162210006], 8);

    return;
}

//將打卡紀錄標註於地圖
function punchMarker(data) {
    var record = JSON.parse(data);

    //刪除標記圖層
    mapP.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
            mapP.removeLayer(layer);
        }
    });

    //標註打卡點
    for(let marker of record) {
        //抵達marker
        if(marker.clock_in === "arrive") {
            L.marker([Number(marker.lat), Number(marker.lng)])
            .addTo(mapP)
            .bindPopup(
                "<h5>抵達</h5>" +
                "姓名：" + marker.name + "<br>" +
                "時間：" + marker.time
            );
        }
        //離開marker
        else {
            L.marker([Number(marker.lat), Number(marker.lng)])
            .addTo(mapP)
            .bindPopup(
                "<h5>離開</h5>" +
                "姓名：" + marker.name + "<br>" +
                "時間：" + marker.time
            );
        }
    }

    //將中點移到台灣地理中心碑位置
    mapP.setView([23.558725740711726, 120.87524162210006], 8);

    return;
}