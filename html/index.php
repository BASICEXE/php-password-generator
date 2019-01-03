<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>パスワード作成ツール</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>
<body>
    <?php
    $number = 1;
    $randStr = 9;
    $strNum = 3;
    $fukuzatu = false;
    if (empty($_REQUEST['make'])) {
        if (empty($_POST['number'])) {
            $number = (int)$_POST['number'];
            if ($number > 40) {
                $number = 40;
            }
        }
        if(!empty($_POST['complexity']) && $_POST['complexity'] == 'strong'){
            $randStr = 12;
            $strNum = 4;
        }elseif(!empty($_POST['complexity']) && $_POST['complexity'] == 'complexity'){
            $randStr = 16;
            $fukuzatu = true;
        }
    }

        function pass($text,$num){
            return wordwrap($text,$num,'-',true);
        }
        function html_radio($name,$value,$text,$checked = false){
            if($checked){
                $checked = 'checked';
            }else{
                $checked = '';
            }
            return '
            <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="'.$value.'" name="'.$name.'" class="custom-control-input" value="'.$value.'" '.$checked.'>
            <label class="custom-control-label" for="'.$value.'">'.$text.'</label>
            </div>';
        }

        function makeRandStr($length = 8,$symbol=false) {
            static $str;
            if(!$str){
                $str = array_merge(
                    range('0', '9'),
                    range('A', 'Z'),
                    range('a', 'z')
                );
                if($symbol==true){
                    $str = array_merge(
                        $str,
                        range('!', '/'),
                        range('{', '~')
                    );
                }
            }
            $r_str = '';
            for ($i = 0; $i < $length; $i++) {
                $key = random_int(0,count($str)-1);
                $r_str .= $str[$key];
                if(rand(0,1) == 1){
                    unset($key);
                    $str = array_values($str);
                }
            }
            return $r_str;
        }
    ?>
<div class="wrapper">
    <header class="headter">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
            <a class="navbar-brand" href="./">パスワード作成ツール</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./">Top <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#disclaimer">免責事項</a>
                </li>
                </ul>
            </div>
            </div>
        </nav>
    </header>
    <section class="contents">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 py-3">
                    <div class="page-header" id="banner">
                        <h1 class="display-5">パスワード生成ツール</h1>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">パスワード生成ツール</div>
                        <div class="card-body">
                        <p class="card-text">パスワードを生成することができます。強度の担保としてパスワードの強度チェックが行われます。</p>
                        <table class="table table-hover bg-light">
                            <thead>
                            <tr>
                                <th scope="col">パスワード</th>
                                <th scope="col">強度</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php for ($i=0; $i < $number; $i++): ?>
                                <tr>
                                    <td class="text-center pwd" style="font-family: 'PT Mono', monospace;"><?php
                                    if ($fukuzatu == true) {
                                        $password = makeRandStr($randStr,true);
                                    }else{
                                        $password = pass(makeRandStr($randStr),$strNum);
                                    }
                                    echo $password;
                                    ?></td>
                                    <td class="pm-indicator"></td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                        <form action="index.php" method="post" class="form-group">
                            <table class="table mb-4">
                                <tbody>
                                    <tr>
                                        <th scope="row">強度</th>
                                        <td>
                                        <?php echo html_radio('complexity','usually','普通',true); ?>
                                        <?php echo html_radio('complexity','strong','強'); ?>
                                        <?php echo html_radio('complexity','complexity','複雑'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>個数</th>
                                        <td>
                                            <input type="number" class="form-control" name="number" placeholder="1" min="1" max="40">
                                            <small class="form-text text-muted">1〜40個</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row my-3">
                                <div class="col-3"><input type="submit" class="btn btn-success btn-block" name="make" value="作成"></div>
                                <div class="col-3"><div class="btn btn-outline-success btn-block passCheck">強度チェック</div></div>
                                <div class="col-3"><input type="reset" class="btn btn-outline-secondary" value="リセット"></div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <div id="disclaimer" class="col-lg-4 py-3">
                    <h2>使い方</h2>
                    <p>パスワードを生成します。</p>
                    <p>普通は11文字でハイフンあり英字（大・小）、数字</p>
                    <p>強は14文字でハイフンあり英字（大・小）、数字</p>
                    <p>複雑は16文字で英字（大・小）、数字、シンボル</p>
                    <p>強度チェックについて</p>
                    <p>このツールのパスワード強度のチェックにはzxcvbnを使用しています。
                    </p>
                    <p>※このプログラムはPHP7系にて作成、動作確認を行っております。</p>
                    <h3>記号文字について</h3>
                    <p>このツールは記号文字は以下の文字を使用しております。</p>
                    <p><?php
                    $sinbol = array_merge(
                        range('!', '/'),
                        range('{', '~')
                    );
                    echo implode($sinbol); ?></p>
                    <h3>免責事項</h3>
                    <p>パスワード作成ツール（https://service.7code.work/）（以下、「当サイト」とします。）における免責事項は、下記の通りです。</p>
                    <p></p>
                    <p>当サイトを利用される場合は、ここに記載した条件に準拠いただくものとします。当サイトをご利用いただいた時点で、以下利用条件の全てに同意頂いたものとさせて頂きます。</p>
                    <p>当サイトは、ご利用者様御自身の責任において利用頂く事ができるものとします。</p>
                    <p>当サイトにコンテンツを掲載するにあたって、その内容、機能等（以下、「コンテンツ」とします。）について細心の注意を払っておりますが、コンテンツの内容が正確であるかどうか、最新のものであるかどうか、安全なものであるか等について保証をするものではなく、当サイトの管理者は何らの責任を負うものではありません。</p>
                    <p>当サイト、またはコンテンツのご利用により、万一、ご利用者様に何らかの不都合や損害が発生したとしても、当サイトの管理者は何らの責任を負うものではありません。</p>
                    <p>当サイトの管理者は通知することなく当サイトに掲載したコンテンツの訂正、修正、追加、中断、削除等をいつでも行うことができるものとします。</p>
                    <p>また、当サイトを装ったウェブサイトによって生じた損害にも責任を負いかねます。</p>
                    <p>平成30年10月3日 策定</p>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer text-white bg-dark text-center py-3">
        <small>copyright all</small>
    </footer>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/zxcvbn.js"></script>
<script>
$(document).ready(function(){

    $('.passCheck').on('click',function(){
        $('.pwd').each(function(index){
        var result = zxcvbn($(this).text());
        var str = [
            'ありえない・・・ <i class="far fa-grin-beam-sweat"></i>',
            '弱い・・・　<i class="far fa-sad-tear"></i>',
            'おーけー <i class="far fa-laugh"></i>',
            '良い <i class="far fa-grin-beam"></i>'
        ];
        var message = str[Number(result.score)-1];
        console.log(message);
        $(this).next().html(message);
        });
    });
});
</script>

</body>
</html>