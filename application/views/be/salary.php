    <form method="post" action="<?php echo site_url('salary/calculate'); ?>">
        <label for="start">輸入計算月份:</label>
        <input type="month" id="start" name="start"  />
        <label for="start">輸入時薪:</label>
        <input type="number" id="hours_money" name="hours_money"  />
        <input type="submit" value="Submit">
    </form>
    <div class="container"> 
        <h2 class="text-center">照服員總薪資</h2>
        <div class="table-responsive">
        <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">照服員姓名</th>
                <th scope="col">時數</th>
                <th scope="col">薪資</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($cares != NULL) { // 檢查是否有資料 ?>
            <?php foreach ($cares as $care): ?>
                <tr>
                    <td><?php echo $care['name']; ?></td>
                    <td><?php echo $care['salary']; ?></td>
                    <td><?php echo $care['salary']*200 ?></td>
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
            <?php if ($schedules != NULL) { // 檢查是否有資料 ?>
            <?php foreach ($schedules as $schedule): ?>
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
