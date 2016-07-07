<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div>

    <table class="table table-hover">
        <tr>
            <th>会员卡号</th>
            <th>姓名</th>
            <th>手机号</th>
            <th>余额</th>
            <th>剩余积分</th>
            <th>绑定情况</th>
            <th>操作</th>
        </tr>
        <?php for($i=0;$i<$num;$i++){?>
        <tr>
            <td><?php echo $data[$i]['cardId']?></td>
            <td><?php echo $data[$i]['name']?></td>
            <td><?php echo $data[$i]['phone']?></td>
            <td><?php echo $data[$i]['coin']?></td>
            <td><?php echo $data[$i]['amount']?></td>
            <td><?php if($data[$i]['isbind']){echo '已绑定';}else{echo '未绑定';}?></td>
            <td><a href="/index.php/userapi/edituser?cardid=<?=$data[$i]['cardId']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                <a href="/index.php/userapi/editcash?cardid=<?=$data[$i]['cardId']?>"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></a>
                <a href="/index.php/userapi/editscore?cardid=<?=$data[$i]['cardId']?>"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span></a>
                </td>
        </tr>
        <?php }?>
    </table>
</div>
<nav>
    <ul class="pagination">
        <li>
            <a href="/index.php/userApi/apientry?page=0" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for($k=0;$k<$pagenum;$k++){?>
        <li><a href="/index.php/userApi/apientry?page=<?=$k;?>"><?php echo $k+1;?></a></li><?php }?>
        <li>
            <a href="/index.php/userApi/apientry?page=<?=($pagenum-1)?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

</body>
</html>