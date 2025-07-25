
var mapC;
var mapR;
var markersArray;
var url = 'http://localhost/CI/';

window.onload = function() {
    onLoad();
    showCurrentPosition();
}

function onLoad() {
    //定義全域地圖物件
    mapC = L.map('currentMap').setView([51.505, -0.09], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenCurrentMap</a> contributors'
    }).addTo(mapC);
    //將中點移到台灣地理中心碑位置
    mapC.setView([23.558725740711726, 120.87524162210006], 8);

    mapR = L.map('recordMap').setView([51.505, -0.09], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenRecordMap</a> contributors'
    }).addTo(mapR);
    //將中點移到台灣地理中心碑位置
    mapR.setView([23.558725740711726, 120.87524162210006], 8);

    //定義全域位置陣列
    markersArray = [];

    return;
}

//顯示照服員目前位置
function showCurrentPosition() {
    document.getElementById('currentPosition').style.display = 'block';
    document.getElementById('recordPosition').style.display = 'none';
    
    // currentB切換為按下狀態
    document.getElementById('currentB').classList.remove("btn-outline-secondary");
    document.getElementById('currentB').classList.add("btn-secondary");

    // recordB判斷
    if(document.getElementById('recordB').classList.contains("btn-secondary")) {
        document.getElementById('recordB').classList.remove("btn-secondary");
        document.getElementById('recordB').classList.add("btn-outline-secondary");
    }

    //接收mqtt
    currentMqtt();

    return;
}

//顯示照服員位置紀錄頁面
function showRecordPosition() {
    document.getElementById('recordPosition').style.display = 'block';
    document.getElementById('currentPosition').style.display = 'none';

    // recordB切換為按下狀態
    document.getElementById('recordB').classList.remove("btn-outline-secondary");
    document.getElementById('recordB').classList.add("btn-secondary");

    // currentB判斷
    if(document.getElementById('currentB').classList.contains("btn-secondary")) {
        document.getElementById('currentB').classList.remove("btn-secondary");
        document.getElementById('currentB').classList.add("btn-outline-secondary");
    }

    return;
}

//查詢位置紀錄
function searchRecord() {
    if(document.getElementById('recordDate').value.trim().length === 0) {
        alert('請選擇查詢日期');
    }
    else if(document.getElementById('recordName').value.trim().length === 0) {
        alert('請選擇查詢照服員');
    }
    else {
        var id = document.getElementById('recordName').value.split('、');

        //整理輸入資料至data
        var data = {
            date: document.getElementById('recordDate').value,
            id: id[0]
        };

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/carerPosition/searchRecord",
            type: "POST",
            data: data,
            success: function(response) {
                if(response === 'error') {
                    alert('查無紀錄！');
                }
                else {
                    //畫紀錄線
                    trackRecord(response);
                }
            },
            error: function() {
                alert('查詢失敗！');
            }
        });
    }
}

//將位置紀錄標註於地圖
function trackRecord(data) {
    var record = JSON.parse(data);

    //刪除標記圖層
    mapR.eachLayer(function (layer) {
        if (layer instanceof L.Marker || layer instanceof L.Polyline) {
            mapR.removeLayer(layer);
        }
    });

    //將路線分類整理
    var line = [];
    var totalLine = [];
    for(let x of record) {
        if(x.track_status === 'First' || x.track_status === 'Continue') {
            line.push(x);
        }
        else {
            line.push(x);
            totalLine.push(line);
            line = [];
        }
    }

    //畫路線
    for(let group of totalLine) {
        //將全部點畫線連起來
        for(let i = 0; i < group.length-1; i++) {
            var point1 = [group[i].lat, group[i].lng];
            var point2 = [group[i+1].lat, group[i+1].lng];
            L.polyline([point1, point2], {color: 'red'}).addTo(mapR);
        }
        //起點marker
        L.marker([Number(group[0].lat), Number(group[0].lng)])
            .addTo(mapR)
            .bindPopup(
                "<h5>起點</h5>" +
                "姓名：" + group[0].name + "<br>" +
                "時間：" + group[0].time
            );
        //終點marker
        L.marker([Number(group[group.length-1].lat), Number(group[group.length-1].lng)])
            .addTo(mapR)
            .bindPopup(
                "<h5>終點</h5>" +
                "姓名：" + group[group.length-1].name + "<br>" +
                "時間：" + group[group.length-1].time
            );
        //將中點移到台灣地理中心碑位置
        mapR.setView([23.558725740711726, 120.87524162210006], 8);
    }

    return;
}

//current持續接收mqtt
function currentMqtt() {
    //初始化
    markersArray = [];

    // 建立 MQTT 連線
    var client = mqtt.connect('ws://163.22.17.136:8080/mqtt');

    // 連線成功時
    client.on('connect', function () {
        console.log('Connected to MQTT broker');

        // 訂閱主題
        client.subscribe('MapL');
    });

    // 接收訊息
    client.on('message', function (topic, message) {
        var nowMessage = message.toString();
        var mqttMessage = JSON.parse(nowMessage);
        console.log('Received message:', mqttMessage);

        //向後端發送新增資料請求
        $.ajax({
            url: url + "index.php/carerPosition/mqttAdd",
            type: "POST",
            data: mqttMessage,
            success: function(response) {
                console.log(response);
            },
            error: function() {
                alert('新增失敗！');
            }
        });

        //將接收資料加進顯示陣列，並判斷有無存在
        markersArray.push(mqttMessage);
        console.log(markersArray);

        // 在地圖上加入多個 marker
        markersArray.forEach(function(markerData) {
            // 將接收到的座標顯示(Latitude, Longitude)
            L.marker([markerData.lat, markerData.lng]).addTo(mapC)
                .bindPopup(markerData.name)
                .openPopup();
            mapC.setView([markerData.lat, markerData.lng], 15);
        });
    });
}

//mqtt測試
function mqttEx() {
    var client = mqtt.connect('ws://163.22.17.136:8080/mqtt');
    client.publish('MapL', JSON.stringify({uid:1, time:"2023-09-01 10:45:00", name:"李恩榮", lat:23.952433499351287, lng:120.92725451687915, track_status: "First"}));

    return;
}