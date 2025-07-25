<!DOCTYPE html>
<html>
<head>

    <title>簡易長照排班</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale-all.js"></script> <!-- 引入中文本地化文件 -->
    <script>
    $(document).ready(function(){
        var dialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true,
            buttons: {
                "新增事件": function() {
                    var service_name = $("#service_name").val();
                    var startDate = $("#start-date-time").val();
                    var endDate = $("#end-date-time").val();
                    //
                    var uid= $("#user_id").val();
                    var case_id= $("#case_id").val();
                    if (service_name && startDate && endDate && uid) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>schedule/insert",
                            type: "POST",
                            data: { service_name: service_name, start: startDate, end: endDate ,uid:uid,case_id:case_id},
                            success: function() {
                                calendar.fullCalendar('refetchEvents');
                                alert("新增成功");
                            }
                        });
                        dialog.dialog("close");
                    } else {
                        alert("請輸入有效的排班標題和日期時間。");
                    }
                },
                "取消": function() {
                    dialog.dialog("close");
                }
            },
            close: function() {
                $("#service_name").val("");
                $("#start-date-time").val("");
                $("#end-date-time").val("");
                $("user_id").val("");
                $("#case_id").val("");
            }
        });

        var calendar = $('#calendar').fullCalendar({
            editable:true,
            header:{
                left:'promptResource today prev,next today',
                center:'title',
                right:'month,agendaWeek,agendaDay'
            },
            locale: 'zh-tw', // 设置语言为中文
            events:"<?php echo base_url(); ?>schedule/load",
            selectable:true,
            selectHelper:true,
            select:function(start, end, allDay)
            {
                dialog.dialog("open");
            },
            eventResize:function(event)
            {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                var title = event.title;

                var id = event.id;

                $.ajax({
                    url:"<?php echo base_url(); ?>fullcalendar/update",
                    type:"POST",
                    data:{start:start, end:end, id:id},
                    success:function()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Update");
                    }
                })
            },
            eventDrop:function(event)
            {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;

                $.ajax({
                    url:"<?php echo base_url(); ?>schedule/update",
                    type:"POST",
                    data:{title:title, start:start, end:end, id:id},
                    success:function()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated");
                    }
                })
            },
            eventClick:function(event)
            {
                if(confirm("Are you sure you want to remove it?"))
                {
                    var id = event.id;
                    $.ajax({
                        url:"<?php echo base_url(); ?>schedule/delete",
                        type:"POST",
                        data:{id:id},
                        success:function()
                        {
                            calendar.fullCalendar('refetchEvents');
                            alert('Event Removed');
                        }
                    })
                }
            }
        });

        $("#addEventButton").click(function() {
            dialog.dialog("open");
        });
    });
    </script>
</head>
<body>
    <br />
    <h2 align="center"><a href="#">簡易長照排班</a></h2>
    <br />
    <div class="container">
        <!-- 新增按鈕 -->
        <button id="addEventButton">新增班表</button>
        <div id="calendar"></div>
    </div>

    <!-- 新增對話框 -->
    <div id="dialog-form" title="新增班表">
        <form>
            <label for="user_id">照服員姓名：</label>
            <select name='user_id' id='user_id'>    
                
                <?php foreach ($cares as $care): ?>
                    <option><?php echo $care['user_id']; ?><?php echo $care['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="case_id">服務個案：</label>
            <select name= "case_id" id='case_id'>    
                <?php foreach ($cases as $case): ?>
                    <option><?php echo $case['user_id']; ?><?php echo $case['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="service_name">服務項目：</label>
            <select name= "service_name" id='service_name'>    
                <?php foreach ($options as $option): ?>
                    <option><?php echo $option['service_number']; ?><?php echo $option['service']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            
            <label for="start-date-time">開始日期時間：</label>
            <input type="datetime-local" id="start-date-time" name="start-date-time" required>
            <br>
            <label for="end-date-time">結束日期時間：</label>
            <input type="datetime-local" id="end-date-time" name="end-date-time" required>
        </form>
    </div>
</body>
</html>
