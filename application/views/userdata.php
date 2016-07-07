<html>
<head>
    <meta charset="UTF-8">
    <title>会员信息</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<form class="form-horizontal" action="/index.php/userapi/toedituser" method="post">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">会员卡卡号</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?=$cardId?>" name='cardid' placeholder="" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">姓名</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="name" value="<?=$name?>" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">会员手机号</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="phone" value="<?=$phone?>" placeholder="" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">会员性别</label>
        <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="gender" id="inlineRadio1" value="1" <?php if($gender==1)echo "checked"?>> 男
            </label>
            <label class="radio-inline">
                <input type="radio" name="gender" id="inlineRadio2" value="2" <?php if($gender==2)echo "checked"?>> 女
            </label>
        </div>

    </div>

    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">生日</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?=$birthday?>" name="birthday" placeholder="YYYY-MM-DD">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">等级ID</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?=$grade?>" name="gradeid" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">账号状态</label>
        <div class="col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="status" id="inlineRadio1" value="0" <?php if($status==0)echo "checked"?>> 可使用
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" id="inlineRadio2" value="1" <?php if($status==1)echo "checked"?>> 已冻结
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" id="inlineRadio2" value="2" <?php if($status==2)echo "checked"?>> 已过期
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-4 control-label">线下门店ID</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?=$store_id?>" name="storeid" placeholder="">
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