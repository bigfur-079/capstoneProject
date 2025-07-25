<form method="post" action="<?php echo site_url('schedule/get_care_schedule'); ?>">
        <label for="start">查詢班表月份:</label>
        <input type="month" id="month" name="month"  />
        <input type="submit" value="查詢">
</form>
    <div class="container"> 
        <h2 class="text-center">班表</h2>
        <div class="table-responsive">
        <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">照服員姓名</th>
                <th scope="col">服務個案</th>
                <th scope="col">服務項目</th>
                <th scope="col">開始時間</th>
                <th scope="col">結束時間</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($care_schedule != NULL) { // 檢查是否有資料 ?>
            <?php foreach ($care_schedule as $care): ?>
                <tr>
                    <td><?php echo $care['care_name']; ?></td>
                    <td><?php echo $care['case_name'] ?></td>
                    <td><?php echo $care['service_name'] ?></td>
                    <td><?php echo $care['service_time_start'] ?></td>
                    <td><?php echo $care['service_time_end'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php }?>
        </tbody>
        
        <table class="table">
    </div>
    </div>
    <div class="container "> 
        <h2 class="text-center">照服員薪資紀錄</h2>
        <div class="table-responsive">
        
        <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">照服員姓名</th>
                <th scope="col">個案姓名</th>
                <th scope="col">服務項目</th>
                <th scope="col">時數</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($care_schedule != NULL) { // 檢查是否有資料 ?>
            <?php foreach ($care_schedule as $schedule): ?>
                <tr>
                    <td><?php echo $schedule['care_name']; ?></td>
                    <td><?php echo $schedule['case_name']; ?></td>
                    <td><?php echo $schedule['service_name']; ?></td>
                    <td><?php echo $schedule['time_difference']; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php }?>
        </tbody>
        
        </table>
    </div>
    </div>
</body>
</html>