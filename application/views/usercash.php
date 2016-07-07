<html>
<head>
    <meta charset="UTF-8">
    <title>余额</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<form class="form-horizontal" action="/index.php/userapi/toeditcash" method="post">
    <input type="hidden" value="<?=$coin?>" name="cashBefore">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">会员卡卡号</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?=$cardId?>" name='cardid' placeholder="" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">余额</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="cash" value="" placeholder="+500（充值）/-500（消费）">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">店铺ID</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="storeId" value="<?=$store_id?>" placeholder="" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">业务描述</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="cashSource" value="" placeholder="" >
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-default">Save</button>
        </div>
    </div>
</form>
</body>
</html>