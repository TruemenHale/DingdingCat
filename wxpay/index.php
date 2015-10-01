<?php
    session_start();
    if(isset($_SESSION['money'])) {
        session_unset($_SESSION['money']);
    }
?>
<html>
        <form action="./example/jsapi.php/" method="post">
            <label>捐款金额</label><br>
            <div class="NumHolder">
                <span class="cell">￥</span><input class="moneyNum" name="money" id="moneyNum" type="text" value="1.00"/>
            </div>
            <input type="submit" value="我要捐款" id="pay" style="height: 40px;"/>
        </form>



</html>